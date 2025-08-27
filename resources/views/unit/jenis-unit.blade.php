@extends('layouts.app')

@section('content')
<div class="content">
    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Data Jenis Unit</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"
                    style="padding:12px 20px; background:linear-gradient(135deg,#667eea,#764ba2);
                           color:#fff; font-weight:600; border-radius:10px; text-decoration:none;
                           box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:0.3s; border:none;">
                <i class="fas fa-plus"></i> Tambah Jenis Unit
            </button>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('jenis-unit.index') }}" method="GET" style="display:flex; gap:10px; align-items:center;">
                    <input type="text" name="search" placeholder="Cari nama jenis unit..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:300px;">
                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('jenis-unit.index') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table id="jenisUnitTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.08);
                          font-size:14px;">
                <thead>
                    <tr style="background:#f8fafc; color:#374151; text-transform:uppercase; font-size:12px; letter-spacing:0.5px; font-weight:600;">
                        <th style="padding:18px 15px; text-align:center; width:60px;">No</th>
                        <th style="padding:18px 15px; width:200px;">Nama Jenis Unit</th>
                        <th style="padding:18px 15px; width:300px;">Deskripsi</th>
                        <th style="padding:18px 15px; width:150px;">Tanggal Dibuat</th>
                        <th style="padding:18px 15px; width:150px;">Terakhir Update</th>
                        <th style="padding:18px 15px; width:130px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisUnits as $index => $jenisUnit)
                        <tr style="border-bottom:1px solid #e5e7eb; transition:background 0.3s;"
                            onmouseover="this.style.background='#f9fafb'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:18px 15px; text-align:center; font-weight:600;">
                                {{ ($jenisUnits->currentPage() - 1) * $jenisUnits->perPage() + $index + 1 }}
                            </td>
                            <td style="padding:18px 15px; font-weight:600;">{{ $jenisUnit->nama_jenis }}</td>
                            <td style="padding:18px 15px; color:#6b7280;">
                                {{ $jenisUnit->deskripsi ? Str::limit($jenisUnit->deskripsi, 50) : 'Tidak ada deskripsi' }}
                            </td>
                            <td style="padding:18px 15px; color:#6b7280;">
                                {{ \Carbon\Carbon::parse($jenisUnit->created_at)->format('d F Y') }}
                            </td>
                            <td style="padding:18px 15px; color:#6b7280;">
                                {{ \Carbon\Carbon::parse($jenisUnit->updated_at)->format('d F Y') }}
                            </td>
                            <td style="padding:18px 15px; text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    <!-- Edit Button -->
                                    <button type="button"
                                            onclick="editJenisUnit({{ $jenisUnit->id_jenis_unit }}, '{{ addslashes($jenisUnit->nama_jenis) }}', '{{ addslashes($jenisUnit->deskripsi ?? '') }}')"
                                            style="background:#fbbf24; padding:8px 12px; border:none; border-radius:6px;
                                                   color:#fff; font-size:12px; cursor:pointer; transition:all 0.2s;
                                                   min-width:36px; height:36px; display:flex; align-items:center; justify-content:center;"
                                            title="Edit">

                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="{{ route('jenis-unit.destroy', $jenisUnit->id_jenis_unit) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="background:#ef4444; padding:8px 12px; border:none; border-radius:6px;
                                                       color:#fff; font-size:12px; cursor:pointer; transition:all 0.2s;
                                                       min-width:36px; height:36px; display:flex; align-items:center; justify-content:center;"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus jenis unit ini?')"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:50px 20px; color:#6b7280; font-style:italic;">
                                <i class="fas fa-cube" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                <span style="font-size:16px;">Tidak ada data jenis unit</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($jenisUnits->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $jenisUnits->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2); color:#fff;">
                <h5 class="modal-title" id="createModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Jenis Unit Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm" action="{{ route('jenis-unit.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="padding:30px;">
                    <div class="mb-4">
                        <label for="nama_jenis" class="form-label" style="font-weight:600; color:#374151;">
                            <i class="fas fa-tag me-2"></i>Nama Jenis Unit <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" class="form-control" id="nama_jenis" name="nama_jenis" required
                               style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"
                               placeholder="Masukkan nama jenis unit">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label" style="font-weight:600; color:#374151;">
                            <i class="fas fa-align-left me-2"></i>Deskripsi
                        </label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                  style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"
                                  placeholder="Masukkan deskripsi jenis unit (opsional)"></textarea>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background:#fbbf24; color:#fff;">
                <h5 class="modal-title" id="editModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Jenis Unit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body" style="padding:30px;">
                    <div class="mb-4">
                        <label for="edit_nama_jenis" class="form-label" style="font-weight:600; color:#374151;">
                            <i class="fas fa-tag me-2"></i>Nama Jenis Unit <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" class="form-control" id="edit_nama_jenis" name="nama_jenis" required
                               style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label" style="font-weight:600; color:#374151;">
                            <i class="fas fa-align-left me-2"></i>Deskripsi
                        </label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="4"
                                  style="padding:12px 15px; border:2px solid #e5e7eb; border-radius:8px; font-size:14px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="padding:20px 30px; background:#f8fafc;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            style="padding:10px 20px; border-radius:8px;">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white"
                            style="padding:10px 20px; background:#fbbf24; border:#fbbf24; border-radius:8px;">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#jenisUnitTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0], orderable: false },
                { targets: [5], orderable: false, className: 'text-center' }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'asc']] // Sort by nama_jenis
        });
    });


    // Function to show edit modal
    function editJenisUnit(id, nama, deskripsi) {
        document.getElementById('edit_nama_jenis').value = nama;
        document.getElementById('edit_deskripsi').value = deskripsi || '';
        document.getElementById('editForm').action = `{{ route('jenis-unit.index') }}/${id}`;

        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }

    // Form validation
    document.getElementById('createForm').addEventListener('submit', function(e) {
        const namaJenis = document.getElementById('nama_jenis').value.trim();

        if (namaJenis.length < 3) {
            e.preventDefault();
            showToast('Nama jenis unit minimal 3 karakter', 'error');
            return false;
        }
    });

    document.getElementById('editForm').addEventListener('submit', function(e) {
        const namaJenis = document.getElementById('edit_nama_jenis').value.trim();

        if (namaJenis.length < 3) {
            e.preventDefault();
            showToast('Nama jenis unit minimal 3 karakter', 'error');
            return false;
        }
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
</script>
@endsection
