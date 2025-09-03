<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Termwind\Components\Raw;

class MutasiUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
      $query = DB::table('unit_proyek as up')
    ->leftJoin('unit as u', 'up.unit_id', '=', 'u.id_unit')
    ->leftJoin('proyek as p', 'up.proyek_id', '=', 'p.id_proyek')
    ->select([
        'up.*',
        'u.nama_unit',
        'u.no_polisi',
        'p.nama_proyek',
        'p.lokasi_proyek',
    ]);



        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('u.nama_unit', 'like', $search)
                  ->orWhere('p.nama_proyek', 'like', $search);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('up.status', $request->status);
        }

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('up.unit_id', $request->unit_id);
        }

        // Filter berdasarkan proyek
        if ($request->filled('proyek_id')) {
            $query->where('up.proyek_id', $request->proyek_id);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('up.tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->where('up.tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Get data dengan pagination
        $unitProyek = $query->orderBy('up.created_at', 'desc')
                           ->paginate(15);

        // Get data untuk dropdown filter
        $units = DB::table('unit')
                   ->select('id_unit', 'nama_unit')
                   ->where('status_kondisi', 'baik')
                   ->orderBy('nama_unit')
                   ->get();

        $proyek = DB::table('proyek')
                    ->select('id_proyek', 'nama_proyek')
                    ->where('status', 'aktif')
                    ->orderBy('nama_proyek')
                    ->get();

        return view('proyek.mutasi.index', compact('unitProyek', 'units', 'proyek'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id = null)
        {
                // Get available units (hanya unit yang tersedia)
                $units = DB::table('unit')
                        ->select('id_unit', 'nama_unit', 'jenis_unit_id', 'kode_unit')
                        ->where('status_operasional', 'standby')
                        ->orderBy('nama_unit')
                        ->get();

                // Data unit proyek jika sedang edit
                $unitProyek = null;
                if ($id) {
                    $unitProyek = DB::table('unit_proyek')->where('id_unit_proyek', $id)->first();
                }

                // Get active projects
                $proyek = DB::table('proyek')
                            ->select('id_proyek', 'nama_proyek', 'lokasi_proyek', 'tanggal_mulai', 'tanggal_selesai_aktual')
                            ->where('status', 'aktif')
                            ->orderBy('nama_proyek')
                            ->get();

                return view('proyek.mutasi.form', compact('units', 'proyek', 'unitProyek'));
            }


        /**
         * Store a newly created resource in storage.
         */
    public function store(Request $request)
{
            // ✅ Validasi input - sesuai dengan form request
            $validator = Validator::make($request->all(), [
                'unit_id' => 'required|integer|exists:unit,id_unit',
                'proyek_id' => 'required|integer|exists:proyek,id_proyek',
                'tanggal_mutasi' => 'required|date|after_or_equal:today',
                'jam_mutasi' => 'required|date_format:H:i',
                'status_mutasi' => 'required|in:aktif,selesai,dibatalkan',
                'estimasi_tiba' => 'nullable|date',
                'waktu_tiba_aktual' => 'nullable|date|after_or_equal:estimasi_tiba',
                'keterangan' => 'nullable|string|max:500',
            ], [
                'unit_id.required' => 'Unit harus dipilih',
                'unit_id.exists' => 'Unit tidak valid',
                'proyek_id.required' => 'Proyek harus dipilih',
                'proyek_id.exists' => 'Proyek tidak valid',

                'tanggal_mutasi.required' => 'Tanggal mutasi harus diisi',
                'tanggal_mutasi.date' => 'Format tanggal mutasi tidak valid',
                'tanggal_mutasi.after_or_equal' => 'Tanggal mutasi tidak boleh kurang dari hari ini',

                'jam_mutasi.required' => 'Jam mutasi harus diisi',
                'jam_mutasi.date_format' => 'Format jam mutasi harus HH:MM',

                'status_mutasi.required' => 'Status mutasi harus dipilih',
                'status_mutasi.in' => 'Status mutasi tidak valid',

                'estimasi_tiba.date' => 'Format estimasi tiba tidak valid',
                'waktu_tiba_aktual.date' => 'Format waktu tiba aktual tidak valid',
                'waktu_tiba_aktual.after_or_equal' => 'Waktu tiba aktual harus setelah atau sama dengan estimasi tiba',

                'keterangan.string' => 'Keterangan harus berupa teks',
                'keterangan.max' => 'Keterangan maksimal 500 karakter',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                // ✅ Transformasi data form ke format database
                $tanggalMulai = $request->tanggal_mutasi;

                // Gabungkan tanggal_mutasi + jam_mutasi untuk tanggal_mulai yang lebih presisi
                if ($request->jam_mutasi) {
                    $tanggalMulai = $request->tanggal_mutasi . ' ' . $request->jam_mutasi . ':00';
                }

                // Tentukan tanggal_selesai berdasarkan waktu_tiba_aktual atau estimasi_tiba
                $tanggalSelesai = null;
                if ($request->waktu_tiba_aktual) {
                    $tanggalSelesai = $request->waktu_tiba_aktual;
                } elseif ($request->estimasi_tiba) {
                    $tanggalSelesai = $request->estimasi_tiba;
                }

                // Konversi status_mutasi ke status database
                $statusMapping = [
                    'aktif' => 'aktif',
                    'selesai' => 'selesai',
                    'dibatalkan' => 'dibatalkan'
                ];
                $status = $statusMapping[$request->status_mutasi] ?? 'aktif';

                // ✅ Cek apakah unit sudah digunakan dalam periode yang sama
                $conflictCheck = DB::table('unit_proyek')
                    ->where('unit_id', $request->unit_id)
                    ->where('status', '!=', 'selesai')
                    ->where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                        if ($tanggalSelesai) {
                            // Jika ada tanggal selesai, cek overlap
                            $q->where(function ($subQ) use ($tanggalMulai, $tanggalSelesai) {
                                $subQ->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                                    ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                                    ->orWhere(function ($innerQ) use ($tanggalMulai, $tanggalSelesai) {
                                        $innerQ->where('tanggal_mulai', '<=', $tanggalMulai)
                                                ->where('tanggal_selesai', '>=', $tanggalSelesai);
                                    });
                            });
                        } else {
                            // Jika tidak ada tanggal selesai, cek dari tanggal mulai
                            $q->where(function ($subQ) use ($tanggalMulai) {
                                $subQ->where('tanggal_mulai', '<=', $tanggalMulai)
                                    ->where(function ($innerQ) use ($tanggalMulai) {
                                        $innerQ->whereNull('tanggal_selesai')
                                            ->orWhere('tanggal_selesai', '>=', $tanggalMulai);
                                    });
                            });
                        }
                    })
                    ->exists();

                if ($conflictCheck) {
                    return redirect()->back()
                        ->withErrors(['unit_id' => 'Unit sudah digunakan dalam periode tersebut'])
                        ->withInput();
                }

                // ✅ Ambil tarif default dari unit jika tidak ada di form
                $tarifSewa = null;
                $unitData = DB::table('unit')
                    ->where('id_unit', $request->unit_id)
                    ->first();

                if ($unitData && isset($unitData->tarif_sewa_harian)) {
                    $tarifSewa = $unitData->tarif_sewa_harian;
                }

                // ✅ Insert data ke unit_proyek dengan mapping yang benar
                $unitProyekData = [
                    'unit_id' => $request->unit_id,
                    'proyek_id' => $request->proyek_id,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => $tanggalSelesai,
                    'operator_id' => null, // Tidak ada di form, set null atau tambahkan jika diperlukan
                    'tarif_sewa_harian' => $tarifSewa,
                    'status' => $status,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                $unitProyekId = DB::table('unit_proyek')->insertGetId($unitProyekData);

                // ✅ Simpan data tambahan dari form ke tabel terpisah jika diperlukan
                // Atau buat tabel mutasi_detail untuk menyimpan data form yang tidak ada di unit_proyek
                if ($request->keterangan || $request->estimasi_tiba || $request->waktu_tiba_aktual || $request->jam_mutasi) {
                    // Opsi 1: Buat tabel mutasi_detail
                    // DB::table('mutasi_detail')->insert([
                    //     'unit_proyek_id' => $unitProyekId,
                    //     'jam_mutasi' => $request->jam_mutasi,
                    //     'estimasi_tiba' => $request->estimasi_tiba,
                    //     'waktu_tiba_aktual' => $request->waktu_tiba_aktual,
                    //     'keterangan' => $request->keterangan,
                    //     'created_at' => Carbon::now(),
                    //     'updated_at' => Carbon::now()
                    // ]);

                    // Opsi 2: Atau simpan sebagai JSON di kolom keterangan jika memungkinkan
                    $detailData = [
                        'jam_mutasi' => $request->jam_mutasi,
                        'estimasi_tiba' => $request->estimasi_tiba,
                        'waktu_tiba_aktual' => $request->waktu_tiba_aktual,
                        'keterangan' => $request->keterangan
                    ];

                    // Update keterangan dengan data JSON (opsional)
                    // DB::table('unit_proyek')
                    //     ->where('id_unit_proyek', $unitProyekId)
                    //     ->update(['keterangan' => json_encode($detailData)]);
                }

                // ✅ Update status unit jadi 'digunakan' kalau status aktif
                if ($request->status_mutasi === 'aktif') {
                    DB::table('unit')
                        ->where('id_unit', $request->unit_id)
                        ->update([
                            'status_operasional' => 'operasional',
                            'updated_at' => Carbon::now()
                        ]);
                }

                DB::commit();

                return redirect()->route('Mutasi-Unit.index')
                    ->with('success', 'Mutasi unit berhasil disimpan');

            } catch (\Exception $e) {
                DB::rollBack();

                return redirect()->back()
                    ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                    ->withInput();
            }
        }    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $unitProyek = DB::table('unit_proyek as up')
    //                     ->leftJoin('unit as u', 'up.unit_id', '=', 'u.id_unit')
    //                     ->leftJoin('proyek as p', 'up.proyek_id', '=', 'p.id_proyek')
    //                     ->leftJoin('karyawan as k', 'up.operator_id', '=', 'k.id_karyawan')
    //                     ->select([
    //                         'up.*',
    //                         'u.nama_unit',
    //                         'u.tipe_unit',
    //                         'u.nomor_polisi',
    //                         'u.merek',
    //                         'u.tahun_pembuatan',
    //                         'u.kondisi',
    //                         'u.foto_unit',
    //                         'p.nama_proyek',
    //                         'p.lokasi',
    //                         'p.deskripsi as proyek_deskripsi',
    //                         'p.tanggal_mulai as proyek_mulai',
    //                         'p.tanggal_selesai as proyek_selesai',
    //                         'k.nama as operator_nama',
    //                         'k.jabatan as operator_jabatan',
    //                         'k.nomor_telepon as operator_telepon'
    //                     ])
    //                     ->where('up.id_unit_proyek', $id)
    //                     ->first();

    //     if (!$unitProyek) {
    //         return redirect()->route('Mutasi-Unit.index')
    //                        ->with('error', 'Data tidak ditemukan');
    //     }

    //     return view('proyek.mutasi.show', compact('unitProyek'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $unitProyek = DB::table('unit_proyek')->where('id_unit_proyek', $id)->first();

        if (!$unitProyek) {
            return redirect()->route('Mutasi-Unit.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        // Get available units (termasuk unit yang sedang digunakan untuk edit)
        $units = DB::table('unit')
                   ->select('id_unit', 'nama_unit', 'tipe_unit', 'nomor_polisi', 'tarif_sewa_harian')
                   ->where(function($q) use ($unitProyek) {
                       $q->where('status', 'tersedia')
                         ->orWhere('id_unit', $unitProyek->unit_id);
                   })
                   ->orderBy('nama_unit')
                   ->get();

        // Get active projects
        $proyek = DB::table('proyek')
                    ->select('id_proyek', 'nama_proyek', 'lokasi', 'tanggal_mulai', 'tanggal_selesai')
                    ->where('status', 'aktif')
                    ->orderBy('nama_proyek')
                    ->get();

        // Get available operators
        $operators = DB::table('karyawan')
                       ->select('id_karyawan', 'nama', 'jabatan', 'status')
                       ->where('status', 'aktif')
                       ->where(function($q) {
                           $q->where('jabatan', 'like', '%operator%')
                             ->orWhere('jabatan', 'like', '%driver%')
                             ->orWhere('jabatan', 'like', '%pengemudi%');
                       })
                       ->orderBy('nama')
                       ->get();

        return view('proyek.mutasi.create', compact('unitProyek', 'units', 'proyek', 'operators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|integer|exists:unit,id_unit',
            'proyek_id' => 'required|integer|exists:proyek,id_proyek',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
            'operator_id' => 'nullable|integer|exists:karyawan,id_karyawan',
            'tarif_sewa_harian' => 'nullable|numeric|min:0|max:999999999999.99',
            'status' => 'required|in:aktif,selesai,ditunda'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $existingData = DB::table('unit_proyek')->where('id_unit_proyek', $id)->first();

        if (!$existingData) {
            return redirect()->route('Mutasi-Unit.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        DB::beginTransaction();

        try {
            // Update data unit_proyek
            DB::table('unit_proyek')
              ->where('id_unit_proyek', $id)
              ->update([
                  'unit_id' => $request->unit_id,
                  'proyek_id' => $request->proyek_id,
                  'tanggal_mulai' => $request->tanggal_mulai,
                  'tanggal_selesai' => $request->tanggal_selesai,
                  'operator_id' => $request->operator_id,
                  'tarif_sewa_harian' => $request->tarif_sewa_harian,
                  'status' => $request->status,
                  'updated_at' => Carbon::now()
              ]);

            // Update status unit berdasarkan status assignment
            if ($request->status === 'aktif') {
                DB::table('unit')
                  ->where('id_unit', $request->unit_id)
                  ->update(['status' => 'digunakan', 'updated_at' => Carbon::now()]);

                // Jika unit berubah, kembalikan status unit lama
                if ($existingData->unit_id !== $request->unit_id) {
                    DB::table('unit')
                      ->where('id_unit', $existingData->unit_id)
                      ->update(['status' => 'tersedia', 'updated_at' => Carbon::now()]);
                }
            } elseif ($request->status === 'selesai' && $existingData->status === 'aktif') {
                DB::table('unit')
                  ->where('id_unit', $request->unit_id)
                  ->update(['status' => 'tersedia', 'updated_at' => Carbon::now()]);
            }

            DB::commit();

            return redirect()->route('Mutasi-Unit.index')
                           ->with('success', 'Data unit proyek berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                           ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $unitProyek = DB::table('unit_proyek')->where('id_unit_proyek', $id)->first();

        if (!$unitProyek) {
            return redirect()->route('Mutasi-Unit.index')
                           ->with('error', 'Data tidak ditemukan');
        }

        DB::beginTransaction();

        try {
            // Kembalikan status unit menjadi tersedia jika assignment aktif
            if ($unitProyek->status === 'aktif') {
                DB::table('unit')
                  ->where('id_unit', $unitProyek->unit_id)
                  ->update([
                      'status' => 'tersedia',
                      'updated_at' => Carbon::now()
                  ]);
            }

            // Hapus data unit_proyek
            DB::table('unit_proyek')->where('id_unit_proyek', $id)->delete();

            DB::commit();

            return redirect()->route('Mutasi-Unit.index')
                           ->with('success', 'Assignment unit berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('Mutasi-Unit.index')
                           ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Update status assignment secara langsung
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:aktif,selesai,ditunda'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid'
            ]);
        }

        $unitProyek = DB::table('unit_proyek')->where('id_unit_proyek', $id)->first();

        if (!$unitProyek) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        DB::beginTransaction();

        try {
            // Update status
            DB::table('unit_proyek')
              ->where('id_unit_proyek', $id)
              ->update([
                  'status' => $request->status,
                  'updated_at' => Carbon::now()
              ]);

            // Update status unit
            $unitStatus = match($request->status) {
                'aktif' => 'digunakan',
                'selesai' => 'tersedia',
                'ditunda' => 'tersedia',
                default => 'tersedia'
            };

            DB::table('unit')
              ->where('id_unit', $unitProyek->unit_id)
              ->update([
                  'status' => $unitStatus,
                  'updated_at' => Carbon::now()
              ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get unit details for AJAX
     */
    public function getUnitDetails($id)
    {
        $unit = DB::table('unit')
                  ->where('id_unit', $id)
                  ->first();

        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Unit tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $unit
        ]);
    }

    /**
     * Get proyek details for AJAX
     */
    public function getProyekDetails($id)
    {
        $proyek = DB::table('proyek')
                    ->where('id_proyek', $id)
                    ->first();

        if (!$proyek) {
            return response()->json([
                'success' => false,
                'message' => 'Proyek tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $proyek
        ]);
    }
}
