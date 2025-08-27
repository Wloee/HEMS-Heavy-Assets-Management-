<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnit extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Basic unit information
            'kode_unit'          => 'required|string|max:50|unique:unit,kode_unit',
            'nama_unit'          => 'required|string|max:255',
            'jenis_unit_id'      => 'required|exists:jenis_unit,id_jenis_unit',

            // Vehicle details
            'merk'               => 'nullable|string|max:100',
            'model'              => 'nullable|string|max:100',
            'tahun_pembuatan'    => 'nullable|integer|min:1900|max:' . date('Y'),
            'no_rangka'          => 'nullable|string|max:100|unique:unit,no_rangka',
            'no_mesin'           => 'nullable|string|max:100|unique:unit,no_mesin',
            'no_polisi'          => 'nullable|string|max:20',

            // Location and ownership
            'pemilik'            => 'nullable|string|max:255',
            'alamat_unit'        => 'nullable|string',
            'kota'               => 'nullable|string|max:100',
            'provinsi'           => 'nullable|string|max:100',

            // Operational data
            'jam_operasi'        => 'nullable|integer|min:0',
            'status_kepemilikan' => 'required|in:milik_sendiri,sewa,kontrak',
            'status_kondisi'     => 'required|in:baik,perlu_maintenance,rusak',
            'status_operasional' => 'required|in:operasional,maintenance,standby,tidak_aktif',
            'is_active'          => 'boolean',

            // Image uploads
            'gambar_depan'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_belakang'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_kiri'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'gambar_kanan'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
           'kode_unit.required'      => 'Kode unit wajib diisi.',
    'kode_unit.string'        => 'Kode unit harus berupa teks.',
    'kode_unit.max'           => 'Kode unit tidak boleh lebih dari 50 karakter.',
    'kode_unit.unique'        => 'Kode unit sudah digunakan.',

    'nama_unit.required'      => 'Nama unit wajib diisi.',
    'nama_unit.string'        => 'Nama unit harus berupa teks.',
    'nama_unit.max'           => 'Nama unit tidak boleh lebih dari 255 karakter.',

    'jenis_unit_id.required'  => 'Jenis unit wajib dipilih.',
    'jenis_unit_id.exists'    => 'Jenis unit tidak valid.',

    'merk.string'             => 'Merk harus berupa teks.',
    'merk.max'                => 'Merk tidak boleh lebih dari 100 karakter.',
    'model.string'            => 'Model harus berupa teks.',
    'model.max'               => 'Model tidak boleh lebih dari 100 karakter.',

    'tahun_pembuatan.integer' => 'Tahun pembuatan harus berupa angka.',
    'tahun_pembuatan.min'     => 'Tahun pembuatan minimal 1900.',
    'tahun_pembuatan.max'     => 'Tahun pembuatan tidak boleh lebih dari tahun sekarang.',

    'no_rangka.string'        => 'Nomor rangka harus berupa teks.',
    'no_rangka.max'           => 'Nomor rangka tidak boleh lebih dari 100 karakter.',
    'no_rangka.unique'        => 'Nomor rangka sudah terdaftar.',
    'no_mesin.string'         => 'Nomor mesin harus berupa teks.',
    'no_mesin.max'            => 'Nomor mesin tidak boleh lebih dari 100 karakter.',
    'no_mesin.unique'         => 'Nomor mesin sudah terdaftar.',
    'no_polisi.string'        => 'Nomor polisi harus berupa teks.',
    'no_polisi.max'           => 'Nomor polisi tidak boleh lebih dari 20 karakter.',

    'pemilik.string'          => 'Pemilik harus berupa teks.',
    'pemilik.max'             => 'Nama pemilik tidak boleh lebih dari 255 karakter.',
    'alamat_unit.string'      => 'Alamat harus berupa teks.',
    'kota.string'             => 'Kota harus berupa teks.',
    'kota.max'                => 'Kota tidak boleh lebih dari 100 karakter.',
    'provinsi.string'         => 'Provinsi harus berupa teks.',
    'provinsi.max'            => 'Provinsi tidak boleh lebih dari 100 karakter.',

    'jam_operasi.integer'     => 'Jam operasi harus berupa angka.',
    'jam_operasi.min'         => 'Jam operasi tidak boleh kurang dari 0.',

    'status_kepemilikan.required' => 'Status kepemilikan wajib diisi.',
    'status_kepemilikan.in'       => 'Status kepemilikan harus salah satu dari: milik_sendiri, sewa, kontrak.',
    'status_kondisi.required'     => 'Status kondisi wajib diisi.',
    'status_kondisi.in'           => 'Status kondisi harus salah satu dari: baik, perlu_maintenance, rusak.',
    'status_operasional.required' => 'Status operasional wajib diisi.',
    'status_operasional.in'       => 'Status operasional harus salah satu dari: operasional, maintenance, standby, tidak_aktif.',

    'is_active.boolean'           => 'Status aktif harus berupa true atau false.',

    'gambar_depan.image'          => 'Gambar depan harus berupa file gambar.',
    'gambar_depan.mimes'          => 'Gambar depan harus berformat jpeg, png, atau jpg.',
    'gambar_depan.max'            => 'Ukuran gambar depan maksimal 2MB.',
    'gambar_belakang.image'       => 'Gambar belakang harus berupa file gambar.',
    'gambar_belakang.mimes'       => 'Gambar belakang harus berformat jpeg, png, atau jpg.',
    'gambar_belakang.max'         => 'Ukuran gambar belakang maksimal 2MB.',
    'gambar_kiri.image'           => 'Gambar kiri harus berupa file gambar.',
    'gambar_kiri.mimes'           => 'Gambar kiri harus berformat jpeg, png, atau jpg.',
    'gambar_kiri.max'             => 'Ukuran gambar kiri maksimal 2MB.',
    'gambar_kanan.image'          => 'Gambar kanan harus berupa file gambar.',
    'gambar_kanan.mimes'          => 'Gambar kanan harus berformat jpeg, png, atau jpg.',
    'gambar_kanan.max'            => 'Ukuran gambar kanan maksimal 2MB.',
        ];
    }
}
