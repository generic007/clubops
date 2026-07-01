@extends('layouts.app')

@section('title', 'Chart of Accounts')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📊 Chart of Accounts</h1>
    <a href="{{ route('ledger.accounts.create') }}" class="btn btn-primary">+ New Account</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    <tr>
                        <td><code>{{ $account->code }}</code></td>
                        <td>{{ $account->name }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($account->type) }}</span></td>
                        <td>${{ number_format($account->balance, 2) }}</td>
                        <td>
                            @if($account->active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('ledger.accounts.edit', $account) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No accounts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
