<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Proyek;
use App\Models\JenisPekerjaan;
use App\Models\DetailBiayaPekerjaan;
use App\Models\invoice;
use Carbon\Carbon;


class ProyekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    try {
        $query = DB::table('proyek')
            ->leftJoin('detail_biaya_pekerjaan', 'proyek.id_proyek', '=', 'detail_biaya_pekerjaan.proyek_id')
            ->leftJoin('invoice', 'invoice.proyek_id', '=', 'proyek.id_proyek')
            ->select(
                'proyek.*',
                DB::raw('SUM(detail_biaya_pekerjaan.biaya_total) as biaya_total'),
                DB::raw('GROUP_CONCAT(invoice.status) as status_invoice')
            )
            ->groupBy('proyek.id_proyek') // penting agar proyek tetap 1 row
            ->orderBy('proyek.nama_proyek', 'asc'); // urut berdasarkan nama proyek

        // Pagination
        $proyek = $query->paginate(25)->withQueryString();

        return view('proyek.index', compact('proyek'));
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal memuat proyek: ' . $e->getMessage());
    }


}

//     public function index(Request $request)
// {
//     try {
//         // Optimized query - avoid multiple joins that cause cartesian products
//         $query = DB::table('proyek')
//             ->leftJoin('invoice', function($join) {
//                 // Get latest invoice per project to avoid duplicates
//                 $join->on('invoice.proyek_id', '=', 'proyek.id_proyek')
//                      ->whereRaw('invoice.id = (SELECT MAX(id) FROM invoice i2 WHERE i2.proyek_id = proyek.id_proyek)');
//             })
//             ->select(
//                 'proyek.id_proyek',
//                 'proyek.nama_proyek',
//                 'proyek.nama_client',
//                 'proyek.tanggal_mulai',
//                 'proyek.tanggal_selesai',
//                 'proyek.status as status_proyek',
//                 'proyek.nilai_kontrak',
//                 'proyek.created_at',
//                 'proyek.updated_at',
//                 'invoice.status as status_invoice'
//             );

//         // Search filter - applied early for better performance
//         if ($request->filled('search')) {
//             $search = $request->get('search');
//             $query->where(function ($q) use ($search) {
//                 $q->where('proyek.nama_proyek', 'like', '%' . $search . '%')
//                   ->orWhere('proyek.nama_client', 'like', '%' . $search . '%');
//             });
//         }

//         // Invoice status filter
//         if ($request->filled('status_invoice')) {
//             $query->where('invoice.status', $request->get('status_invoice'));
//         }

//         // Date range filter (optional - good for performance)
//         if ($request->filled('date_from')) {
//             $query->where('proyek.tanggal_mulai', '>=', $request->get('date_from'));
//         }

//         if ($request->filled('date_to')) {
//             $query->where('proyek.tanggal_mulai', '<=', $request->get('date_to'));
//         }

//         // Status proyek filter (optional)
//         if ($request->filled('status_proyek')) {
//             $query->where('proyek.status', $request->get('status_proyek'));
//         }

//         // Execute query with pagination
//         $proyek = $query->orderBy('proyek.nama_proyek', 'asc')
//                         ->paginate(25)
//                         ->withQueryString();

//         // Get summary data for dashboard (optional)
//         $summary = [
//             'total_proyek' => DB::table('proyek')->count(),
//             'total_nilai_kontrak' => DB::table('proyek')->sum('nilai_kontrak'),
//             'proyek_aktif' => DB::table('proyek')->where('status', 'aktif')->count(),
//         ];

//         return view('proyek.index', compact('proyek', 'summary'));

//     } catch (\Exception $e) {
//         \Log::error('Failed to load projects: ' . $e->getMessage(), [
//             'request' => $request->all(),
//             'trace' => $e->getTraceAsString()
//         ]);

//         return redirect()->back()
//                ->withErrors(['error' => 'Failed to load projects. Please try again.'])
//                ->withInput();
//     }
// }//Failed to load the form: compact(): Undefined variable $jenis_pekerjaan


  public function create()
{
    try {
        $jenis_unit = DB::table('jenis_unit')->get();
        $jenis_pekerjaan = DB::table('jenis_pekerjaan')->get();
        $proyek = null; // Tambahkan ini supaya Blade tidak error

        return view('proyek.input', compact('jenis_unit','jenis_pekerjaan','proyek'));
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Failed to load the form: ' . $e->getMessage()]);
    }
}


public function store(Request $request)
{
    dd($request->all());
    Log::info('Mulai proses store proyek', ['request' => $request->all()]);

    DB::beginTransaction();

    try {
        // Simpan data proyek
        $proyekData = [
            'nama_proyek' => $request->input('nama_proyek', ''),
            'nama_client' => $request->input('nama_client', ''),
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_selesai_aktual' => $request->input('tanggal_selesai_aktual') ?: null,
            'status' => $request->input('status'),
            'deskripsi' => $request->input('deskripsi') ?: null,
            'lokasi_proyek' => $request->input('lokasi_proyek', ''),
            'created_at' => now(),
            'updated_at' => now(),
        ];


$proyekId = DB::table('proyek')->insertGetId($proyekData, 'id_proyek');
        Log::info('Data proyek berhasil disimpan', ['proyek_id' => $proyekId]);

        // Simpan data biaya
        $biayaData = [
            'proyek_id' => $proyekId,
            'jenis_pekerjaan_id' => $request->input('biaya_jenis_pekerjaan_id'),
            'biaya_total' => $request->input('biaya_total'),
            'deskripsi' => $request->input('biaya_deskripsi'),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $tes = DB::table('detail_biaya_pekerjaan')->insert($biayaData);
        Log::info('Biaya proyek ditambahkan', $biayaData);
                $invoiceData = [
                    'proyek_id' => $proyekId,
                    'tanggal_invoice' => $request->input('invoice_tanggal_invoice'),
                    'tanggal_jatuh_tempo' => $request->input('invoice_tanggal_jatuh_tempo'),
                    'jumlah_tagihan' => $request->input('invoice_jumlah_tagihan'),
                    'sisa_piutang' => $request->input('sisa_piutang'),
                    'status' => $request->input('invoice_status', 'draft'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::table('invoice')->insert($invoiceData);
                Log::info('Invoice proyek ditambahkan', $invoiceData);



        DB::commit();
        Log::info('Transaksi store proyek berhasil diselesaikan', ['proyek_id' => $proyekId]);

        return redirect()->route('dashboard')->with('success', 'Proyek berhasil ditambahkan!');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Gagal menyimpan proyek', ['error' => $e->getMessage()]);

        return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()]);
    }
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        try {
            // Ambil data proyek dengan relasi
            $proyek = Proyek::with([
                'detailBiayaPekerjaan.jenisPekerjaan',
                'invoices'
            ])->findOrFail($id);

            // Ambil semua jenis pekerjaan untuk dropdown
            $jenis_pekerjaan = JenisPekerjaan::orderBy('nama_jenis_pekerjaan')->get();

            // Ambil data biaya proyek (untuk keperluan form)
            $biaya_proyek = $proyek->detailBiayaPekerjaan;

            return view('proyek.input', compact('proyek', 'jenis_pekerjaan', 'biaya_proyek'));
        } catch (\Exception $e) {
            return redirect()->route('proyek.index')
                ->with('error', 'Proyek tidak ditemukan.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama_proyek' => 'required|string|max:255',
            'nama_client' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai_aktual' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:draft,aktif,selesai,ditunda,dibatalkan',
            'deskripsi' => 'nullable|string',
            'lokasi_proyek' => 'nullable|string',

            // Validasi untuk detail biaya pekerjaan
            'biaya_jenis_pekerjaan_id' => 'required|exists:jenis_pekerjaan,id_jenis_pekerjaan',
            'biaya_total' => 'required|numeric|min:0',
            'biaya_deskripsi' => 'nullable|string',

            // Validasi untuk invoice (opsional)
            'invoice_tanggal_invoice' => 'nullable|date',
            'invoice_tanggal_jatuh_tempo' => 'nullable|date|after_or_equal:invoice_tanggal_invoice',
            'invoice_jumlah_tagihan' => 'nullable|numeric|min:0',
            'invoice_status' => 'nullable|in:draft,terkirim,dibayar_sebagian,lunas,jatuh_tempo',
        ], [
            // Custom error messages
            'nama_proyek.required' => 'Nama proyek harus diisi.',
            'nama_client.required' => 'Nama client harus diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_selesai_aktual.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'status.required' => 'Status proyek harus dipilih.',
            'biaya_jenis_pekerjaan_id.required' => 'Jenis pekerjaan harus dipilih.',
            'biaya_jenis_pekerjaan_id.exists' => 'Jenis pekerjaan tidak valid.',
            'biaya_total.required' => 'Biaya total harus diisi.',
            'biaya_total.numeric' => 'Biaya total harus berupa angka.',
            'biaya_total.min' => 'Biaya total tidak boleh negatif.',
            'invoice_tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo tidak boleh sebelum tanggal invoice.',
            'invoice_jumlah_tagihan.numeric' => 'Jumlah tagihan harus berupa angka.',
        ]);

        // Validasi khusus
        $validator->after(function ($validator) use ($request) {
            // Jika status selesai, tanggal selesai aktual harus diisi
            if ($request->status === 'selesai' && !$request->tanggal_selesai_aktual) {
                $validator->errors()->add('tanggal_selesai_aktual', 'Tanggal selesai aktual wajib diisi untuk status "Selesai".');
            }

            // Validasi invoice: jika salah satu field invoice diisi, field wajib lainnya harus diisi
            $hasInvoiceData = $request->invoice_tanggal_invoice ||
                             $request->invoice_jumlah_tagihan ||
                             $request->invoice_status;

            if ($hasInvoiceData) {
                if (!$request->invoice_tanggal_invoice) {
                    $validator->errors()->add('invoice_tanggal_invoice', 'Tanggal invoice harus diisi.');
                }
                if (!$request->invoice_jumlah_tagihan) {
                    $validator->errors()->add('invoice_jumlah_tagihan', 'Jumlah tagihan harus diisi.');
                }
                if (!$request->invoice_status) {
                    $validator->errors()->add('invoice_status', 'Status invoice harus dipilih.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan dalam input data.');
        }

        DB::beginTransaction();

        try {
            // Cari proyek yang akan diupdate
            $proyek = Proyek::findOrFail($id);

            // Update data proyek
            $proyek->update([
                'nama_proyek' => $request->nama_proyek,
                'nama_client' => $request->nama_client,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai_aktual' => $request->tanggal_selesai_aktual,
                'status' => $request->status,
                'deskripsi' => $request->deskripsi,
                'lokasi_proyek' => $request->lokasi_proyek,
            ]);

            // Update atau create detail biaya pekerjaan
            $detailBiaya = DetailBiayaPekerjaan::where('proyek_id', $proyek->id_proyek)->first();

            $biayaData = [
                'proyek_id' => $proyek->id_proyek,
                'jenis_pekerjaan_id' => $request->biaya_jenis_pekerjaan_id,
                'deskripsi' => $request->biaya_deskripsi,
                'biaya_total' => $request->biaya_total,
            ];

            if ($detailBiaya) {
                $detailBiaya->update($biayaData);
            } else {
                DetailBiayaPekerjaan::insert($biayaData);
            }

            // Handle invoice data (opsional)
            if ($request->invoice_tanggal_invoice ||
                $request->invoice_jumlah_tagihan ||
                $request->invoice_status) {

                $invoiceData = [
                    'proyek_id' => $proyek->id_proyek,
                    'tanggal_invoice' => $request->invoice_tanggal_invoice,
                    'tanggal_jatuh_tempo' => $request->invoice_tanggal_jatuh_tempo,
                    'jumlah_tagihan' => $request->invoice_jumlah_tagihan,
                    'sisa_piutang' => $request->invoice_status === 'lunas' ? 0 : $request->invoice_jumlah_tagihan,
                    'status' => $request->invoice_status,
                ];

                $invoice = Invoice::where('proyek_id', $proyek->id_proyek)->first();

                if ($invoice) {
                    $invoice->update($invoiceData);
                } else {
                    Invoice::create($invoiceData);
                }
            } else {
                // Jika tidak ada data invoice, hapus invoice yang ada (jika ada)
                Invoice::where('proyek_id', $proyek->id_proyek)->delete();
            }

            DB::commit();

            return redirect()->route('proyek.index')
                ->with('success', 'Proyek "' . $proyek->nama_proyek . '" berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui proyek: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        try {
            $proyek = Proyek::findOrFail($id);
            $proyek->delete();

            return redirect()->route('proyek.index')
                ->with('success', 'Proyek berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus proyek: ' . $e->getMessage());
        }
    }

    // Method untuk menampilkan form addendum
//     public function addendum($id)
//     {
//         try {
//             $proyek = Proyek::findOrFail($id);
//             return view('proyek.addendum', compact('proyek'));
//         } catch (\Exception $e) {
//             return redirect()->route('proyek.index')
//                 ->with('error', 'Proyek tidak ditemukan.');
//         }
//     }
//     public function storeAddendum(Request $request, $id)
//     {
//         $request->validate([
//             'tanggal_addendum' => 'required|date',
//             'deskripsi' => 'required|string|max:255',
//             'biaya_addendum' => 'required|numeric|min:0',
//         ]);

//         try {
//             $addendum = new \App\Models\Addendum();
//             $addendum->proyek_id = $id;
//             $addendum->tanggal_addendum = $request->tanggal_addendum;
//             $addendum->deskripsi = $request->deskripsi;
//             $addendum->biaya_addendum = $request->biaya_addendum;
//             $addendum->save();

//             return redirect()->route('proyek.index')
//                 ->with('success', 'Addendum berhasil ditambahkan.');
//         } catch (\Exception $e) {
//             return redirect()->back()
//                 ->withInput()
//                 ->with('error', 'Termintan terjadi kesalahan saat menambahkan addendum: ' . $e->getMessage());
// }
// }
// public function showAddendum($id, $addendumId)
// {
//     try {
//         $addendum = \App\Models\Addendum::where('proyek_id', $id)->findOrFail($addendumId);
//         return view('proyek.show_addendum', compact('addendum'));
//     } catch (\Exception $e) {
//         return redirect()->route('proyek.index')
//             ->with('error', 'Addendum tidak ditemukan.');
//     }
// }
// public function editAddendum($id, $addendumId)
// {
//     try {
//         $addendum = \App\Models\Addendum::where('proyek_id', $id)->findOrFail($addendumId);
//         return view('proyek.edit_addendum', compact('addendum'));
//     } catch (\Exception $e) {
//         return redirect()->route('proyek.index')
//             ->with('error', 'Addendum tidak ditemukan.');
//     }
// }
// public function updateAddendum(Request $request, $id, $addendumId)
// {
//     $request->validate([
//         'tanggal_addendum' => 'required|date',
//         'deskripsi' => 'required|string|max:255',
//         'biaya_addendum' => 'required|numeric|min:0',
//     ]);

//     try {
//         $addendum = \App\Models\Addendum::where('proyek_id', $id)->findOrFail($addendumId);
//         $addendum->tanggal_addendum = $request->tanggal_addendum;
//         $addendum->deskripsi = $request->deskripsi;
//         $addendum->biaya_addendum = $request->biaya_addendum;
//         $addendum->save();

//         return redirect()->route('proyek.index')
//             ->with('success', 'Addendum berhasil diperbarui.');
//     } catch (\Exception $e) {
//         return redirect()->back()
//             ->withInput()
//             ->with('error', 'Terjadi kesalahan saat memperbarui addendum: ' . $e->getMessage());
//     }
// }
// public function destroyAddendum($id, $addendumId)
// {
//     try {
//         $addendum = \App\Models\Addendum::where('proyek_id', $id)->findOrFail($addendumId);
//         $addendum->delete();

//         return redirect()->route('proyek.index')
//             ->with('success', 'Addendum berhasil dihapus.');
//     } catch (\Exception $e) {
//         return redirect()->back()
//             ->with('error', 'Terjadi kesalahan saat menghapus addendum: ' . $e->getMessage());
//     }
// }
// }
}
