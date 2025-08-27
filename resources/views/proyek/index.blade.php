@extends('layouts.app')

@section('title', 'Manajemen Proyek')

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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 32px 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }

    .page-header h2 {
        margin: 0;
        font-weight: 600;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

    .status-draft {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    .status-active {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    .status-completed {
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
    }
    .status-pending {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }
    .status-cancelled {
        background: linear-gradient(135deg, #dc3545, #e83e8c);
        color: white;
    }

    .invoice-paid {
        color: #28a745;
        font-weight: 600;
        background: #d4edda;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
    }
    .invoice-unpaid {
        color: #dc3545;
        font-weight: 600;
        background: #f8d7da;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
    }
    .invoice-partial {
        color: #fd7e14;
        font-weight: 600;
        background: #ffeaa7;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
    }
    .invoice-sent {
        color: #17a2b8;
        font-weight: 600;
        background: #d1ecf1;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
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
        max-height: 600px;
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

    .addendum-badge {
        background: linear-gradient(135deg, #6c757d, #495057);
        border: none;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .addendum-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .addendum-badge a {
        color: white !important;
        text-decoration: none !important;
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

    .project-name {
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }

    .project-location {
        font-size: 0.8rem;
        color: #6c757d;
        font-style: italic;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 12px 16px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-color: #667eea;
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
                        <i class="fas fa-project-diagram me-3"></i>
                        Manajemen Proyek
                    </h2>
                    <a href="{{ route('proyek.create') }}" class="btn btn-light mt-3 mt-md-0">
                        <i class="fas fa-plus me-2"></i>In  put Proyek
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

            <!-- Search & Filter -->
            <div class="search-container">
                <form id="searchForm" method="GET" action="{{ route('proyek.index') }}" class="row g-3">
                    <div class="col-md-6">
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
                                   placeholder="Cari nama proyek atau client..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="error-message" id="search-error"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="status_invoice" class="form-label fw-semibold">
                            <i class="fas fa-file-invoice me-1"></i>Status Invoice
                        </label>
                        <select id="status_invoice" name="status_invoice" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('status_invoice') == 'paid' ? 'selected' : '' }}>Lunas</option>
                            <option value="unpaid" {{ request('status_invoice') == 'unpaid' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="partial" {{ request('status_invoice') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                        </select>
                        <div class="error-message" id="status_invoice-error"></div>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('proyek.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-container">
                @if($proyek->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">#</th>
                                    <th scope="col" style="width: 20%">Proyek</th>
                                    <th scope="col" style="width: 15%">Client</th>
                                    <th scope="col" style="width: 12%">Tanggal Mulai</th>
                                    <th scope="col" style="width: 12%">Tanggal Selesai</th>
                                    <th scope="col" style="width: 10%">Status</th>
                                    <th scope="col" style="width: 10%">Status Invoice</th>
                                    <th scope="col" style="width: 12%">Total Biaya</th>
                                    <th scope="col" style="width: 10%">Surat Kontrak</th>
                                    <th scope="col" style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proyek as $index => $item)
                                    <tr>
                                        <td>{{ $proyek->firstItem() + $index }}</td>
                                        <td>
                                            <div class="project-name">{{ $item->nama_proyek }}</div>
                                            @if($item->lokasi_proyek)
                                                <div class="project-location">
                                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $item->lokasi_proyek }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $item->nama_client }}</strong>
                                        </td>
                                        <td>
                                            @if($item->tanggal_mulai)
                                                <span class="date-display text-primary">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tanggal_selesai_aktual)
                                                <span class="date-display text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai_aktual)->format('d/m/Y') }}
                                                </span>
                                            @elseif($item->tanggal_selesai)
                                                <span class="date-display text-warning">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($item->status ?? 'draft') {
                                                    'draft' => 'status-draft',
                                                    'aktif' => 'status-active',
                                                    'selesai' => 'status-completed',
                                                    'ditunda' => 'status-pending',
                                                    'dibatalkan' => 'status-cancelled',
                                                    default => 'status-draft'
                                                };
                                                $statusText = match($item->status ?? 'draft') {
                                                    'draft' => 'Draft',
                                                    'aktif' => 'Aktif',
                                                    'selesai' => 'Selesai',
                                                    'ditunda' => 'Ditunda',
                                                    'dibatalkan' => 'Dibatalkan',
                                                    default => 'Draft'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} status-badge">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $invoiceStatus = $item->status_invoice ?? 'unpaid';
                                                $iconClass = match($invoiceStatus) {
                                                    'terkirim' => 'invoice-sent',
                                                    'paid' => 'invoice-paid',
                                                    'partial' => 'invoice-partial',
                                                    default => 'invoice-unpaid'
                                                };
                                                $invoiceText = match($invoiceStatus) {
                                                    'terkirim' => 'Terkirim',
                                                    'paid' => 'Lunas',
                                                    'partial' => 'Sebagian',
                                                    default => 'Belum Dibayar'
                                                };
                                            @endphp
                                            <span class="{{ $iconClass }}">{{ $invoiceText }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="currency-amount">
                                                Rp {{ number_format($item->biaya_total ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(isset($item->addendum_proyek) && $item->addendum_proyek->count() > 0)
                                                <span class="badge addendum-badge">
                                                    <a href="{{ route('proyek.addendum', $item->id_proyek) }}">
                                                        <i class="fas fa-file-alt me-1"></i>{{ $item->addendum_proyek->count() }}
                                                    </a>
                                                </span>
                                            @else
                                                <span class="badge addendum-badge">
                                                    <a href="{{ route('proyek.addendum', $item->id_proyek) }}">
                                                        <i class="fas fa-plus me-1"></i>Tambah
                                                    </a>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ route('proyek.edit', $item->id_proyek) }}"
                                                   class="btn btn-sm btn-outline-primary btn-action"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('proyek.show', $item->id_proyek) }}"
                                                   class="btn btn-sm btn-outline-info btn-action"
                                                   title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('proyek.destroy', $item->id_proyek) }}"
                                                      method="POST"
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus proyek ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger btn-action"
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
                            Menampilkan {{ $proyek->firstItem() }} - {{ $proyek->lastItem() }}
                            dari {{ $proyek->total() }} data
                        </div>
                        {{ $proyek->withQueryString()->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h5 class="text-muted mb-3">Tidak ada data proyek</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status_invoice']))
                                Tidak ditemukan data yang sesuai dengan filter.
                                <br>
                                <a href="{{ route('proyek.index') }}" class="btn btn-outline-primary mt-3">
                                    <i class="fas fa-times me-1"></i>Reset Filter
                                </a>
                            @else
                                Belum ada proyek yang terdaftar.
                                <br>
                                <a href="{{ route('proyek.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-1"></i>Tambah Proyek Pertama
                                </a>
                            @endif
                        </p>
                    </div>
                @endif
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

        // Validasi status invoice
        validateStatusInvoice: function(value) {
            const errors = [];
            const allowedStatuses = ['', 'paid', 'unpaid', 'partial'];

            if (!allowedStatuses.includes(value)) {
                errors.push('Status invoice tidak valid');
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

        // Validasi status invoice
        const statusErrors = validators.validateStatusInvoice(formData.status_invoice || '');
        if (!showError('status_invoice', statusErrors)) isValid = false;

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
    // EVENT LISTENERS & HANDLERS
    // ============================

    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');

        // Real-time validation
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const errors = validators.validateSearch(e.target.value);
                showError('search', errors);
            });
        }

        const statusSelect = document.getElementById('status_invoice');
        if (statusSelect) {
            statusSelect.addEventListener('change', function(e) {
                const errors = validators.validateStatusInvoice(e.target.value);
                showError('status_invoice', errors);
            });
        }

        // Form submission dengan validasi
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const formData = {
                    search: document.getElementById('search').value,
                    status_invoice: document.getElementById('status_invoice').value
                };

                if (!validateForm(formData)) {
                    e.preventDefault();
                    showToast('Harap perbaiki kesalahan pada form', 'danger');
                }
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
    });
</script>
@endsection
