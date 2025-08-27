<?php

namespace App\Http\Controllers;

use App\Models\JenisUnit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class JenisUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        try {
            $query = JenisUnit::query();

            // Search functionality
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_jenis', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            // Order by nama_jenis ascending
            $query->orderBy('nama_jenis', 'asc');

            $jenisUnits = $query->paginate(25)->appends($request->query());

            return view('unit.jenis-unit', compact('jenisUnits'));

        } catch (\Exception $e) {
            Log::error('Error loading jenis unit data: ' . $e->getMessage());

            return view('unit.jenis-unit', [
                'error' => 'Gagal memuat data jenis unit: ' . $e->getMessage(),
                'jenisUnits' => new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]),
                    0,
                    25,
                    1,
                    ['path' => request()->url()]
                )
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            // Validation rules
            $validated = $request->validate([
                'nama_jenis' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'unique:jenis_unit,nama_jenis'
                ],
                'deskripsi' => 'nullable|string|max:1000'
            ], [
                'nama_jenis.required' => 'Nama jenis unit wajib diisi.',
                'nama_jenis.min' => 'Nama jenis unit minimal 3 karakter.',
                'nama_jenis.max' => 'Nama jenis unit maksimal 100 karakter.',
                'nama_jenis.unique' => 'Nama jenis unit sudah ada, gunakan nama lain.',
                'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.'
            ]);

            // Create new jenis unit
            JenisUnit::create([
                'nama_jenis' => trim($validated['nama_jenis']),
                'deskripsi' => $validated['deskripsi'] ? trim($validated['deskripsi']) : null
            ]);

            return redirect()->route('jenis-unit.index')
                           ->with('success', 'Jenis unit berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                           ->withErrors($e->validator)
                           ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating jenis unit: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Gagal menambahkan jenis unit: ' . $e->getMessage())
                           ->withInput();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $jenisUnit = JenisUnit::findOrFail($id);

            // Validation rules
            $validated = $request->validate([
                'nama_jenis' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    Rule::unique('jenis_unit', 'nama_jenis')->ignore($jenisUnit->id_jenis_unit, 'id_jenis_unit')
                ],
                'deskripsi' => 'nullable|string|max:1000'
            ], [
                'nama_jenis.required' => 'Nama jenis unit wajib diisi.',
                'nama_jenis.min' => 'Nama jenis unit minimal 3 karakter.',
                'nama_jenis.max' => 'Nama jenis unit maksimal 100 karakter.',
                'nama_jenis.unique' => 'Nama jenis unit sudah ada, gunakan nama lain.',
                'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.'
            ]);

            // Update jenis unit
            $jenisUnit->update([
                'nama_jenis' => trim($validated['nama_jenis']),
                'deskripsi' => $validated['deskripsi'] ? trim($validated['deskripsi']) : null
            ]);

            return redirect()->route('jenis-unit.index')
                           ->with('success', 'Jenis unit berhasil diperbarui!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('jenis-unit.index')
                           ->with('error', 'Jenis unit tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                           ->withErrors($e->validator)
                           ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating jenis unit: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Gagal memperbarui jenis unit: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $jenisUnit = JenisUnit::findOrFail($id);

            /*
            $isUsed = DB::table('units')
                       ->where('id_jenis_unit', $id)
                       ->exists();

            if ($isUsed) {
                return redirect()->route('jenis-unit.index')
                               ->with('error', 'Jenis unit tidak dapat dihapus karena masih digunakan.');
            }
            */

            // Store name for success message
            $namaJenis = $jenisUnit->nama_jenis;

            // Delete the jenis unit
            $jenisUnit->delete();

            return redirect()->route('jenis-unit.index')
                           ->with('success', "Jenis unit '{$namaJenis}' berhasil dihapus!");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('jenis-unit.index')
                           ->with('error', 'Jenis unit tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error deleting jenis unit: ' . $e->getMessage());

            return redirect()->route('jenis-unit.index')
                           ->with('error', 'Gagal menghapus jenis unit: ' . $e->getMessage());
        }
    }

    /**
     * Get jenis unit data for API/AJAX calls
     */
    public function getJenisUnits(Request $request)
    {
        try {
            $query = JenisUnit::query();

            // Search functionality for API
            if ($search = $request->input('search')) {
                $query->where('nama_jenis', 'like', "%{$search}%");
            }

            $jenisUnits = $query->orderBy('nama_jenis', 'asc')
                               ->get(['id_jenis_unit', 'nama_jenis', 'deskripsi']);

            return response()->json([
                'success' => true,
                'data' => $jenisUnits
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching jenis units: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jenis unit.'
            ], 500);
        }
    }

    /**
     * Bulk delete jenis units
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'exists:jenis_unit,id_jenis_unit'
            ], [
                'ids.required' => 'Pilih minimal satu jenis unit untuk dihapus.',
                'ids.*.exists' => 'Jenis unit tidak valid.'
            ]);

            $deletedCount = JenisUnit::whereIn('id_jenis_unit', $validated['ids'])->count();
            JenisUnit::whereIn('id_jenis_unit', $validated['ids'])->delete();

            return redirect()->route('jenis-unit.index')
                           ->with('success', "Berhasil menghapus {$deletedCount} jenis unit!");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator);
        } catch (\Exception $e) {
            Log::error('Error bulk deleting jenis units: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Gagal menghapus jenis unit: ' . $e->getMessage());
        }
    }

    /**
     * Export jenis units to CSV
     */
    public function export()
    {
        try {
            $jenisUnits = JenisUnit::orderBy('nama_jenis', 'asc')->get();

            $csvData = "ID,Nama Jenis Unit,Deskripsi,Tanggal Dibuat,Terakhir Update\n";

            foreach ($jenisUnits as $jenisUnit) {
                $csvData .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    $jenisUnit->id_jenis_unit,
                    '"' . str_replace('"', '""', $jenisUnit->nama_jenis) . '"',
                    '"' . str_replace('"', '""', $jenisUnit->deskripsi ?? '') . '"',
                    $jenisUnit->created_at->format('d/m/Y H:i:s'),
                    $jenisUnit->updated_at->format('d/m/Y H:i:s')
                );
            }

            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="jenis_unit_' . date('Y-m-d_H-i-s') . '.csv"');

        } catch (\Exception $e) {
            Log::error('Error exporting jenis units: ' . $e->getMessage());

            return redirect()->back()
                           ->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
