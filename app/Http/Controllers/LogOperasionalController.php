<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LogOperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

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
    public function create()
    {
        try {
         $unitProyek = DB::table('unit_proyek as up')
            ->join('unit as u', 'up.unit_id', '=', 'u.id_unit')
            ->join('proyek as p', 'up.proyek_id', '=', 'p.id_proyek')
            ->select(
                'up.id_unit_proyek',
                'u.kode_unit',
                'u.nama_unit',
                'p.nama_proyek',
                DB::raw("CONCAT(u.kode_unit, ' - ', u.nama_unit, ' (', p.nama_proyek, ')') as unit_display")
            )
            ->where('up.status', 'aktif')
            ->orderBy('u.kode_unit')
            ->get();

            // Ambil data operator/karyawan untuk dropdown
            $operators = DB::table('karyawan')
                ->select(
                    'id_karyawan',
                    'nama_karyawan',
                    'nama_posisi',
                    DB::raw("CONCAT(nama_karyawan, ' - ', id_karyawan, ' (', posisi_id, ')') as operator_display")
                )->leftJoin('posisi', 'karyawan.posisi_id', '=', 'posisi.id_posisi')
                ->where('status', 'aktif')
                ->whereIn('posisi_id', ['2', '1', '3', '9'])
                ->orderBy('nama_karyawan')
                ->get();

            // Ambil data proyek untuk informasi tambahan
            $proyek = DB::table('proyek')
                ->select('id_proyek', 'nama_proyek', 'lokasi_proyek', 'status')
                ->where('status', 'aktif')
                ->orderBy('nama_proyek')
                ->get();

            return view('operasional.create', compact('unitProyek', 'operators', 'proyek'));

        } catch (\Exception $e) {
            return 'Gagal memuat form: ' . $e->getMessage();
        }
    }

    /**
     * Store log operasional baru ke database
     */


public function store(Request $request)
{
    Log::info('Store log operasional dipanggil', ['payload' => $request->all()]);

    // ✅ VALIDASI
    $validator = Validator::make($request->all(), [
        'unit_proyek_id'   => 'required|integer|exists:unit_proyek,id_unit_proyek',
        'tanggal_operasi'  => 'required|date|before_or_equal:today',
        'jam_mulai'        => 'nullable|date_format:H:i',
        'jam_selesai'      => 'nullable|date_format:H:i',
        'jam_operasi'      => 'nullable|numeric|min:0|max:24',
        'operator_id'      => 'nullable|integer|exists:karyawan,id_karyawan',
        'jenis_pekerjaan'  => 'nullable|string|max:255',
        'keterangan'       => 'nullable|string',
        'lokasi_kerja'     => 'nullable|string|max:255',
        'biaya_operasional'=> 'nullable|numeric|min:0|max:9999999999999.99',
    ]);

    if ($validator->fails()) {
        Log::warning('Validasi gagal saat store log operasional', [
            'errors' => $validator->errors()->toArray()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
        $logData = [
            'unit_proyek_id'   => $request->unit_proyek_id,
            'tanggal_operasi'  => $request->tanggal_operasi,
            'jam_mulai'        => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'operator_id'      => $request->operator_id ?: null,
            'jenis_pekerjaan'  => $request->jenis_pekerjaan,
            'keterangan'       => $request->keterangan,
            'lokasi_kerja'     => $request->lokasi_kerja,
            'biaya_operasional'=> $request->biaya_operasional ?: null,
            'created_at'       => Carbon::now(),
            'updated_at'       => Carbon::now(),
        ];

        Log::info('Data yang akan diinsert ke log_operasional', $logData);

        // Kalkulasi jam operasi
        if ($request->jam_mulai && $request->jam_selesai) {
            $jamOperasiCalculated = $this->calculateJamOperasi($request->jam_mulai, $request->jam_selesai);
            $logData['jam_operasi'] = $jamOperasiCalculated;
            Log::info('Jam operasi dihitung otomatis', ['jam_operasi' => $jamOperasiCalculated]);
        } elseif ($request->jam_operasi) {
            $logData['jam_operasi'] = $request->jam_operasi;
            Log::info('Jam operasi diambil dari input', ['jam_operasi' => $request->jam_operasi]);
        }

        // Cek duplikasi
        $existingLog = DB::table('log_operasional')
            ->where('unit_proyek_id', $request->unit_proyek_id)
            ->where('tanggal_operasi', $request->tanggal_operasi)
            ->where('jam_mulai', $request->jam_mulai)
            ->first();

        if ($existingLog) {
            Log::warning('Terdeteksi duplikasi log_operasional', [
                'unit_proyek_id'  => $request->unit_proyek_id,
                'tanggal_operasi' => $request->tanggal_operasi,
                'jam_mulai'       => $request->jam_mulai
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log operasional untuk unit ini pada tanggal dan jam yang sama sudah ada'
                ], 409);
            }
            return redirect()->back()->with('error', 'Log operasional untuk unit ini pada tanggal dan jam yang sama sudah ada')->withInput();
        }

        // Insert
        $logId = DB::table('log_operasional')->insertGetId($logData);
        Log::info('Log operasional berhasil disimpan', ['id_log' => $logId]);

        // Update jam operasi total
        $this->updateTotalJamOperasi($request->unit_proyek_id, $request->tanggal_operasi);
        Log::info('Total jam operasi diperbarui', ['unit_proyek_id' => $request->unit_proyek_id]);

        DB::commit();

        // Ambil data lengkap
        $insertedLog = DB::table('log_operasional')
            ->select(
                'log_operasional.*',
                'unit.nama_unit',
                'karyawan.nama_karyawan',
                'proyek.nama_proyek'
            )
            ->leftJoin('unit_proyek', 'log_operasional.unit_proyek_id', '=', 'unit_proyek.id_unit_proyek')
            ->leftJoin('unit', 'unit_proyek.unit_id', '=', 'unit.id_unit')
            ->leftJoin('karyawan', 'log_operasional.operator_id', '=', 'karyawan.id_karyawan')
            ->leftJoin('proyek', 'unit_proyek.proyek_id', '=', 'proyek.id_proyek')
            ->where('log_operasional.id_log', $logId)
            ->first();

        Log::info('Data log operasional final setelah insert', (array) $insertedLog);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Log operasional berhasil disimpan',
                'data'    => $insertedLog
            ], 201);
        }

        return redirect()->route('log-operasional.index')
            ->with('success', 'Log operasional berhasil disimpan')
            ->with('log_data', $insertedLog);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error saat menyimpan log operasional', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan log operasional: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Gagal menyimpan log operasional: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Kalkulasi jam operasi berdasarkan jam mulai dan selesai
     */
    private function calculateJamOperasi($jamMulai, $jamSelesai)
    {
        try {
            $startTime = Carbon::createFromFormat('H:i', $jamMulai);
            $endTime = Carbon::createFromFormat('H:i', $jamSelesai);

            // Jika jam selesai lebih kecil dari jam mulai, anggap lanjut ke hari berikutnya
            if ($endTime->lt($startTime)) {
                $endTime->addDay();
            }

            $diffInHours = $startTime->diffInMinutes($endTime) / 60;

            return round($diffInHours, 2);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Update total jam operasi per unit (optional feature)
     */
    private function updateTotalJamOperasi($unitProyekId, $tanggalOperasi)
    {
        try {
            // Hitung total jam operasi untuk unit pada bulan ini
            $totalJamBulanIni = DB::table('log_operasional')
                ->where('unit_proyek_id', $unitProyekId)
                ->whereMonth('tanggal_operasi', Carbon::parse($tanggalOperasi)->month)
                ->whereYear('tanggal_operasi', Carbon::parse($tanggalOperasi)->year)
                ->sum('jam_operasi');

            // Update ke tabel unit_proyek jika ada kolom total_jam_operasi
            $unitExists = DB::table('information_schema.columns')
                ->where('table_schema', env('DB_DATABASE'))
                ->where('table_name', 'unit_proyek')
                ->where('column_name', 'total_jam_operasi')
                ->exists();

            if ($unitExists) {
                DB::table('unit_proyek')
                    ->where('id_unit_proyek', $unitProyekId)
                    ->update([
                        'total_jam_operasi' => $totalJamBulanIni,
                        'updated_at' => Carbon::now()
                    ]);
            }
        } catch (\Exception $e) {
            // Log error tapi tidak menggagalkan proses utama
            Log::warning('Gagal update total jam operasi: ' . $e->getMessage());
        }
    }

    /**
     * Get data for AJAX requests
     */
    public function getUnitProyek(Request $request)
    {
        try {
            $query = DB::table('unit_proyek')
                ->select(
                    'unit_proyek.id_unit_proyek',
                    'unit_proyek.kode_unit',
                    'unit_proyek.nama_unit',
                    'unit_proyek.jenis_unit',
                    'proyek.nama_proyek',
                    DB::raw("CONCAT(unit_proyek.kode_unit, ' - ', unit_proyek.nama_unit, ' (', proyek.nama_proyek, ')') as display_name")
                )
                ->join('proyek', 'unit_proyek.proyek_id', '=', 'proyek.id_proyek')
                ->where('unit_proyek.status', 'aktif');

            if ($request->has('proyek_id') && $request->proyek_id) {
                $query->where('unit_proyek.proyek_id', $request->proyek_id);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('unit_proyek.kode_unit', 'like', "%{$search}%")
                      ->orWhere('unit_proyek.nama_unit', 'like', "%{$search}%")
                      ->orWhere('proyek.nama_proyek', 'like', "%{$search}%");
                });
            }

            $units = $query->orderBy('unit_proyek.kode_unit')->get();

            return response()->json([
                'success' => true,
                'data' => $units
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data unit proyek'
            ], 500);
        }
    }

    /**
     * Get operators for AJAX requests
     */
    public function getOperators(Request $request)
    {
        try {
            $query = DB::table('karyawan')
                ->select(
                    'id_karyawan',
                    'nama_karyawan',
                    'kode_karyawan',
                    'jabatan',
                    'divisi',
                    DB::raw("CONCAT(nama_karyawan, ' - ', kode_karyawan, ' (', jabatan, ')') as display_name")
                )
                ->where('status', 'aktif')
                ->whereIn('jabatan', ['Operator', 'Driver', 'Mekanik', 'Helper']);

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_karyawan', 'like', "%{$search}%")
                      ->orWhere('kode_karyawan', 'like', "%{$search}%")
                      ->orWhere('jabatan', 'like', "%{$search}%");
                });
            }

            $operators = $query->orderBy('nama_karyawan')->get();

            return response()->json([
                'success' => true,
                'data' => $operators
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data operator'
            ], 500);
        }
    }

    /**
     * Validate time range for better UX
     */
    public function validateTimeRange(Request $request)
    {
        $jamMulai = $request->jam_mulai;
        $jamSelesai = $request->jam_selesai;

        if (!$jamMulai || !$jamSelesai) {
            return response()->json([
                'valid' => true,
                'message' => ''
            ]);
        }

        try {
            $jamOperasi = $this->calculateJamOperasi($jamMulai, $jamSelesai);

            $warnings = [];

            if ($jamOperasi > 12) {
                $warnings[] = 'Jam operasi lebih dari 12 jam, pastikan data sudah benar';
            }

            if ($jamOperasi > 24) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Jam operasi tidak boleh lebih dari 24 jam'
                ]);
            }

            return response()->json([
                'valid' => true,
                'jam_operasi' => $jamOperasi,
                'warnings' => $warnings
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Format waktu tidak valid'
            ]);
        }
    }
}


