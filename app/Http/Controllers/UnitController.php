<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource with search and filter functionality.
     */
    public function index(Request $request)
    {
        $query = DB::table('unit as u')
            ->leftJoin('jenis_unit as ju', 'u.jenis_unit_id', '=', 'ju.id_jenis_unit')
            ->leftJoin('pemilik_unit as pu', 'u.pemilik_id', '=', 'pu.id_pemilik')
            ->select(
                'u.*',
                'ju.nama_jenis',
                'pu.nama_pemilik'
            )
            ->where('u.is_active', 1);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('u.kode_unit', 'like', "%{$search}%")
                  ->orWhere('u.nama_unit', 'like', "%{$search}%")
                  ->orWhere('u.no_polisi', 'like', "%{$search}%")
                  ->orWhere('u.merk', 'like', "%{$search}%")
                  ->orWhere('u.model', 'like', "%{$search}%");
            });
        }

        // Filter by jenis unit
        if ($request->filled('jenis_unit_id')) {
            $query->where('u.jenis_unit_id', $request->jenis_unit_id);
        }

        // Filter by status operasional
        if ($request->filled('status_operasional')) {
            $query->where('u.status_operasional', $request->status_operasional);
        }

        // Filter by status kondisi
        if ($request->filled('status_kondisi')) {
            $query->where('u.status_kondisi', $request->status_kondisi);
        }

        // Get paginated results
        $units = $query->orderBy('u.kode_unit', 'asc')->paginate(25);
        // Get jenis unit for filter dropdown
        $jenisUnits = DB::table('jenis_unit')
            ->select('id_jenis_unit', 'nama_jenis')
            ->orderBy('nama_jenis', 'asc')
            ->get();
        return view('unit.index', compact('units', 'jenisUnits'));
    }

    public function create()
    {
        // Get jenis unit for dropdown
        $jenis_units = DB::table('jenis_unit')
            ->select('id_jenis_unit', 'nama_jenis')
            ->orderBy('nama_jenis', 'asc')
            ->get();

        // Get pemilik for dropdown
        $pemiliks = DB::table('pemilik_unit')
            ->select('id_pemilik', 'nama_pemilik')
            ->orderBy('nama_pemilik', 'asc')
            ->get();

        return view('unit.form', compact('jenis_units', 'pemiliks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $Unit = DB::table('unit')
        ->leftJoin('pemilik_unit', 'unit.pemilik_id', '=', 'pemilik_unit.id_pemilik')
        ->where('unit.id_unit', $id)
        ->where('unit.is_active', 1)
        ->select('unit.*', 'pemilik_unit.nama_pemilik')
        ->first();

        if (!$Unit) {
            return redirect()->route('data_Unit')->with('error', 'Data unit tidak ditemukan.');
        }

        // Get jenis unit for dropdown
        $jenisUnits = DB::table('jenis_unit')
            ->select('id_jenis_unit', 'nama_jenis')
            ->orderBy('nama_jenis', 'asc')
            ->get();


        $gambar_unit = DB::table('gambar_unit')
            ->where('unit_id', $id)
            ->first();

        return view('unit.form', compact('Unit', 'jenisUnits', 'gambar_unit'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Check if unit exists
        $unitExists = DB::table('unit')
            ->where('id_unit', $id)
            ->where('is_active', 1)
            ->exists();

        if (!$unitExists) {
            return redirect()->route('data_Unit')->with('error', 'Data unit tidak ditemukan.');
        }

        // Prepare data for update
        $updateData = [
            'kode_unit' => $request->kode_unit,
            'nama_unit' => $request->nama_unit,
            'jenis_unit_id' => $request->jenis_unit_id,
            'merk' => $request->merk,
            'model' => $request->model,
            'tahun_pembuatan' => $request->tahun_pembuatan,
            'no_rangka' => $request->no_rangka,
            'no_mesin' => $request->no_mesin,
            'no_polisi' => $request->no_polisi,
            'pemilik_id' => $request->pemilik_id ?: null,
            'alamat_unit' => $request->alamat_unit,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'jam_operasi' => $request->jam_operasi ?: 0,
            'status_kepemilikan' => $request->status_kepemilikan ?: 'milik_sendiri',
            'status_kondisi' => $request->status_kondisi ?: 'baik',
            'status_operasional' => $request->status_operasional ?: 'standby',
            'updated_at' => now()
        ];

        // Remove null values to avoid overwriting with null
        $updateData = array_filter($updateData, function($value) {
            return $value !== null && $value !== '';
        });

        try {
            DB::table('unit')
                ->where('id_unit', $id)
                ->update($updateData);

            return redirect()->route('data_Unit')->with('success', 'Data unit berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data unit: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy($id)
    {
        // Check if unit exists
        $unitExists = DB::table('unit')
            ->where('id_unit', $id)
            ->where('is_active', 1)
            ->exists();

        if (!$unitExists) {
            return redirect()->route('data_Unit')->with('error', 'Data unit tidak ditemukan.');
        }

        try {
            // Soft delete by setting is_active to 0
            DB::table('unit')
                ->where('id_unit', $id)
                ->update([
                    'is_active' => 0,
                    'updated_at' => now()
                ]);

            return redirect()->route('data_Unit')->with('success', 'Data unit berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('data_Unit')->with('error', 'Gagal menghapus data unit: ' . $e->getMessage());
        }
    }

    /**
     * Get unit data for AJAX requests
     */
    public function getUnitData(Request $request)
    {
        $query = DB::table('unit as u')
            ->leftJoin('jenis_unit as ju', 'u.jenis_unit_id', '=', 'ju.id_jenis_unit')
            ->leftJoin('pemilik_unit as pu', 'u.pemilik_id', '=', 'pu.id_pemilik')
            ->select(
                'u.id_unit',
                'u.kode_unit',
                'u.nama_unit',
                'u.status_operasional',
                'u.status_kondisi',
                'ju.nama_jenis_unit',
                'pu.nama_pemilik'
            )
            ->where('u.is_active', 1);

        // Filter by status if provided
        if ($request->filled('status_operasional')) {
            $query->where('u.status_operasional', $request->status_operasional);
        }

        $units = $query->orderBy('u.kode_unit', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $units
        ]);
    }

    /**
     * Update unit status
     */
    public function updateStatus(Request $request, $id)
    {
        // Check if unit exists
        $unitExists = DB::table('unit')
            ->where('id_unit', $id)
            ->where('is_active', 1)
            ->exists();

        if (!$unitExists) {
            return response()->json([
                'success' => false,
                'message' => 'Data unit tidak ditemukan.'
            ], 404);
        }

        $updateData = [];

        if ($request->filled('status_operasional')) {
            $updateData['status_operasional'] = $request->status_operasional;
        }

        if ($request->filled('status_kondisi')) {
            $updateData['status_kondisi'] = $request->status_kondisi;
        }

        if (empty($updateData)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data status yang akan diperbarui.'
            ], 400);
        }

        $updateData['updated_at'] = now();

        try {
            DB::table('unit')
                ->where('id_unit', $id)
                ->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Status unit berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status unit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unit statistics
     */
    public function getStatistics()
    {
        $statistics = [
            'total_unit' => DB::table('unit')->where('is_active', 1)->count(),
            'operasional' => DB::table('unit')->where('is_active', 1)->where('status_operasional', 'operasional')->count(),
            'maintenance' => DB::table('unit')->where('is_active', 1)->where('status_operasional', 'maintenance')->count(),
            'standby' => DB::table('unit')->where('is_active', 1)->where('status_operasional', 'standby')->count(),
            'tidak_aktif' => DB::table('unit')->where('is_active', 1)->where('status_operasional', 'tidak_aktif')->count(),
            'kondisi_baik' => DB::table('unit')->where('is_active', 1)->where('status_kondisi', 'baik')->count(),
            'perlu_maintenance' => DB::table('unit')->where('is_active', 1)->where('status_kondisi', 'perlu_maintenance')->count(),
            'rusak' => DB::table('unit')->where('is_active', 1)->where('status_kondisi', 'rusak')->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Export unit data to CSV
     */
    public function exportCsv(Request $request)
    {
        $query = DB::table('unit as u')
            ->leftJoin('jenis_unit as ju', 'u.jenis_unit_id', '=', 'ju.id_jenis_unit')
            ->leftJoin('pemilik_unit as pu', 'u.pemilik_id', '=', 'pu.id_pemilik')
            ->select(
                'u.kode_unit',
                'u.nama_unit',
                'ju.nama_jenis_unit',
                'u.merk',
                'u.model',
                'u.tahun_pembuatan',
                'u.no_rangka',
                'u.no_mesin',
                'u.no_polisi',
                'pu.nama_pemilik',
                'u.alamat_unit',
                'u.kota',
                'u.provinsi',
                'u.jam_operasi',
                'u.status_kepemilikan',
                'u.status_kondisi',
                'u.status_operasional'
            )
            ->where('u.is_active', 1);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('u.kode_unit', 'like', "%{$search}%")
                  ->orWhere('u.nama_unit', 'like', "%{$search}%")
                  ->orWhere('u.no_polisi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_unit_id')) {
            $query->where('u.jenis_unit_id', $request->jenis_unit_id);
        }

        if ($request->filled('status_operasional')) {
            $query->where('u.status_operasional', $request->status_operasional);
        }

        if ($request->filled('status_kondisi')) {
            $query->where('u.status_kondisi', $request->status_kondisi);
        }

        $units = $query->orderBy('u.kode_unit', 'asc')->get();

        $filename = 'data_unit_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($units) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'Kode Unit', 'Nama Unit', 'Jenis Unit', 'Merk', 'Model', 'Tahun',
                'No Rangka', 'No Mesin', 'No Polisi', 'Pemilik', 'Alamat Unit',
                'Kota', 'Provinsi', 'Jam Operasi', 'Status Kepemilikan',
                'Status Kondisi', 'Status Operasional'
            ]);

            // CSV Data
            foreach ($units as $unit) {
                fputcsv($file, [
                    $unit->kode_unit,
                    $unit->nama_unit,
                    $unit->nama_jenis_unit,
                    $unit->merk,
                    $unit->model,
                    $unit->tahun_pembuatan,
                    $unit->no_rangka,
                    $unit->no_mesin,
                    $unit->no_polisi,
                    $unit->nama_pemilik,
                    $unit->alamat_unit,
                    $unit->kota,
                    $unit->provinsi,
                    $unit->jam_operasi,
                    $unit->status_kepemilikan,
                    $unit->status_kondisi,
                    $unit->status_operasional
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    public function store(StoreUnit $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            DB::beginTransaction();

            // 1. Sanitasi dan validasi data
            $sanitizedData = $this->sanitizeInputData($validatedData);

            // 2. Check duplicate dengan lebih komprehensif
            $this->checkDuplicateData($sanitizedData);

            // 3. Handle pemilik (cek existing atau create new)
            $pemilikId = $this->handlePemilikData($sanitizedData);

            // 4. Upload dan validasi gambar
            $imagePaths = $this->handleImageUploads($request);

            // 5. Generate kode unit otomatis jika kosong
            if (empty($sanitizedData['kode_unit'])) {
                $sanitizedData['kode_unit'] = $this->generateUnitCode($sanitizedData['jenis_unit_id']);
            }

            // 6. Insert data unit
            $unitId = $this->insertUnitData($sanitizedData, $pemilikId);

            // 7. Insert gambar unit
            if (!empty($imagePaths)) {
                $this->insertGambarUnit($unitId, $imagePaths);
            }

            // 8. Log activity
            $this->logUnitCreation($unitId, $sanitizedData);

            DB::commit();

            return $this->successResponse($unitId, $sanitizedData);

        } catch (Exception $e) {
            DB::rollBack();

            // Cleanup uploaded images on error
            if (isset($imagePaths)) {
                $this->cleanupUploadedImages($imagePaths);
            }

            Log::error('Unit creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['gambar_depan', 'gambar_belakang', 'gambar_kiri', 'gambar_kanan'])
            ]);

            return $this->errorResponse($e->getMessage());
        }
    }
    //private Function
    /**
     * Sanitize input data untuk mencegah XSS dan format data.
     *
     * @param array $data
     * @return array
     */
    private function sanitizeInputData(array $data): array
    {
        return [
            // Basic unit information - sanitized
            'kode_unit'          => isset($data['kode_unit']) ? strtoupper(trim(strip_tags($data['kode_unit']))) : null,
            'nama_unit'          => trim(strip_tags($data['nama_unit'])),
            'jenis_unit_id'      => (int) $data['jenis_unit_id'],

            // Vehicle details - sanitized and formatted
            'merk'               => isset($data['merk']) ? ucwords(strtolower(trim(strip_tags($data['merk'])))) : null,
            'model'              => isset($data['model']) ? trim(strip_tags($data['model'])) : null,
            'tahun_pembuatan'    => isset($data['tahun_pembuatan']) ? (int) $data['tahun_pembuatan'] : null,
            'no_rangka'          => isset($data['no_rangka']) ? strtoupper(trim(strip_tags($data['no_rangka']))) : null,
            'no_mesin'           => isset($data['no_mesin']) ? strtoupper(trim(strip_tags($data['no_mesin']))) : null,
            'no_polisi'          => isset($data['no_polisi']) ? strtoupper(trim(strip_tags($data['no_polisi']))) : null,

            // Owner and location - sanitized
            'nama_pemilik'       => isset($data['nama_pemilik']) ? trim(strip_tags($data['nama_pemilik'])) : null,
            'kontak_pemilik'     => isset($data['kontak_pemilik']) ? preg_replace('/[^0-9+\-\(\)\s]/', '', $data['kontak_pemilik']) : null,
            'alamat_unit'        => isset($data['alamat_unit']) ? trim(strip_tags($data['alamat_unit'])) : null,
            'kota'               => isset($data['kota']) ? ucwords(strtolower(trim(strip_tags($data['kota'])))) : null,
            'provinsi'           => isset($data['provinsi']) ? ucwords(strtolower(trim(strip_tags($data['provinsi'])))) : null,

            // Operational data - validated
            'jam_operasi'        => isset($data['jam_operasi']) ? max(0, (int) $data['jam_operasi']) : 0,
            'status_kepemilikan' => $data['status_kepemilikan'],
            'status_kondisi'     => $data['status_kondisi'],
            'status_operasional' => $data['status_operasional'] ?? 'standby',
            'is_active'          => $data['is_active'] ?? true,
        ];
    }

    /**
     * Check untuk data duplicate yang lebih komprehensif.
     *
     * @param array $data
     * @throws Exception
     */
    private function checkDuplicateData(array $data): void
    {
        $duplicateChecks = [];

        // Check kode unit
        if (!empty($data['kode_unit'])) {
            $existing = DB::table('unit')->where('kode_unit', $data['kode_unit'])->exists();
            if ($existing) {
                $duplicateChecks[] = "Kode unit '{$data['kode_unit']}' sudah digunakan";
            }
        }

        // Check no rangka
        if (!empty($data['no_rangka'])) {
            $existing = DB::table('unit')->where('no_rangka', $data['no_rangka'])->exists();
            if ($existing) {
                $duplicateChecks[] = "No. rangka '{$data['no_rangka']}' sudah terdaftar";
            }
        }

        // Check no mesin
        if (!empty($data['no_mesin'])) {
            $existing = DB::table('unit')->where('no_mesin', $data['no_mesin'])->exists();
            if ($existing) {
                $duplicateChecks[] = "No. mesin '{$data['no_mesin']}' sudah terdaftar";
            }
        }

        if (!empty($duplicateChecks)) {
            throw new Exception('Data duplikat ditemukan: ' . implode(', ', $duplicateChecks));
        }
    }

    /**
     * Handle pemilik data - find existing atau create new.
     *
     * @param array $data
     * @return int|null
     */
    private function handlePemilikData(array $data): ?int
    {
        if (empty($data['nama_pemilik'])) {
            return null;
        }

        // Cek pemilik existing berdasarkan nama dan kontak
        $existingPemilik = DB::table('pemilik_unit')
            ->where('nama_pemilik', $data['nama_pemilik']);

        if (!empty($data['kontak_pemilik'])) {
            $existingPemilik->where('kontak_pemilik', $data['kontak_pemilik']);
        }

        $pemilik = $existingPemilik->first();

        if ($pemilik) {
            // Update kontak jika ada data baru
            if (!empty($data['kontak_pemilik']) && $pemilik->kontak_pemilik !== $data['kontak_pemilik']) {
                DB::table('pemilik_unit')
                    ->where('id_pemilik', $pemilik->id_pemilik)
                    ->update([
                        'kontak_pemilik' => $data['kontak_pemilik'],
                        'updated_at' => now()
                    ]);
            }
            return $pemilik->id_pemilik;
        }

        // Create new pemilik
        return DB::table('pemilik_unit')->insertGetId([
            'nama_pemilik'   => $data['nama_pemilik'],
            'kontak_pemilik' => $data['kontak_pemilik'],
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    /**
     * Handle upload gambar dengan validasi dan resize.
     *
     * @param Request $request
     * @return array
     */
    private function handleImageUploads(Request $request): array
    {
        $imagePaths = [];
        $imageTypes = ['gambar_depan', 'gambar_belakang', 'gambar_kiri', 'gambar_kanan'];
        $maxFileSize = 2048; // KB
        $allowedMimes = ['jpeg', 'png', 'jpg', 'webp'];

        foreach ($imageTypes as $imageType) {
            if ($request->hasFile($imageType)) {
                $file = $request->file($imageType);

                // Validasi file
                if (!$file->isValid()) {
                    throw new Exception("File {$imageType} tidak valid");
                }

                if ($file->getSize() > $maxFileSize * 1024) {
                    throw new Exception("File {$imageType} terlalu besar (max {$maxFileSize}KB)");
                }

                if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedMimes)) {
                    throw new Exception("Format file {$imageType} tidak didukung");
                }

                // Generate unique filename
                $filename = $this->generateImageFilename($imageType, $file);

                // Store file
                $path = $file->storeAs('unit-images', $filename, 'public');
                $imagePaths[$imageType] = $path;
            }
        }

        return $imagePaths;
    }

    /**
     * Generate kode unit otomatis berdasarkan jenis unit.
     *
     * @param int $jenisUnitId
     * @return string
     */
    private function generateUnitCode(int $jenisUnitId): string
    {
        // Get jenis unit prefix
        $jenisUnit = DB::table('jenis_unit')
            ->where('id_jenis_unit', $jenisUnitId)
            ->first();

        $prefix = $jenisUnit ? strtoupper(substr($jenisUnit->nama_jenis_unit ?? 'UN', 0, 2)) : 'UN';

        // Get next sequence number
        $lastUnit = DB::table('unit')
            ->where('kode_unit', 'like', $prefix . '%')
            ->orderBy('kode_unit', 'desc')
            ->first();

        $sequence = 1;
        if ($lastUnit) {
            $lastSequence = (int) substr($lastUnit->kode_unit, -4);
            $sequence = $lastSequence + 1;
        }

        return $prefix . date('Y') . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Insert data unit ke database.
     *
     * @param array $data
     * @param int|null $pemilikId
     * @return int
     */
    private function insertUnitData(array $data, ?int $pemilikId): int
    {
        return DB::table('unit')->insertGetId([
            'kode_unit'          => $data['kode_unit'],
            'nama_unit'          => $data['nama_unit'],
            'jenis_unit_id'      => $data['jenis_unit_id'],
            'merk'               => $data['merk'],
            'model'              => $data['model'],
            'tahun_pembuatan'    => $data['tahun_pembuatan'],
            'no_rangka'          => $data['no_rangka'],
            'no_mesin'           => $data['no_mesin'],
            'no_polisi'          => $data['no_polisi'],
            'pemilik_id'         => $pemilikId,
            'alamat_unit'        => $data['alamat_unit'],
            'kota'               => $data['kota'],
            'provinsi'           => $data['provinsi'],
            'jam_operasi'        => $data['jam_operasi'],
            'status_kepemilikan' => $data['status_kepemilikan'],
            'status_kondisi'     => $data['status_kondisi'],
            'status_operasional' => $data['status_operasional'],
            'is_active'          => $data['is_active'],
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    /**
     * Insert gambar unit ke tabel gambar_unit.
     *
     * @param int $unitId
     * @param array $imagePaths
     * @return void
     */
    private function insertGambarUnit(int $unitId, array $imagePaths): void
    {
        $gambarData = [
            'unit_id'         => $unitId,
            'gambar_depan'    => $imagePaths['gambar_depan'] ?? null,
            'gambar_belakang' => $imagePaths['gambar_belakang'] ?? null,
            'gambar_kiri'     => $imagePaths['gambar_kiri'] ?? null,
            'gambar_kanan'    => $imagePaths['gambar_kanan'] ?? null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ];

        // Remove null values
        $gambarData = array_filter($gambarData, function($value) {
            return $value !== null;
        });

        if (count($gambarData) > 3) { // unit_id, created_at, updated_at + at least 1 image
            DB::table('gambar_unit')->insert($gambarData);
        }
    }

    /**
     * Generate unique filename untuk gambar.
     *
     * @param string $imageType
     * @param $file
     * @return string
     */
    private function generateImageFilename(string $imageType, $file): string
    {
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        $extension = $file->getClientOriginalExtension();

        return "{$timestamp}_{$imageType}_{$random}.{$extension}";
    }

    /**
     * Log unit creation untuk audit trail.
     *
     * @param int $unitId
     * @param array $data
     * @return void
     */
    private function logUnitCreation(int $unitId, array $data): void
    {
        Log::info('Unit created successfully', [
            'unit_id'    => $unitId,
            'kode_unit'  => $data['kode_unit'],
            'nama_unit'  => $data['nama_unit'],
            'user_id'    => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Cleanup uploaded images jika terjadi error.
     *
     * @param array $imagePaths
     * @return void
     */
    private function cleanupUploadedImages(array $imagePaths): void
    {
        foreach ($imagePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::info("Cleaned up uploaded image: {$path}");
            }
        }
    }

    /**
     * Return success response.
     *
     * @param int $unitId
     * @param array $data
     * @return JsonResponse
     */
    private function successResponse(int $unitId, array $data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil disimpan',
            'data'    => [
                'id_unit'   => $unitId,
                'kode_unit' => $data['kode_unit'],
                'nama_unit' => $data['nama_unit'],
            ]
        ], 201);
    }

    /**
     * Return error response.
     *
     * @param string $message
     * @return JsonResponse
     */
    private function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menyimpan unit: ' . $message,
            'errors'  => [
                'general' => [$message]
            ]
        ], 422);
    }

}
