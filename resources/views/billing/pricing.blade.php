@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Choose Your Plan</h1>
        <p class="text-muted fs-5">Everything you need to run your poker club, in one place.</p>

        @if($onTrial)
            <div class="alert alert-info d-inline-block mt-2">
                🎉 You're on a <strong>free trial</strong> until {{ $club->trial_ends_at->format('M d, Y') }}.
            </div>
        @endif

        @if($isActive)
            <div class="d-flex justify-content-center gap-2 mt-2">
                <span class="badge bg-success fs-6">Active: {{ $currentPlan->name ?? 'Current Plan' }}</span>
                @if($club->stripe_id)
                    <a href="{{ route('billing.portal') }}" class="btn btn-outline-secondary btn-sm">Manage Billing</a>
                @endif
            </div>
        @endif
    </div>

    <div class="row justify-content-center g-4">
        @foreach($plans as $plan)
        <div class="col-md-5 col-lg-4">
            <div class="card h-100 shadow-sm {{ $currentPlan && $currentPlan->id === $plan->id ? 'border-primary border-2' : '' }}">
                @if($currentPlan && $currentPlan->id === $plan->id)
                    <div class="card-header bg-primary text-white text-center fw-semibold">
                        Current Plan
                    </div>
                @endif
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title fw-bold">{{ $plan->name }}</h3>
                    <p class="text-muted small">{{ $plan->description }}</p>

                    <div class="my-3 text-center">
                        <span class="display-5 fw-bold">{{ $plan->monthlyPrice() }}</span>
                        <span class="text-muted">/month</span>
                        @if($plan->yearlyPrice())
                            <div class="small text-muted mt-1">
                                {{ $plan->yearlyPrice() }}/year ({{ $plan->monthlyPricePerMonth() }}/mo)
                            </div>
                        @endif
                    </div>

                    <ul class="list-unstyled flex-grow-1">
                        @foreach(json_decode($plan->features ?? '[]', true) as $feature)
                        <li class="mb-2">
                            <span class="text-success me-2">✓</span> {{ $feature }}
                        </li>
                        @endforeach
                    </ul>

                    @if($isActive && $currentPlan && $currentPlan->id === $plan->id)
                        <button class="btn btn-success w-100 mt-auto" disabled>Current Plan</button>
                    @elseif($isActive && $currentPlan && $currentPlan->tier > $plan->tier)
                        <button class="btn btn-outline-secondary w-100 mt-auto" disabled>Downgrade via Billing Portal</button>
                    @else
                        <div class="d-grid gap-2 mt-auto">
                            <a href="{{ route('billing.checkout', ['plan' => $plan->slug, 'interval' => 'monthly']) }}"
                               class="btn btn-primary">
                                Subscribe Monthly
                            </a>
                            @if($plan->yearly_price_cents)
                                <a href="{{ route('billing.checkout', ['plan' => $plan->slug, 'interval' => 'yearly']) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    Subscribe Yearly — Save 17%
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-5 text-muted small">
        <p>All plans include a <strong>30-day free trial</strong> to evaluate ClubOps risk-free.</p>
        <p>Need something custom? <a href="mailto:hello@juncturelogic.com">Contact us</a>.</p>
    </div>
</div>
@endsection
