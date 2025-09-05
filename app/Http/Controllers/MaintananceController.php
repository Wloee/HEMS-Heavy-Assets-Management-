<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ILluminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

            // 2. Insert maintenance_log
            $logId = DB::table('maintanance_log')->insertGetId([
                'Keluhan_id' => $keluhanId,
                'diagnosa' => $request->diagnosa,
                'Mulai_dikerjakan' => $request->mulai_dikerjakan,
                'Selesai_dikerjakan' => $request->selesai_dikerjakan,
                'operator_id' => $request->operator_id,
                'admin_id' => $request->admin_id,
                'Mekanik_id' => $request->mekanik_id,
                'Status' => $request->status_maintenance,
                'Created_at' => now(),
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
}
