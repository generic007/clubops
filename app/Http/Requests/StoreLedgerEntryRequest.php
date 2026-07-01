<?php

namespace App\Http\Requests;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;

class StoreLedgerEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|in:' . implode(',', array_map(fn($t) => $t->value, TransactionType::cases())),
            'description' => 'required|string|max:1000',
            'entry_date' => 'required|date|before_or_equal:today',
            'source_type' => 'nullable|string|max:255',
            'source_id' => 'nullable|integer',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:ledger_accounts,id',
            'lines.*.player_id' => 'nullable|exists:players,id',
            'lines.*.debit' => 'required_without:lines.*.credit|numeric|min:0|max:999999999.99',
            'lines.*.credit' => 'required_without:lines.*.debit|numeric|min:0|max:999999999.99',
            'reference' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $lines = $this->input('lines', []);
            $totalDebit = collect($lines)->sum('debit');
            $totalCredit = collect($lines)->sum('credit');

            if (abs($totalDebit - $totalCredit) > 0.01) {
                $validator->errors()->add(
                    'lines',
                    "Ledger entry must balance. Total debit (\${$totalDebit}) ≠ Total credit (\${$totalCredit})."
                );
            }
        });
    }
}
