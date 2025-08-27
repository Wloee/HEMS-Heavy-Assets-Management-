<?php

namespace App\Http\Controllers;

use App\Http\Requests\SparepartRequest;
use App\Models\Suplier;
use Exception;
use App\Http\Requests\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SuplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Suplier::query(); // Perbaikan: konsistensi penamaan model

            // Filter berdasarkan pencarian
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_supplier', 'like', "%{$search}%")
                      ->orWhere('alamat', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('no_handphone', 'like', "%{$search}%");
                });
            }

            // Filter berdasarkan status (dengan validasi yang lebih baik)
            if ($request->has('status') && $request->input('status') !== '') {
                $status = $request->input('status');
                // Konversi string ke boolean jika diperlukan
                if ($status === 'active' || $status === '1' || $status === 'true') {
                    $query->where('is_active', 1);
                } elseif ($status === 'inactive' || $status === '0' || $status === 'false') {
                    $query->where('is_active', 0);
                }
            }

            // Urutkan berdasarkan nama supplier secara ascending
            $query->orderBy('nama_supplier', 'asc');

            // Pagination dengan parameter yang dipertahankan
            $suppliers = $query->paginate(10)->appends($request->query());

            // Tambahan data untuk statistik (opsional)
            $totalSuppliers = Suplier::count();
            $activeSuppliers = Suplier::where('is_active', 1)->count();
            $inactiveSuppliers = Suplier::where('is_active', 0)->count();

            return view('sparepart.supplier', compact(
                'suppliers',
                'totalSuppliers',
                'activeSuppliers',
                'inactiveSuppliers'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading supplier data: ' . $e->getMessage(), [
                'user_id' => auth(), // Perbaikan: tambah ->id()
                'request_data' => $request->all()
            ]);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Supplier $request): RedirectResponse
    {
        try {
            $validated = $request->validated();

            Suplier::create($validated); // Gunakan create() daripada insert()

            return redirect()->route('supplier.index')
                ->with('success', 'Data supplier berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating supplier: ' . $e->getMessage()); // Perbaikan pesan error

            return redirect()->back()
                ->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Suplier $suplier)
    {
        // try {
        //     return view('sparepart.supplier-detail', compact('suplier'));
        // } catch (\Exception $e) {
        //     Log::error('Error showing supplier: ' . $e->getMessage());

        //     return redirect()->route('supplier.index')
        //         ->with('error', 'Gagal menampilkan detail supplier.');
        // }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Supplier $request, Suplier $suplier): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $suplier->update($validated);

            return redirect()->route('supplier.index')
                ->with('success', 'Data supplier berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating supplier: ' . $e->getMessage()); // Perbaikan pesan error

            return redirect()->back()
                ->with('error', 'Gagal memperbarui supplier: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
              $suplier = suplier::findOrFail($id);
             /*
            if ($suplier->orders()->exists() || $suplier->products()->exists()) {
                return redirect()->route('supplier.index')
                     ->with('error', 'Supplier tidak dapat dihapus karena masih memiliki data terkait.');
            }
            */
            // Store name for success message
            $supplierName = $suplier->nama_jenis;

            // Delete the jenis unit
            $suplier->delete();

            return redirect()->route('supplier.index')
                ->with('success', "Supplier '{$supplierName}' berhasil dihapus");

        } catch (\Exception $e) {
            Log::error('Error deleting supplier: ' . $e->getMessage());

            return redirect()->route('supplier.index')
                ->with('error', 'Gagal menghapus supplier: ' . $e->getMessage());
        }
    }

    /**
     * Toggle supplier status (active/inactive).
     */
    public function toggleStatus(Suplier $suplier): RedirectResponse
    {
        try {
            $suplier->update(['is_active' => !$suplier->is_active]);

            $status = $suplier->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return redirect()->route('supplier.index')
                ->with('success', "Supplier '{$suplier->nama_supplier}' berhasil {$status}");

        } catch (\Exception $e) {
            Log::error('Error toggling supplier status: ' . $e->getMessage());

            return redirect()->route('supplier.index')
                ->with('error', 'Gagal mengubah status supplier.');
        }
    }
}
