<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Satuan;
use Illuminate\Http\Request;
use App\Models\Sparepart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Suplier;
use Illuminate\Support\Facades\DB;
use Exception;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = DB::table('sparepart')
                ->select('sparepart.*', 'supplier.nama_supplier')
                ->leftJoin('supplier', 'sparepart.supplier_id', '=', 'supplier.id_supplier');

            // Search filter
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('sparepart.kode_sparepart', 'like', '%' . $search . '%')
                      ->orWhere('sparepart.nama_sparepart', 'like', '%' . $search . '%')
                      ->orWhere('sparepart.merk', 'like', '%' . $search . '%');
                });
            }

            // Supplier filter
            if ($request->filled('supplier_id')) {
                $query->where('sparepart.supplier_id', $request->get('supplier_id'));
            }

            // Status filter
            if ($request->filled('status')) {
                $query->where('sparepart.is_active', $request->get('status'));
            }

            $spareparts = $query->orderBy('sparepart.nama_sparepart', 'asc')
                                ->where('sparepart.is_active', true)
                                ->paginate(25)
                                ->withQueryString();

            $suppliers = DB::table('supplier')
                ->where('is_active', true)
                ->orderBy('nama_supplier', 'asc')
                ->get();

            return view('sparepart.table-sparepart', compact('spareparts', 'suppliers'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk menambah sparepart baru
     */
    public function create()
    {
        try {
            $suppliers = Suplier::select('id_supplier', 'nama_supplier')
                ->where('is_active', '1')
                ->orderBy('nama_supplier', 'asc')
                ->get();
            $satuanList = Satuan::select('id_satuan', 'nama_satuan')
                ->where('is_active', '1')
                ->orderBy('nama_satuan', 'asc')
                ->get();

            return view('sparepart.pembelian-sparepart', compact('suppliers', 'satuanList'));
        } catch (\Exception $e) {
            Log::error('Error saat mengambil data supplier: ' . $e->getMessage());

            return redirect()->route('sparepart.index')->with('error', 'Terjadi kesalahan saat memuat form. Silakan coba lagi.'. ' ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        try {
            $sparepart = DB::table('sparepart')
                ->where('id_sparepart', $id)
                ->first();

            if (!$sparepart) {
                return redirect()->route('sparepart.index')
                    ->with('error', 'Sparepart tidak ditemukan');
            }

            $suppliers = Suplier::select('id_supplier', 'nama_supplier')
                ->where('is_active', '1')
                ->orderBy('nama_supplier', 'asc')
                ->get();

            return view('sparepart.sparepart-edit', compact('sparepart', 'suppliers'));

        } catch (Exception $e) {
            Log::error('Error saat mengambil data sparepart untuk edit: ' . $e->getMessage());
            return redirect()->route('sparepart.index')
                ->with('error', 'Terjadi kesalahan saat memuat data. Silakan coba lagi.');
        }
    }

    /**
     * Update sparepart yang sudah ada
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kode_sparepart' => 'required|string|max:50|unique:sparepart,kode_sparepart,' . $id . ',id_sparepart',
                'nama_sparepart' => 'required|string|max:100',
                'merk' => 'nullable|string|max:50',
                'supplier_id' => 'required|exists:supplier,id_supplier',
                'harga_beli' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
                'stok_minimum' => 'required|integer|min:0',
                'satuan' => 'required|string|max:20',
                'lokasi_penyimpanan' => 'nullable|string|max:100',
                'deskripsi' => 'nullable|string|max:500',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $sparepart = DB::table('sparepart')
                ->where('id_sparepart', $id)
                ->first();

            if (!$sparepart) {
                return redirect()->route('sparepart.index')
                    ->with('error', 'Sparepart tidak ditemukan');
            }

            DB::beginTransaction();

            $updateData = [
                'kode_sparepart' => $request->kode_sparepart,
                'nama_sparepart' => $request->nama_sparepart,
                'merk' => $request->merk,
                'supplier_id' => $request->supplier_id,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok_minimum' => $request->stok_minimum,
                'satuan' => $request->satuan,
                'lokasi_penyimpanan' => $request->lokasi_penyimpanan,
                'deskripsi' => $request->deskripsi,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'updated_at' => now()
            ];

            DB::table('sparepart')
                ->where('id_sparepart', $id)
                ->update($updateData);

            DB::commit();

            return redirect()->route('sparepart.index')
                ->with('success', 'Data sparepart berhasil diperbarui');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error saat update sparepart: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $sparepart = DB::table('sparepart')
                ->where('id_sparepart', $id)
                ->first();

            if (!$sparepart) {
                return redirect()->back()->with('error', 'Sparepart tidak ditemukan');
            }

            DB::beginTransaction();

            DB::table('sparepart')
                ->where('id_sparepart', $id)
                ->delete();

            DB::commit();

            return redirect()->route('sparepart.index')
                ->with('success', 'Sparepart berhasil dihapus');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function lowStock()
    {
        try {
            $spareparts = DB::table('sparepart')
                ->select('sparepart.*', 'supplier.nama_supplier')
                ->leftJoin('supplier', 'sparepart.supplier_id', '=', 'supplier.id_supplier')
                ->whereRaw('sparepart.stok_saat_ini <= sparepart.stok_minimum')
                ->where('sparepart.is_active', true)
                ->orderBy('sparepart.stok_saat_ini', 'asc')
                ->paginate(25);

            return view('sparepart.low-stock', compact('spareparts'));

        } catch (Exception $e) {
            Log::error('Error saat mengambil data low stock: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form untuk update stok sparepart
     */
    public function editStock(string $id)
    {
        try {
            $sparepart = DB::table('sparepart')
                ->select('sparepart.*', 'supplier.nama_supplier')
                ->leftJoin('supplier', 'sparepart.supplier_id', '=', 'supplier.id_supplier')
                ->where('sparepart.id_sparepart', $id)
                ->first();

            if (!$sparepart) {
                return redirect()->route('sparepart.index')
                    ->with('error', 'Sparepart tidak ditemukan');
            }

            return view('sparepart.update-stock', compact('sparepart'));

        } catch (Exception $e) {
            Log::error('Error saat mengambil data sparepart untuk update stock: ' . $e->getMessage());
            return redirect()->route('sparepart.index')
                ->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    /**
     * Update stok sparepart
     */
    public function updateStock(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_update' => 'required|in:tambah,kurang,set',
                'jumlah' => 'required|integer|min:1',
                'keterangan' => 'nullable|string|max:200'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $sparepart = DB::table('sparepart')
                ->where('id_sparepart', $id)
                ->first();

            if (!$sparepart) {
                return redirect()->route('sparepart.index')
                    ->with('error', 'Sparepart tidak ditemukan');
            }

            DB::beginTransaction();

            $stokLama = $sparepart->stok_saat_ini;
            $jumlah = $request->jumlah;
            $jenisUpdate = $request->jenis_update;

            switch ($jenisUpdate) {
                case 'tambah':
                    $stokBaru = $stokLama + $jumlah;
                    break;
                case 'kurang':
                    $stokBaru = $stokLama - $jumlah;
                    if ($stokBaru < 0) {
                        return redirect()->back()
                            ->with('error', 'Stok tidak dapat menjadi negatif. Stok saat ini: ' . $stokLama)
                            ->withInput();
                    }
                    break;
                case 'set':
                    $stokBaru = $jumlah;
                    break;
                default:
                    throw new Exception('Jenis update tidak valid');
            }

            DB::table('sparepart')
                ->where('id_sparepart', $id)
                ->update([
                    'stok_saat_ini' => $stokBaru,
                    'updated_at' => now()
                ]);

            DB::commit();

            $message = "Stok berhasil diperbarui dari {$stokLama} menjadi {$stokBaru}";
            return redirect()->route('sparepart.index')
                ->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error saat update stock: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui stok: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan laporan stok sparepart
     */
    public function stockReport(Request $request)
    {
        try {
            $query = DB::table('sparepart')
                ->select('sparepart.*', 'supplier.nama_supplier')
                ->leftJoin('supplier', 'sparepart.supplier_id', '=', 'supplier.id_supplier')
                ->where('sparepart.is_active', true);

            if ($request->filled('stock_category')) {
                $category = $request->get('stock_category');
                switch ($category) {
                    case 'low':
                        $query->whereRaw('sparepart.stok_saat_ini <= sparepart.stok_minimum');
                        break;
                    case 'empty':
                        $query->where('sparepart.stok_saat_ini', 0);
                        break;
                    case 'sufficient':
                        $query->whereRaw('sparepart.stok_saat_ini > sparepart.stok_minimum');
                        break;
                }
            }

            if ($request->filled('supplier_id')) {
                $query->where('sparepart.supplier_id', $request->get('supplier_id'));
            }

            $spareparts = $query->orderBy('sparepart.nama_sparepart', 'asc')
                               ->paginate(25)
                               ->withQueryString();

            $suppliers = DB::table('supplier')
                ->where('is_active', true)
                ->orderBy('nama_supplier', 'asc')
                ->get();

            $statistics = [
                'total_items' => DB::table('sparepart')->where('is_active', true)->count(),
                'low_stock_items' => DB::table('sparepart')
                    ->whereRaw('stok_saat_ini <= stok_minimum')
                    ->where('is_active', true)
                    ->count(),
                'empty_stock_items' => DB::table('sparepart')
                    ->where('stok_saat_ini', 0)
                    ->where('is_active', true)
                    ->count(),
                'total_stock_value' => DB::table('sparepart')
                    ->where('is_active', true)
                    ->sum(DB::raw('stok_saat_ini * harga_beli'))
            ];

            return view('sparepart.stock-report', compact('spareparts', 'suppliers', 'statistics'));

        } catch (Exception $e) {
            Log::error('Error saat mengambil stock report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update stok untuk multiple sparepart
     */
    public function bulkUpdateStock(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sparepart_ids' => 'required|array|min:1',
                'sparepart_ids.*' => 'exists:sparepart,id_sparepart',
                'jenis_update' => 'required|in:tambah,kurang,set',
                'jumlah' => 'required|integer|min:1',
                'keterangan' => 'nullable|string|max:200'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            $sparepartIds = $request->sparepart_ids;
            $jenisUpdate = $request->jenis_update;
            $jumlah = $request->jumlah;
            $updatedCount = 0;

            foreach ($sparepartIds as $sparepartId) {
                $sparepart = DB::table('sparepart')
                    ->where('id_sparepart', $sparepartId)
                    ->first();

                if (!$sparepart) continue;

                $stokLama = $sparepart->stok_saat_ini;

                switch ($jenisUpdate) {
                    case 'tambah':
                        $stokBaru = $stokLama + $jumlah;
                        break;
                    case 'kurang':
                        $stokBaru = max(0, $stokLama - $jumlah);
                        break;
                    case 'set':
                        $stokBaru = $jumlah;
                        break;
                    default:
                        continue 2;
                }

                DB::table('sparepart')
                    ->where('id_sparepart', $sparepartId)
                    ->update([
                        'stok_saat_ini' => $stokBaru,
                        'updated_at' => now()
                    ]);

                $updatedCount++;
            }

            DB::commit();

            return redirect()->back()
                ->with('success', "Berhasil memperbarui stok untuk {$updatedCount} sparepart");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error saat bulk update stock: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui stok: ' . $e->getMessage());
        }
    }

    public function permintaan(){
        try {
            $karyawan = Karyawan::all(); // untuk dropdown karyawan
            $spareparts = Sparepart::with('satuan')->get(); // untuk dropdown sparepart dengan relasi satuan
            $satuanList = Satuan::all(); // untuk dropdown satuan

            return view('sparepart.pengadaan-sparepart', compact('karyawan', 'spareparts', 'satuanList'));

        } catch (Exception $e) {
            Log::error('Error saat mengambil data pengadaan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function storePermintaan(Request $request)
    {
        dd($request->all());
    }
}
