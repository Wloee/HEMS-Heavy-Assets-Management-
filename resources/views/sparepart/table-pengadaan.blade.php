@extends('layouts.app')

@section('content')
<div class="content">
    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Data Pengadaan Sparepart</h2>
            <a href="{{ route('pengadaan-sparepart.create') }}"
               style="padding:12px 20px; background:linear-gradient(135deg,#667eea,#764ba2);
                      color:#fff; font-weight:600; border-radius:10px; text-decoration:none;
                      box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:0.3s;">
                <i class="fas fa-plus"></i> Tambah Pengadaan
            </a>
        </div>
        <!-- Statistik Cards -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:20px; margin-bottom:20px;">
            <div style="background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                <h6 style="color:#6b7280; font-size:14px; margin-bottom:8px;">Total Pengadaan</h6>
                <h3 style="color:#1e293b; font-weight:700;">{{ $totalPengadaan }}</h3>
            </div>
            <div style="background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                <h6 style="color:#6b7280; font-size:14px; margin-bottom:8px;">Bulan Ini</h6>
                <h3 style="color:#10b981; font-weight:700;">{{ $pengadaanBulanIni }}</h3>
            </div>
            <div style="background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                <h6 style="color:#6b7280; font-size:14px; margin-bottom:8px;">Total Nilai</h6>
                <h3 style="color:#3b82f6; font-weight:700;">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h3>
            </div>
            <div style="background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                <h6 style="color:#6b7280; font-size:14px; margin-bottom:8px;">Supplier Aktif</h6>
                <h3 style="color:#f59e0b; font-weight:700;">{{ $supplierAktif }}</h3>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('pengadaan-sparepart.index') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari berdasarkan supplier atau kode..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:300px;">

                    <select name="supplier" style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id_supplier }}" {{ request('supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">

                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">

                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>

                    @if(request('search') || request('supplier') || request('tanggal_dari') || request('tanggal_sampai'))
                        <a href="{{ route('pengadaan-sparepart.index') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Error Message -->
        @if(isset($error))
            <div style="background:#fef2f2; color:#b91c1c; padding:15px; border-radius:8px; margin-bottom:20px;">
                {{ $error }}
            </div>
        @endif

        <!-- Table -->
        <div style="overflow-x:auto;">
            <table id="pengadaanTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.08);
                          font-size:14px;">
                <thead>
                    <tr style="background:#f8fafc; color:#374151; text-transform:uppercase; font-size:12px; letter-spacing:0.5px; font-weight:600;">
                        <th style="padding:18px 15px; text-align:center; width:60px;">No</th>
                        <th style="padding:18px 15px; width:120px;">ID Pengadaan</th>
                        <th style="padding:18px 15px; width:200px;">Supplier</th>
                        <th style="padding:18px 15px; width:150px;">Tanggal</th>
                        <th style="padding:18px 15px; width:100px;">Total Item</th>
                        <th style="padding:18px 15px; width:150px;">Total Harga</th>
                        <th style="padding:18px 15px; width:130px;">Status</th>
                        <th style="padding:18px 15px; width:150px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengadaanList as $index => $pengadaan)
                        <tr style="border-bottom:1px solid #e5e7eb; transition:background 0.3s;"
                            onmouseover="this.style.background='#f9fafb'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:18px 15px; text-align:center; font-weight:600;">{{ $pengadaanList->firstItem() + $index }}</td>
                            <td style="padding:18px 15px; font-weight:600; color:#3b82f6;">PGD-{{ str_pad($pengadaan->id_pembelian, 4, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding:18px 15px; color:#1e293b; font-weight:500;">{{ $pengadaan->nama_supplier ?? '-' }}</td>
                            <td style="padding:18px 15px; color:#6b7280;">{{ \Carbon\Carbon::parse($pengadaan->tanggal_pembelian)->format('d F Y') }}</td>
                            <td style="padding:18px 15px; color:#6b7280; text-align:center;">
                                <span style="background:#e0e7ff; color:#3730a3; padding:4px 8px; border-radius:12px; font-size:12px; font-weight:600;">
                                    {{ $pengadaan->details_count ?? 0 }} item
                                </span>
                            </td>
                            <td style="padding:18px 15px; color:#059669; font-weight:600;">Rp {{ number_format($pengadaan->total_harga, 0, ',', '.') }}</td>
                            <td style="padding:18px 15px;">
                                @php
                                    if ($pengadaan->Status == 'completed') {
                                        $statusClass = ['bg' => '#10b981', 'text' => '#fff', 'label' => 'Completed'];
                                    } elseif ($pengadaan->Status == 'pending') {
                                        $statusClass = ['bg' => '#f59e0b', 'text' => '#fff', 'label' => 'pending'];
                                    } else {
                                        $statusClass = ['bg' => '#6b7280', 'text' => '#fff', 'label' => 'canceled'];
                                    }
                                @endphp
                                <span style="background:{{ $statusClass['bg'] }}; color:{{ $statusClass['text'] }};
                                           padding:4px 12px; border-radius:20px; font-size:10px; font-weight:600; text-transform:uppercase;">
                                    {{ $statusClass['label'] }}
                                </span>
                            </td>
                            <td style="padding:18px 15px; text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    <a href="{{ route('pengadaan-sparepart.show', $pengadaan->id_pembelian) }}"
                                       style="background:#3b82f6; padding:8px 12px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:36px; height:36px;"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('pengadaan-sparepart.edit', $pengadaan->id_pembelian) }}"
                                       style="background:#fbbf24; padding:8px 12px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:36px; height:36px;"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href=""
                                       style="background:#10b981; padding:8px 12px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:36px; height:36px;"
                                       title="Print"
                                       target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <form action="{{ route('pengadaan-sparepart.destroy', $pengadaan->id_pembelian) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengadaan ini? Semua detail pengadaan akan ikut terhapus.')"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="background:#ef4444; padding:8px 12px; border:none; border-radius:6px;
                                                       color:#fff; font-size:12px; cursor:pointer; transition:all 0.2s;
                                                       min-width:36px; height:36px; display:flex; align-items:center; justify-content:center;"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:50px 20px; color:#6b7280; font-style:italic;">
                                <i class="fas fa-boxes" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                <span style="font-size:16px;">Tidak ada data pengadaan sparepart</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Laravel Pagination -->
        <div style="margin-top:20px;">
            {{ $pengadaanList->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#pengadaanTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0, 7], orderable: false },
                { targets: [4, 7], className: 'text-center' }
            ],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[3, 'desc']], // Order by date descending
            searching: false, // Disable DataTables search to use server-side search
            serverSide: false,
            paging: false // Disable DataTables paging to use Laravel pagination
        });
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '1055';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';

        const toastHTML = `
            <div id="${toastId}" class="toast ${bgClass} text-white" role="alert">
                <div class="toast-header ${bgClass} text-white border-0">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    <strong class="me-auto">${type === 'success' ? 'Berhasil' : 'Error'}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Show success or error messages
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error') || isset($error))
        showToast('{{ session('error') ?? $error }}', 'error');
    @endif
</script>
@endsection
