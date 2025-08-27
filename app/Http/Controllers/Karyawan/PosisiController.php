<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posisi;

class PosisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posisis = Posisi::all();
        return view('karyawan.form', compact('posisis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_posisi' => 'required|string|max:50',
        ]);

        Posisi::create($request->all());

        return redirect()->route('input_karyawan')->with('success', 'Posisi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $posisi = Posisi::findOrFail($id);
        return view('karyawan.form', compact('posisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $posisi = Posisi::findOrFail($id);
        return view('karyawan.form', compact('posisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_posisi' => 'required|string|max:50',
        ]);

        $posisi = Posisi::findOrFail($id);
        $posisi->update($request->all());

        return redirect()->route('posisi.index')->with('success', 'Posisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $posisi = Posisi::findOrFail($id);
        $posisi->delete();

        return redirect()->route('posisi.index')->with('success', 'Posisi berhasil dihapus.');
    }
}
