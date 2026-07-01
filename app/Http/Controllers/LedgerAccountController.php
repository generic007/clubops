<?php

namespace App\Http\Controllers;

use App\Models\LedgerAccount;
use App\Services\AuditService;
use Illuminate\Http\Request;

class LedgerAccountController extends Controller
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function index()
    {
        $accounts = LedgerAccount::active()->orderBy('code')->get();
        return view('ledger.accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('ledger.accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:ledger_accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:asset,liability,equity,income,expense',
            'description' => 'nullable|string|max:1000',
            'currency' => 'nullable|string|max:3',
        ]);

        $account = LedgerAccount::create($validated);

        $this->audit->log(
            $request->user(),
            'ledger_account_created',
            $account,
            null,
            ['code' => $account->code, 'type' => $account->type],
            "Created account {$account->code} - {$account->name}"
        );

        return redirect()->route('ledger.accounts.index')
            ->with('success', "Account '{$account->code} - {$account->name}' created.");
    }

    public function edit(LedgerAccount $account)
    {
        return view('ledger.accounts.edit', compact('account'));
    }

    public function update(Request $request, LedgerAccount $account)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:ledger_accounts,code,' . $account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:asset,liability,equity,income,expense',
            'description' => 'nullable|string|max:1000',
            'currency' => 'nullable|string|max:3',
            'active' => 'boolean',
        ]);

        $account->update($validated);

        $this->audit->log(
            $request->user(),
            'ledger_account_updated',
            $account,
            null,
            ['code' => $account->code],
            "Updated account {$account->code}"
        );

        return redirect()->route('ledger.accounts.index')
            ->with('success', "Account '{$account->code}' updated.");
    }

    public function destroy(LedgerAccount $account, Request $request)
    {
        $account->update(['active' => false]);

        $this->audit->log(
            $request->user(),
            'ledger_account_deactivated',
            $account,
            null,
            null,
            "Deactivated account {$account->code}"
        );

        return redirect()->route('ledger.accounts.index')
            ->with('success', "Account '{$account->code}' deactivated.");
    }
}
