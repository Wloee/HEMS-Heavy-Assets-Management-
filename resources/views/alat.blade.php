<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'HEMS') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/laporan.css') }}" rel="stylesheet">
    <style>
        .navbar-nav .nav-item .nav-link {
            font-weight: 500;
            color: #333;
        }
        .navbar-nav .dropdown-menu {
            font-size: 14px;
        }
    </style>
<body>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow px-6 py-3 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="text-blue-600 font-bold text-xl">🌐 HEMS</div>
            <ul class="hidden md:flex gap-4 text-sm text-gray-700">
                <a href="{{ url('/dashboard') }}">Home</a>
                <a href="{{ url('/unit') }}">Unit</a>
                <a href="{{ url('/alat') }}">Operasional</a>
                <a href="{{ url('/maintenance') }}">Maintenance</a>
                <a href="{{ url('/biaya/index') }}">Biaya</a>
                <a href="{{ url('/proyek/input') }}">Proyek</a>
                <a href="{{ url('/sparepart/input') }}">Sparepart</a>
                <a href="{{ url('/karyawan/form') }}">Karyawan</a>
                <a href="{{ url('/kas/form') }}">Kas</a>
                <li>Laporan</li>
                <li>Setting</li>
                <li>Tools</li>
                <li>Bantuan</li>
            </ul>
        </div> -->
         <div class="flex items-center gap-2 text-sm">
            <i class="fas fa-user-circle text-xl text-blue-600"></i>
            <span>John Wick</span>
        </div>
    </nav>
        <div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Laporan Harian Pemakaian Alat</h4>
            <form>
                <div class="mb-3">
                    <label class="form-label">Tanggal<span class="text-danger">*</span></label>
                    <input type="date" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Operator / Driver<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="ID - Nama Lengkap">
                </div>

                <div class="mb-3">
                    <label class="form-label">ID Alat<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="123454678">
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Jam Mulai<span class="text-danger">*</span></label>
                        <input type="time" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jam Selesai<span class="text-danger">*</span></label>
                        <input type="time" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Jam<span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-danger" placeholder="00:00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hitung Bayar<span class="text-danger">*</span></label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="hitungBayar" id="hitung" checked>
                            <label class="form-check-label" for="hitung">Hitung</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="hitungBayar" id="tidak">
                            <label class="form-check-label" for="tidak">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    <div class="col-md-3">
                        <label class="form-label">Kondisi<span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Pilih Kondisi</option>
                            <option>Bagus</option>
                            <option>Rusak Ringan</option>
                            <option>Rusak Berat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Baru?<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder=".....">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Biaya<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="Rp 0000000">
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary">Kembali</button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-warning text-white">Edit</button>
                        <button type="button" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-success">Service</button>
                        <button type="button" class="btn btn-primary">Cetak</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</html>