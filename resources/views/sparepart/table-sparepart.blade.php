@extends('layouts.app')

@section('content')
<div class="content">
    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Data Sparepart</h2>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('sparepart.index') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari kode atau nama sparepart..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:300px;">
                    <select name="supplier_id" style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                        <option value="">Semua Supplier</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id_supplier }}" {{ request('supplier_id') == $supplier->id_supplier ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px;">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if(request()->hasAny(['search', 'supplier_id', 'status']))
                        <a href="{{ route('sparepart.index') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div style="margin-bottom: 20px;">
            <form id="bulkForm" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            </form>
        </div>

        <div style="overflow-x:auto;">
            <table id="sparepartTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.08);
                          font-size:13px;">
                <thead>
                    <tr style="background:#f8fafc; color:#374151; text-transform:uppercase; font-size:11px; letter-spacing:0.5px; font-weight:600;">
                        <th style="padding:15px 10px; text-align:center; width:50px;">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th style="padding:15px 10px; text-align:center; width:50px;">No</th>
                        <th style="padding:15px 10px; width:120px;">Kode</th>
                        <th style="padding:15px 10px; width:200px;">Nama Sparepart</th>
                        <th style="padding:15px 10px; width:120px;">Merk</th>
                        <th style="padding:15px 10px; width:150px;">Supplier</th>
                        <th style="padding:15px 10px; width:80px; text-align:center;">Stok Min</th>
                        <th style="padding:15px 10px; width:80px; text-align:center;">Stok Saat Ini</th>
                        <th style="padding:15px 10px; width:80px; text-align:center;">Status</th>
                        <th style="padding:15px 10px; width:150px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($spareparts as $index => $sparepart)
                        <tr style="border-bottom:1px solid #e5e7eb; transition:background 0.3s;"
                            onmouseover="this.style.background='#f9fafb'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:15px 10px; text-align:center;">
                                <input type="checkbox" name="sparepart_ids[]" value="{{ $sparepart->id_sparepart }}"
                                       class="sparepart-checkbox" onchange="updateBulkActions()">
                            </td>
                            <td style="padding:15px 10px; text-align:center; font-weight:600;">
                                {{ ($spareparts->currentPage() - 1) * $spareparts->perPage() + $index + 1 }}
                            </td>
                            <td style="padding:15px 10px; font-weight:600; color:#667eea;">
                                {{ $sparepart->kode_sparepart ?: 'Auto Generate' }}
                            </td>
                            <td style="padding:15px 10px; font-weight:600;">{{ $sparepart->nama_sparepart }}</td>
                            <td style="padding:15px 10px; color:#6b7280;">{{ $sparepart->merk ?: '-' }}</td>
                            <td style="padding:15px 10px; color:#6b7280;">
                                {{ $sparepart->nama_supplier ?: '-' }}
                            </td>
                            <td style="padding:15px 10px; text-align:center; color:#6b7280;">{{ $sparepart->stok_minimum }}</td>
                            <td style="padding:15px 10px; text-align:center;">
                                @if($sparepart->stok_saat_ini <= $sparepart->stok_minimum)
                                    <span style="background:#ef4444; color:#fff; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                        {{ $sparepart->stok_saat_ini }}
                                    </span>
                                @elseif($sparepart->stok_saat_ini <= ($sparepart->stok_minimum * 1.5))
                                    <span style="background:#f59e0b; color:#fff; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                        {{ $sparepart->stok_saat_ini }}
                                    </span>
                                @else
                                    <span style="background:#10b981; color:#fff; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                        {{ $sparepart->stok_saat_ini }}
                                    </span>
                                @endif
                            </td>
                            <td style="padding:15px 10px; text-align:center;">
                                @if($sparepart->is_active)
                                    <span style="background:#10b981; color:#fff; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                        Aktif
                                    </span>
                                @else
                                    <span style="background:#6b7280; color:#fff; padding:4px 8px; border-radius:12px; font-size:11px; font-weight:600;">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td style="padding:15px 10px; text-align:center;">
                                <div style="display:flex; gap:4px; justify-content:center; align-items:center; flex-wrap:wrap;">
                                    <!-- Show Button -->
                                    <button type="button"
                                            style="background:#3b82f6; padding:6px 10px; border:none; border-radius:6px;
                                                   color:#fff; font-size:11px; cursor:pointer; transition:all 0.2s;
                                                   display:flex; align-items:center;"
                                            title="Detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#showModal{{ $sparepart->id_sparepart }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- Edit Button -->
                                    <button type="button"
                                            style="background:#fbbf24; padding:6px 10px; border:none; border-radius:6px;
                                                   color:#fff; font-size:11px; cursor:pointer; transition:all 0.2s;
                                                   display:flex; align-items:center;"
                                            title="Edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $sparepart->id_sparepart }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- Delete Button -->
                                    <form action="{{ route('sparepart.destroy', $sparepart->id_sparepart) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="background:#ef4444; padding:6px 10px; border:none; border-radius:6px;
                                                       color:#fff; font-size:11px; cursor:pointer; transition:all 0.2s;
                                                       display:flex; align-items:center;"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus sparepart ini?')"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Show Modal -->
                        <div class="modal fade" id="showModal{{ $sparepart->id_sparepart }}" tabindex="-1" aria-labelledby="showModalLabel{{ $sparepart->id_sparepart }}" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header" style="background:linear-gradient(135deg,#3b82f6,#60a5fa); color:#fff;">
                                        <h5 class="modal-title" id="showModalLabel{{ $sparepart->id_sparepart }}">
                                            <i class="fas fa-eye me-2"></i>Detail Sparepart
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="padding:30px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-barcode me-2"></i>Kode Sparepart
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->kode_sparepart ?: 'Auto Generate' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-tag me-2"></i>Nama Sparepart
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->nama_sparepart }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-copyright me-2"></i>Merk
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->merk ?: '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-truck me-2"></i>Supplier
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->nama_supplier ?: '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>Stok Minimum
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->stok_minimum }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-boxes me-2"></i>Stok Saat Ini
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->stok_saat_ini }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-dollar-sign me-2"></i>Harga Beli
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->harga_beli_terakhir ? number_format($sparepart->harga_beli_terakhir, 2, ',', '.') : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-4">
                                                    <label class="form-label" style="font-weight:600; color:#374151;">
                                                        <i class="fas fa-toggle-on me-2"></i>Status
                                                    </label>
                                                    <p class="form-control-static" style="font-size:14px;">
                                                        {{ $sparepart->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" style="padding:20px 30px; background:#f8fafc;">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                style="padding:10px 20px; border-radius:8px;">
                                            <i class="fas fa-times me-2"></i>Tutup
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $sparepart->id_sparepart }}" tabindex="-1" aria-labelledby="editModalLabel{{ $sparepart->id_sparepart }}" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header" style="background:linear-gradient(135deg,#fbbf24,#facc15); color:#fff;">
                                        <h5 class="modal-title" id="editModalLabel{{ $sparepart->id_sparepart }}">
                                            <i class="fas fa-edit me-2"></i>Edit Sparepart
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="editForm{{ $sparepart->id_sparepart }}" action="{{ route('sparepart.update', $sparepart->id_sparepart) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body" style="padding:30px;">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="kode_sparepart_{{ $sparepart->id_sparepart }}" class="form-label" style="font-weight:600; color:#374151;">
                                                            <i class="fas fa-barcode me-2"></i>Kode Sparepart
                                                        </label>
                                                        <input type="text" class="form-control" id="kode_sparepart_{{ $sparepart->id_sparepart }}" name="kode_sparepart"
                                                               value="{{ $sparepart->kode_sparepart }}"
                                                               style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"
                                                               placeholder="Kosongkan untuk auto generate">
                                                        <small class="text-muted">Kosongkan jika ingin dibuat otomatis</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="nama_sparepart_{{ $sparepart->id_sparepart }}" class="form-label" style="font-weight:600; color:#374151;">
                                                            <i class="fas fa-tag me-2"></i>Nama Sparepart <span style="color:#ef4444;">*</span>
                                                        </label>
                                                        <input type="text" class="form-control" id="nama_sparepart_{{ $sparepart->id_sparepart }}" name="nama_sparepart" required
                                                               value="{{ $sparepart->nama_sparepart }}"
                                                               style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"
                                                               placeholder="Masukkan nama sparepart">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="merk_{{ $sparepart->id_sparepart }}" class="form-label" style="font-weight:600; color:#374151;">
                                                            <i class="fas fa-copyright me-2"></i>Merk
                                                        </label>
                                                        <input type="text" class="form-control" id="merk_{{ $sparepart->id_sparepart }}" name="merk"
                                                               value="{{ $sparepart->merk }}"
                                                               style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"
                                                               placeholder="Masukkan merk sparepart">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <label for="supplier_id_{{ $sparepart->id_sparepart }}" class="form-label" style="font-weight:600; color:#374151;">
                                                            <i class="fas fa-truck me-2"></i>Supplier
                                                        </label>
                                                        <select class="form-control" id="supplier_id_{{ $sparepart->id_sparepart }}" name="supplier_id"
                                                                style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;">
                                                            <option value="">Pilih Supplier</option>
                                                            @foreach($suppliers as $supplier)
                                                                <option value="{{ $supplier->id_supplier }}" {{ $sparepart->supplier_id == $supplier->id_supplier ? 'selected' : '' }}>
                                                                    {{ $supplier->nama_supplier }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-4">
                                                        <label for="stok_minimum_{{ $sparepart->id_sparepart }}" class="form-label" style="font-weight:600; color:#374151;">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>Stok Minimum <span style="color:#ef4444;">*</span>
                                                        </label>
                                                        <input type="number" class="form-control" id="stok_minimum_{{ $sparepart->id_sparepart }}" name="stok_minimum" required
                                                               value="{{ $sparepart->stok_minimum }}" min="0"
                                                               style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"
                                                               placeholder="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-4">
                                                        <label for="harga_beli_{{ $sparepart->id_sparepart }}" class="form-label" style="font-weight:600; color:#374151;">
                                                            <i class="fas fa-shopping-cart me-2"></i>Harga Beli
                                                        </label>
                                                        <input type="number" class="form-control" id="harga_beli_{{ $sparepart->id_sparepart }}" name="harga_beli"
                                                               value="{{ $sparepart->harga_beli_terakhir }}"
                                                               step="0.01" min="0"
                                                               style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"
                                                               placeholder="0.00">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer" style="padding:20px 30px; background:#f8fafc;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                    style="padding:10px 20px; border-radius:8px;">
                                                <i class="fas fa-times me-2"></i>Batal
                                            </button>
                                            <button type="submit" class="btn btn-primary"
                                                    style="padding:10px 20px; background:#667eea; border:#667eea; border-radius:8px;">
                                                <i class="fas fa-save me-2"></i>Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="12" style="text-align:center; padding:50px 20px; color:#6b7280; font-style:italic;">
                                <i class="fas fa-cogs" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                <span style="font-size:16px;">Tidak ada data sparepart</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($spareparts->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $spareparts->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Bulk actions functionality
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.sparepart-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });

        updateBulkActions();
    }

    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.sparepart-checkbox:checked');
        const selectedCount = checkboxes.length;
        const bulkSubmitBtn = document.getElementById('bulkSubmitBtn');
        const selectedCountSpan = document.getElementById('selectedCount');

        selectedCountSpan.textContent = selectedCount + ' item terpilih';
        bulkSubmitBtn.disabled = selectedCount === 0;

        // Update form with selected IDs
        const bulkForm = document.getElementById('bulkForm');
        // Remove existing hidden inputs
        const existingInputs = bulkForm.querySelectorAll('input[name="sparepart_ids[]"]');
        existingInputs.forEach(input => input.remove());

        // Add selected IDs as hidden inputs
        checkboxes.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'sparepart_ids[]';
            hiddenInput.value = checkbox.value;
            bulkForm.appendChild(hiddenInput);
        });
    }

    // Bulk form validation
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        const jenisUpdate = document.getElementById('bulk_jenis_update').value;
        const jumlah = document.getElementById('bulk_jumlah').value;

        if (!jenisUpdate) {
            e.preventDefault();
            showToast('Pilih jenis aksi terlebih dahulu', 'error');
            return false;
        }

        if (!jumlah || jumlah < 1) {
            e.preventDefault();
            showToast('Masukkan jumlah yang valid', 'error');
            return false;
        }

        const checkboxes = document.querySelectorAll('.sparepart-checkbox:checked');
        if (checkboxes.length === 0) {
            e.preventDefault();
            showToast('Pilih minimal 1 sparepart', 'error');
            return false;
        }

        const action = jenisUpdate === 'tambah' ? 'menambah' : jenisUpdate === 'kurang' ? 'mengurangi' : 'mengeset';
        if (!confirm(`Apakah Anda yakin ingin ${action} stok ${checkboxes.length} sparepart dengan jumlah ${jumlah}?`)) {
            e.preventDefault();
            return false;
        }
    });

    $(document).ready(function() {
        $('#sparepartTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0, 1], orderable: false },
                { targets: [11], orderable: false, className: 'text-center' }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[3, 'asc']] // Sort by nama_sparepart
        });
    });

    // Form validation for Create and Edit
    function validateForm(formId) {
        const form = document.getElementById(formId);
        form.addEventListener('submit', function(e) {
            const namaSparepart = form.querySelector('[name="nama_sparepart"]').value.trim();
            const satuan = form.querySelector('[name="satuan"]').value.trim();
            const stokMin = parseInt(form.querySelector('[name="stok_minimum"]').value);

            if (namaSparepart.length < 3) {
                e.preventDefault();
                showToast('Nama sparepart minimal 3 karakter', 'error');
                return false;
            }

            if (satuan.length < 1) {
                e.preventDefault();
                showToast('Satuan harus diisi', 'error');
                return false;
            }

            if (stokMin < 0) {
                e.preventDefault();
                showToast('Stok minimum tidak boleh negatif', 'error');
                return false;
            }
        });
    }

    validateForm('createForm');
    @foreach($spareparts as $sparepart)
        validateForm('editForm{{ $sparepart->id_sparepart }}');
    @endforeach

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

    // Show success/error messages if exist
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif

    @if($errors->any())
        showToast('{{ $errors->first() }}', 'error');
    @endif

    // Format currency input
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        input.value = value;
    }

    // Number formatting for display
    document.addEventListener('DOMContentLoaded', function() {
        const hargaInputs = document.querySelectorAll('input[name="harga_beli"], input[name="harga_jual"]');
        hargaInputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });
        });
    });
</script>
@endsection
