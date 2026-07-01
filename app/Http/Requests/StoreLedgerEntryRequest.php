<?php

namespace App\Http\Requests;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLedgerEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_column(TransactionType::cases(), 'value'))],
            'description' => 'required|string|max:1000',
            'entry_date' => 'nullable|date|before_or_equal:today',
            'source_type' => 'nullable|string|in:player,agent',
            'source_id' => 'required_with:source_type|integer|min:1',
            'reference' => 'nullable|string|max:255',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:ledger_accounts,id',
            'lines.*.player_id' => 'nullable|exists:players,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $lines = $this->input('lines', []);

            // Each line must have at least debit or credit
            foreach ($lines as $i => $line) {
                $debit = (float) ($line['debit'] ?? 0);
                $credit = (float) ($line['credit'] ?? 0);
                if ($debit <= 0 && $credit <= 0) {
                    $validator->errors()->add(
                        "lines.{$i}.debit",
                        "Line #{$i} must have a debit or credit amount greater than 0."
                    );
                }
            }

            // Debits must equal credits
            $totalDebit = collect($lines)->sum(fn($l) => (float) ($l['debit'] ?? 0));
            $totalCredit = collect($lines)->sum(fn($l) => (float) ($l['credit'] ?? 0));

            if (abs($totalDebit - $totalCredit) > 0.01) {
                $validator->errors()->add(
                    'lines',
                    "Entry is not balanced. Debits (\${$totalDebit}) must equal Credits (\${$totalCredit})."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'lines.required' => 'At least 2 ledger lines are required.',
            'lines.min' => 'At least 2 ledger lines are required (a balanced entry needs at least one debit and one credit).',
        ];
    }
}
