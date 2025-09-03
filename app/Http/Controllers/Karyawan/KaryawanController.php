<?php


namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\Posisi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;



class KaryawanController extends Controller
{
    public function index()
    {
      $posisi = Posisi::all();
        $departemen = Departemen::all();
        return view('karyawan.form', compact('posisi', 'departemen'));
    }

   public function data(Request $request): View
        {
            try {
                $query = Karyawan::query()->with('posisi');

                // Filter berdasarkan input yang tersedia
                if ($search = $request->input('search')) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('no_nik', 'like', "%{$search}%");
                    });
                } elseif ($role = $request->input('role')) {
                    // Perbaikan: gunakan whereHas untuk relasi
                    $query->whereHas('posisi', function ($q) use ($role) {
                        $q->where('nama_posisi', $role);
                    });
                } elseif ($status = $request->input('status')) {
                    // Perbaikan: konsistensi nama kolom (lowercase)
                    $query->where('status', $status);
                }

                $karyawan = $query->paginate(25);

                // Menambahkan warna untuk status
                $karyawan->getCollection()->transform(function ($item) {
                    // Perbaikan: konsistensi nama field (lowercase) dan switch statement
                    switch (strtolower($item->status)) {
                        case 'aktif':
                            $item->status_color = '#37e414';
                            break;
                        case 'cuti':
                            $item->status_color = '#ff0000';
                            break;
                        case 'tidak aktif':
                            $item->status_color = '#6b7280';
                            break;
                        default:
                            $item->status_color = '#000000';
                            break;
                    }
                    return $item;
                });

                return view('karyawan.data', compact('karyawan'));

            } catch (\Exception $e) {
                // Perbaikan: struktur array yang konsisten dan logging error
                Log::error('Error loading karyawan data: ' . $e->getMessage());

                return view('karyawan.data', [
                    'error' => 'Gagal memuat data: ' . $e->getMessage(),
                    'karyawan' => new \Illuminate\Pagination\LengthAwarePaginator(
                        collect([]),
                        0,
                        25,
                        1,
                        ['path' => request()->url()]
                    )
                ]);
            }
        }

   public function store(Request $request)
{
    // Validasi input dengan batas nilai yang sesuai
    $validator = Validator::make($request->all(), [
        // Data Pribadi
        'nama_lengkap' => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'nomor_hp' => 'required|string|max:20',
        'no_nik' => 'required|string|max:16|unique:karyawan,no_nik',
        'scan_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'surat_lamaran' => 'required|file|mimes:pdf,doc,docx|max:2048',

        // Data Pekerjaan
        'id_posisi' => 'required|exists:posisi,id_posisi',
        'id_departemen' => 'required|exists:departemen,id_departemen',
        'tanggal_bergabung' => 'required|date',

        // Data Penggajian
        'gaji' => 'required|numeric|min:0|max:2147483647',
        'tunjangan' => 'nullable|numeric|min:0|max:2147483647',
        'insentif' => 'nullable|numeric|min:0|max:2147483647',
    ], [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi',
        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
        'nomor_hp.required' => 'Nomor HP/WA wajib diisi',
        'no_nik.required' => 'NIK wajib diisi',
        'no_nik.unique' => 'NIK sudah terdaftar',
        'scan_ktp.required' => 'Scan KTP wajib diupload',
        'scan_ktp.mimes' => 'Format file KTP harus PDF, JPG, JPEG, atau PNG',
        'surat_lamaran.required' => 'Surat lamaran wajib diupload',
        'surat_lamaran.mimes' => 'Format surat lamaran harus PDF, DOC, atau DOCX',
        'id_posisi.required' => 'Posisi wajib dipilih',
        'id_departemen.required' => 'Departemen wajib dipilih',
        'tanggal_bergabung.required' => 'Tanggal bergabung wajib diisi',
        'gaji.required' => 'Gaji wajib diisi',
        'gaji.numeric' => 'Gaji harus berupa angka',
        'gaji.max' => 'Gaji tidak boleh lebih dari Rp 2.147.483.647',
        'tunjangan.max' => 'Tunjangan tidak boleh lebih dari Rp 2.147.483.647',
        'insentif.max' => 'Insentif tidak boleh lebih dari Rp 2.147.483.647',
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        // Handle file uploads
        $scanKtpPath = null;
        $suratLamaranPath = null;

        if ($request->hasFile('scan_ktp')) {
            $scanKtpPath = $request->file('scan_ktp')->store('documents/ktp', 'public');
        }

        if ($request->hasFile('surat_lamaran')) {
            $suratLamaranPath = $request->file('surat_lamaran')->store('documents/lamaran', 'public');
        }

        // Konversi nilai numeric
        $gaji = (int) $request->gaji;
        $tunjangan = $request->tunjangan ? (int) $request->tunjangan : 0;
        $insentif = $request->insentif ? (int) $request->insentif : 0;

        // Insert data karyawan
        $karyawan = Karyawan::create([
            'nama_karyawan' => $request->nama_lengkap,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_handphone' => $request->nomor_hp,
            'no_nik' => $request->no_nik,
            'posisi_id' => $request->id_posisi,
            'departemen_id' => $request->id_departemen,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'Gaji' => $gaji,
            'Tunjangan' => $tunjangan,
            'Intensif' => $insentif,
        ]);

        // Insert dokumen dengan foreign key
        DB::table('dokumen_karyawan')->insert([
            'karyawan_id' => $karyawan->id_karyawan,
            'image_ktp' => $scanKtpPath,
            'surat_lamaran' => $suratLamaranPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/dashboard')
            ->with('success', 'Data karyawan berhasil ditambahkan!');

    } catch (\Exception $e) {
        // Delete uploaded files if database insert fails
        if ($scanKtpPath) {
            Storage::disk('public')->delete($scanKtpPath);
        }
        if ($suratLamaranPath) {
            Storage::disk('public')->delete($suratLamaranPath);
        }

        return back()
            ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
            ->withInput();
    }
}

    public function update(Request $request, $id)
{
        $karyawan = Karyawan::where('id_karyawan',$id)->first();

    $validator = Validator::make($request->all(), [
        'nama_lengkap'      => 'required|string|max:255',
        'tanggal_lahir'     => 'required|date',
        'nomor_hp'          => 'required|string|max:20',
        'no_nik'            => 'required|string|max:16|unique:karyawan,no_nik,'.$id.',id_karyawan',
        'id_posisi'         => 'required|exists:posisi,id_posisi',
        'id_departemen'     => 'required|exists:departemen,id_departemen',
        'tanggal_bergabung' => 'required|date',
        'gaji'              => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Update data
    $karyawan->update([
        'nama_lengkap'       => $request->nama_lengkap,
        'tanggal_lahir'      => $request->tanggal_lahir,
        'no_handphone'       => $request->nomor_hp,
        'no_nik'             => $request->no_nik,
        'posisi_id'          => $request->id_posisi,
        'departemen_id'      => $request->id_departemen,
        'tanggal_bergabung'  => $request->tanggal_bergabung,
        'Gaji'               => $request->gaji,
        'Tunjangan'          => $request->tunjangan ?? 0,
        'Intensif'           => $request->insentif ?? 0,
    ]);

    return redirect()->route('data_karyawan')->with('success', 'Data karyawan berhasil diupdate');
}



    public function edit($id)
    {
        $karyawan = Karyawan::where('id_karyawan',$id)->first();
        $departemen = Departemen::all();
        $posisi = Posisi::all();

        return view('karyawan.form', compact('karyawan', 'departemen', 'posisi'));
    }

    public function destroy($id){
        $karyawan = Karyawan::find($id);
        if ($karyawan) {
            $karyawan->delete();
            return redirect()->route('data_karyawan')->with('success','Data karyawan berhasil dihapus');
        }
        return redirect()->route('data_karyawan')->with('error','Data karyawan tidak ditemukan');
    }
}
