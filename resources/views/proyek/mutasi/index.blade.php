@extends('layouts.app')

@section('title', 'Manajemen Unit Proyek')

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
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 32px 24px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 16px rgba(40, 167, 69, 0.3);
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

    .status-aktif {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    .status-selesai {
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
    }
    .status-ditunda {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }

    .unit-badge {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .operator-badge {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 12px;
        display: inline-block;
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

    .unit-info {
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
    }

    .unit-type {
        font-size: 0.8rem;
        color: #6c757d;
        font-style: italic;
    }

    .btn-primary {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 12px 16px;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
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
        background: linear-gradient(135deg, #28a745, #20c997);
        border-color: #28a745;
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
                        <i class="fas fa-cogs me-3"></i>
                        Manajemen Mutasi Unit Proyek
                    </h2>
                    <a href="{{ route('Mutasi-Unit.create') }}" class="btn btn-light mt-3 mt-md-0">
                        <i class="fas fa-plus me-2"></i>Assign Unit ke Proyek
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
                <form id="searchForm" method="GET" action="{{ route('Mutasi-Unit.index') }}" class="row g-3">
                    <div class="col-md-4">
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
                                   placeholder="Cari nama unit atau proyek..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="error-message" id="search-error"></div>
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold">
                            <i class="fas fa-flag me-1"></i>Status
                        </label>
                        <select id="status" name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditunda" {{ request('status') == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                        </select>
                        <div class="error-message" id="status-error"></div>
                    </div>

                    <div class="col-md-3">
                        <label for="proyek_id" class="form-label fw-semibold">
                            <i class="fas fa-project-diagram me-1"></i>Proyek
                        </label>
                        <select id="proyek_id" name="proyek_id" class="form-select">
                            <option value="">Semua Proyek</option>
                            @if(isset($proyekList))
                                @foreach($proyekList as $proyek)
                                    <option value="{{ $proyek->id_proyek }}"
                                            {{ request('proyek_id') == $proyek->id_proyek ? 'selected' : '' }}>
                                        {{ $proyek->nama_proyek }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <div class="error-message" id="proyek_id-error"></div>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('Mutasi-Unit.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-container">
                @if($unitProyek->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%">#</th>
                                    <th scope="col" style="width: 15%">Unit</th>
                                    <th scope="col" style="width: 18%">Proyek</th>
                                    <th scope="col" style="width: 12%">Tanggal Mulai</th>
                                    <th scope="col" style="width: 12%">Tanggal Selesai</th>
                                    <th scope="col" style="width: 8%">Status</th>
                                    <th scope="col" style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unitProyek as $index => $item)
                                    <tr>
                                       <td>{{ $unitProyek->firstItem() + $index }}</td>
<td>
    <div class="unit-info">{{ $item->nama_unit ?? 'N/A' }}</div>
    @if(!empty($item->tipe_unit))
        <div class="unit-type">
            <i class="fas fa-tag me-1"></i>{{ $item->tipe_unit }}
        </div>
    @endif
    @if(!empty($item->no_polisi))
        <div class="unit-type">
            <i class="fas fa-car me-1"></i>{{ $item->no_polisi }}
        </div>
    @endif
</td>
<td>
    <div class="project-name">{{ $item->nama_proyek ?? 'N/A' }}</div>
    @if(!empty($item->lokasi_proyek))
        <div class="unit-type">
            <i class="fas fa-map-marker-alt me-1"></i>{{ $item->lokasi_proyek }}
        </div>
    @endif
</td>


                                        <td>
                                            <span class="date-display text-primary">
                                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($item->tanggal_selesai)
                                                <span class="date-display text-success">
                                                    <i class="fas fa-check-circle me-1"></i>
                                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                                </span>
                                                @php
                                                    $durasi = \Carbon\Carbon::parse($item->tanggal_mulai)
                                                                ->diffInDays(\Carbon\Carbon::parse($item->tanggal_selesai));
                                                @endphp
                                                <div class="duration-display">
                                                    {{ $durasi }} hari
                                                </div>
                                            @else
                                                @php
                                                    $durasi = \Carbon\Carbon::parse($item->tanggal_mulai)
                                                                ->diffInDays(\Carbon\Carbon::now());
                                                @endphp
                                                <span class="text-warning">
                                                    <i class="fas fa-clock me-1"></i>Berlangsung
                                                </span>
                                                <div class="duration-display">
                                                    {{ $durasi }} hari
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($item->status) {
                                                    'aktif' => 'status-aktif',
                                                    'selesai' => 'status-selesai',
                                                    'ditunda' => 'status-ditunda',
                                                    default => 'status-aktif'
                                                };
                                                $statusText = match($item->status) {
                                                    'aktif' => 'Aktif',
                                                    'selesai' => 'Selesai',
                                                    'ditunda' => 'Ditunda',
                                                    default => 'Aktif'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} status-badge">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ route('Mutasi-Unit.edit', $item->id_unit_proyek) }}"
                                                   class="btn btn-sm btn-outline-primary btn-action"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('Mutasi-Unit.show', $item->id_unit_proyek) }}"
                                                   class="btn btn-sm btn-outline-info btn-action"
                                                   title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('Mutasi-Unit.destroy', $item->id_unit_proyek) }}"
                                                      method="POST"
                                                      style="display: inline-block;"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus assignment unit ini?')">
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
                            Menampilkan {{ $unitProyek->firstItem() }} - {{ $unitProyek->lastItem() }}
                            dari {{ $unitProyek->total() }} data
                        </div>
                        {{ $unitProyek->withQueryString()->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-cogs"></i>
                        <h5 class="text-muted mb-3">Tidak ada data unit proyek</h5>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status', 'proyek_id']))
                                Tidak ditemukan data yang sesuai dengan filter.
                                <br>
                                <a href="{{ route('Mutasi-Unit.index') }}" class="btn btn-outline-primary mt-3">
                                    <i class="fas fa-times me-1"></i>Reset Filter
                                </a>
                            @else
                                Belum ada unit yang di-assign ke proyek.
                                <br>
                                <a href="{{ route('Mutasi-Unit.create') }}" class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-1"></i>Assign Unit Pertama
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

        // Validasi status
        validateStatus: function(value) {
            const errors = [];
            const allowedStatuses = ['', 'aktif', 'selesai', 'ditunda'];

            if (!allowedStatuses.includes(value)) {
                errors.push('Status tidak valid');
            }

            return errors;
        },

        // Validasi proyek ID
        validateProyekId: function(value) {
            const errors = [];

            if (value && (isNaN(value) || parseInt(value) <= 0)) {
                errors.push('ID proyek tidak valid');
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

        // Validasi proyek ID
        const proyekIdErrors = validators.validateProyekId(formData.proyek_id || '');
        if (!showError('proyek_id', proyekIdErrors)) isValid = false;

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

        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.addEventListener('change', function(e) {
                const errors = validators.validateStatus(e.target.value);
                showError('status', errors);
            });
        }

        const proyekSelect = document.getElementById('proyek_id');
        if (proyekSelect) {
            proyekSelect.addEventListener('change', function(e) {
                const errors = validators.validateProyekId(e.target.value);
                showError('proyek_id', errors);
            });
        }

        // Form submission dengan validasi
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const formData = {
                    search: document.getElementById('search').value,
                    status: document.getElementById('status').value,
                    proyek_id: document.getElementById('proyek_id').value
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

        // Tooltip initialization untuk button actions
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-refresh status untuk unit yang masih aktif
        if (document.querySelector('.status-aktif')) {
            setInterval(function() {
                updateActiveDurations();
            }, 60000); // Update setiap menit
        }
    });

    // ============================
    // FUNGSI TAMBAHAN
    // ============================

    function updateActiveDurations() {
        const activeRows = document.querySelectorAll('tr');
        activeRows.forEach(row => {
            const statusBadge = row.querySelector('.status-aktif');
            if (statusBadge) {
                const durationElement = row.querySelector('.duration-display');
                if (durationElement && durationElement.textContent.includes('hari')) {
                    // Update durasi secara real-time jika diperlukan
                    // Implementasi bisa disesuaikan dengan kebutuhan
                }
            }
        });
    }

    function exportData(format = 'excel') {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('export', format);

        showLoading();
        window.location.href = currentUrl.toString();

        setTimeout(() => {
            hideLoading();
        }, 3000);
    }

    // Fungsi untuk print data
    function printData() {
        window.print();
    }

    // // Fungsi untuk bulk action (jika diperlukan di masa depan)
    // function handleBulkAction(action) {
    //     const selectedItems = document.querySelectorAll('input[name="selected_items[]"]:checked');

    //     if (selectedItems.length === 0) {
    //         showToast('Pilih minimal satu item', 'warning');
    //         return;
    //     }

    //     if (confirm(`Apakah Anda yakin ingin ${action} ${selectedItems.length} item?`)) {
    //         const form = document.createElement('form');
    //         form.method = 'POST';
    //         form.action = `
    // { route(
    // 'Mutasi-Unit.bulk-action') }}`;

    //         // Add CSRF token
    //         const csrfInput = document.createElement('input');
    //         csrfInput.type = 'hidden';
    //         csrfInput.name = '_token';
    //         csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    //         form.appendChild(csrfInput);

    //         // Add action
    //         const actionInput = document.createElement('input');
    //         actionInput.type = 'hidden';
    //         actionInput.name = 'action';
    //         actionInput.value = action;
    //         form.appendChild(actionInput);

    //         // Add selected items
    //         selectedItems.forEach(item => {
    //             const hiddenInput = document.createElement('input');
    //             hiddenInput.type = 'hidden';
    //             hiddenInput.name = 'selected_items[]';
    //             hiddenInput.value = item.value;
    //             form.appendChild(hiddenInput);
    //         });

    //         document.body.appendChild(form);
    //         form.submit();
    //     }
    }
</script>
@endsection
