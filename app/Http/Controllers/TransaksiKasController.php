<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiKasController extends Controller
{
    public function storeMetode(Request $request)
{
    $validated = $request->validate([
        'nama_metode' => 'required|string|max:100',
        'deskripsi'   => 'nullable|string',
    ]);

    DB::table('metode_pembayaran')->insert([
        'nama_metode' => $validated['nama_metode'],
        'deskripsi'   => $validated['deskripsi'] ?? null,
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    return redirect()->back()->with('success', 'Metode pembayaran berhasil ditambahkan!');
}
     public function create()
    {
        // ambil metode pembayaran via query builder
        $metodes = DB::table('metode_pembayaran')->get();

        return view('kas.form', compact('metodes'));
    }

    /**
     * Simpan data transaksi kas baru
     */
    public function store(Request $request)
    {
        // validasi data input
        $validated = $request->validate([
            'id_metode'        => 'nullable|exists:metode_pembayaran,id_metode',
            'jenis_transaksi'  => 'required|string|max:255',
            'tanggal'          => 'required|date',
            'jumlah'           => 'required|numeric|min:0',
            'keterangan'       => 'nullable|string',
        ]);

        // simpan ke database pakai query builder
        DB::table('transaksi_kas')->insert([
            'id_metode'       => $validated['id_metode'] ?? null,
            'jenis_transaksi' => $validated['jenis_transaksi'],
            'tanggal'         => $validated['tanggal'],
            'jumlah'          => $validated['jumlah'],
            'keterangan'      => $validated['keterangan'] ?? null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Transaksi kas berhasil ditambahkan');
    }
}
