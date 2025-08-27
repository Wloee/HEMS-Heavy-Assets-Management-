<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departemen;

class DepartemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departemens = Departemen::all();
        return view('karyawan.form', compact('departemens'));
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
            'nama_departemen' => 'required|string|max:50',
        ]);

        Departemen::create($request->all());

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $departemen = Departemen::findOrFail($id);
        return view('karyawan.form', compact('departemen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $departemen = Departemen::findOrFail($id);
        return view('karyawan.form', compact('departemen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_departemen' => 'required|string|max:50',
        ]);

        $departemen = Departemen::findOrFail($id);
        $departemen->update($request->all());

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $departemen = Departemen::findOrFail($id);
        $departemen->delete();

        return redirect()->route('departemen.index')->with('success', 'Departemen berhasil dihapus.');
    }
}
