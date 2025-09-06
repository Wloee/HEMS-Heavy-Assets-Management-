@extends('layouts.app')

@section('title', 'Manajemen Data Maintenance')

@section('styles')
<style>
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 24px;
        margin: 24px 0;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .search-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .page-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 32px 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(23, 162, 184, 0.3);
    }

    .page-header h2 {
        margin: 0;
        font-weight: 600;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .statistics-container {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        padding: 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(108, 117, 125, 0.3);
    }

    .info-box {
        display: flex;
        align-items: center;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        margin-bottom: 16px;
    }

    .info-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        background: rgba(255, 255, 255, 0.15);
    }

    .info-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-right: 20px;
        color: white;
        font-size: 1.5rem;
    }

    .info-box-content {
        flex: 1;
    }

    .info-box-text {
        display: block;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 5px;
        font-weight: 500;
    }

    .info-box-number {
        display: block;
        font-size: 24px;
        font-weight: bold;
        color: white;
    }

    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .status-baik {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    .status-proses {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }
    .status-selesai {
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
    }

    .budget-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 12px;
        display: inline-block;
    }

    .budget-over {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    .budget-under {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    .budget-sesuai {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }

    .deviation-positive {
        color: #dc3545;
        font-weight: 600;
    }

    .deviation-negative {
        color: #28a745;
        font-weight: 600;
    }

    .deviation-normal {
        color: #17a2b8;
        font-weight: 600;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
        display: none;
        font-weight: 500;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .btn-action {
        margin-right: 8px;
        padding: 8px 12px;
        font-size: 0.875em;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .table-responsive {
        max-height: 700px;
        overflow-y: auto;
        border-radius: 8px;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background: linear-gradient(135deg, #495057, #343a40);
        color: white;
        border: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 16px 12px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }

    .table tbody td {
        padding: 16px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    .empty-state {
        padding: 80px 20px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 24px;
        opacity: 0.6;
    }

    .currency-amount {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: #495057;
    }

    .date-display {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .maintenance-info {
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }

    .maintenance-detail {
        font-size: 0.8rem;
        color: #6c757d;
        font-style: italic;
    }

    .btn-primary {
        background: linear-gradient(135deg, #17a2b8, #138496);
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(23, 162, 184, 0.4);
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 12px 16px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
    }

    .input-group-text {
        background: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 8px 0 0 8px;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        color: #495057;
        padding: 8px 16px;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #17a2b8, #138496);
        border-color: #17a2b8;
    }

    .duration-display {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 500;
    }

    .operator-info {
        font-size: 0.9rem;
        color: #495057;
    }

    @media (max-width: 768px) {
        .search-container {
            padding: 20px 16px;
        }

        .btn-action {
            margin-bottom: 8px;
            padding: 6px 8px;
        }

        .page-header {
            padding: 24px 16px;
            text-align: center;
        }

        .page-header h2 {
            font-size: 1.5rem;
        }

        .table-container {
            padding: 16px;
            margin: 16px 0;
        }

        .table thead th {
            font-size: 0.7rem;
            padding: 12px 8px;
        }

        .table tbody td {
            padding: 12px 8px;
            font-size: 0.9rem;
        }

        .info-box {
            padding: 16px;
        }

        .info-box-icon {
            width: 50px;
            height: 50px;
            margin-right: 15px;
        }

        .info-box-number {
            font-size: 18px;
        }
    }

    @media (max-width: 576px) {
        .d-flex.flex-wrap.gap-1 {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            margin-bottom: 4px;
            margin-right: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h2>
                        <i class="fas fa-tools me-3"></i>
                        Manajemen Data Maintenance - Analisis Biaya
                    </h2>
                    <a href="{{ route('biaya.index') }}" class="btn btn-light mt-3 mt-md-0">
                        <i class="fas fa-plus me-2"></i>Input Biaya Maintenance
                    </a>
                </div>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Container -->
            <div class="statistics-container">
                <h5 class="mb-3">
                    <i class="fas fa-chart-line me-2"></i>
                    Statistik Biaya Maintenance
                </h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-wrench"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Maintenance</span>
                                <span class="info-box-number">{{ number_format($costStatistics->total_maintenance) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Biaya Aktual</span>
                                <span class="info-box-number">Rp {{ number_format($costStatistics->total_biaya_aktual ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-calculator"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Rata-rata Biaya</span>
                                <span class="info-box-number">Rp {{ number_format($costStatistics->rata_rata_biaya_aktual ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Over Budget</span>
                                <span class="info-box-number">{{ $costStatistics->jumlah_over_budget }} Item</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="search-container">
                <form id="searchForm" method="GET" action="{{ route('data_biaya') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label fw-semibold">
                            <i class="fas fa-search me-1"></i>Pencarian
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                   id="search"
                                   name="search"
                                   class="form-control"
                                   placeholder="Cari diagnosa atau ID log..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="error-message" id="search-error"></div>
                    </div>

                    <div class="col-md-2">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-flag me-1"></i>Status
                        </label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="baik" {{ request('status') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <div class="error-message" id="status-error"></div>
                    </div>

                    <div class="col-md-2">
                        <label for="status_budget" class="form-label fw-semibold">
                            <i class="fas fa-chart-pie me-1"></i>Status Budget
                        </label>
                        <select id="status_budget" name="status_budget" class="form-select">
                            <option value="">Semua</option>
                            <option value="over" {{ request('status_budget') == 'over' ? 'selected' : '' }}>Over Budget</option>
                            <option value="under" {{ request('status_budget') == 'under' ? 'selected' : '' }}>Under Budget</option>
                            <option value="sesuai" {{ request('status_budget') == 'sesuai' ? 'selected' : '' }}>Sesuai Budget</option>
                        </select>
                        <div class="error-message" id="status_budget-error"></div>
                    </div>

                    <div class="col-md-2">
                        <label for="tanggal_mulai" class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt me-1"></i>Dari Tanggal
                        </label>
                        <input type="date"
                               id="tanggal_mulai"
                               name="tanggal_mulai"
                               class="form-control"
                               value="{{ request('tanggal_mulai') }}">
                        <div class="error-message" id="tanggal_mulai-error"></div>
                    </div>

                    <div class="col-md-2">
                        <label for="tanggal_selesai" class="form-label fw-semibold">
                            <i class="fas fa-calendar-check me-1"></i>Sampai Tanggal
                        </label>
                        <input type="date"
                               id="tanggal_selesai"
                               name="tanggal_selesai"
                               class="form-control"
                               value="{{ request('tanggal_selesai') }}">
                        <div class="error-message" id="tanggal_selesai-error"></div>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('data_biaya') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-container">
                @if($maintenanceLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">#</th>
                                    <th scope="col" style="width: 8%">ID Log</th>
                                    <th scope="col" style="width: 20%">Diagnosa</th>
                                    <th scope="col" style="width: 12%">Tanggal</th>
                                    <th scope="col" style="width: 8%">Status</th>
                                    <th scope="col" style="width: 12%">Biaya Estimasi</th>
                                    <th scope="col" style="width: 12%">Biaya Aktual</th>
                                    <th scope="col" style="width: 8%">Deviasi</th>
                                    <th scope="col" style="width: 8%">Status Budget</th>
                                    <th scope="col" style="width: 7%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($maintenanceLogs as $index => $log)
                                    <tr>
                                        <td>{{ $maintenanceLogs->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $log->id_Log }}</span>
                                        </td>
                                        <td>
                                            <div class="maintenance-info">
                                                {{ Str::limit($log->diagnosa, 60) }}
                                            </div>
                                            @if($log->operator_nama || $log->mekanik_nama)
                                                <div class="maintenance-detail">
                                                    @if($log->operator_nama)
                                                        Op: {{ $log->operator_nama }}
                                                    @endif
                                                    @if($log->mekanik_nama)
                                                        | Mek: {{ $log->mekanik_nama }}
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="date-display">
                                                <strong>Mulai:</strong> {{ \Carbon\Carbon::parse($log->Mulai_dikerjakan)->format('d/m/Y') }}
                                            </div>
                                            <div class="date-display">
                                                <strong>Selesai:</strong> {{ \Carbon\Carbon::parse($log->Selesai_dikerjakan)->format('d/m/Y') }}
                                            </div>
                                            @if($log->durasi_hari)
                                                <div class="duration-display">
                                                    {{ $log->durasi_hari }} hari
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($log->Status) {
                                                    'selesai' => 'status-selesai',
                                                    'proses' => 'status-proses',
                                                    'baik' => 'status-baik',
                                                    default => 'status-baik'
                                                };
                                                $statusText = match($log->Status) {
                                                    'selesai' => 'Selesai',
                                                    'proses' => 'Proses',
                                                    'baik' => 'Baik',
                                                    default => ucfirst($log->Status)
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} status-badge">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            @if($log->biaya_estimasi)
                                                <span class="currency-amount text-muted">
                                                    Rp {{ number_format($log->biaya_estimasi, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->biaya_aktual)
                                                <span class="currency-amount">
                                                    Rp {{ number_format($log->biaya_aktual, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->deviasi_persen !== null)
                                                @php
                                                    $deviation = floatval($log->deviasi_persen);
                                                    $deviationClass = '';
                                                    if($deviation > 5) $deviationClass = 'deviation-positive';
                                                    elseif($deviation < -5) $deviationClass = 'deviation-negative';
                                                    else $deviationClass = 'deviation-normal';
                                                @endphp
                                                <span class="{{ $deviationClass }}">
                                                    {{ $deviation > 0 ? '+' : '' }}{{ number_format($deviation, 2) }}%
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->status_budget == 'Over Budget')
                                                <span class="badge budget-over">Over</span>
                                            @elseif($log->status_budget == 'Under Budget')
                                                <span class="badge budget-under">Under</span>
                                            @elseif($log->status_budget == 'Sesuai Budget')
                                                <span class="badge budget-sesuai">Sesuai</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-info btn-action"
                                                        onclick="viewDetail({{ $log->id_Log }})"
                                                        title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($log->bukti)
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-success btn-action"
                                                            onclick="viewEvidence('{{ asset('storage/' . $log->bukti) }}')"
                                                            title="Bukti">
                                                        <i class="fas fa-file-image"></i>
                                                    </button>
                                                @endif
                                                <a href="{{ route('biaya_edit', $log->id_Log) }}"
                                                   class="btn btn-sm btn-outline-primary btn-action"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                        <div class="text-muted mb-2 mb-md-0">
                            Menampilkan {{ $maintenanceLogs->firstItem() }} - {{ $maintenanceLogs->lastItem() }}
                            dari {{ $maintenanceLogs->total() }} data
                        </div>
                        {{ $maintenanceLogs->withQueryString()->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-tools"></i>
                        <h5 class="text-muted mb-3">Tidak ada data maintenance</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status', 'status_budget', 'tanggal_mulai', 'tanggal_selesai']))
                                Tidak ditemukan data yang sesuai dengan filter.
                                <br>
                                <a href="{{ route('data_biaya') }}" class="btn btn-outline-primary mt-3">
                                    <i class="fas fa-times me-1"></i>Reset Filter
                                </a>
                            @else
                                Belum ada data maintenance yang tersedia.
                                <br>
                                <a href="{{ route('biaya.index') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-1"></i>Input Data Pertama
                                </a>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Maintenance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <div class="text-center">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Bukti -->
<div class="modal fade" id="evidenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="evidenceImage" src="" alt="Bukti Pembayaran" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none"
     style="background-color: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ============================
    // FUNGSI VALIDASI FRONTEND
    // ============================

    const validators = {
        // Validasi input search
        validateSearch: function(value) {
            const errors = [];

            if (value.length > 0) {
                if (value.length < 2) {
                    errors.push('Pencarian minimal 2 karakter');
                }

                if (value.length > 100) {
                    errors.push('Pencarian maksimal 100 karakter');
                }

                // Cek karakter khusus yang tidak diizinkan
                const invalidChars = /[<>'"&]/;
                if (invalidChars.test(value)) {
                    errors.push('Karakter <, >, \', ", & tidak diizinkan');
                }
            }

            return errors;
        },

        // Validasi status
        validateStatus: function(value) {
            const errors = [];
            const allowedStatuses = ['', 'baik', 'proses', 'selesai'];

            if (!allowedStatuses.includes(value)) {
                errors.push('Status tidak valid');
            }

            return errors;
        },

        // Validasi status budget
        validateStatusBudget: function(value) {
            const errors = [];
            const allowedStatuses = ['', 'over', 'under', 'sesuai'];

            if (!allowedStatuses.includes(value)) {
                errors.push('Status budget tidak valid');
            }

            return errors;
        },

        // Validasi tanggal
        validateDate: function(value) {
            const errors = [];

            if (value && !moment(value, 'YYYY-MM-DD', true).isValid()) {
                errors.push('Format tanggal tidak valid');
            }

            return errors;
        },

        // Validasi rentang tanggal
        validateDateRange: function(startDate, endDate) {
            const errors = [];

            if (startDate && endDate) {
                const start = moment(startDate);
                const end = moment(endDate);

                if (start.isAfter(end)) {
                    errors.push('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                }

                if (start.isAfter(moment())) {
                    errors.push('Tanggal mulai tidak boleh lebih besar dari hari ini');
                }
            }

            return errors;
        }
    };

    // ============================
    // FUNGSI HELPER VALIDASI
    // ============================

    function showError(fieldId, errors) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '-error');

        if (errors.length > 0) {
            field.classList.add('is-invalid');
            errorDiv.textContent = errors[0];
            errorDiv.style.display = 'block';
            return false;
        } else {
            field.classList.remove('is-invalid');
            errorDiv.style.display = 'none';
            return true;
        }
    }

    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });

        const invalidFields = document.querySelectorAll('.is-invalid');
        invalidFields.forEach(field => {
            field.classList.remove('is-invalid');
        });
    }

    function validateForm(formData) {
        let isValid = true;
        clearErrors();

        // Validasi search
        const searchErrors = validators.validateSearch(formData.search || '');
        if (!showError('search', searchErrors)) isValid = false;

        // Validasi status
        const statusErrors = validators.validateStatus(formData.status || '');
        if (!showError('status', statusErrors)) isValid = false;

        // Validasi status budget
        const statusBudgetErrors = validators.validateStatusBudget(formData.status_budget || '');
        if (!showError('status_budget', statusBudgetErrors)) isValid = false;

        // Validasi tanggal mulai
        const tanggalMulaiErrors = validators.validateDate(formData.tanggal_mulai || '');
        if (!showError('tanggal_mulai', tanggalMulaiErrors)) isValid = false;

        // Validasi tanggal selesai
        const tanggalSelesaiErrors = validators.validateDate(formData.tanggal_selesai || '');
        if (!showError('tanggal_selesai', tanggalSelesaiErrors)) isValid = false;

        // Validasi rentang tanggal
        if (formData.tanggal_mulai || formData.tanggal_selesai) {
            const dateRangeErrors = validators.validateDateRange(formData.tanggal_mulai, formData.tanggal_selesai);
            if (dateRangeErrors.length > 0) {
                showError('tanggal_selesai', dateRangeErrors);
                isValid = false;
            }
        }

        return isValid;
    }

    // ============================
    // FUNGSI UTILITY
    // ============================

    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('d-none');
    }

    function hideLoading() {
        document.getElementById('loadingOverlay').classList.add('d-none');
    }

    function showToast(message, type = 'info') {
        // Implementasi toast notification
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'danger' ? 'danger' : 'info'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        // Buat container toast jika belum ada
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toast = new bootstrap.Toast(toastContainer.lastElementChild);
        toast.show();
    }

    // ============================
    // FUNGSI MODAL DAN DETAIL
    // ============================

    function viewDetail(logId) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();

        // Simulate AJAX call - replace with actual endpoint
        setTimeout(() => {
            document.getElementById('modalContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h6>
                        <table class="table table-sm">
                            <tr><td><strong>ID Log:</strong></td><td>${logId}</td></tr>
                            <tr><td><strong>Status:</strong></td><td><span class="badge bg-primary">Loading...</span></td></tr>
                            <tr><td><strong>Operator:</strong></td><td>Loading...</td></tr>
                            <tr><td><strong>Mekanik:</strong></td><td>Loading...</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-money-bill me-2"></i>Informasi Biaya</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Biaya Estimasi:</strong></td><td>Loading...</td></tr>
                            <tr><td><strong>Biaya Aktual:</strong></td><td>Loading...</td></tr>
                            <tr><td><strong>Deviasi:</strong></td><td>Loading...</td></tr>
                            <tr><td><strong>Status Budget:</strong></td><td>Loading...</td></tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6><i class="fas fa-stethoscope me-2"></i>Diagnosa</h6>
                <p class="text-muted">Loading diagnosa...</p>
                <h6><i class="fas fa-calendar me-2"></i>Timeline</h6>
                <div class="timeline">
                    <small class="text-muted">Loading timeline...</small>
                </div>
            `;
        }, 500);
    }

    function viewEvidence(imageSrc) {
        document.getElementById('evidenceImage').src = imageSrc;
        const modal = new bootstrap.Modal(document.getElementById('evidenceModal'));
        modal.show();
    }

    function exportData(format = 'excel') {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('export', format);

        showLoading();
        showToast('Memproses export data...', 'info');

        // Create a temporary link to download
        const link = document.createElement('a');
        link.href = currentUrl.toString();
        link.download = `maintenance_data_${format}_${new Date().toISOString().split('T')[0]}.${format}`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        setTimeout(() => {
            hideLoading();
        }, 3000);
    }

    // ============================
    // EVENT LISTENERS & HANDLERS
    // ============================

    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');

        // Real-time validation
        const searchInput = document.getElementById('search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const errors = validators.validateSearch(e.target.value);
                    showError('search', errors);
                }, 300);
            });
        }

        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.addEventListener('change', function(e) {
                const errors = validators.validateStatus(e.target.value);
                showError('status', errors);
            });
        }

        const statusBudgetSelect = document.getElementById('status_budget');
        if (statusBudgetSelect) {
            statusBudgetSelect.addEventListener('change', function(e) {
                const errors = validators.validateStatusBudget(e.target.value);
                showError('status_budget', errors);
            });
        }

        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalSelesaiInput = document.getElementById('tanggal_selesai');

        if (tanggalMulaiInput) {
            tanggalMulaiInput.addEventListener('change', function(e) {
                const errors = validators.validateDate(e.target.value);
                showError('tanggal_mulai', errors);

                // Validate date range if both dates are filled
                if (tanggalSelesaiInput.value) {
                    const rangeErrors = validators.validateDateRange(e.target.value, tanggalSelesaiInput.value);
                    showError('tanggal_selesai', rangeErrors);
                }
            });
        }

        if (tanggalSelesaiInput) {
            tanggalSelesaiInput.addEventListener('change', function(e) {
                const errors = validators.validateDate(e.target.value);
                showError('tanggal_selesai', errors);

                // Validate date range if both dates are filled
                if (tanggalMulaiInput.value) {
                    const rangeErrors = validators.validateDateRange(tanggalMulaiInput.value, e.target.value);
                    if (rangeErrors.length > 0) {
                        showError('tanggal_selesai', rangeErrors);
                    }
                }
            });
        }

        // Form submission dengan validasi
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const formData = {
                    search: document.getElementById('search').value,
                    status: document.getElementById('status').value,
                    status_budget: document.getElementById('status_budget').value,
                    tanggal_mulai: document.getElementById('tanggal_mulai').value,
                    tanggal_selesai: document.getElementById('tanggal_selesai').value
                };

                if (!validateForm(formData)) {
                    e.preventDefault();
                    showToast('Harap perbaiki kesalahan pada form', 'danger');
                    return false;
                }

                showLoading();
            });
        }

        // Auto hide alerts setelah 5 detik
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert && alert.querySelector('.btn-close')) {
                    alert.querySelector('.btn-close').click();
                }
            }, 5000);
        });

        // Smooth scroll untuk form dengan error
        const errorFields = document.querySelectorAll('.is-invalid');
        if (errorFields.length > 0) {
            errorFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add click handlers for action buttons
        document.querySelectorAll('.btn-action').forEach(button => {
            button.addEventListener('click', function(e) {
                // Add subtle animation feedback
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Auto-refresh untuk data yang sedang dalam proses
        const processElements = document.querySelectorAll('.status-proses');
        if (processElements.length > 0) {
            // Uncomment if you want auto-refresh functionality
            // setInterval(function() {
            //     location.reload();
            // }, 300000); // Refresh every 5 minutes
        }
    });

    // ============================
    // FUNGSI TAMBAHAN
    // ============================

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + F untuk fokus ke search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('search').focus();
        }

        // Escape untuk clear search
        if (e.key === 'Escape') {
            const searchInput = document.getElementById('search');
            if (searchInput === document.activeElement) {
                searchInput.value = '';
                searchInput.blur();
            }
        }
    });

    // Print functionality
    function printData() {
        const printContent = document.querySelector('.table-container').innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Data Maintenance</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-size: 12px; }
                        .btn-action { display: none; }
                        @media print {
                            .btn-action { display: none !important; }
                        }
                    </style>
                </head>
                <body>
                    <h3>Data Maintenance - ${new Date().toLocaleDateString()}</h3>
                    ${printContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }

    // Global functions untuk onclick events
    window.viewDetail = viewDetail;
    window.viewEvidence = viewEvidence;
    window.exportData = exportData;
    window.printData = printData;
</script>
@endsection
