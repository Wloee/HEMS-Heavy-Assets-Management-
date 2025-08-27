<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEMS - Maintenance</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        nav a {
            text-decoration: none;
            color: #000;
            margin-right: 15px;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
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
    <div class="container">
        <!-- Input Biaya -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Input Biaya Service / Maintenance</h5>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Item<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Actual (cost)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Plan (cost)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">% Deviasi<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Upload Bukti Pembayaran<span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control">
                                <span class="input-group-text"><i class="bi bi-upload"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="button" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Input Kategori -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">Input Kategori Service / Maintenance</h5>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelompok<span class="text-danger">*</span></label>
                        <select class="form-select">
                            <option selected disabled>Pilih Kelompok</option>
                            <option>Kelompok A</option>
                            <option>Kelompok B</option>
                            <option>Kelompok C</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lainnya<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder=".....">
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (opsional jika kamu mau tambah interaktivitas) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
