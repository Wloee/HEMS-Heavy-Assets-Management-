<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class HemsSeeder extends Seeder
{   public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        $this->truncateTables();

        // Seed in order of dependencies
        $this->seedSatuan();
        $this->seedDepartemen();
        $this->seedPosisi();
        $this->seedKaryawan();
        $this->seedUsers();
        $this->seedClient();
        $this->seedJenisPekerjaan();
        $this->seedJenisUnit();
        $this->seedPemilikUnit();
        $this->seedUnit();
        $this->seedGambarUnit();
        $this->seedSupplier();
        $this->seedSparepart();
        $this->seedSparepartSatuan();
        $this->seedProyek();
        $this->seedDetailBiayaPekerjaan();
        $this->seedUnitProyek();
        $this->seedInvoice();
        $this->seedMaintenanceSchedule();
        $this->seedMaintenanceLog();
        $this->seedLogOperasional();
        $this->seedSparepartPengadaan();
        $this->seedPermintaanSparepart();
        $this->seedDokumenKaryawan();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function truncateTables()
    {
        $tables = [
            'dokumen_karyawan', 'permintaan_sparepart_detail', 'permintaan_sparepart',
            'sparepart_pengadaan_detail', 'sparepart_pengadaan', 'maintenance_sparepart',
            'maintenance_log', 'maintenance_schedule', 'log_operasional', 'unit_proyek',
            'invoice', 'detail_biaya_pekerjaan', 'gambar_unit', 'sparepart_satuan',
            'sparepart', 'unit', 'addendum', 'proyek', 'users', 'karyawan',
            'supplier', 'pemilik_unit', 'jenis_unit', 'jenis_pekerjaan', 'client',
            'posisi', 'departemen', 'satuan'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }

    private function seedSatuan()
    {
        $data = [
            ['nama_satuan' => 'Pcs', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Set', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Liter', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Kg', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Meter', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Box', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Roll', 'created_at' => now(), 'updated_at' => now()],
            ['nama_satuan' => 'Botol', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('satuan')->insert($data);
    }

    private function seedDepartemen()
    {
        $data = [
            ['nama_departemen' => 'Operasional', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'Maintenance', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'Administrasi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'Keuangan', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('departemen')->insert($data);
    }

    private function seedPosisi()
    {
        $data = [
            ['nama_posisi' => 'Operator Excavator', 'created_at' => now(), 'updated_at' => now()],
            ['nama_posisi' => 'Operator Bulldozer', 'created_at' => now(), 'updated_at' => now()],
            ['nama_posisi' => 'Operator Dump Truck', 'created_at' => now(), 'updated_at' => now()],
            ['nama_posisi' => 'Teknisi Mekanik', 'created_at' => now(), 'updated_at' => now()],
            ['nama_posisi' => 'Supervisor', 'created_at' => now(), 'updated_at' => now()],
            ['nama_posisi' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['nama_posisi' => 'Manager', 'created_at' => now(), 'updated_at' => now()],
            ['nama_posisi' => 'Teknisi Listrik', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('posisi')->insert($data);
    }

    private function seedKaryawan()
    {
        $data = [
            [
                'nama_karyawan' => 'Ahmad Fauzi',
                'no_nik' => '1234567890123456',
                'tanggal_lahir' => '1985-05-15',
                'no_handphone' => '081234567890',
                'tanggal_bergabung' => '2020-01-15',
                'departemen_id' => 1,
                'posisi_id' => 1,
                'Gaji' => 5000000.00,
                'Tunjangan' => 500000.00,
                'Intensif' => 300000.00,
                'Status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_karyawan' => 'Budi Santoso',
                'no_nik' => '1234567890123457',
                'tanggal_lahir' => '1982-08-22',
                'no_handphone' => '081234567891',
                'tanggal_bergabung' => '2019-03-10',
                'departemen_id' => 2,
                'posisi_id' => 4,
                'Gaji' => 4500000.00,
                'Tunjangan' => 450000.00,
                'Intensif' => 250000.00,
                'Status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_karyawan' => 'Citra Dewi',
                'no_nik' => '1234567890123458',
                'tanggal_lahir' => '1990-12-08',
                'no_handphone' => '081234567892',
                'tanggal_bergabung' => '2021-06-01',
                'departemen_id' => 3,
                'posisi_id' => 6,
                'Gaji' => 3500000.00,
                'Tunjangan' => 350000.00,
                'Intensif' => 200000.00,
                'Status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_karyawan' => 'Dedi Kurniawan',
                'no_nik' => '1234567890123459',
                'tanggal_lahir' => '1987-03-25',
                'no_handphone' => '081234567893',
                'tanggal_bergabung' => '2018-09-15',
                'departemen_id' => 1,
                'posisi_id' => 7,
                'Gaji' => 8000000.00,
                'Tunjangan' => 1000000.00,
                'Intensif' => 500000.00,
                'Status' => 'Aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('karyawan')->insert($data);
    }

    private function seedUsers()
    {
        $data = [
            [
                'name' => 'admin',
                'email' => 'admin@hems.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'karyawan_id' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'manager',
                'email' => 'manager@hems.com',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'karyawan_id' => 4,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'operator1',
                'email' => 'operator1@hems.com',
                'password' => Hash::make('password123'),
                'role' => 'operator',
                'karyawan_id' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'teknisi1',
                'email' => 'teknisi1@hems.com',
                'password' => Hash::make('password123'),
                'role' => 'teknisi',
                'karyawan_id' => 2,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('users')->insert($data);
    }

    private function seedClient()
    {
        $data = [
            [
                'nama_client' => 'PT. Pembangunan Infrastruktur',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Selatan',
                'no_handphone' => '021-5551234',
                'email' => 'info@ptpi.co.id',
                'contact_person' => 'Ir. Bambang Wijaya',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_client' => 'CV. Konstruksi Mandiri',
                'alamat' => 'Jl. Gatot Subroto No. 456, Bandung',
                'no_handphone' => '022-7771234',
                'email' => 'admin@cvkm.co.id',
                'contact_person' => 'Sandi Pratama',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('client')->insert($data);
    }

    private function seedJenisPekerjaan()
    {
        $data = [
            ['nama_jenis_pekerjaan' => 'Penggalian Tanah', 'deskripsi' => 'Pekerjaan penggalian dan pemindahan tanah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_pekerjaan' => 'Perataan Lahan', 'deskripsi' => 'Pekerjaan perataan dan grading lahan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_pekerjaan' => 'Pembangunan Jalan', 'deskripsi' => 'Konstruksi dan pemeliharaan jalan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_pekerjaan' => 'Demolisi', 'deskripsi' => 'Pembongkaran bangunan dan struktur', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_pekerjaan' => 'Pemindahan Material', 'deskripsi' => 'Transport material konstruksi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_pekerjaan' => 'Drainase', 'deskripsi' => 'Pembangunan sistem drainase', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_pekerjaan' => 'Landscaping', 'deskripsi' => 'Penataan lanskap dan taman', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis_pekerjaan' => 'Pemadatan Tanah', 'deskripsi' => 'Kompaksi dan pemadatan tanah', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('jenis_pekerjaan')->insert($data);
    }

    private function seedJenisUnit()
    {
        $data = [
            ['nama_jenis' => 'Excavator', 'deskripsi' => 'Alat berat untuk penggalian', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Bulldozer', 'deskripsi' => 'Alat berat untuk perataan tanah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Dump Truck', 'deskripsi' => 'Truk pengangkut material', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Crane', 'deskripsi' => 'Alat angkat berat', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Compactor', 'deskripsi' => 'Alat pemadat tanah', 'created_at' => now(), 'updated_at' => now()],
            ['nama_jenis' => 'Grader', 'deskripsi' => 'Alat perata jalan', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('jenis_unit')->insert($data);
    }

    private function seedPemilikUnit()
    {
        $data = [
            ['nama_pemilik' => 'PT. Heavy Equipment Rental', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nama_pemilik' => 'CV. Alat Berat Sejahtera', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('pemilik_unit')->insert($data);
    }

    private function seedUnit()
    {
        $data = [
            [
                'kode_unit' => 'EXC001',
                'nama_unit' => 'Excavator Komatsu PC200-8',
                'jenis_unit_id' => 1,
                'merk' => 'Komatsu',
                'model' => 'PC200-8',
                'tahun_pembuatan' => 2018,
                'no_rangka' => 'KMTPC200X18001234',
                'no_mesin' => 'SAA6D107E-1-001',
                'no_polisi' => 'B 1234 ABC',
                'pemilik_id' => 1,
                'alamat_unit' => 'Workshop Cibitung',
                'kota' => 'Bekasi',
                'provinsi' => 'Jawa Barat',
                'jam_operasi' => 2450,
                'status_kepemilikan' => 'milik_sendiri',
                'status_kondisi' => 'baik',
                'status_operasional' => 'operasional',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_unit' => 'BLD001',
                'nama_unit' => 'Bulldozer Caterpillar D6T',
                'jenis_unit_id' => 2,
                'merk' => 'Caterpillar',
                'model' => 'D6T',
                'tahun_pembuatan' => 2019,
                'no_rangka' => 'CATD6TX19005678',
                'no_mesin' => 'C7.1-002',
                'no_polisi' => 'B 5678 DEF',
                'pemilik_id' => 1,
                'alamat_unit' => 'Workshop Cibitung',
                'kota' => 'Bekasi',
                'provinsi' => 'Jawa Barat',
                'jam_operasi' => 1800,
                'status_kepemilikan' => 'milik_sendiri',
                'status_kondisi' => 'baik',
                'status_operasional' => 'standby',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_unit' => 'DMP001',
                'nama_unit' => 'Dump Truck Hino FM260JD',
                'jenis_unit_id' => 3,
                'merk' => 'Hino',
                'model' => 'FM260JD',
                'tahun_pembuatan' => 2020,
                'no_rangka' => 'HNOFM260X20009876',
                'no_mesin' => 'J08E-UH-003',
                'no_polisi' => 'B 9876 GHI',
                'pemilik_id' => 2,
                'alamat_unit' => 'Pool Karawang',
                'kota' => 'Karawang',
                'provinsi' => 'Jawa Barat',
                'jam_operasi' => 1200,
                'status_kepemilikan' => 'sewa',
                'status_kondisi' => 'baik',
                'status_operasional' => 'operasional',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('unit')->insert($data);
    }

    private function seedGambarUnit()
    {
        $data = [
            [
                'unit_id' => 1,
                'gambar_depan' => 'units/EXC001_depan.jpg',
                'gambar_belakang' => 'units/EXC001_belakang.jpg',
                'gambar_kiri' => 'units/EXC001_kiri.jpg',
                'gambar_kanan' => 'units/EXC001_kanan.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('gambar_unit')->insert($data);
    }

    private function seedSupplier()
    {
        $data = [
            [
                'nama_supplier' => 'PT. Sparepart Heavy Equipment',
                'alamat' => 'Jl. Industri No. 45, Cibitung',
                'no_handphone' => '021-8881234',
                'email' => 'sales@sphe.co.id',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_supplier' => 'CV. Onderdil Alat Berat',
                'alamat' => 'Jl. Raya Bekasi No. 78, Bekasi',
                'no_handphone' => '021-8889876',
                'email' => 'info@cvoab.co.id',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_supplier' => 'Toko Spare Part Mandiri',
                'alamat' => 'Jl. Ahmad Yani No. 123, Karawang',
                'no_handphone' => '0267-123456',
                'email' => 'mandiri@gmail.com',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('supplier')->insert($data);
    }

    private function seedSparepart()
    {
        $data = [
            [
                'kode_sparepart' => 'SP001',
                'nama_sparepart' => 'Filter Oli Hydraulic',
                'merk' => 'Komatsu',
                'supplier_id' => 1,
                'stok_minimum' => 5,
                'stok_saat_ini' => 15,
                'is_active' => 1,
                'deksripsi_produk' => 'Filter oli hydraulic untuk excavator Komatsu PC200',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_sparepart' => 'SP002',
                'nama_sparepart' => 'Belt Fan Alternator',
                'merk' => 'Caterpillar',
                'supplier_id' => 1,
                'stok_minimum' => 3,
                'stok_saat_ini' => 8,
                'is_active' => 1,
                'deksripsi_produk' => 'Belt kipas alternator untuk bulldozer CAT D6T',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_sparepart' => 'SP003',
                'nama_sparepart' => 'Brake Pad Depan',
                'merk' => 'Hino',
                'supplier_id' => 2,
                'stok_minimum' => 4,
                'stok_saat_ini' => 12,
                'is_active' => 1,
                'deksripsi_produk' => 'Kampas rem depan untuk dump truck Hino',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_sparepart' => 'SP004',
                'nama_sparepart' => 'Seal O-Ring Kit',
                'merk' => 'Universal',
                'supplier_id' => 3,
                'stok_minimum' => 10,
                'stok_saat_ini' => 25,
                'is_active' => 1,
                'deksripsi_produk' => 'Set seal O-ring untuk sistem hydraulic',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode_sparepart' => 'SP005',
                'nama_sparepart' => 'Air Filter Element',
                'merk' => 'Donaldson',
                'supplier_id' => 1,
                'stok_minimum' => 6,
                'stok_saat_ini' => 18,
                'is_active' => 1,
                'deksripsi_produk' => 'Element filter udara untuk mesin diesel',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('sparepart')->insert($data);
    }

    private function seedSparepartSatuan()
    {
        $data = [
            ['sparepart_id' => 1, 'satuan_id' => 1, 'konversi' => 1, 'harga_satuan' => 125000.00, 'is_default' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['sparepart_id' => 2, 'satuan_id' => 1, 'konversi' => 1, 'harga_satuan' => 85000.00, 'is_default' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['sparepart_id' => 3, 'satuan_id' => 2, 'konversi' => 1, 'harga_satuan' => 450000.00, 'is_default' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['sparepart_id' => 4, 'satuan_id' => 2, 'konversi' => 1, 'harga_satuan' => 75000.00, 'is_default' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['sparepart_id' => 5, 'satuan_id' => 1, 'konversi' => 1, 'harga_satuan' => 165000.00, 'is_default' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('sparepart_satuan')->insert($data);
    }

    private function seedProyek()
    {
        $data = [
            [
                'nama_proyek' => 'Pembangunan Jalan Tol Cibitung-Cikarang',
                'deskripsi' => 'Proyek pembangunan jalan tol sepanjang 15 km dengan lebar 4 lajur',
                'tanggal_mulai' => '2024-01-15',
                'tanggal_selesai_aktual' => null,
                'id_addendum' => null,
                'nama_client' => 'PT. Pembangunan Infrastruktur',
                'lokasi_proyek' => 'Cibitung - Cikarang, Jawa Barat',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_proyek' => 'Pembangunan Perumahan Green Valley',
                'deskripsi' => 'Land clearing dan site preparation untuk perumahan 500 unit',
                'tanggal_mulai' => '2024-03-01',
                'tanggal_selesai_aktual' => '2024-07-15',
                'id_addendum' => null,
                'nama_client' => 'CV. Konstruksi Mandiri',
                'lokasi_proyek' => 'Bandung, Jawa Barat',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('proyek')->insert($data);
    }

    private function seedDetailBiayaPekerjaan()
    {
        $data = [
            [
                'proyek_id' => 1,
                'jenis_pekerjaan_id' => 1,
                'deskripsi' => 'Penggalian tanah untuk fondasi jalan sepanjang 5 km',
                'biaya_total' => 2500000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'proyek_id' => 1,
                'jenis_pekerjaan_id' => 2,
                'deskripsi' => 'Perataan lahan untuk persiapan aspal',
                'biaya_total' => 1800000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'proyek_id' => 2,
                'jenis_pekerjaan_id' => 1,
                'deskripsi' => 'Land clearing area 50 hektar',
                'biaya_total' => 800000000.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('detail_biaya_pekerjaan')->insert($data);
    }

    private function seedUnitProyek()
    {
        $data = [
            [
                'unit_id' => 1,
                'proyek_id' => 1,
                'tanggal_mulai' => '2024-01-15',
                'tanggal_selesai' => null,
                'operator_id' => 1,
                'tarif_sewa_harian' => 2500000.00,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'unit_id' => 3,
                'proyek_id' => 1,
                'tanggal_mulai' => '2024-01-20',
                'tanggal_selesai' => null,
                'operator_id' => null,
                'tarif_sewa_harian' => 1800000.00,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('unit_proyek')->insert($data);
    }

    private function seedInvoice()
    {
        $data = [
            [
                'proyek_id' => 1,
                'tanggal_invoice' => '2024-02-01',
                'tanggal_jatuh_tempo' => '2024-03-02',
                'jumlah_tagihan' => 450000000.00,
                'sisa_piutang' => 450000000.00,
                'status' => 'terkirim',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'proyek_id' => 1,
                'tanggal_invoice' => '2024-03-01',
                'tanggal_jatuh_tempo' => '2024-04-01',
                'jumlah_tagihan' => 520000000.00,
                'sisa_piutang' => 260000000.00,
                'status' => 'dibayar_sebagian',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'proyek_id' => 2,
                'tanggal_invoice' => '2024-07-15',
                'tanggal_jatuh_tempo' => '2024-08-15',
                'jumlah_tagihan' => 800000000.00,
                'sisa_piutang' => 0.00,
                'status' => 'lunas',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('invoice')->insert($data);
    }

    private function seedMaintenanceSchedule()
    {
        $data = [
            [
                'unit_id' => 1,
                'jenis_maintenance' => 'Service Rutin',
                'interval_jam' => 250,
                'interval_hari' => null,
                'deskripsi' => 'Ganti oli mesin dan filter, cek sistem hydraulic',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'unit_id' => 1,
                'jenis_maintenance' => 'Greasing',
                'interval_jam' => 50,
                'interval_hari' => null,
                'deskripsi' => 'Pelumasan semua titik grease nipple',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'unit_id' => 2,
                'jenis_maintenance' => 'Service Berkala',
                'interval_jam' => 500,
                'interval_hari' => null,
                'deskripsi' => 'Service lengkap engine dan transmission',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'unit_id' => 3,
                'jenis_maintenance' => 'Inspeksi Harian',
                'interval_jam' => null,
                'interval_hari' => 1,
                'deskripsi' => 'Cek kondisi ban, rem, dan sistem kelistrikan',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('maintenance_schedule')->insert($data);
    }

    private function seedMaintenanceLog()
    {
        $data = [
            [
                'unit_id' => 1,
                'schedule_id' => 1,
                'tanggal_maintenance' => '2024-02-15',
                'jenis_maintenance' => 'rutin',
                'deskripsi_pekerjaan' => 'Ganti oli mesin SAE 15W-40, ganti filter oli hydraulic, cek level coolant',
                'teknisi_id' => 2,
                'workshop' => 'Workshop Cibitung',
                'jam_operasi_saat_maintenance' => 2250,
                'biaya_jasa' => 500000.00,
                'biaya_sparepart' => 350000.00,
                'biaya_total' => 850000.00,
                'status' => 'selesai',
                'catatan' => 'Kondisi unit baik, tidak ada masalah yang ditemukan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'unit_id' => 2,
                'schedule_id' => null,
                'tanggal_maintenance' => '2024-03-10',
                'jenis_maintenance' => 'perbaikan',
                'deskripsi_pekerjaan' => 'Perbaikan kebocoran hydraulic cylinder',
                'teknisi_id' => 2,
                'workshop' => 'Workshop Cibitung',
                'jam_operasi_saat_maintenance' => 1650,
                'biaya_jasa' => 1200000.00,
                'biaya_sparepart' => 850000.00,
                'biaya_total' => 2050000.00,
                'status' => 'selesai',
                'catatan' => 'Seal hydraulic sudah diganti, test operasi normal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'unit_id' => 3,
                'schedule_id' => 4,
                'tanggal_maintenance' => '2024-04-01',
                'jenis_maintenance' => 'rutin',
                'deskripsi_pekerjaan' => 'Ganti brake pad depan dan belakang',
                'teknisi_id' => 2,
                'workshop' => 'Workshop Karawang',
                'jam_operasi_saat_maintenance' => 1180,
                'biaya_jasa' => 300000.00,
                'biaya_sparepart' => 900000.00,
                'biaya_total' => 1200000.00,
                'status' => 'selesai',
                'catatan' => 'Brake pad lama sudah tipis, diganti preventif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('maintenance_log')->insert($data);
    }

    private function seedLogOperasional()
    {
        $dates = [
            '2024-08-01', '2024-08-02', '2024-08-03', '2024-08-04', '2024-08-05',
            '2024-08-06', '2024-08-07', '2024-08-08', '2024-08-09', '2024-08-10'
        ];

        $data = [];

        foreach ($dates as $date) {
            // Log untuk unit_proyek_id 1 (Excavator)
            $data[] = [
                'unit_proyek_id' => 1,
                'tanggal_operasi' => $date,
                'jam_mulai' => '07:00:00',
                'jam_selesai' => '16:00:00',
                'jam_operasi' => 8.0,
                'jam_idle' => 1.0,
                'operator_id' => 1,
                'lokasi_kerja' => 'KM 5+000 - KM 7+500',
                'jenis_pekerjaan' => 'Penggalian tanah dan loading ke dump truck',
                'keterangan' => 'Kondisi cuaca cerah, produktivitas normal',
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Log untuk unit_proyek_id 2 (Dump Truck)
            $data[] = [
                'unit_proyek_id' => 2,
                'tanggal_operasi' => $date,
                'jam_mulai' => '07:30:00',
                'jam_selesai' => '15:30:00',
                'jam_operasi' => 7.5,
                'jam_idle' => 0.5,
                'operator_id' => null,
                'lokasi_kerja' => 'KM 5+000 - Disposal Area B',
                'jenis_pekerjaan' => 'Transport tanah galian ke disposal area',
                'keterangan' => '15 rit berhasil diselesaikan',
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        DB::table('log_operasional')->insert($data);
    }

    private function seedSparepartPengadaan()
    {
        $data = [
            [
                'supplier_id' => 1,
                'tanggal_pembelian' => '2024-01-15',
                'total_harga' => 2500000.00,
                'Status' => 'completed',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'supplier_id' => 2,
                'tanggal_pembelian' => '2024-02-10',
                'total_harga' => 1800000.00,
                'Status' => 'completed',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'supplier_id' => 1,
                'tanggal_pembelian' => '2024-03-05',
                'total_harga' => 3200000.00,
                'Status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('sparepart_pengadaan')->insert($data);
    }

    private function seedSparepartPengadaanDetail()
    {
        $data = [
            [
                'pembelian_id' => 1,
                'sparepart_id' => 1,
                'satuan_id' => 1,
                'jumlah' => 10,
                'harga_satuan' => 125000.00,
                'subtotal' => 1250000.00,
                'kode_sparepart' => 'SP001',
                'nama_sparepart' => 'Filter Oli Hydraulic'
            ],
            [
                'pembelian_id' => 1,
                'sparepart_id' => 5,
                'satuan_id' => 1,
                'jumlah' => 8,
                'harga_satuan' => 165000.00,
                'subtotal' => 1320000.00,
                'kode_sparepart' => 'SP005',
                'nama_sparepart' => 'Air Filter Element'
            ],
            [
                'pembelian_id' => 2,
                'sparepart_id' => 3,
                'satuan_id' => 2,
                'jumlah' => 4,
                'harga_satuan' => 450000.00,
                'subtotal' => 1800000.00,
                'kode_sparepart' => 'SP003',
                'nama_sparepart' => 'Brake Pad Depan'
            ],
            [
                'pembelian_id' => 3,
                'sparepart_id' => 2,
                'satuan_id' => 1,
                'jumlah' => 15,
                'harga_satuan' => 85000.00,
                'subtotal' => 1275000.00,
                'kode_sparepart' => 'SP002',
                'nama_sparepart' => 'Belt Fan Alternator'
            ],
            [
                'pembelian_id' => 3,
                'sparepart_id' => 4,
                'satuan_id' => 2,
                'jumlah' => 25,
                'harga_satuan' => 75000.00,
                'subtotal' => 1875000.00,
                'kode_sparepart' => 'SP004',
                'nama_sparepart' => 'Seal O-Ring Kit'
            ],
        ];

        DB::table('sparepart_pengadaan_detail')->insert($data);
    }

    private function seedPermintaanSparepart()
    {
        $data = [
            [
                'id_karyawan' => 2,
                'tanggal_permintaan' => '2024-08-15',
                'status' => 'approved',
                'keterangan' => 'Untuk maintenance rutin excavator EXC001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id_karyawan' => 2,
                'tanggal_permintaan' => '2024-08-20',
                'status' => 'pending',
                'keterangan' => 'Stok menipis, perlu restock untuk emergency',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('permintaan_sparepart')->insert($data);
    }

    private function seedPermintaanSparepartDetail()
    {
        $data = [
            [
                'id_permintaan' => 1,
                'id_sparepart' => 1,
                'jumlah' => 2,
                'satuan_id' => 1
            ],
            [
                'id_permintaan' => 1,
                'id_sparepart' => 4,
                'jumlah' => 1,
                'satuan_id' => 2
            ],
            [
                'id_permintaan' => 2,
                'id_sparepart' => 2,
                'jumlah' => 5,
                'satuan_id' => 1
            ],
            [
                'id_permintaan' => 2,
                'id_sparepart' => 3,
                'jumlah' => 2,
                'satuan_id' => 2
            ],
        ];

        DB::table('permintaan_sparepart_detail')->insert($data);
    }

    private function seedMaintenanceSparepart()
    {
        $data = [
            [
                'maintenance_id' => 1,
                'sparepart_id' => 1,
                'jumlah_digunakan' => 1.00,
                'satuan_id' => 1,
                'harga_satuan' => 125000.00,
                'total_harga' => 125000.00,
                'created_at' => now()
            ],
            [
                'maintenance_id' => 2,
                'sparepart_id' => 4,
                'jumlah_digunakan' => 1.00,
                'satuan_id' => 2,
                'harga_satuan' => 75000.00,
                'total_harga' => 75000.00,
                'created_at' => now()
            ],
            [
                'maintenance_id' => 3,
                'sparepart_id' => 3,
                'jumlah_digunakan' => 1.00,
                'satuan_id' => 2,
                'harga_satuan' => 450000.00,
                'total_harga' => 450000.00,
                'created_at' => now()
            ],
        ];

        DB::table('maintenance_sparepart')->insert($data);
    }

    private function seedDokumenKaryawan()
    {
        $data = [
            [
                'karyawan_id' => 1,
                'image_ktp' => 'documents/karyawan_1_ktp.jpg',
                'surat_lamaran' => 'documents/karyawan_1_lamaran.pdf',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('dokumen_karyawan')->insert($data);
    }

    private function seedAddendum()
    {
        $data = [
            [
                'id_proyek' => 1,
                'nama_addendum' => 'Addendum Perpanjangan Waktu',
                'image_addendum' => 'addendum/proyek_1_addendum_1.pdf',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('addendum')->insert($data);
    }
}
