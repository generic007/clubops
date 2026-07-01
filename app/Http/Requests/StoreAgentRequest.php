<?php

namespace App\Http\Requests;

use App\Enums\AgentRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $agentId = $this->route('agent')?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('agents', 'email')->ignore($agentId),
            ],
            'password' => $agentId
                ? 'nullable|string|min:8|max:100'
                : 'required|string|min:8|max:100',
            'role' => ['required', Rule::in(array_column(AgentRole::cases(), 'value'))],
            'phone' => 'nullable|string|max:50',
            'active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'A password is required when creating a new agent.',
            'password.min' => 'Password must be at least 8 characters.',
        ];
    }
}
