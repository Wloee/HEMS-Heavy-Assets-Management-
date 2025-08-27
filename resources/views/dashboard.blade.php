@extends('layouts.app')
@section('content')
    <div class="p-6">

        <h1 class="text-2xl font-semibold mb-4">Dashboard</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-green-100 text-green-800 p-4 rounded shadow text-center">
                <p>Total Equipment</p>
                <h2 class="text-2xl font-bold">0</h2>
            </div>
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded shadow text-center">
                <p>Tersedia</p>
                <h2 class="text-2xl font-bold">0</h2>
            </div>
            <div class="bg-cyan-100 text-cyan-800 p-4 rounded shadow text-center">
                <p>Terpakai</p>
                <h2 class="text-2xl font-bold">0</h2>
            </div>
            <div class="bg-rose-100 text-rose-800 p-4 rounded shadow text-center">
                <p>Maintenance</p>
                <h2 class="text-2xl font-bold">0</h2>
            </div>
        </div>
        <div class="bg-white p-4 rounded shadow mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <input type="text" placeholder="Search by any field" class="w-full sm:w-64 border px-3 py-2 rounded" />
                <div class="flex items-center gap-1 text-sm">
                    <label>Filter :</label>
                    <select class="border px-2 py-1 rounded">
                        <option>ID</option>
                    </select>
                    <select class="border px-2 py-1 rounded">
                        <option>Type</option>
                    </select>
                    <select class="border px-2 py-1 rounded">
                        <option>Nama</option>
                    </select>
                    <select class="border px-2 py-1 rounded">
                        <option>Lokasi</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="w-full text-sm table-auto">
                <thead class="bg-gray-100">
                    <tr class="text-left">
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">ID & No. Alat</th>
                        <th class="px-4 py-2">Merk / Type / Jenis</th>
                        <th class="px-4 py-2">Nama Site & Proyek</th>
                        <th class="px-4 py-2">Alamat Lokasi</th>
@endsection
