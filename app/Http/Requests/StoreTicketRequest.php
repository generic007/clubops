<?php

namespace App\Http\Requests;

use App\Enums\TicketType;
use Illuminate\Foundation\Http\FormRequest;

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
            'assigned_to' => 'nullable|exists:agents,id',
            'type' => 'required|in:' . implode(',', array_map(fn($t) => $t->value, TicketType::cases())),
            'priority' => 'required|in:low,normal,high,urgent',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }
}
