@extends('layouts.app')

@section('title', 'Entry #' . $entry->entry_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Entry #{{ $entry->entry_number }}</h1>
        <div class="d-flex gap-2">
            <span class="badge bg-secondary">{{ str_replace('_', ' ', $entry->type) }}</span>
            <span class="badge bg-{{ $entry->locked ? 'secondary' : 'success' }}">{{ $entry->locked ? 'Locked' : 'Open' }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('ledger.entries.index') }}" class="btn btn-outline-secondary">← Back</a>
        @if(!$entry->locked)
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#voidModal">↩ Void Entry</button>
        @endif
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Date</td><td>{{ $entry->entry_date->format('M d, Y') }}</td></tr>
                    <tr><td class="text-muted">Created By</td><td>{{ $entry->creator?->name }}</td></tr>
                    <tr><td class="text-muted">Reference</td><td>{{ $entry->reference ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Balance</td>
                        <td class="fw-bold {{ $entry->isBalanced() ? 'text-success' : 'text-danger' }}">
                            {{ $entry->isBalanced() ? '✅ Balanced' : '❌ Unbalanced' }}
                        </td>
                    </tr>
                    @if($entry->reversedEntry)
                    <tr><td class="text-muted">Reverses</td><td><a href="{{ route('ledger.entries.show', $entry->reversedEntry) }}">#{{ $entry->reversedEntry->entry_number }}</a></td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>Journal Lines</strong></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Account</th><th>Player</th><th>Debit</th><th>Credit</th></tr>
                    </thead>
                    <tbody>
                        @php $totalDebit = 0; $totalCredit = 0; @endphp
                        @foreach($entry->lines as $line)
                        <tr>
                            <td>{{ $line->account?->code }} — {{ $line->account?->name }}</td>
                            <td>{{ $line->player?->name ?? '—' }}</td>
                            <td>${{ number_format($line->debit, 2) }}</td>
                            <td>${{ number_format($line->credit, 2) }}</td>
                        </tr>
                        @php $totalDebit += $line->debit; $totalCredit += $line->credit; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2">Totals</td>
                            <td>${{ number_format($totalDebit, 2) }}</td>
                            <td>${{ number_format($totalCredit, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white"><strong>📝 Description</strong></div>
            <div class="card-body">
                {{ $entry->description }}
            </div>
        </div>
    </div>
</div>

<!-- Void Modal -->
@if(!$entry->locked)
<div class="modal fade" id="voidModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('ledger.entries.void', $entry) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">↩ Void Entry #{{ $entry->entry_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will create a reversal entry. The original entry will be locked.</p>
                    <div class="mb-3">
                        <label class="form-label">Reason for void *</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Why is this being voided?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Void</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
