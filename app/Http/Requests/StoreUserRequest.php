<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'role' => 'required|in:admin,manajer_gudang,staff_gudang',
            'password' => $this->isMethod('POST') ? 'required|string|min:6' : 'nullable|string|min:6',
        ];
    }
}