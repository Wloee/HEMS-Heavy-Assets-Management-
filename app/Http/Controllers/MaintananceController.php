<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ILluminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Validator;

class MaintananceController extends Controller
{
    public function index(Request $request)
    {
        // Query builder untuk mengambil data keluhan unit dengan relasi
        $query = DB::table('keluhan_unit as k')
            ->select([
                'k.id_keluhan',
                'k.site',
                'k.no_keluhan',
                'k.tanggal_keluhan',
                'k.km_hm',
                'k.deskripsi',
                'k.status',
                'k.created_at',
                'p.nama_proyek',
                'u.nama_unit',
                'u.kode_unit',
                'kry.nama_karyawan as created_by_name',
                // Tambahan info dari maintenance log jika ada
                'ml.id_Log as maintenance_id',
                'ml.diagnosa',
                'ml.Status as maintenance_status',
                'ml.Mulai_dikerjakan',
                'ml.Selesai_dikerjakan'
            ])
            ->leftJoin('proyek as p', 'k.proyek_id', '=', 'p.id_proyek')
            ->leftJoin('unit as u', 'k.unit_id', '=', 'u.id_unit')
            ->leftJoin('karyawan as kry', 'k.created_by', '=', 'kry.id_karyawan')
            ->leftJoin('maintanance_log as ml', 'k.id_keluhan', '=', 'ml.Keluhan_id');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('k.status', $request->status);
        }

        // Filter berdasarkan site jika ada
        if ($request->has('site') && $request->site != '') {
            $query->where('k.site', 'like', '%' . $request->site . '%');
        }

        // Filter berdasarkan proyek jika ada
        if ($request->has('proyek_id') && $request->proyek_id != '') {
            $query->where('k.proyek_id', $request->proyek_id);
        }

        // Filter berdasarkan tanggal jika ada
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->where('k.tanggal_keluhan', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->where('k.tanggal_keluhan', '<=', $request->tanggal_sampai);
        }

        // Urutkan berdasarkan tanggal keluhan terbaru
        $query->orderBy('k.tanggal_keluhan', 'desc')
              ->orderBy('k.created_at', 'desc');

        // Pagination
        $keluhan = $query->paginate(15);

        // Ambil data untuk dropdown filter
        $proyeks = DB::table('proyek')->select('id_proyek', 'nama_proyek')->get();
        $sites = DB::table('keluhan_unit')->distinct()->pluck('site');

        // Statistik untuk dashboard
        $stats = [
            'total_keluhan' => DB::table('keluhan_unit')->count(),
            'keluhan_baru' => DB::table('keluhan_unit')->where('status', 'baru')->count(),
            'keluhan_proses' => DB::table('keluhan_unit')->where('status', 'proses')->count(),
            'keluhan_selesai' => DB::table('keluhan_unit')->where('status', 'selesai')->count(),
        ];

        return view('maintanance.index', compact('keluhan', 'proyeks', 'sites', 'stats'));
    }

    // public function show($id)
    // {
    //     // Detail keluhan beserta maintenance log
    //     $keluhan = DB::table('keluhan_unit as k')
    //         ->select([
    //             'k.*',
    //             'p.nama_proyek',
    //             'u.nama_unit',
    //             'u.nomor_unit',
    //             'u.merk',
    //             'u.model',
    //             'kry.nama_karyawan as created_by_name'
    //         ])
    //         ->leftJoin('proyek as p', 'k.proyek_id', '=', 'p.id_proyek')
    //         ->leftJoin('unit as u', 'k.unit_id', '=', 'u.id_unit')
    //         ->leftJoin('karyawan as kry', 'k.created_by', '=', 'kry.id_karyawan')
    //         ->where('k.id_keluhan', $id)
    //         ->first();

    //     if (!$keluhan) {
    //         return redirect()->route('keluhan.index')->with('error', 'Data keluhan tidak ditemukan');
    //     }

    //     // Ambil history maintenance log untuk keluhan ini
    //     $maintenanceLogs = DB::table('maintanance_log as ml')
    //         ->select([
    //             'ml.*',
    //             'op.nama_karyawan as operator_name',
    //             'adm.nama_karyawan as admin_name',
    //             'mek.nama_karyawan as mekanik_name'
    //         ])
    //         ->leftJoin('karyawan as op', 'ml.operator_id', '=', 'op.id_karyawan')
    //         ->leftJoin('karyawan as adm', 'ml.admin_id', '=', 'adm.id_karyawan')
    //         ->leftJoin('karyawan as mek', 'ml.Mekanik_id', '=', 'mek.id_karyawan')
    //         ->where('ml.Keluhan_id', $id)
    //         ->orderBy('ml.Created_at', 'desc')
    //         ->get();
    //         }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    /**
     * Show the form for creating a new maintenance record
     */
    public function create()
    {
        try {
            // Get data for dropdowns
            $proyeks = DB::table('proyek')->select('id_proyek', 'nama_proyek')->get();
            $units = DB::table('unit')->select('id_unit', 'nama_unit', 'model')->get();
            $spareparts = DB::table('sparepart')->select('id_sparepart', 'nama_sparepart', 'stok_saat_ini')->get();

            // Get mechanics (karyawan with mechanic position)
            $mechanics = DB::table('karyawan as k')
                ->join('posisi as p', 'k.posisi_id', '=', 'p.id_posisi')
                ->where('p.id_posisi', 4)
                ->select('k.id_karyawan', 'k.nama_karyawan')
                ->get();

            // Get all active employees for operator and admin selection
            $karyawans = DB::table('karyawan')
                ->where('Status', 'Aktif')
                ->select('id_karyawan', 'nama_karyawan')
                ->get();
            return view('maintanance.create', compact('proyeks', 'units', 'spareparts', 'mechanics', 'karyawans'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading form: ' . $e->getMessage());
        }
    }

    /**
     * Store a new maintenance record (keluhan + maintenance log)
     */
    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            // Keluhan validation
            'site' => 'required|string|max:255',
            'proyek_id' => 'required|exists:proyek,id_proyek',
            'unit_id' => 'required|exists:unit,id_unit',
            'tanggal_keluhan' => 'required|date',
            'km_hm' => 'required|integer|min:0',
            'deskripsi_keluhan' => 'required|string',

            // Maintenance log validation
            'diagnosa' => 'required|string',
            'mulai_dikerjakan' => 'required|date',
            'selesai_dikerjakan' => 'required|date|after_or_equal:mulai_dikerjakan',
            'operator_id' => 'required|exists:karyawan,id_karyawan',
            'admin_id' => 'required|exists:karyawan,id_karyawan',
            'mekanik_id' => 'required|exists:karyawan,id_karyawan',
            'status_maintenance' => 'required|in:baik,proses,selesai',

            // Spareparts validation (optional)
            'spareparts' => 'nullable|array',
            'spareparts.*.sparepart_id' => 'required_with:spareparts|exists:sparepart,id_sparepart',
            'spareparts.*.qty' => 'required_with:spareparts|integer|min:1',
            'spareparts.*.keterangan' => 'nullable|string',

            // Additional mechanics (optional)
            'additional_mechanics' => 'nullable|array',
            'additional_mechanics.*' => 'exists:karyawan,id_karyawan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            // Generate complaint number
            $lastKeluhan = DB::table('keluhan_unit')
                ->whereYear('created_at', date('Y'))
                ->orderBy('id_keluhan', 'desc')
                ->first();

            $nextNumber = $lastKeluhan ? (intval(substr($lastKeluhan->no_keluhan, -4)) + 1) : 1;
            $noKeluhan = 'KLH-' . date('Y') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // 1. Insert keluhan_unit
            $keluhanId = DB::table('keluhan_unit')->insertGetId([
                'site' => $request->site,
                'proyek_id' => $request->proyek_id,
                'unit_id' => $request->unit_id,
                'no_keluhan' => $noKeluhan,
                'tanggal_keluhan' => $request->tanggal_keluhan,
                'km_hm' => $request->km_hm,
                'deskripsi' => $request->deskripsi_keluhan,
                'status' => 'proses', // Set to process since maintenance is being created
                'created_by' => Auth::id(),
                'created_at' => now(),
                'update_at' => now(),
            ]);

            // 2. Insert maintanance_log
            $logId = DB::table('maintanance_log')->insertGetId([
                'Keluhan_id' => $keluhanId,
                'diagnosa' => $request->diagnosa,
                'Mulai_dikerjakan' => $request->mulai_dikerjakan,
                'Selesai_dikerjakan' => $request->selesai_dikerjakan,
                'operator_id' => $request->operator_id,
                'admin_id' => $request->admin_id,
                'Mekanik_id' => $request->mekanik_id,
                'Status' => $request->status_maintenance,
                'Created_at'
                 => now(),
                'Updated_at' => now(),
            ]);

            // 3. Insert spareparts if provided
            if ($request->has('spareparts') && is_array($request->spareparts)) {
                foreach ($request->spareparts as $sparepart) {
                    if (isset($sparepart['sparepart_id']) && isset($sparepart['qty'])) {
                        DB::table('maintanance_sparepart')->insert([
                            'log_id' => $logId,
                            'sparepart_id' => $sparepart['sparepart_id'],
                            'keterangan' => $sparepart['keterangan'] ?? null,
                            'qty' => $sparepart['qty'],
                        ]);

                        // Update sparepart stock
                        DB::table('sparepart')
                            ->where('id_sparepart', $sparepart['sparepart_id'])
                            ->decrement('stok_saat_ini', $sparepart['qty']);
                    }
                }
            }

            // 4. Insert additional maintenance team members if provided
            if ($request->has('additional_mechanics') && is_array($request->additional_mechanics)) {
                foreach ($request->additional_mechanics as $mechanicId) {
                    if ($mechanicId != $request->mekanik_id) { // Avoid duplicate main mechanic
                        DB::table('maintenance_team')->insert([
                            'log_id' => $logId,
                            'mekanik_id' => $mechanicId,
                        ]);
                    }
                }
            }

            // 5. Update keluhan status based on maintenance status
            if ($request->status_maintenance === 'selesai') {
                DB::table('keluhan_unit')
                    ->where('id_keluhan', $keluhanId)
                    ->update([
                        'status' => 'selesai',
                        'update_at' => now(),
                    ]);
            }

            DB::commit();

            return redirect()->route('dashboard')
                ->with('success', 'Maintenance record created successfully. Complaint No: ' . $noKeluhan);

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                ->with('error', 'Error creating maintenance record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get units by project (AJAX helper)
     */
    public function getUnitsByProject($proyekId)
    {
        try {
            $units = DB::table('unit')
                ->where('proyek_id', $proyekId)
                ->select('id_unit', 'nama_unit', 'model_unit')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get sparepart details (AJAX helper)
     */
    public function getSparepartDetails($sparepartId)
    {
        try {
            $sparepart = DB::table('sparepart')
                ->where('id_sparepart', $sparepartId)
                ->select('id_sparepart', 'nama_sparepart', 'stock', 'harga_satuan')
                ->first();

            return response()->json([
                'success' => true,
                'data' => $sparepart
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function biaya()
    {
        try {
            // Fixed table name and improved query
            $maintenance = DB::table('maintanance_log') // tiadak perlu di ganti karena sudah sesuai
                ->select(['created_at', 'id_log', 'diagnosa']) // Added more context fields
                ->whereNull('biaya_aktual')
                ->whereNull('biaya_estimasi')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('biaya.index', compact('maintenance'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data maintenance.');
        }
    }

    public function biaya_update(Request $request, string $id)
    {
        // Enhanced validation
        $request->validate([
            'maintenance_id' => 'required|integer|exists:maintanance_log,id_log',
            'biaya_aktual'   => 'required|numeric|min:0',
            'biaya_estimasi' => 'required|numeric|min:0',
            'deviasi_persen' => 'required|numeric',
            'bukti'          => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:5120', // Allow PDF and larger size
        ], [
            'maintenance_id.required' => 'Maintenance harus dipilih.',
            'maintenance_id.exists' => 'Data maintenance tidak ditemukan.',
            'biaya_aktual.required' => 'Biaya aktual harus diisi.',
            'biaya_aktual.min' => 'Biaya aktual tidak boleh negatif.',
            'biaya_estimasi.required' => 'Biaya estimasi harus diisi.',
            'biaya_estimasi.min' => 'Biaya estimasi tidak boleh negatif.',
            'deviasi_persen.required' => 'Deviasi persen harus diisi.',
            'bukti.image' => 'File bukti harus berupa gambar atau PDF.',
            'bukti.max' => 'Ukuran file bukti maksimal 5MB.',
        ]);

        DB::beginTransaction();

        try {
            // Handle file upload
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                // Delete old file if exists
                $oldRecord = DB::table('maintanance_log')
                    ->where('id_log', $request->maintenance_id)
                    ->first();

                if ($oldRecord && $oldRecord->bukti) {
                    Storage::disk('public')->delete($oldRecord->bukti);
                }

                $buktiPath = $request->file('bukti')->store('bukti_pembayaran', 'public');
            }

            // Calculate deviation automatically
            $deviasi_persen = 0;
            if ($request->biaya_estimasi > 0) {
                $deviasi_persen = (($request->biaya_aktual - $request->biaya_estimasi) / $request->biaya_estimasi) * 100;
            }

            // Update maintanance_log (using your table name)
            $updated = DB::table('maintanance_log')
                ->where('id_log', $request->maintenance_id)
                ->update([
                    'biaya_aktual'   => $request->biaya_aktual,
                    'biaya_estimasi' => $request->biaya_estimasi,
                    'deviasi_persen' => round($deviasi_persen, 2),
                    'bukti'    => $buktiPath,
                    'status'         => 'proses', // Add status tracking (if column exists)
                    'updated_at'     => now(),
                    'Updated_by'     => auth()->id(), // Track who updated (using your column name)
                ]);

            if (!$updated) {
                throw new \Exception('Gagal mengupdate data maintenance.');
            }

            DB::commit();

            return redirect()->route('biaya.index')
                ->with('success', 'Data biaya berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();

            // Delete uploaded file if transaction failed
            if ($buktiPath) {
                Storage::disk('public')->delete($buktiPath);
            }


            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function data_biaya(Request $request)
    {
        $query = DB::table('maintanance_log as ml')
            ->leftJoin('keluhan_unit as ku', 'ml.Keluhan_id', '=', 'ku.id_keluhan')
            ->leftJoin('karyawan as operator', 'ml.operator_id', '=', 'operator.id_karyawan')
            ->leftJoin('karyawan as admin', 'ml.admin_id', '=', 'admin.id_karyawan')
            ->leftJoin('karyawan as mekanik', 'ml.Mekanik_id', '=', 'mekanik.id_karyawan')
            ->select([
                'ml.id_Log',
                'ml.diagnosa',
                'ml.Mulai_dikerjakan',
                'ml.Selesai_dikerjakan',
                'ml.Status',
                'ml.biaya_aktual',
                'ml.biaya_estimasi',
                'ml.deviasi_persen',
                'ml.bukti',
                'ml.Created_at',
                'ml.Updated_at',
                // Informasi keluhan
                'ku.deskripsi',
                // Informasi karyawan
                'operator.nama_karyawan as operator_nama',
                'admin.nama_karyawan as admin_nama',
                'mekanik.nama_karyawan as mekanik_nama',
                // Perhitungan biaya tambahan
                DB::raw('CASE
                    WHEN ml.biaya_aktual IS NOT NULL AND ml.biaya_estimasi IS NOT NULL
                    THEN (ml.biaya_aktual - ml.biaya_estimasi)
                    ELSE NULL
                END as selisih_biaya'),
                DB::raw('CASE
                    WHEN ml.biaya_aktual IS NOT NULL AND ml.biaya_estimasi IS NOT NULL
                    THEN CASE
                        WHEN ml.biaya_aktual > ml.biaya_estimasi THEN "Over Budget"
                        WHEN ml.biaya_aktual < ml.biaya_estimasi THEN "Under Budget"
                        ELSE "Sesuai Budget"
                    END
                    ELSE "Belum Ada Data"
                END as status_budget'),
                DB::raw('DATEDIFF(ml.Selesai_dikerjakan, ml.Mulai_dikerjakan) as durasi_hari')
            ]);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('ml.Status', $request->status);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->where('ml.Mulai_dikerjakan', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->where('ml.Selesai_dikerjakan', '<=', $request->tanggal_selesai);
        }

        // Filter berdasarkan range biaya
        if ($request->filled('biaya_min')) {
            $query->where('ml.biaya_aktual', '>=', $request->biaya_min);
        }

        if ($request->filled('biaya_max')) {
            $query->where('ml.biaya_aktual', '<=', $request->biaya_max);
        }

        // Filter berdasarkan status budget
        if ($request->filled('status_budget')) {
            switch ($request->status_budget) {
                case 'over':
                    $query->whereRaw('ml.biaya_aktual > ml.biaya_estimasi');
                    break;
                case 'under':
                    $query->whereRaw('ml.biaya_aktual < ml.biaya_estimasi');
                    break;
                case 'sesuai':
                    $query->whereRaw('ml.biaya_aktual = ml.biaya_estimasi');
                    break;
            }
        }

        // Filter berdasarkan deviasi persentase
        if ($request->filled('deviasi_min')) {
            $query->where('ml.deviasi_persen', '>=', $request->deviasi_min);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'ml.Created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSortFields = [
            'ml.Created_at',
            'ml.Mulai_dikerjakan',
            'ml.Selesai_dikerjakan',
            'ml.biaya_aktual',
            'ml.biaya_estimasi',
            'ml.deviasi_persen',
            'durasi_hari'
        ];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $maintenanceLogs = $query->paginate($perPage);

        // Statistik biaya untuk dashboard
        $costStatistics = $this->getCostStatistics($request);

        return view('biaya.data', compact('maintenanceLogs', 'costStatistics'));
    }

    /**
     * Mendapatkan statistik biaya maintenance
     */
    private function getCostStatistics($request = null)
    {
        $query = DB::table('maintanance_log');

        // Terapkan filter yang sama jika ada
        if ($request) {
            if ($request->filled('tanggal_mulai')) {
                $query->where('Mulai_dikerjakan', '>=', $request->tanggal_mulai);
            }

            if ($request->filled('tanggal_selesai')) {
                $query->where('Selesai_dikerjakan', '<=', $request->tanggal_selesai);
            }
        }

        return $query->select([
            DB::raw('COUNT(*) as total_maintenance'),
            DB::raw('SUM(biaya_aktual) as total_biaya_aktual'),
            DB::raw('SUM(biaya_estimasi) as total_biaya_estimasi'),
            DB::raw('AVG(biaya_aktual) as rata_rata_biaya_aktual'),
            DB::raw('AVG(biaya_estimasi) as rata_rata_biaya_estimasi'),
            DB::raw('AVG(deviasi_persen) as rata_rata_deviasi'),
            DB::raw('COUNT(CASE WHEN biaya_aktual > biaya_estimasi THEN 1 END) as jumlah_over_budget'),
            DB::raw('COUNT(CASE WHEN biaya_aktual < biaya_estimasi THEN 1 END) as jumlah_under_budget'),
            DB::raw('COUNT(CASE WHEN Status = "selesai" THEN 1 END) as jumlah_selesai'),
            DB::raw('COUNT(CASE WHEN Status = "proses" THEN 1 END) as jumlah_proses'),
            DB::raw('MAX(biaya_aktual) as biaya_tertinggi'),
            DB::raw('MIN(biaya_aktual) as biaya_terendah')
        ])->first();
    }
    public function biaya_edit(){
        return 'halo rek';
    }

    // Additional method to show cost details
//     public function biaya_show($id)
//     {
//         try {
//             $maintenance = DB::table('maintanance_log')
//                 ->where('id_log', $id)
//                 ->first();

//             if (!$maintenance) {
//                 return redirect()->route('dashboard')
//                     ->with('error', 'Data maintenance tidak ditemukan.');
//             }

//             return view('biaya.show', compact('maintenance'));
//         } catch (\Exception $e) {
//             return redirect()->route('dashboard')
//                 ->with('error', 'Terjadi kesalahan saat mengambil data.');
//         }

// }

  }
