<?php

namespace App\Http\Requests;

use App\Enums\TicketType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_id' => 'nullable|exists:players,id',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'type' => ['required', Rule::in(array_column(TicketType::cases(), 'value'))],
            'priority' => 'required|string|in:low,medium,high,urgent',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'A ticket subject is required.',
            'type.required' => 'Please select a ticket type.',
            'priority.required' => 'Please select a priority level.',
        ];
    }
}
