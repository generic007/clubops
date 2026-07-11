<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Stripe;

class BillingController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::active()->orderBy('tier')->get();
        $club = $this->getCurrentClub();
        $currentPlan = $club ? $club->subscriptionPlan : null;
        $onTrial = $club && $club->trial_ends_at && $club->trial_ends_at->isFuture();
        $isActive = $club && $club->subscription_status === 'active';

        return view('billing.pricing', compact('plans', 'currentPlan', 'onTrial', 'isActive'));
    }

    public function checkout(Request $request, SubscriptionPlan $plan, string $interval = 'monthly')
    {
        $club = $this->getCurrentClub();
        if (!$club) {
            return redirect()->route('login')->with('error', 'Please log in to subscribe.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $priceCents = $interval === 'yearly'
            ? $plan->yearly_price_cents
            : $plan->monthly_price_cents;

        if (!$priceCents) {
            return redirect()->route('billing.index')->with('error', 'Invalid plan interval.');
        }

        $checkoutSession = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'ClubOps ' . $plan->name . ' (' . ucfirst($interval) . ')',
                        'description' => $plan->description,
                    ],
                    'recurring' => [
                        'interval' => $interval === 'yearly' ? 'year' : 'month',
                    ],
                    'unit_amount' => $priceCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.cancelled'),
            'metadata' => [
                'club_id' => $club->id,
                'plan_id' => $plan->id,
            ],
        ]);

        return redirect($checkoutSession->url);
    }

    public function success(Request $request)
    {
        $club = $this->getCurrentClub();

        if ($club && $request->has('session_id')) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($request->session_id);

            if ($session->payment_status === 'paid' || $session->payment_status === 'no_payment_required') {
                $planId = $session->metadata->plan_id ?? null;
                $plan = $planId ? SubscriptionPlan::find($planId) : null;

                $club->update([
                    'stripe_id' => $session->customer,
                    'subscription_plan_id' => $plan ? $plan->id : $club->subscription_plan_id,
                    'subscription_status' => 'active',
                    'subscription_ends_at' => now()->addMonth(),
                    'trial_ends_at' => null,
                ]);

                return view('billing.success', ['plan' => $plan]);
            }
        }

        return view('billing.success', ['plan' => null]);
    }

    public function cancelled()
    {
        return view('billing.cancelled');
    }

    public function portal(Request $request)
    {
        $club = $this->getCurrentClub();
        if (!$club || !$club->stripe_id) {
            return redirect()->route('billing.index')->with('error', 'No active subscription found.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $portalSession = BillingPortalSession::create([
            'customer' => $club->stripe_id,
            'return_url' => route('billing.index'),
        ]);

        return redirect($portalSession->url);
    }

    private function getCurrentClub()
    {
        $guard = Auth::guard('agent');
        if (!$guard->check()) {
            return null;
        }

        return $guard->user()->club;
    }
}
