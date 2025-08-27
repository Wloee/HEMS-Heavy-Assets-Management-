<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HemsSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate all tables
        $tables = [
            'users', 'karyawan', 'departemen', 'posisi', 'dokumen_karyawan',
            'client', 'supplier', 'pemilik_unit', 'jenis_unit', 'unit', 'gambar_unit',
            'satuan', 'sparepart', 'sparepart_satuan', 'jenis_pekerjaan',
            'proyek', 'detail_biaya_pekerjaan', 'unit_proyek', 'log_operasional',
            'maintenance_schedule', 'maintenance_log', 'maintenance_sparepart',
            'invoice'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // 1. Seed Departemen
        DB::table('departemen')->insert([
            ['id_departemen' => 1, 'nama_departemen' => 'Operasional', 'created_at' => now(), 'updated_at' => now()],
            ['id_departemen' => 2, 'nama_departemen' => 'Maintenance', 'created_at' => now(), 'updated_at' => now()],
            ['id_departemen' => 3, 'nama_departemen' => 'Administrasi', 'created_at' => now(), 'updated_at' => now()],
            ['id_departemen' => 4, 'nama_departemen' => 'Keuangan', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Seed Posisi
        DB::table('posisi')->insert([
            ['id_posisi' => 1, 'nama_posisi' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['id_posisi' => 2, 'nama_posisi' => 'Operator', 'created_at' => now(), 'updated_at' => now()],
            ['id_posisi' => 3, 'nama_posisi' => 'Teknisi', 'created_at' => now(), 'updated_at' => now()],
            ['id_posisi' => 4, 'nama_posisi' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id_posisi' => 5, 'nama_posisi' => 'Supervisor', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Karyawan
        DB::table('karyawan')->insert([
            [
                'id_karyawan' => 1,
                'nama_lengkap' => 'Budi Santoso',
                'no_nik' => '3274051234567890',
                'tanggal_lahir' => '1980-05-15',
                'no_handphone' => '081234567890',
                'tanggal_bergabung' => '2020-01-15',
                'departemen_id' => 1,
                'posisi_id' => 1,
                'Gaji' => 8000000.00,
                'Tunjangan' => 1000000.00,
                'Intensif' => 500000.00,
                'Status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_karyawan' => 2,
                'nama_lengkap' => 'Ahmad Wijaya',
                'no_nik' => '3274051234567891',
                'tanggal_lahir' => '1985-03-20',
                'no_handphone' => '081234567891',
                'tanggal_bergabung' => '2020-03-01',
                'departemen_id' => 1,
                'posisi_id' => 2,
                'Gaji' => 5000000.00,
                'Tunjangan' => 750000.00,
                'Intensif' => 300000.00,
                'Status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_karyawan' => 3,
                'nama_lengkap' => 'Sari Indah',
                'no_nik' => '3274051234567892',
                'tanggal_lahir' => '1990-07-10',
                'no_handphone' => '081234567892',
                'tanggal_bergabung' => '2021-06-15',
                'departemen_id' => 2,
                'posisi_id' => 3,
                'Gaji' => 6000000.00,
                'Tunjangan' => 800000.00,
                'Intensif' => 400000.00,
                'Status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 4. Seed Users
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@hems.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'karyawan_id' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'operator1',
                'email' => 'operator@hems.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'karyawan_id' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 5. Seed Client
        DB::table('client')->insert([
            [
                'id_client' => 1,
                'nama_client' => 'PT Pembangunan Jaya',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta',
                'no_handphone' => '02112345678',
                'email' => 'contact@pjaya.com',
                'contact_person' => 'Ir. Bambang Sutrisno',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_client' => 2,
                'nama_client' => 'CV Konstruksi Mandiri',
                'alamat' => 'Jl. Gatot Subroto No. 456, Bandung',
                'no_handphone' => '02287654321',
                'email' => 'info@konstruksimandiri.com',
                'contact_person' => 'Drs. Agus Prasetyo',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 6. Seed Supplier
        DB::table('supplier')->insert([
            [
                'id_supplier' => 1,
                'nama_supplier' => 'PT Sparepart Alat Berat',
                'alamat' => 'Jl. Industri No. 789, Bekasi',
                'no_handphone' => '02198765432',
                'email' => 'sales@sparepartab.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_supplier' => 2,
                'nama_supplier' => 'CV Teknik Jaya',
                'alamat' => 'Jl. Raya Bogor No. 321, Depok',
                'no_handphone' => '02156789012',
                'email' => 'contact@teknikjaya.com',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 7. Seed Pemilik Unit
        DB::table('pemilik_unit')->insert([
            [
                'id_pemilik' => 1,
                'nama_pemilik' => 'PT Heavy Equipment Rental',
                'no_handphone' => '02123456789',
                'email' => 'owner@her.com',
                'alamat' => 'Jl. Raya Cikampek KM 47, Karawang',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_pemilik' => 2,
                'nama_pemilik' => 'CV Alat Berat Nusantara',
                'no_handphone' => '02134567890',
                'email' => 'info@abnusantara.com',
                'alamat' => 'Jl. Bypass Ngurah Rai, Denpasar',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 8. Seed Jenis Unit
        DB::table('jenis_unit')->insert([
            ['id_jenis_unit' => 1, 'nama_jenis' => 'Excavator', 'deskripsi' => 'Alat berat untuk penggalian', 'created_at' => now(), 'updated_at' => now()],
            ['id_jenis_unit' => 2, 'nama_jenis' => 'Bulldozer', 'deskripsi' => 'Alat berat untuk perataan tanah', 'created_at' => now(), 'updated_at' => now()],
            ['id_jenis_unit' => 3, 'nama_jenis' => 'Dump Truck', 'deskripsi' => 'Kendaraan pengangkut material', 'created_at' => now(), 'updated_at' => now()],
            ['id_jenis_unit' => 4, 'nama_jenis' => 'Crane', 'deskripsi' => 'Alat pengangkat beban berat', 'created_at' => now(), 'updated_at' => now()],
            ['id_jenis_unit' => 5, 'nama_jenis' => 'Wheel Loader', 'deskripsi' => 'Alat muat dengan roda', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 9. Seed Unit
        DB::table('unit')->insert([
            [
                'id_unit' => 1,
                'kode_unit' => 'EXC001',
                'nama_unit' => 'Excavator Komatsu PC200',
                'jenis_unit_id' => 1,
                'merk' => 'Komatsu',
                'model' => 'PC200-8',
                'tahun_pembuatan' => 2018,
                'no_rangka' => 'KMTPC200180001',
                'no_mesin' => 'SAA6D107E001',
                'no_polisi' => 'B 9001 AB',
                'pemilik_id' => 1,
                'alamat_unit' => 'Workshop Cikampek',
                'kota' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'jam_operasi' => 2500,
                'status_kepemilikan' => 'milik_sendiri',
                'status_kondisi' => 'baik',
                'status_operasional' => 'operasional',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_unit' => 2,
                'kode_unit' => 'BLD001',
                'nama_unit' => 'Bulldozer Caterpillar D6T',
                'jenis_unit_id' => 2,
                'merk' => 'Caterpillar',
                'model' => 'D6T LGP',
                'tahun_pembuatan' => 2019,
                'no_rangka' => 'CATD6T190001',
                'no_mesin' => 'C7ACERT001',
                'no_polisi' => 'B 9002 AB',
                'pemilik_id' => 1,
                'alamat_unit' => 'Workshop Cikampek',
                'kota' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'jam_operasi' => 1800,
                'status_kepemilikan' => 'milik_sendiri',
                'status_kondisi' => 'baik',
                'status_operasional' => 'standby',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 10. Seed Satuan
        DB::table('satuan')->insert([
            ['id_satuan' => 1, 'nama_satuan' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
            ['id_satuan' => 2, 'nama_satuan' => 'liter', 'created_at' => now(), 'updated_at' => now()],
            ['id_satuan' => 3, 'nama_satuan' => 'set', 'created_at' => now(), 'updated_at' => now()],
            ['id_satuan' => 4, 'nama_satuan' => 'kg', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 11. Seed Sparepart
        DB::table('sparepart')->insert([
            [
                'id_sparepart' => 1,
                'kode_sparepart' => 'SP001',
                'nama_sparepart' => 'Filter Oli Hydraulic',
                'merk' => 'Komatsu',
                'deskripsi' => 'Filter oli sistem hydraulic untuk excavator',
                'supplier_id' => 1,
                'stok_minimum' => 5,
                'stok_saat_ini' => 15,
                'harga_beli_terakhir' => 450000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_sparepart' => 2,
                'kode_sparepart' => 'SP002',
                'nama_sparepart' => 'Track Pad',
                'merk' => 'Generic',
                'deskripsi' => 'Karet track untuk excavator',
                'supplier_id' => 1,
                'stok_minimum' => 2,
                'stok_saat_ini' => 8,
                'harga_beli_terakhir' => 2500000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 12. Seed Sparepart Satuan
        DB::table('sparepart_satuan')->insert([
            [
                'id_sparepart_satuan' => 1,
                'sparepart_id' => 1,
                'satuan_id' => 1,
                'konversi' => 1,
                'harga_satuan' => 450000.00,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_sparepart_satuan' => 2,
                'sparepart_id' => 2,
                'satuan_id' => 3,
                'konversi' => 1,
                'harga_satuan' => 2500000.00,
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 13. Seed Jenis Pekerjaan
        DB::table('jenis_pekerjaan')->insert([
            [
                'id_jenis_pekerjaan' => 1,
                'nama_jenis_pekerjaan' => 'Penggalian Tanah',
                'deskripsi' => 'Pekerjaan penggalian untuk pondasi atau saluran',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_jenis_pekerjaan' => 2,
                'nama_jenis_pekerjaan' => 'Perataan Tanah',
                'deskripsi' => 'Pekerjaan perataan dan pembentukan kontur tanah',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_jenis_pekerjaan' => 3,
                'nama_jenis_pekerjaan' => 'Pengangkutan Material',
                'deskripsi' => 'Pekerjaan pengangkutan tanah, pasir, atau material lain',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 14. Seed Proyek
        DB::table('proyek')->insert([
            [
                'id_proyek' => 1,
                'kode_proyek' => 'PRJ2024001',
                'nama_proyek' => 'Pembangunan Perumahan Green Valley',
                'deskripsi' => 'Proyek pembangunan infrastruktur perumahan seluas 10 hektar',
                'tanggal_mulai' => '2024-01-15',
                'tanggal_selesai_rencana' => '2024-06-15',
                'tanggal_selesai_aktual' => null,
                'no_spk' => 'SPK/001/2024',
                'client_id' => 1,
                'project_manager_id' => 1,
                'lokasi_proyek' => 'Jl. Raya Bogor KM 35, Cibinong',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_proyek' => 2,
                'kode_proyek' => 'PRJ2024002',
                'nama_proyek' => 'Pembangunan Jalan Tol Seksi A',
                'deskripsi' => 'Pembangunan jalan tol sepanjang 15 km',
                'tanggal_mulai' => '2024-03-01',
                'tanggal_selesai_rencana' => '2024-12-31',
                'tanggal_selesai_aktual' => null,
                'no_spk' => 'SPK/002/2024',
                'client_id' => 2,
                'project_manager_id' => 1,
                'lokasi_proyek' => 'Ruas Cikampek - Purwakarta',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 15. Seed Detail Biaya Pekerjaan
        DB::table('detail_biaya_pekerjaan')->insert([
            [
                'id_detail_biaya' => 1,
                'proyek_id' => 1,
                'jenis_pekerjaan_id' => 1,
                'deskripsi' => 'Penggalian pondasi rumah tipe 36',
                'volume' => 500.00,
                'satuan' => 'm3',
                'harga_satuan' => 75000.00,
                'biaya_total' => 37500000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_detail_biaya' => 2,
                'proyek_id' => 1,
                'jenis_pekerjaan_id' => 2,
                'deskripsi' => 'Perataan lahan untuk infrastruktur',
                'volume' => 2000.00,
                'satuan' => 'm2',
                'harga_satuan' => 25000.00,
                'biaya_total' => 50000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 16. Seed Unit Proyek
        DB::table('unit_proyek')->insert([
            [
                'id_unit_proyek' => 1,
                'unit_id' => 1,
                'proyek_id' => 1,
                'tanggal_mulai' => '2024-01-15',
                'tanggal_selesai' => null,
                'operator_id' => 2,
                'tarif_sewa_harian' => 2500000.00,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_unit_proyek' => 2,
                'unit_id' => 2,
                'proyek_id' => 2,
                'tanggal_mulai' => '2024-03-01',
                'tanggal_selesai' => null,
                'operator_id' => 2,
                'tarif_sewa_harian' => 3000000.00,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 17. Seed Log Operasional
        DB::table('log_operasional')->insert([
            [
                'id_log' => 1,
                'unit_proyek_id' => 1,
                'tanggal_operasi' => '2024-01-15',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '15:00:00',
                'jam_operasi' => 8.00,
                'jam_idle' => 0.00,
                'operator_id' => 2,
                'lokasi_kerja' => 'Area A - Penggalian Pondasi',
                'jenis_pekerjaan' => 'Penggalian tanah untuk pondasi',
                'keterangan' => 'Operasi normal, tidak ada kendala',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_log' => 2,
                'unit_proyek_id' => 1,
                'tanggal_operasi' => '2024-01-16',
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '14:00:00',
                'jam_operasi' => 6.50,
                'jam_idle' => 0.50,
                'operator_id' => 2,
                'lokasi_kerja' => 'Area B - Penggalian Pondasi',
                'jenis_pekerjaan' => 'Penggalian tanah untuk pondasi',
                'keterangan' => 'Sempat idle 30 menit karena hujan',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 18. Seed Maintenance Schedule
        DB::table('maintenance_schedule')->insert([
            [
                'id_schedule' => 1,
                'unit_id' => 1,
                'jenis_maintenance' => 'Service Rutin 250 Jam',
                'interval_jam' => 250,
                'interval_hari' => null,
                'deskripsi' => 'Ganti oli mesin, filter oli, filter udara, dan pengecekan umum',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_schedule' => 2,
                'unit_id' => 1,
                'jenis_maintenance' => 'Greasing Mingguan',
                'interval_jam' => null,
                'interval_hari' => 7,
                'deskripsi' => 'Pelumasan semua titik grease nipple',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 19. Seed Maintenance Log
        DB::table('maintenance_log')->insert([
            [
                'id_maintenance' => 1,
                'unit_id' => 1,
                'schedule_id' => 1,
                'tanggal_maintenance' => '2024-01-10',
                'jenis_maintenance' => 'rutin',
                'deskripsi_pekerjaan' => 'Service rutin 250 jam operasi',
                'teknisi_id' => 3,
                'workshop' => 'Workshop Utama Cikampek',
                'jam_operasi_saat_maintenance' => 2500,
                'biaya_jasa' => 500000.00,
                'biaya_sparepart' => 750000.00,
                'biaya_total' => 1250000.00,
                'status' => 'selesai',
                'catatan' => 'Semua komponen dalam kondisi baik',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 20. Seed Maintenance Sparepart
        DB::table('maintenance_sparepart')->insert([
            [
                'id_maintenance_sparepart' => 1,
                'maintenance_id' => 1,
                'sparepart_id' => 1,
                'jumlah_digunakan' => 1.00,
                'satuan_id' => 1,
                'harga_satuan' => 450000.00,
                'total_harga' => 450000.00,
                'created_at' => now()
            ],
        ]);

        // 21. Seed Invoice
        DB::table('invoice')->insert([
            [
                'id_invoice' => 1,
                'no_invoice' => 'INV/2024/001',
                'proyek_id' => 1,
                'tanggal_invoice' => '2024-01-31',
                'tanggal_jatuh_tempo' => '2024-02-29',
                'jumlah_tagihan' => 75000000.00,
                'jumlah_terbayar' => 25000000.00,
                'sisa_piutang' => 50000000.00,
                'status' => 'dibayar_sebagian',
                'keterangan' => 'Invoice untuk bulan Januari 2024',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('HEMS database seeded successfully!');
    }
}
