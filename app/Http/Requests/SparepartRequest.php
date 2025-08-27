<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SparepartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah ke true jika ingin mengizinkan request, atau tambahkan logika otorisasi
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode_sparepart' => [
                'nullable',
                'string',
                'max:50',
            ],
            'nama_sparepart' => 'required|string|max:255|min:3',
            'merk' => 'nullable|string|max:100',
            'supplier_id' => 'nullable|exists:supplier,id_supplier',
            'stok_minimum' => 'required|integer|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
            'is_active' => [
                'required',
                'boolean', ],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode_sparepart.unique' => 'Kode sparepart sudah digunakan.',
            'kode_sparepart.max' => 'Kode sparepart tidak boleh lebih dari 50 karakter.',
            'nama_sparepart.required' => 'Nama sparepart wajib diisi.',
            'nama_sparepart.min' => 'Nama sparepart minimal 3 karakter.',
            'nama_sparepart.max' => 'Nama sparepart maksimal 255 karakter.',
            'merk.max' => 'Merk tidak boleh lebih dari 100 karakter.',
            'supplier_id.exists' => 'Supplier tidak valid.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'stok_minimum.min' => 'Stok minimum tidak boleh negatif.',
            'stok_saat_ini.required' => 'Stok saat ini wajib diisi.',
            'stok_saat_ini.min' => 'Stok saat ini tidak boleh negatif.',
            'is_active.required' => 'Status aktif wajib dipilih.',
            'is_active.boolean' => 'Status aktif harus berupa pilihan ya/tidak.',
        ];
    }
}
