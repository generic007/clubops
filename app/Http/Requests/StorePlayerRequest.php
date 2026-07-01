<?php

namespace App\Http\Requests;

use App\Enums\PlayerStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'status' => ['required', Rule::in(array_column(PlayerStatus::cases(), 'value'))],
            'referral_source' => 'nullable|string|max:255',
            'agent_id' => 'nullable|exists:agents,id',
            'platform_accounts' => 'nullable|array',
            'platform_accounts.*.platform' => 'required_with:platform_accounts|string|max:50',
            'platform_accounts.*.username' => 'required_with:platform_accounts|string|max:255',
            'platform_accounts.*.user_id' => 'nullable|string|max:255',
            'platform_accounts.*.verified' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'platform_accounts.*.platform.required_with' => 'Each platform account must have a platform selected.',
            'platform_accounts.*.username.required_with' => 'Each platform account must have a username.',
        ];
    }
}
