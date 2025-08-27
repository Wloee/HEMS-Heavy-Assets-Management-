<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // atau sesuai logic authorization Anda
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? $this->user;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'role' => [
                'required',
                'string',
                'in:admin,user,manager,operator,teknisi,admin,supervisor,logistik' // sesuaikan dengan role yang ada
            ],
            'karyawan_id' => [
                'nullable',
                'integer',
                'exists:karyawan,id_karyawan' // pastikan foreign key valid
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed' // memerlukan password_confirmation field
            ],
            'password_confirmation' => [
                'nullable',
                'required_with:password'
            ],
            'is_active' => [
                'boolean'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 2 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'karyawan_id.exists' => 'Karyawan tidak ditemukan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Clean up data before validation
        $this->merge([
            'name' => trim($this->name ?? ''),
            'email' => strtolower(trim($this->email ?? '')),
            'is_active' => $this->boolean('is_active'),
            'karyawan_id' => $this->karyawan_id ?: null
        ]);
    }
}
