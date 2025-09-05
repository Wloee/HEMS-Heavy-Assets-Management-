@extends('layouts.app')

@section('content')
<div class="content">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['total_schedule'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Total Jadwal</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #ffeaa7, #fdcb6e); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['active_schedule'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Jadwal Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #74b9ff, #0984e3); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['pending_schedule'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background: linear-gradient(135deg, #55a3ff, #003d82); color: white; border-radius: 15px;">
                <div class="card-body text-center">
                    <h3 style="font-weight: 700;">{{ $stats['completed_schedule'] }}</h3>
                    <p style="margin: 0; opacity: 0.9;">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Jadwal Maintenance</h2>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal"
                    style="padding:10px 16px; background:linear-gradient(135deg,#667eea,#764ba2);
                           color:#fff; font-weight:600; border-radius:10px; border:none;
                           box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:0.3s;">
                <i class="fas fa-plus"></i> Tambah Jadwal
            </button>
        </div>

        <!-- Filter Form -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('maintenance-schedule.index') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari jenis maintenance, unit, atau operator..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:320px;">

                    <select name="unit_id" class="form-select" style="width:180px;">
                        <option value="" {{ !request('unit_id') ? 'selected' : '' }}>Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id_unit }}" {{ request('unit_id') == $unit->id_unit ? 'selected' : '' }}>
                                {{ $unit->nama_unit }}
                            </option>
                        @endforeach
                    </select>

                    <select name="operator_id" class="form-select" style="width:180px;">
                        <option value="" {{ !request('operator_id') ? 'selected' : '' }}>Semua Operator</option>
                        @foreach($operators as $operator)
                            <option value="{{ $operator->id_karyawan }}" {{ request('operator_id') == $operator->id_karyawan ? 'selected' : '' }}>
                                {{ $operator->nama_karyawan }}
                            </option>
                        @endforeach
                    </select>

                    <select name="is_active" class="form-select" style="width:120px;">
                        <option value="" {{ request('is_active') === '' ? 'selected' : '' }}>Status</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:150px;">

                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:150px;">

                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>

                    @if(request()->hasAny(['search', 'unit_id', 'operator_id', 'is_active', 'tanggal_dari', 'tanggal_sampai']))
                        <a href="{{ route('maintenance-schedule.index') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Table -->
        <div style="overflow-x:auto;">
            <table id="maintenanceTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.05);
                          min-width: 1400px;">
                <thead>
                    <tr style="background:#f5f7fa; color:#334155; text-transform:uppercase; font-size:12px; letter-spacing:0.5px;">
                        <th style="padding:15px 12px; text-align:center; width:50px;">No</th>
                        <th style="padding:15px 12px; width:120px;">Jenis Maintenance</th>
                        <th style="padding:15px 12px; width:150px;">Unit</th>
                        <th style="padding:15px 12px; width:120px;">Operator</th>
                        <th style="padding:15px 12px; width:150px;">Sparepart</th>
                        <th style="padding:15px 12px; width:200px;">Deskripsi</th>
                        <th style="padding:15px 12px; width:100px;">Mulai</th>
                        <th style="padding:15px 12px; width:100px;">Selesai</th>
                        <th style="padding:15px 12px; width:80px; text-align:center;">Status</th>
                        <th style="padding:15px 12px; width:120px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $index => $item)
                        <tr style="border-bottom:1px solid #e2e8f0; transition:background 0.3s;"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:15px 12px; text-align:center; font-weight:500;">
                                {{ ($schedules->currentPage() - 1) * $schedules->perPage() + $index + 1 }}
                            </td>
                            <td style="padding:15px 12px; font-weight:600; color:#667eea;">
                                {{ $item->jenis_maintenance }}
                            </td>
                            <td style="padding:15px 12px;">
                                <div style="font-weight: 500;">{{ $item->nama_unit ?? '-' }}</div>
                                <div style="font-size: 11px; color: #6b7280;">{{ $item->kode_unit ?? '' }}</div>
                            </td>
                            <td style="padding:15px 12px; font-weight:500;">
                                {{ $item->operator_name ?? '-' }}
                            </td>
                            <td style="padding:15px 12px;">
                                {{ $item->nama_sparepart ?? '-' }}
                            </td>
                            <td style="padding:15px 12px;">
                                <div style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                     title="{{ $item->deskripsi }}">
                                    {{ $item->deskripsi ?? '-' }}
                                </div>
                            </td>
                            <td style="padding:15px 12px;">
                                {{ $item->Mulai_dikerjakan ? \Carbon\Carbon::parse($item->Mulai_dikerjakan)->format('d/m/Y') : '-' }}
                            </td>
                            <td style="padding:15px 12px;">
                                {{ $item->Selesai_dikerjakan ? \Carbon\Carbon::parse($item->Selesai_dikerjakan)->format('d/m/Y') : '-' }}
                            </td>

                            @php
                                $statusColor = $item->is_active ? '#10b981' : '#ef4444';
                                $statusText = $item->is_active ? 'Aktif' : 'Nonaktif';
                            @endphp

                            <td style="padding:15px 12px; text-align:center;">
                                <span style="padding:4px 8px; background:{{ $statusColor }}20; color:{{ $statusColor }};
                                             border-radius:12px; font-size:11px; font-weight:600; text-transform:uppercase;">
                                    {{ $statusText }}
                                </span>
                            </td>

                            <td style="padding:15px 12px; text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    <a href="{{ route('maintenance-schedule.show', $item->id_schedule) }}"
                                       style="background:#3b82f6; padding:8px 10px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:32px; height:32px;"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button type="button" class="btn-edit-maintenance"
                                            data-id="{{ $item->id_schedule }}"
                                            data-unit_id="{{ $item->unit_id }}"
                                            data-jenis_maintenance="{{ $item->jenis_maintenance }}"
                                            data-deskripsi="{{ $item->deskripsi }}"
                                            data-mulai_dikerjakan="{{ $item->Mulai_dikerjakan }}"
                                            data-selesai_dikerjakan="{{ $item->Selesai_dikerjakan }}"
                                            data-sparepart_id="{{ $item->Sparepart_id }}"
                                            data-operator_id="{{ $item->operator_id }}"
                                            data-is_active="{{ $item->is_active }}"
                                            style="background:#f59e0b; padding:8px 10px; border:none; border-radius:6px;
                                                   color:#fff; font-size:12px; cursor:pointer; transition:all 0.2s;
                                                   min-width:32px; height:32px; display:flex; align-items:center; justify-content:center;"
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('maintenance-schedule.destroy', $item->id_schedule) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal maintenance ini?')"
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
                            <td colspan="10" style="text-align:center; padding:40px 20px; color:#6b7280; font-style:italic;">
                                <i class="fas fa-exclamation-triangle" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                Tidak ada data jadwal maintenance
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div style="color: #6b7280;">
                Menampilkan {{ $schedules->firstItem() ?? 0 }} sampai {{ $schedules->lastItem() ?? 0 }}
                dari {{ $schedules->total() }} data
            </div>
            {{ $schedules->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-labelledby="addMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title" id="addMaintenanceModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Jadwal Maintenance
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('maintenance-schedule.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-select" id="unit_id" name="unit_id" required>
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }} ({{ $unit->kode_unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis_maintenance" class="form-label">Jenis Maintenance <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="jenis_maintenance" name="jenis_maintenance"
                                   placeholder="Masukkan jenis maintenance" maxlength="100" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="operator_id" class="form-label">Operator <span class="text-danger">*</span></label>
                            <select class="form-select" id="operator_id" name="operator_id" required>
                                <option value="">Pilih Operator</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id_karyawan }}">{{ $operator->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="Sparepart_id" class="form-label">Sparepart <span class="text-danger">*</span></label>
                            <select class="form-select" id="Sparepart_id" name="Sparepart_id" required>
                                <option value="">Pilih Sparepart</option>
                                @foreach($spareparts as $sparepart)
                                    <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->nama_sparepart }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="Mulai_dikerjakan" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="Mulai_dikerjakan" name="Mulai_dikerjakan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="Selesai_dikerjakan" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="Selesai_dikerjakan" name="Selesai_dikerjakan">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Masukkan deskripsi maintenance"></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">
                            Status Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editMaintenanceModal" tabindex="-1" aria-labelledby="editMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <h5 class="modal-title" id="editMaintenanceModalLabel">
                    <i class="fas fa-edit me-2"></i>Edit Jadwal Maintenance
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMaintenanceForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_unit_id" class="form-label">Unit <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_unit_id" name="unit_id" required>
                                <option value="">Pilih Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }} ({{ $unit->kode_unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_jenis_maintenance" class="form-label">Jenis Maintenance <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_jenis_maintenance" name="jenis_maintenance"
                                   placeholder="Masukkan jenis maintenance" maxlength="100" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_operator_id" class="form-label">Operator <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_operator_id" name="operator_id" required>
                                <option value="">Pilih Operator</option>
                                @foreach($operators as $operator)
                                    <option value="{{ $operator->id_karyawan }}">{{ $operator->nama_karyawan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_Sparepart_id" class="form-label">Sparepart <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_Sparepart_id" name="Sparepart_id" required>
                                <option value="">Pilih Sparepart</option>
                                @foreach($spareparts as $sparepart)
                                    <option value="{{ $sparepart->id_sparepart }}">{{ $sparepart->nama_sparepart }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_Mulai_dikerjakan" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="edit_Mulai_dikerjakan" name="Mulai_dikerjakan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_Selesai_dikerjakan" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="edit_Selesai_dikerjakan" name="Selesai_dikerjakan">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"
                                  placeholder="Masukkan deskripsi maintenance"></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active">
                        <label class="form-check-label" for="edit_is_active">
                            Status Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Update
                    </button>
                </div>
            </form>
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
        $('#maintenanceTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0], orderable: false },
                { targets: [8, 9], orderable: false, className: 'text-center' },
                { targets: [5], orderable: false } // Deskripsi tidak bisa diurutkan
            ],
            pageLength: 15,
            lengthMenu: [[10, 15, 25, 50, 100], [10, 15, 25, 50, 100]],
            order: [[6, 'desc']] // Order by tanggal mulai descending
        });

        // Handle edit button click
        $('.btn-edit-maintenance').on('click', function() {
            const data = $(this).data();

            $('#editMaintenanceForm').attr('action', `{{ route('maintenance-schedule.index') }}/${data.id}`);
            $('#edit_unit_id').val(data.unit_id);
            $('#edit_jenis_maintenance').val(data.jenis_maintenance);
            $('#edit_operator_id').val(data.operator_id);
            $('#edit_Sparepart_id').val(data.sparepart_id);
            $('#edit_Mulai_dikerjakan').val(data.mulai_dikerjakan);
            $('#edit_Selesai_dikerjakan').val(data.selesai_dikerjakan);
            $('#edit_deskripsi').val(data.deskripsi);
            $('#edit_is_active').prop('checked', data.is_active == 1);

            $('#editMaintenanceModal').modal('show');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Validation for date fields
        $('#Selesai_dikerjakan, #edit_Selesai_dikerjakan').on('change', function() {
            const startDate = $(this).closest('form').find('[name="Mulai_dikerjakan"]').val();
            const endDate = $(this).val();

            if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
                $(this).val('');
            }
        });
    });
</script>
@endsection
