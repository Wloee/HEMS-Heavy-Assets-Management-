<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                'unique:users,name',
                'regex:/^[a-zA-Z0-9\s\-_.]+$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role' => [
                'required',
                Rule::in(['admin', 'manager', 'operator', 'teknisi'])
            ],
            'karyawan_id' => [
                'nullable',
                'exists:karyawan,id_karyawan'
            ],
            'is_active' => [
                'nullable',
                'boolean'
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 50 karakter.',
            'name.unique' => 'Nama sudah digunakan.',
            'name.regex' => 'Nama hanya boleh mengandung huruf, angka, spasi, tanda hubung, underscore, dan titik.',

            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'email.unique' => 'Email sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung minimal 1 huruf kecil, 1 huruf besar, dan 1 angka.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',

            'karyawan_id.exists' => 'Karyawan tidak ditemukan.',

            'is_active.boolean' => 'Status aktif harus berupa true/false.'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'password',
            'role' => 'role',
            'karyawan_id' => 'karyawan',
            'is_active' => 'status aktif'
        ];
    }
}
