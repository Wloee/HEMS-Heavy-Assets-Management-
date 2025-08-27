<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .action-icons i {
            margin: 0 5px;
            cursor: pointer;
        }
        .action-icons i:hover {
            color: #0d6efd;
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
    <div class="container py-5">
        <h4 class="mb-4">Unit Baru</h4>

        <div class="mb-3 d-flex gap-2">
            <input type="text" class="form-control" placeholder="Search by any field">
            <select class="form-select w-auto">
                <option selected>ID</option>
            </select>
            <select class="form-select w-auto">
                <option selected>Type</option>
            </select>
            <input type="text" class="form-control" placeholder="Nama">
            <input type="text" class="form-control" placeholder="Lokasi">
        </div>

        <div class="table-container">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>ID & No. Alat</th>
                        <th>Merk / Type / Jenis</th>
                        <th>Nama Site & Proyek</th>
                        <th>Alamat Lokasi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="action-icons">
                            <i class="text-success">🔍</i>
                            <i class="text-primary">📄</i>
                            <i class="text-warning">✏️</i>
                            <i class="text-danger">🗑️</i>
                        </td>
                    </tr>
                    <!-- Tambahkan baris lainnya sesuai kebutuhan -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
