<?php

namespace App\Http\Requests;

use App\Enums\AgentRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() || $this->user()?->isManager();
    }

    public function rules(): array
    {
        $agentId = $this->route('agent')?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('agents')->ignore($agentId)],
            'password' => $agentId ? 'nullable|min:8' : 'required|min:8',
            'role' => 'required|in:' . implode(',', array_map(fn($r) => $r->value, AgentRole::cases())),
            'phone' => 'nullable|string|max:20',
            'active' => 'boolean',
        ];
    }
}
