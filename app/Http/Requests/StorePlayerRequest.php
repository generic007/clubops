<?php

namespace App\Http\Requests;

use App\Enums\PlayerStatus;
use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'preferred_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:' . implode(',', array_map(fn($s) => $s->value, PlayerStatus::cases())),
            'referral_source' => 'nullable|string|max:255',
            'agent_id' => 'nullable|exists:agents,id',
            'assigned_admin_id' => 'nullable|exists:agents,id',
            'compliance_complete' => 'boolean',
            'notes' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'platform_accounts' => 'nullable|array',
            'platform_accounts.*.platform' => 'required_with:platform_accounts|in:ClubGG,PPPoker,PokerBros',
            'platform_accounts.*.username' => 'required_with:platform_accounts|string|max:255',
            'platform_accounts.*.user_id' => 'nullable|string|max:255',
            'platform_accounts.*.verified' => 'boolean',
        ];
    }
}
