<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Supplier extends FormRequest
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
            'nama_supplier'      => 'required',
            'alamat'             => 'required',
            'no_handphone'       => 'required',
            'email'              => 'required',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_supplier.required'   => 'Nama supplier wajib diisi.',
            'alamat_supplier.required' => 'Alamat supplier wajib diisi.',
            'no_handphone.required' => 'Kontak supplier wajib diisi.',
            'is_active.required'       => 'Status aktif harus dipilih.',
            'email.required'            =>'Email wajib diisi.',
        ];
    }
}
