<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KaryawanRequest extends FormRequest
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
            // Data Pribadi
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'nomor_hp' => 'required|string|max:15|regex:/^[0-9+\-\s]+$/',
            'nik' => 'required|string|size:16|unique:karyawans,nik',
            'scan_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|', // max 2MB
            'surat_lamaran' => 'required|file|mimes:pdf,doc,docx|', // max 5MB

            // Data Pekerjaan
            'id_posisi' => 'required|exists:posisis,id',
            'id_departemen' => 'required|exists:departemens,id',
            'tanggal_bergabung' => 'required|date|after_or_equal:today',

            // Data Penggajian
            'gaji' => 'required|numeric|min:0',
            'tunjangan' => 'nullable|numeric|min:0',
            'insentif' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Data Pribadi
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'nama_lengkap.string' => 'Nama lengkap harus berupa teks',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter',

            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',

            'nomor_hp.required' => 'Nomor HP/WA wajib diisi',
            'nomor_hp.regex' => 'Format nomor HP tidak valid',
            'nomor_hp.max' => 'Nomor HP maksimal 15 karakter',

            'nik.required' => 'NIK wajib diisi',
            'nik.size' => 'NIK harus terdiri dari 16 digit',
            'nik.unique' => 'NIK sudah terdaftar dalam sistem',

            'scan_ktp.required' => 'Scan KTP wajib diupload',
            'scan_ktp.file' => 'Scan KTP harus berupa file',
            'scan_ktp.mimes' => 'Format file KTP harus PDF, JPG, JPEG, atau PNG',
            'scan_ktp.max' => 'Ukuran file KTP maksimal 2MB',

            'surat_lamaran.required' => 'Surat lamaran wajib diupload',
            'surat_lamaran.file' => 'Surat lamaran harus berupa file',
            'surat_lamaran.mimes' => 'Format surat lamaran harus PDF, DOC, atau DOCX',
            'surat_lamaran.max' => 'Ukuran surat lamaran maksimal 5MB',

            // Data Pekerjaan
            'id_posisi.required' => 'Posisi wajib dipilih',
            'id_posisi.exists' => 'Posisi yang dipilih tidak valid',

            'id_departemen.required' => 'Departemen wajib dipilih',
            'id_departemen.exists' => 'Departemen yang dipilih tidak valid',

            'tanggal_bergabung.required' => 'Tanggal bergabung wajib diisi',
            'tanggal_bergabung.date' => 'Format tanggal bergabung tidak valid',
            'tanggal_bergabung.after_or_equal' => 'Tanggal bergabung tidak boleh sebelum hari ini',

            // Data Penggajian
            'gaji.required' => 'Gaji wajib diisi',
            'gaji.numeric' => 'Gaji harus berupa angka',
            'gaji.min' => 'Gaji tidak boleh kurang dari 0',

            'tunjangan.numeric' => 'Tunjangan harus berupa angka',
            'tunjangan.min' => 'Tunjangan tidak boleh kurang dari 0',

            'insentif.numeric' => 'Insentif harus berupa angka',
            'insentif.min' => 'Insentif tidak boleh kurang dari 0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'tanggal_lahir' => 'tanggal lahir',
            'nomor_hp' => 'nomor HP',
            'nik' => 'NIK',
            'scan_ktp' => 'scan KTP',
            'surat_lamaran' => 'surat lamaran',
            'id_posisi' => 'posisi',
            'id_departemen' => 'departemen',
            'tanggal_bergabung' => 'tanggal bergabung',
            'gaji' => 'gaji',
            'tunjangan' => 'tunjangan',
            'insentif' => 'insentif',
        ];
    }


}
