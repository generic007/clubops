@extends('layouts.app')

@section('title', 'Create Ledger Entry')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">💰 New Ledger Entry</h1>
    <a href="{{ route('ledger.entries.index') }}" class="btn btn-outline-secondary">← Back</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('ledger.entries.store') }}" id="ledger-form">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Transaction Type *</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                        @foreach($types as $t)
                            <option value="{{ $t->value }}" {{ old('type') === $t->value ? 'selected' : '' }}>{{ str_replace('_', ' ', $t->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date *</label>
                    <input type="date" name="entry_date" class="form-control @error('entry_date') is-invalid @enderror" value="{{ old('entry_date', today()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reference (optional)</label>
                    <input type="text" name="reference" class="form-control" value="{{ old('reference') }}" placeholder="External ref #">
                </div>
                <div class="col-12">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2" required>{{ old('description') }}</textarea>
                </div>
            </div>

            <h5 class="mb-3">Journal Lines</h5>
            <p class="text-muted small">Each line is a debit OR credit. Total debits must equal total credits.</p>

            <div id="ledger-lines">
                <div class="row g-2 mb-2 line-row">
                    <div class="col-md-3">
                        <select name="lines[0][account_id]" class="form-select" required>
                            <option value="">Select Account</option>
                            @foreach($accounts as $acct)
                                <option value="{{ $acct->id }}">{{ $acct->code }} — {{ $acct->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="lines[0][player_id]" class="form-select">
                            <option value="">All/General</option>
                            @foreach($players as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="lines[0][debit]" class="form-control debit-input" placeholder="Debit $" step="0.01" min="0">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="lines[0][credit]" class="form-control credit-input" placeholder="Credit $" step="0.01" min="0">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.line-row').remove(); updateBalance();">✕</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-outline-primary mb-3" onclick="addLineRow()">+ Add Line</button>

            <div id="balance-display" class="alert alert-info mb-4">
                <strong>Balance Check:</strong> Debits: <span id="total-debit">$0.00</span> | Credits: <span id="total-credit">$0.00</span> |
                <span id="balance-status" class="fw-bold">Waiting for lines...</span>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Create Entry</button>
                <a href="{{ route('ledger.entries.index') }}" class="btn btn-outline-secondary btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let lineIndex = 1;
function addLineRow() {
    const container = document.getElementById('ledger-lines');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 line-row';
    row.innerHTML = `
        <div class="col-md-3">
            <select name="lines[${lineIndex}][account_id]" class="form-select" required>
                <option value="">Select Account</option>
                @foreach($accounts as $acct)
                    <option value="{{ $acct->id }}">{{ $acct->code }} — {{ $acct->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="lines[${lineIndex}][player_id]" class="form-select">
                <option value="">All/General</option>
                @foreach($players as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="lines[${lineIndex}][debit]" class="form-control debit-input" placeholder="Debit $" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <input type="number" name="lines[${lineIndex}][credit]" class="form-control credit-input" placeholder="Credit $" step="0.01" min="0">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.line-row').remove(); updateBalance();">✕</button>
        </div>`;
    container.appendChild(row);
    lineIndex++;
}

// Auto-mutual exclusion: clear debit when credit entered, and vice versa
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('debit-input') && e.target.value) {
        const row = e.target.closest('.line-row');
        row.querySelector('.credit-input').value = '';
    }
    if (e.target.classList.contains('credit-input') && e.target.value) {
        const row = e.target.closest('.line-row');
        row.querySelector('.debit-input').value = '';
    }
    updateBalance();
});

function updateBalance() {
    let totalDebit = 0, totalCredit = 0;
    document.querySelectorAll('.debit-input').forEach(el => totalDebit += parseFloat(el.value) || 0);
    document.querySelectorAll('.credit-input').forEach(el => totalCredit += parseFloat(el.value) || 0);

    document.getElementById('total-debit').textContent = '$' + totalDebit.toFixed(2);
    document.getElementById('total-credit').textContent = '$' + totalCredit.toFixed(2);

    const diff = Math.abs(totalDebit - totalCredit);
    const status = document.getElementById('balance-status');
    if (totalDebit === 0 && totalCredit === 0) {
        status.textContent = 'Add at least one line';
        status.style.color = '#6b7280';
    } else if (diff < 0.01) {
        status.textContent = '✅ Balanced';
        status.style.color = '#16a34a';
    } else {
        status.textContent = '❌ Not balanced (diff: $' + diff.toFixed(2) + ')';
        status.style.color = '#dc2626';
    }
}
</script>
@endpush
@endsection
