@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-4">
        {{ isset($mutasi) ? 'Edit Data Mutasi Unit' : 'Tambah Data Mutasi Unit' }}
    </h4>
    <div class="card p-4">
        <!-- Form yang fleksibel untuk insert dan edit -->
        <form action="{{ isset($mutasi) ? route('Mutasi-Unit.update', $mutasi->id_mutasi) : route('Mutasi-Unit.store') }}" method="POST">
            @csrf
            @if(isset($mutasi))
                @method('PUT')
            @endif

            <!-- Data Unit -->
            <h5 class="fw-semibold mb-3">Informasi Unit</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Pilih Unit*</label>
                    <div class="input-group">
                        <select name="unit_id" class="form-select" id="unitSelect" required>
                            <option value="" disabled {{ !old('unit_id') && !isset($mutasi) ? 'selected' : '' }}>Pilih unit yang akan dimutasi</option>
                            @if(isset($units))
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id_unit }}"
                                        {{ old('unit_id', isset($mutasi) ? $mutasi->unit_id : '') == $unit->id_unit ? 'selected' : '' }}>
                                        {{ $unit->kode_unit }} - {{ $unit->nama_unit }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#unitInfoModal" id="btnInfoUnit" disabled>
                            <i class="fas fa-info-circle"></i> Info Unit
                        </button>
                    </div>
                    @error('unit_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status Unit Saat Ini</label>
                    <input type="text" class="form-control" id="statusUnitInfo" placeholder="Status akan tampil setelah memilih unit" readonly>
                </div>
            </div>

            <!-- Data Proyek -->
            <h5 class="fw-semibold mb-3">Informasi Proyek</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Proyek Tujuan*</label>
                    <div class="input-group">
                        <select name="proyek_id" class="form-select" id="proyekSelect" required>
                            <option value="" disabled {{ !old('proyek_id') && !isset($mutasi) ? 'selected' : '' }}>Pilih proyek tujuan</option>
                            @if(isset($proyek))
                                @foreach($proyek as $p)
                                    <option value="{{ $p->id_proyek }}"
                                        {{ old('proyek_id', isset($mutasi) ? $mutasi->proyek_id : '') == $p->id_proyek ? 'selected' : '' }}
                                        data-lokasi="{{ $p->lokasi_proyek }}"
                                        data-mulai="{{ $p->tanggal_mulai }}"
                                        data-selesai="{{ $p->tanggal_selesai_aktual }}">
                                        {{ $p->nama_proyek }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#proyekInfoModal" id="btnInfoProyek" disabled>
                            <i class="fas fa-info-circle"></i> Info Proyek
                        </button>
                    </div>
                    @error('proyek_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Lokasi Proyek</label>
                    <input type="text" class="form-control" id="lokasiProyekInfo" placeholder="Lokasi akan tampil setelah memilih proyek" readonly>
                </div>
            </div>

            <!-- Data Mutasi -->
            <h5 class="fw-semibold mb-3">Detail Mutasi</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mutasi*</label>
                    <input type="date" name="tanggal_mutasi" class="form-control"
                           value="{{ old('tanggal_mutasi', isset($mutasi) ? $mutasi->tanggal_mutasi : date('Y-m-d')) }}" required>
                    @error('tanggal_mutasi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jam Mutasi*</label>
                    <input type="time" name="jam_mutasi" class="form-control"
                           value="{{ old('jam_mutasi', isset($mutasi) ? $mutasi->jam_mutasi : date('H:i')) }}" required>
                    @error('jam_mutasi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status Mutasi*</label>
                    <select name="status_mutasi" class="form-select" required>
                        <option value="aktif" {{ old('status', isset($mutasi) ? $mutasi->status : 'aktif') == 'aktif' ? 'selected' : '' }}>aktif</option>
                        <option value="selesai" {{ old('status', isset($mutasi) ? $mutasi->status : '') == 'selesai' ? 'selected' : '' }}>selesai</option>
                        <option value="dibatalkan" {{ old('status', isset($mutasi) ? $mutasi->status : '') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status_mutasi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Estimasi Waktu -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Estimasi Waktu Tiba</label>
                    <input type="datetime-local" name="estimasi_tiba" class="form-control"
                           value="{{ old('estimasi_tiba', isset($mutasi) ? $mutasi->estimasi_tiba : '') }}">
                    @error('estimasi_tiba')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Waktu Tiba Aktual</label>
                    <input type="datetime-local" name="waktu_tiba_aktual" class="form-control"
                           value="{{ old('waktu_tiba_aktual', isset($mutasi) ? $mutasi->waktu_tiba_aktual : '') }}">
                    @error('waktu_tiba_aktual')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Keterangan -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="4" placeholder="Masukkan keterangan tambahan untuk mutasi ini">{{ old('keterangan', isset($mutasi) ? $mutasi->keterangan : '') }}</textarea>
                    @error('keterangan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('Mutasi-Unit.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ isset($mutasi) ? 'Update Mutasi' : 'Simpan Mutasi' }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Info Unit -->
<div class="modal fade" id="unitInfoModal" tabindex="-1" aria-labelledby="unitInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unitInfoModalLabel">Informasi Detail Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Unit:</label>
                            <p id="modalKodeUnit" class="mb-1">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Unit:</label>
                            <p id="modalNamaUnit" class="mb-1">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Unit:</label>
                            <p id="modalJenisUnit" class="mb-1">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Operasional:</label>
                            <p id="modalStatusUnit" class="mb-1">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Saat Ini:</label>
                            <p id="modalLokasiUnit" class="mb-1">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Info Proyek -->
<div class="modal fade" id="proyekInfoModal" tabindex="-1" aria-labelledby="proyekInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proyekInfoModalLabel">Informasi Detail Proyek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Proyek:</label>
                            <p id="modalNamaProyek" class="mb-1">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi:</label>
                            <p id="modalLokasiProyek" class="mb-1">-</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Mulai:</label>
                            <p id="modalTanggalMulai" class="mb-1">-</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Selesai:</label>
                            <p id="modalTanggalSelesai" class="mb-1">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Fungsi untuk menampilkan pesan toast
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

    // Handle unit selection change
    document.getElementById('unitSelect').addEventListener('change', function() {
        const unitId = this.value;
        const btnInfoUnit = document.getElementById('btnInfoUnit');
        const statusUnitInfo = document.getElementById('statusUnitInfo');

        if (unitId) {
            btnInfoUnit.disabled = false;
            statusUnitInfo.value = 'Standby'; // Karena hanya unit standby yang bisa dimutasi

            // Fetch unit details untuk modal
            fetch(`/api/unit/${unitId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const unit = data.unit;
                        document.getElementById('modalKodeUnit').textContent = unit.kode_unit || '-';
                        document.getElementById('modalNamaUnit').textContent = unit.nama_unit || '-';
                        document.getElementById('modalJenisUnit').textContent = unit.jenis_unit || '-';
                        document.getElementById('modalStatusUnit').textContent = unit.status_operasional || '-';
                        document.getElementById('modalLokasiUnit').textContent = unit.alamat_unit || '-';
                    }
                })
                .catch(error => {
                    console.error('Error fetching unit details:', error);
                    showToast('Gagal mengambil detail unit', 'error');
                });
        } else {
            btnInfoUnit.disabled = true;
            statusUnitInfo.value = '';
        }
    });

    // Handle proyek selection change
    document.getElementById('proyekSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const btnInfoProyek = document.getElementById('btnInfoProyek');
        const lokasiProyekInfo = document.getElementById('lokasiProyekInfo');

        if (this.value) {
            btnInfoProyek.disabled = false;
            lokasiProyekInfo.value = selectedOption.getAttribute('data-lokasi') || '';

            // Update modal proyek info
            document.getElementById('modalNamaProyek').textContent = selectedOption.textContent || '-';
            document.getElementById('modalLokasiProyek').textContent = selectedOption.getAttribute('data-lokasi') || '-';
            document.getElementById('modalTanggalMulai').textContent = selectedOption.getAttribute('data-mulai') || '-';
            document.getElementById('modalTanggalSelesai').textContent = selectedOption.getAttribute('data-selesai') || '-';
        } else {
            btnInfoProyek.disabled = true;
            lokasiProyekInfo.value = '';
        }
    });

    // Initialize pada page load jika dalam mode edit
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger change event untuk inisialisasi
        const unitSelect = document.getElementById('unitSelect');
        const proyekSelect = document.getElementById('proyekSelect');

        if (unitSelect.value) {
            unitSelect.dispatchEvent(new Event('change'));
        }

        if (proyekSelect.value) {
            proyekSelect.dispatchEvent(new Event('change'));
        }

        // Set minimum date untuk tanggal mutasi (tidak boleh kurang dari hari ini)
        const tanggalMutasi = document.querySelector('input[name="tanggal_mutasi"]');
        if (tanggalMutasi && !tanggalMutasi.value) {
            tanggalMutasi.min = new Date().toISOString().split('T')[0];
        }
    });

    // Validasi tanggal mutasi tidak boleh lampau
    document.querySelector('input[name="tanggal_mutasi"]').addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            showToast('Tanggal mutasi tidak boleh kurang dari hari ini', 'error');
            this.value = new Date().toISOString().split('T')[0];
        }
    });

    // Auto update estimasi tiba berdasarkan tanggal dan jam mutasi
    function updateEstimasiTiba() {
        const tanggalMutasi = document.querySelector('input[name="tanggal_mutasi"]').value;
        const jamMutasi = document.querySelector('input[name="jam_mutasi"]').value;
        const estimasiTiba = document.querySelector('input[name="estimasi_tiba"]');

        if (tanggalMutasi && jamMutasi && !estimasiTiba.value) {
            // Estimasi tiba default adalah 2 jam setelah mutasi
            const mutasiDateTime = new Date(`${tanggalMutasi}T${jamMutasi}`);
            mutasiDateTime.setHours(mutasiDateTime.getHours() + 2);

            const estimasiValue = mutasiDateTime.toISOString().slice(0, 16);
            estimasiTiba.value = estimasiValue;
        }
    }

    document.querySelector('input[name="tanggal_mutasi"]').addEventListener('change', updateEstimasiTiba);
    document.querySelector('input[name="jam_mutasi"]').addEventListener('change', updateEstimasiTiba);

    // Form validation sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const unitId = document.getElementById('unitSelect').value;
        const proyekId = document.getElementById('proyekSelect').value;
        const operatorId = document.querySelector('select[name="operator_id"]').value;

        if (!unitId || !proyekId || !operatorId) {
            e.preventDefault();
            showToast('Harap lengkapi semua field yang wajib diisi', 'error');
            return false;
        }

        // Tampilkan loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        // Re-enable setelah 3 detik jika gagal submit
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '{{ isset($mutasi) ? "Update Mutasi" : "Simpan Mutasi" }}';
        }, 3000);
    });
</script>
@endsection
