@extends('layouts.app')

@section('content')
<div class="content">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['total_keluhan'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Total Keluhan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #ffeaa7, #fdcb6e); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['keluhan_baru'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Keluhan Baru</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #74b9ff, #0984e3); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['keluhan_proses'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Dalam Proses</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #55a3ff, #003d82); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['keluhan_selesai'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Data Keluhan Unit</h2>

            <a href="{{ route('Maintanance.create') }}"
               style="padding:10px 16px; background:linear-gradient(135deg,#667eea,#764ba2);
                      color:#fff; font-weight:600; border-radius:10px; text-decoration:none;
                      box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:0.3s;">
                <i class="fas fa-plus"></i> Tambah Keluhan
            </a>
        </div>

        <!-- Filter Form -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('Maintanance.index') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari nomor keluhan atau deskripsi..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:300px;">

                    <select name="status" class="form-select" style="width:150px;">
                        <option value="" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="baru" {{ request('status') == 'baru' ? 'selected' : '' }}>Baru</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>

                    <input type="text" name="site" placeholder="Site..."
                           value="{{ request('site') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:120px;">

                    <select name="proyek_id" class="form-select" style="width:180px;">
                        <option value="" {{ !request('proyek_id') ? 'selected' : '' }}>Semua Proyek</option>
                        @foreach($proyeks as $proyek)
                            <option value="{{ $proyek->id_proyek }}" {{ request('proyek_id') == $proyek->id_proyek ? 'selected' : '' }}>
                                {{ $proyek->nama_proyek }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:150px;">

                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:150px;">

                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>

                    @if(request()->hasAny(['search', 'status', 'site', 'proyek_id', 'tanggal_dari', 'tanggal_sampai']))
                        <a href="{{ route('Maintanance.index') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Table -->
        <div style="overflow-x:auto;">
            <table id="keluhanTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.05);
                          min-width: 1400px;">
                <thead>
                    <tr style="background:#f5f7fa; color:#334155; text-transform:uppercase; font-size:12px; letter-spacing:0.5px;">
                        <th style="padding:15px 12px; text-align:center; width:50px;">No</th>
                        <th style="padding:15px 12px; width:120px;">No Keluhan</th>
                        <th style="padding:15px 12px; width:100px;">Site</th>
                        <th style="padding:15px 12px; width:100px;">Tanggal</th>
                        <th style="padding:15px 12px; width:150px;">Proyek</th>
                        <th style="padding:15px 12px; width:120px;">Unit</th>
                        <th style="padding:15px 12px; width:80px;">KM/HM</th>
                        <th style="padding:15px 12px; width:200px;">Deskripsi</th>
                        <th style="padding:15px 12px; width:100px;">Pelapor</th>
                        <th style="padding:15px 12px; width:100px; text-align:center;">Status</th>
                        <th style="padding:15px 12px; width:100px; text-align:center;">Maintenance</th>
                        <th style="padding:15px 12px; width:120px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($keluhan as $index => $item)
                        <tr style="border-bottom:1px solid #e2e8f0; transition:background 0.3s;"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:15px 12px; text-align:center; font-weight:500;">
                                {{ ($keluhan->currentPage() - 1) * $keluhan->perPage() + $index + 1 }}
                            </td>
                            <td style="padding:15px 12px; font-weight:600; color:#667eea;">
                                {{ $item->no_keluhan }}
                            </td>
                            <td style="padding:15px 12px; font-weight:500;">
                                {{ $item->site }}
                            </td>
                            <td style="padding:15px 12px;">
                                {{ \Carbon\Carbon::parse($item->tanggal_keluhan)->format('d/m/Y') }}
                            </td>
                            <td style="padding:15px 12px;">
                                {{ $item->nama_proyek ?? '-' }}
                            </td>
                            <td style="padding:15px 12px;">
                                <div style="font-weight: 500;">{{ $item->nama_unit ?? '-' }}</div>
                                <div style="font-size: 11px; color: #6b7280;">{{ $item->nomor_unit ?? '' }}</div>
                            </td>
                            <td style="padding:15px 12px; text-align:right; font-weight:500;">
                                {{ number_format($item->km_hm, 0, ',', '.') }}
                            </td>
                            <td style="padding:15px 12px;">
                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                     title="{{ $item->deskripsi }}">
                                    {{ $item->deskripsi }}
                                </div>
                            </td>
                            <td style="padding:15px 12px;">
                                {{ $item->created_by_name ?? '-' }}
                            </td>

                            @php
                                $statusColor = match($item->status) {
                                    'baru' => '#fbbf24',
                                    'proses' => '#3b82f6',
                                    'selesai' => '#10b981',
                                    default => '#6b7280'
                                };

                                $statusText = match($item->status) {
                                    'baru' => 'Baru',
                                    'proses' => 'Proses',
                                    'selesai' => 'Selesai',
                                    default => $item->status
                                };
                            @endphp

                            <td style="padding:15px 12px; text-align:center;">
                                <span style="padding:4px 8px; background:{{ $statusColor }}20; color:{{ $statusColor }};
                                             border-radius:12px; font-size:11px; font-weight:600; text-transform:uppercase;">
                                    {{ $statusText }}
                                </span>
                            </td>

                            @php
                                $maintenanceColor = match($item->maintenance_status) {
                                    'baik' => '#10b981',
                                    'proses' => '#3b82f6',
                                    'selesai' => '#059669',
                                    default => '#d1d5db'
                                };

                                $maintenanceText = $item->maintenance_status
                                    ? ucfirst($item->maintenance_status)
                                    : 'Belum';
                            @endphp

                            <td style="padding:15px 12px; text-align:center;">
                                <span style="padding:4px 8px; background:{{ $maintenanceColor }}20; color:{{ $maintenanceColor }};
                                             border-radius:12px; font-size:11px; font-weight:600; text-transform:uppercase;">
                                    {{ $maintenanceText }}
                                </span>
                            </td>

                            <td style="padding:15px 12px; text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    <a href="{{ route('Maintanance.show', $item->id_keluhan) }}"
                                       style="background:#3b82f6; padding:8px 10px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:32px; height:32px;"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($item->status !== 'selesai')
                                        <div class="dropdown" style="display: inline-block;">
                                            <button class="btn btn-warning btn-sm dropdown-toggle"
                                                    style="padding:8px 10px; border:none; border-radius:6px; min-width:32px; height:32px;"
                                                    data-bs-toggle="dropdown" aria-expanded="false" title="Update Status">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($item->status !== 'baru')
                                                    <li>
                                                        <form action="" method="POST" style="margin: 0;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="baru">
                                                            <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                                                Set ke Baru
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                @if($item->status !== 'proses')
                                                    <li>
                                                        <form action="" method="POST" style="margin: 0;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="proses">
                                                            <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                                                Set ke Proses
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                                <li>
                                                    <form action="" method="POST" style="margin: 0;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left;">
                                                            Set ke Selesai
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('Maintanance.destroy', $item->id_keluhan) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data keluhan ini?')"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="background:#ef4444; padding:8px 10px; border:none; border-radius:6px;
                                                       color:#fff; font-size:12px; cursor:pointer; transition:all 0.2s;
                                                       min-width:32px; height:32px; display:flex; align-items:center; justify-content:center;"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="text-align:center; padding:40px 20px; color:#6b7280; font-style:italic;">
                                <i class="fas fa-exclamation-triangle" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                Tidak ada data keluhan unit
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div style="color: #6b7280;">
                Menampilkan {{ $keluhan->firstItem() ?? 0 }} sampai {{ $keluhan->lastItem() ?? 0 }}
                dari {{ $keluhan->total() }} data
            </div>
            {{ $keluhan->appends(request()->query())->links() }}
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed"
         style="top: 20px; right: 20px; z-index: 1050; min-width: 300px;" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed"
         style="top: 20px; right: 20px; z-index: 1050; min-width: 300px;" role="alert">
        <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#keluhanTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0], orderable: false },
                { targets: [6], className: 'text-right' },
                { targets: [9, 10, 11], orderable: false, className: 'text-center' },
                { targets: [7], orderable: false } // Deskripsi tidak bisa diurutkan
            ],
            pageLength: 15,
            lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
            order: [[3, 'desc']] // Order by tanggal_keluhan descending
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection
