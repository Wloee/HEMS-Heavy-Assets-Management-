@extends('layouts.app')

@section('content')
<div class="content">
    <div class="demo-card">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Data Log Operasional</h2>
            <a href="{{ route('log-operasional.create') }}"
               style="padding:10px 16px; background:linear-gradient(135deg,#667eea,#764ba2);
                      color:#fff; font-weight:600; border-radius:10px; text-decoration:none;
                      box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:0.3s;">
                <i class="fas fa-plus"></i> Tambah Log Operasional
            </a>
        </div>

        <!-- Search and Filter Section -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('log-operasional.index') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari unit, operator, atau jenis pekerjaan..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:300px;">

                    <select name="unit_proyek_id" class="form-select" style="width:150px;">
                        <option value="" {{ !request('unit_proyek_id') ? 'selected' : '' }}>Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id_unit_proyek }}" {{ request('unit_proyek_id') == $unit->id_unit_proyek ? 'selected' : '' }}>
                                {{ $unit->kode_unit }} - {{ $unit->nama_unit }}
                            </option>
                        @endforeach
                    </select>

                    <select name="operator_id" class="form-select" style="width:150px;">
                        <option value="" {{ !request('operator_id') ? 'selected' : '' }}>Semua Operator</option>
                        @foreach($operators as $operator)
                            <option value="{{ $operator->id_karyawan }}" {{ request('operator_id') == $operator->id_karyawan ? 'selected' : '' }}>
                                {{ $operator->nama_karyawan }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="tanggal_dari" class="form-control" style="width:150px;"
                           value="{{ request('tanggal_dari') }}" placeholder="Tanggal Dari">

                    <input type="date" name="tanggal_sampai" class="form-control" style="width:150px;"
                           value="{{ request('tanggal_sampai') }}" placeholder="Tanggal Sampai">

                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>

                    @if(request('search') || request('unit_proyek_id') || request('operator_id') || request('tanggal_dari') || request('tanggal_sampai'))
                        <a href="{{ route('log-operasional.index') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table -->
        <div style="overflow-x:auto;">
            <table id="logTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.05);
                          min-width: 1400px;">
                <thead>
                    <tr style="background:#f5f7fa; color:#334155; text-transform:uppercase; font-size:12px; letter-spacing:0.5px;">
                        <th style="padding:15px 12px; text-align:center; width:50px;">No</th>
                        <th style="padding:15px 12px; width:100px;">Tanggal</th>
                        <th style="padding:15px 12px; width:120px;">Unit</th>
                        <th style="padding:15px 12px; width:120px;">Operator</th>
                        <th style="padding:15px 12px; width:80px;">Jam Mulai</th>
                        <th style="padding:15px 12px; width:80px;">Jam Selesai</th>
                        <th style="padding:15px 12px; width:80px; text-align:center;">Jam Operasi</th>
                        <th style="padding:15px 12px; width:150px;">Jenis Pekerjaan</th>
                        <th style="padding:15px 12px; width:120px;">Lokasi Kerja</th>
                        <th style="padding:15px 12px; width:120px; text-align:right;">Biaya Operasional</th>
                        <th style="padding:15px 12px; width:200px;">Keterangan</th>
                        <th style="padding:15px 12px; width:120px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logOperasional as $index => $item)
                        <tr style="border-bottom:1px solid #e2e8f0; transition:background 0.3s;"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:15px 12px; text-align:center; font-weight:500;">
                                {{ ($logOperasional->currentPage() - 1) * $logOperasional->perPage() + $index + 1 }}
                            </td>
                            <td style="padding:15px 12px; font-weight:500;">
                                {{ \Carbon\Carbon::parse($item->tanggal_operasi)->format('d/m/Y') }}
                            </td>
                            <td style="padding:15px 12px;">
                                @if($item->kode_unit)
                                    <div style="font-weight:500;">{{ $item->kode_unit }}</div>
                                    <small style="color:#6b7280;">{{ $item->nama_unit }}</small>
                                @else
                                    <span style="color:#ef4444;">Unit Tidak Ditemukan</span>
                                @endif
                            </td>
                            <td style="padding:15px 12px;">
                                @if($item->nama_karyawan)
                                    <div style="font-weight:500;">{{ $item->nama_karyawan }}</div>
                                @else
                                    <span style="color:#6b7280;">-</span>
                                @endif
                            </td>
                            <td style="padding:15px 12px; text-align:center;">
                                {{ $item->jam_mulai ? \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') : '-' }}
                            </td>
                            <td style="padding:15px 12px; text-align:center;">
                                {{ $item->jam_selesai ? \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') : '-' }}
                            </td>
                            <td style="padding:15px 12px; text-align:center; font-weight:500; color:#059669;">
                                {{ $item->jam_operasi ? number_format($item->jam_operasi, 2, ',', '.') : '0,00' }} jam
                            </td>
                            <td style="padding:15px 12px;">{{ $item->jenis_pekerjaan ?? '-' }}</td>
                            <td style="padding:15px 12px;">{{ $item->lokasi_kerja ?? '-' }}</td>
                            <td style="padding:15px 12px; text-align:right; font-weight:500; color:#059669;">
                                @if($item->biaya_operasional)
                                    Rp {{ number_format($item->biaya_operasional, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding:15px 12px;">
                                @if($item->keterangan)
                                    <div style="max-width:200px; word-wrap:break-word;">
                                        {{ Str::limit($item->keterangan, 50) }}
                                        @if(strlen($item->keterangan) > 50)
                                            <a href="#" onclick="showFullDescription('{{ $item->id_log }}')"
                                               style="color:#667eea; text-decoration:none;">
                                                ...selengkapnya
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="padding:15px 12px; text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    <a href="{{ route('log-operasional.show', $item->id_log) }}"
                                       style="background:#3b82f6; padding:8px 10px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:32px; height:32px;"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('log-operasional.edit', $item->id_log) }}"
                                       style="background:#fbbf24; padding:8px 10px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:32px; height:32px;"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('log-operasional.destroy', $item->id_log) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data log operasional ini?')"
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
                                <i class="fas fa-clipboard-list" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                @if(request('search') || request('unit_proyek_id') || request('operator_id') || request('tanggal_dari') || request('tanggal_sampai'))
                                    Tidak ada data log operasional yang sesuai dengan filter pencarian
                                @else
                                    Tidak ada data log operasional
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($logOperasional->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div style="color:#6b7280; font-size:14px;">
                    Menampilkan {{ $logOperasional->firstItem() }} sampai {{ $logOperasional->lastItem() }}
                    dari {{ $logOperasional->total() }} data
                </div>
                <div>
                    {{ $logOperasional->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal for Full Description -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Keterangan Lengkap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="fullDescription"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#logTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0], orderable: false },
                { targets: [6, 9], className: 'text-right' },
                { targets: [4, 5, 6], className: 'text-center' },
                { targets: [11], orderable: false, className: 'text-center' }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'desc']], // Order by tanggal_operasi descending
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Export Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] // Exclude action column
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> Export PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10] // Exclude action column
                    }
                }
            ]
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });

    // Function to calculate jam_operasi automatically
    function calculateJamOperasi(jamMulai, jamSelesai) {
        if (jamMulai && jamSelesai) {
            const mulai = new Date(`2000-01-01 ${jamMulai}`);
            const selesai = new Date(`2000-01-01 ${jamSelesai}`);

            let diff = (selesai - mulai) / (1000 * 60 * 60); // Convert to hours

            // Handle overnight work (crossing midnight)
            if (diff < 0) {
                diff += 24;
            }

            return diff.toFixed(2);
        }
        return 0;
    }

    // Export functions
    function exportToExcel() {
        window.location.href = '{{ route("export_LogOperasional") }}?' + new URLSearchParams(window.location.search);
    }

    function exportToPDF() {
        window.location.href = '{{ route("export_LogOperasionalPDF") }}?' + new URLSearchParams(window.location.search);
    }
</script>

<style>
    .demo-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }

    .form-select, .form-control {
        padding: 10px 15px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
    }

    .content {
        padding: 20px;
        max-width: 100%;
        font-family: 'Inter', sans-serif;
    }

    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: 1px solid #e2e8f0;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-color: #667eea;
    }

    /* DataTables Button Styling */
    .dt-buttons {
        margin-bottom: 15px;
    }

    .dt-buttons .btn {
        margin-right: 5px;
        border-radius: 8px;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 24px;
    }

    .modal-body {
        padding: 24px;
        line-height: 1.6;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content {
            padding: 10px;
        }

        .demo-card {
            padding: 16px;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 15px;
        }

        form[style*="display:flex"] {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        form[style*="display:flex"] > * {
            width: 100% !important;
            margin-bottom: 10px;
        }

        table {
            font-size: 12px;
        }

        th, td {
            padding: 8px 6px !important;
        }
    }
</style>
@endsection
