@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Transaksi Kas</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('kas.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="jenis_transaksi">Jenis Transaksi</label>
            <input type="text" name="jenis_transaksi" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="id_metode">Metode Pembayaran</label>
            <div class="d-flex">
                <select name="id_metode" class="form-control me-2">
                    <option value="">-- Pilih Metode --</option>
                    @foreach($metodes as $metode)
                        <option value="{{ $metode->id_metode }}">{{ $metode->nama_metode }}</option>
                    @endforeach
                </select>
                <!-- Tombol tambah metode -->
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalMetode">
                    + Tambah
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <textarea name="keterangan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>
</div>

<!-- Modal Tambah Metode -->
<div class="modal fade" id="modalMetode" tabindex="-1" aria-labelledby="modalMetodeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('metode.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalMetodeLabel">Tambah Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="nama_metode">Nama Metode</label>
                    <input type="text" name="nama_metode" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi (opsional)</label>
                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
