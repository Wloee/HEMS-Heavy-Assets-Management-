<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sparepart;
use App\Models\Suplier;
use Illuminate\Support\Facades\DB;
use App\Models\sparepart_pembelian_detail;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\sparepart_pembelian;
use Illuminate\Support\Facades\Log;
use App\Models\Satuan;
use Whoops\Run;

class pembelianController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Build query for pengadaan with relationships
            $query = DB::table('sparepart_pengadaan as sp')
                ->leftJoin('supplier as s', 'sp.supplier_id', '=', 's.id_supplier')
                ->leftJoin(DB::raw('(SELECT pembelian_id, COUNT(*) as details_count
                                   FROM sparepart_pengadaan_detail
                                   GROUP BY pembelian_id) as detail_count'),
                          'sp.id_pembelian', '=', 'detail_count.pembelian_id')
                ->select(
                    'sp.*',
                    's.nama_supplier',
                    DB::raw('COALESCE(detail_count.details_count, 0) as details_count')
                );

            // Apply search filter
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('s.nama_supplier', 'LIKE', "%{$search}%")
                      ->orWhere('sp.id_pembelian', 'LIKE', "%{$search}%");
                });
            }

            // Apply supplier filter
            if ($request->filled('supplier')) {
                $query->where('sp.supplier_id', $request->get('supplier'));
            }

            // Apply date range filter
            if ($request->filled('tanggal_dari')) {
                $query->where('sp.tanggal_pembelian', '>=', $request->get('tanggal_dari'));
            }

            if ($request->filled('tanggal_sampai')) {
                $query->where('sp.tanggal_pembelian', '<=', $request->get('tanggal_sampai'));
            }

            // Get paginated results
            $pengadaanList = $query->orderBy('sp.tanggal_pembelian', 'desc')
                                  ->orderBy('sp.id_pembelian', 'desc')
                                  ->paginate(10)
                                  ->appends($request->all());

            // Get statistics
            $statistics = $this->getStatistics();
            // Get suppliers for dropdown filter
            $suppliers = DB::table('supplier')
                          ->select('id_supplier', 'nama_supplier')
                          ->orderBy('nama_supplier')
                          ->get();


            return view('sparepart.table-pengadaan', array_merge([
                'pengadaanList' => $pengadaanList,
                'suppliers' => $suppliers,
            ], $statistics));

        } catch (\Exception $e) {
            return view('dashboard', [
                'suppliers' => collect(),
                'totalPengadaan' => 0,
                'pengadaanBulanIni' => 0,
                'totalNilai' => 0,
                'supplierAktif' => 0,
                'error' => 'Terjadi kesalahan saat memuat data pengadaan: ' . $e->getMessage()
            ]);
        }
    }


    public function create(Request $request)
{
    try {
        $query = sparepart_pembelian::with('supplier')->orderBy('created_at', 'desc');


        // Filter berdasarkan supplier
        if ($request->filled('supplier_id')) {
            $query->bySupplier($request->supplier_id);
        }
        $pembelian = $query->paginate(15);
        $suppliers = Suplier::orderBy('nama_supplier')->get();
        $satuanList = Satuan::orderBy('nama_satuan')->get();
        return view('sparepart.pembelian-sparepart', compact('pembelian', 'suppliers', 'satuanList'))
            ->with('i', ($request->input('page', 1) - 1) * 15);

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Gagal mengambil data pembelian: ' . $e->getMessage()]);
    }
}

// Method untuk debug struktur tabel (tambahkan di controller)
public function debugTableStructure()
{
    try {
        $tables = [
            'sparepart_pembelian',
            'sparepart_pembelian_detail',
            'sparepart_pengadaan_detail',
            'sparepart',
            'supplier',
            'satuan'
        ];

        $result = [];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                $columns = DB::getSchemaBuilder()->getColumnListing($table);
                $result[$table] = [
                    'exists' => true,
                    'columns' => $columns,
                    'sample_data' => DB::table($table)->first()
                ];
            } else {
                $result[$table] = ['exists' => false];
            }
        }

        return response()->json($result, 200, [], JSON_PRETTY_PRINT);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
}

// Method untuk memperbaiki model jika diperlukan
public function fixModel()
{
    // Contoh perbaikan untuk model sparepart_pembelian_detail

    // 1. Cek nama tabel yang benar
    $correctTable = null;
    $possibleTables = [
        'sparepart_pembelian_detail',
        'sparepart_pengadaan_detail',
        'detail_pembelian',
        'pembelian_detail'
    ];

    foreach ($possibleTables as $table) {
        if (DB::getSchemaBuilder()->hasTable($table)) {
            $correctTable = $table;
            break;
        }
    }

    if (!$correctTable) {
        return response()->json(['error' => 'Tabel detail tidak ditemukan']);
    }

    $columns = DB::getSchemaBuilder()->getColumnListing($correctTable);

    return response()->json([
        'correct_table' => $correctTable,
        'columns' => $columns,
        'model_suggestion' => [
            'table' => $correctTable,
            'fillable' => $columns,
            'primary_key' => $this->guessPrimaryKey($columns)
        ]
    ]);
}

private function guessPrimaryKey($columns)
{
    $possibleKeys = ['id', 'id_detail', 'id_pembelian_detail', 'id_pengadaan_detail'];

    foreach ($possibleKeys as $key) {
        if (in_array($key, $columns)) {
            return $key;
        }
    }

    return $columns[0] ?? 'id'; // fallback
}

public function store(Request $request)
{
    try {
        Log::info('=== MULAI PROSES PEMBELIAN ===');
        Log::info('Request data: ', $request->all());

        // ✅ VALIDASI YANG DIPERBAIKI
        $validated = $request->validate([
            'tanggal_pembelian' => 'required|date',
            'supplier_id' => 'required|exists:supplier,id_supplier',
            'items' => 'required|array|min:1',
            'items.*.nama_sparepart' => 'required|string|max:255',
            'items.*.kode_sparepart' => 'required|string|max:50',
            'items.*.satuan_id' => 'required|exists:satuan,id_satuan',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'total_pembelian' => 'required|numeric|min:0',
        ], [
            'supplier_id.required' => 'Supplier harus dipilih.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak valid.',
            'items.required' => 'Minimal harus ada satu item pembelian.',
            'items.*.nama_sparepart.required' => 'Nama sparepart harus diisi.',
            'items.*.kode_sparepart.required' => 'Kode sparepart harus diisi.',
            'items.*.satuan_id.required' => 'Satuan harus dipilih.',
            'items.*.satuan_id.exists' => 'Satuan yang dipilih tidak valid.',
            'items.*.jumlah.required' => 'Jumlah harus diisi.',
            'items.*.jumlah.min' => 'Jumlah minimal 1.',
            'items.*.harga_satuan.required' => 'Harga satuan harus diisi.',
            'items.*.harga_satuan.min' => 'Harga satuan tidak boleh negatif.',
        ]);

        Log::info('Validated data: ', $validated);

        DB::transaction(function () use ($validated) {
            Log::info('--- MULAI DATABASE TRANSACTION ---');

            // 1️⃣ Simpan data pembelian utama
            $pembelian = sparepart_pembelian::create([
                'tanggal_pembelian' => $validated['tanggal_pembelian'],
                'supplier_id' => $validated['supplier_id'],
                'total_pembelian' => $validated['total_pembelian'],
            ]);

            Log::info('Pembelian utama berhasil dibuat dengan ID: ' . $pembelian->id_pembelian);

            // 2️⃣ Simpan detail item
            foreach ($validated['items'] as $index => $item) {
                Log::info("Processing item #{$index}: ", $item);

                // Cari atau buat sparepart
                $sparepart = Sparepart::firstOrCreate(
                    ['kode_sparepart' => $item['kode_sparepart']],
                    [
                        'nama_sparepart' => $item['nama_sparepart'],
                    ]
                );

                Log::info("Sparepart ID: {$sparepart->id_sparepart}");

                // ✅ PERBAIKAN: Menggunakan Query Builder untuk menghindari masalah model
                try {
                    // Cek struktur tabel yang sebenarnya
                    $tableExists = DB::getSchemaBuilder()->hasTable('sparepart_pembelian_detail');
                    $tableName = $tableExists ? 'sparepart_pembelian_detail' : 'sparepart_pengadaan_detail';

                    Log::info("Using table: {$tableName}");

                    // Data yang akan diinsert
                    $detailData = [
                        'pembelian_id' => $pembelian->id_pembelian,
                        'sparepart_id' => $sparepart->id_sparepart,
                        'satuan_id' => $item['satuan_id'],
                        'jumlah' => $item['jumlah'],
                        'kode_sparepart' => $item['kode_sparepart'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $item['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Cek kolom yang ada di tabel
                    $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
                    Log::info("Available columns in {$tableName}: ", $columns);

                    // Filter data berdasarkan kolom yang tersedia
                    $filteredData = array_intersect_key($detailData, array_flip($columns));

                    // Insert menggunakan Query Builder
                    $insertedId = DB::table($tableName)->insertGetId($filteredData);

                    Log::info("Detail pembelian berhasil dibuat dengan ID: {$insertedId}");

                } catch (\Exception $detailError) {
                    Log::error("Error inserting detail: " . $detailError->getMessage());

                    // Fallback: coba dengan struktur tabel yang berbeda
                    $alternativeData = [
                        'id_pembelian' => $pembelian->id_pembelian,
                        'id_sparepart' => $sparepart->id_sparepart,
                        'id_satuan' => $item['satuan_id'],
                        'jumlah' => $item['jumlah'],
                        'kode_sparepart' => $item['kode_sparepart'],
                        'harga_satuan' => $item['harga_satuan'],
                        'subtotal' => $item['subtotal'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Coba beberapa nama tabel yang mungkin
                    $possibleTables = [
                        'sparepart_pembelian_detail',
                        'sparepart_pengadaan_detail',
                        'pembelian_detail',
                        'detail_pembelian'
                    ];

                    $success = false;
                    foreach ($possibleTables as $table) {
                        if (DB::getSchemaBuilder()->hasTable($table)) {
                            try {
                                $tableColumns = DB::getSchemaBuilder()->getColumnListing($table);
                                $tableData = array_intersect_key($alternativeData, array_flip($tableColumns));

                                if (!empty($tableData)) {
                                    $insertedId = DB::table($table)->insertGetId($tableData);
                                    Log::info("Detail berhasil disimpan di tabel {$table} dengan ID: {$insertedId}");
                                    $success = true;
                                    break;
                                }
                            } catch (\Exception $e) {
                                Log::warning("Failed to insert into {$table}: " . $e->getMessage());
                                continue;
                            }
                        }
                    }

                    if (!$success) {
                        throw new \Exception("Gagal menyimpan detail pembelian ke database");
                    }
                }
            }

            Log::info('--- TRANSACTION SELESAI ---');
        });

        Log::info('=== PEMBELIAN BERHASIL DISIMPAN ===');
        return redirect()->route('dashboard')
                        ->with('success', 'Pembelian berhasil disimpan.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation Error: ', $e->errors());
        return redirect()->back()
                        ->withErrors($e->errors())
                        ->withInput();

    } catch (\Exception $e) {
        Log::error('Error saat menyimpan pembelian: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());

        return redirect()->back()
                        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
    }
}
        public function show(string $id)
    {
        try {
            // Get pengadaan with supplier info
            $pengadaan = DB::table('sparepart_pengadaan as sp')
                          ->leftJoin('supplier as s', 'sp.supplier_id', '=', 's.id_supplier')
                          ->select('sp.*', 's.nama_supplier', 's.alamat_supplier', 's.telepon_supplier')
                          ->where('sp.id_pembelian', $id)
                          ->first();

            if (!$pengadaan) {
                return redirect()->route('pengadaan-sparepart.index')
                               ->with('error', 'Data pengadaan tidak ditemukan.');
            }

            // Get pengadaan details
            $details = DB::table('sparepart_pengadaan_detail as spd')
                        ->leftJoin('sparepart as sp', 'spd.sparepart_id', '=', 'sp.id_sparepart')
                        ->leftJoin('satuan as st', 'spd.satuan_id', '=', 'st.id_satuan')
                        ->select(
                            'spd.*',
                            'sp.nama_sparepart',
                            'sp.kode_sparepart',
                            'st.nama_satuan'
                        )
                        ->where('spd.pembelian_id', $id)
                        ->get();

            return view('pengadaan-sparepart.show', compact('pengadaan', 'details'));

        } catch (\Exception $e) {
            return redirect()->route('pengadaan-sparepart.index')
                           ->with('error', 'Terjadi kesalahan saat memuat detail pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Get pengadaan data
            $pengadaan = DB::table('sparepart_pengadaan')->where('id_pembelian', $id)->first();

            if (!$pengadaan) {
                return redirect()->route('pengadaan-sparepart.index')
                               ->with('error', 'Data pengadaan tidak ditemukan.');
            }

            // Get pengadaan details
            $details = DB::table('sparepart_pengadaan_detail as spd')
                        ->leftJoin('sparepart as sp', 'spd.sparepart_id', '=', 'sp.id_sparepart')
                        ->leftJoin('satuan as st', 'spd.satuan_id', '=', 'st.id_satuan')
                        ->select('spd.*', 'sp.nama_sparepart', 'st.nama_satuan')
                        ->where('spd.pembelian_id', $id)
                        ->get();

            // Get suppliers, spareparts, and satuan for dropdowns
            $suppliers = DB::table('supplier')
                          ->select('id_supplier', 'nama_supplier')
                          ->orderBy('nama_supplier')
                          ->get();

            $spareparts = DB::table('sparepart as sp')
                           ->leftJoin('satuan as st', 'sp.satuan_id', '=', 'st.id_satuan')
                           ->select('sp.*', 'st.nama_satuan')
                           ->where('sp.status', 'aktif')
                           ->orderBy('sp.nama_sparepart')
                           ->get();

            $satuans = DB::table('satuan')
                        ->select('id_satuan', 'nama_satuan')
                        ->orderBy('nama_satuan')
                        ->get();

            return view('pengadaan-sparepart.edit', compact('pengadaan', 'details', 'suppliers', 'spareparts', 'satuans'));

        } catch (\Exception $e) {
            return redirect()->route('pengadaan-sparepart.index')
                           ->with('error', 'Terjadi kesalahan saat memuat form edit: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:supplier,id_supplier',
            'tanggal_pembelian' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.sparepart_id' => 'required|exists:sparepart,id_sparepart',
            'items.*.satuan_id' => 'required|exists:satuan,id_satuan',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Check if pengadaan exists
            $pengadaan = DB::table('sparepart_pengadaan')->where('id_pembelian', $id)->first();
            if (!$pengadaan) {
                return redirect()->route('pengadaan-sparepart.index')
                               ->with('error', 'Data pengadaan tidak ditemukan.');
            }

            // Calculate new total harga
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $totalHarga += $item['jumlah'] * $item['harga_satuan'];
            }

            // Update pengadaan
            DB::table('sparepart_pengadaan')
              ->where('id_pembelian', $id)
              ->update([
                  'supplier_id' => $request->supplier_id,
                  'tanggal_pembelian' => $request->tanggal_pembelian,
                  'total_harga' => $totalHarga,
                  'updated_at' => now(),
              ]);

            // Delete old details
            DB::table('sparepart_pengadaan_detail')->where('pembelian_id', $id)->delete();

            // Insert new details
            foreach ($request->items as $item) {
                $subtotal = $item['jumlah'] * $item['harga_satuan'];

                // Get kode sparepart
                $kodeSparepart = DB::table('sparepart')
                                  ->where('id_sparepart', $item['sparepart_id'])
                                  ->value('kode_sparepart');

                DB::table('sparepart_pengadaan_detail')->insert([
                    'pembelian_id' => $id,
                    'sparepart_id' => $item['sparepart_id'],
                    'satuan_id' => $item['satuan_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $subtotal,
                    'kode_sparepart' => $kodeSparepart,
                ]);
            }

            DB::commit();

            return redirect()->route('pengadaan-sparepart.index')
                           ->with('success', 'Pengadaan sparepart berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat memperbarui pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            // Check if pengadaan exists
            $pengadaan = DB::table('sparepart_pengadaan')->where('id_pembelian', $id)->first();
            if (!$pengadaan) {
                return redirect()->route('pengadaan-sparepart.index')
                               ->with('error', 'Data pengadaan tidak ditemukan.');
            }

            // Delete pengadaan (details will be deleted automatically due to CASCADE)
            DB::table('sparepart_pengadaan')->where('id_pembelian', $id)->delete();

            DB::commit();

            return redirect()->route('pengadaan-sparepart.index')
                           ->with('success', 'Pengadaan sparepart berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pengadaan-sparepart.index')
                           ->with('error', 'Terjadi kesalahan saat menghapus pengadaan: ' . $e->getMessage());
        }
    }

    /**
     * Print pengadaan report
     */
    public function print(string $id)
    {
        try {
            // Get pengadaan with supplier info
            $pengadaan = DB::table('sparepart_pengadaan as sp')
                          ->leftJoin('supplier as s', 'sp.supplier_id', '=', 's.id_supplier')
                          ->select('sp.*', 's.nama_supplier', 's.alamat_supplier', 's.telepon_supplier')
                          ->where('sp.id_pembelian', $id)
                          ->first();

            if (!$pengadaan) {
                return redirect()->route('pengadaan-sparepart.index')
                               ->with('error', 'Data pengadaan tidak ditemukan.');
            }

            // Get pengadaan details
            $details = DB::table('sparepart_pengadaan_detail as spd')
                        ->leftJoin('sparepart as sp', 'spd.sparepart_id', '=', 'sp.id_sparepart')
                        ->leftJoin('satuan as st', 'spd.satuan_id', '=', 'st.id_satuan')
                        ->select(
                            'spd.*',
                            'sp.nama_sparepart',
                            'sp.kode_sparepart',
                            'st.nama_satuan'
                        )
                        ->where('spd.pembelian_id', $id)
                        ->get();

            return view('pengadaan-sparepart.print', compact('pengadaan', 'details'));

        } catch (\Exception $e) {
            return redirect()->route('pengadaan-sparepart.index')
                           ->with('error', 'Terjadi kesalahan saat memuat laporan: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics data
     */
    private function getStatistics()
    {
        try {
            // Total pengadaan
            $totalPengadaan = DB::table('sparepart_pengadaan')->count();

            // Pengadaan bulan ini
            $pengadaanBulanIni = DB::table('sparepart_pengadaan')
                                  ->whereYear('tanggal_pembelian', now()->year)
                                  ->whereMonth('tanggal_pembelian', now()->month)
                                  ->count();

            // Total nilai
            $totalNilai = DB::table('sparepart_pengadaan')->sum('total_harga');

            // Supplier aktif (yang pernah melakukan pengadaan)
            $supplierAktif = DB::table('sparepart_pengadaan')
                              ->distinct('supplier_id')
                              ->count();

            return [
                'totalPengadaan' => $totalPengadaan,
                'pengadaanBulanIni' => $pengadaanBulanIni,
                'totalNilai' => $totalNilai ?: 0,
                'supplierAktif' => $supplierAktif,
            ];

        } catch (\Exception $e) {
            return [
                'totalPengadaan' => 0,
                'pengadaanBulanIni' => 0,
                'totalNilai' => 0,
                'supplierAktif' => 0,
            ];
        }
}
}
