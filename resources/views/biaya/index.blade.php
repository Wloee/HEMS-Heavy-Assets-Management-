@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Input Biaya Service / Maintenance
                    </h4>
                </div>

                <div class="card-body">
                    @if($maintenance->count() > 0)
                        <form method="POST" action="{{ route('biaya.update', 0) }}" enctype="multipart/form-data" id="biayaForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Maintenance Selection -->
                                <div class="col-md-6 mb-3">
                                    <label for="maintenance_id" class="form-label">
                                        <i class="fas fa-wrench me-1"></i>
                                        Select Maintenance *
                                    </label>
                                    <select class="form-select @error('maintenance_id') is-invalid @enderror"
                                            id="maintenance_id"
                                            name="maintenance_id"
                                            required
                                            onchange="loadMaintenanceDetails(this.value)">
                                        <option value="">-- Pilih Maintenance --</option>
                                        @foreach ($maintenance as $item)
                                            <option value="{{ $item->id_log }}"
                                                    {{ old('maintenance_id') == $item->id_log ? 'selected' : '' }}>
                                                ID: {{ $item->id_log }} - {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                                                @if(isset($item->description))
                                                    - {{ Str::limit($item->description, 50) }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('maintenance_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Cost Estimation -->
                                <div class="col-md-6 mb-3">
                                    <label for="biaya_estimasi" class="form-label">
                                        <i class="fas fa-calculator me-1"></i>
                                        Biaya Estimasi *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                               class="form-control @error('biaya_estimasi') is-invalid @enderror"
                                               id="biaya_estimasi"
                                               name="biaya_estimasi"
                                               value="{{ old('biaya_estimasi') }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="0.00"
                                               onchange="calculateDeviation()"
                                               required>
                                        @error('biaya_estimasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Actual Cost -->
                                <div class="col-md-6 mb-3">
                                    <label for="biaya_aktual" class="form-label">
                                        <i class="fas fa-receipt me-1"></i>
                                        Biaya Aktual *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                               class="form-control @error('biaya_aktual') is-invalid @enderror"
                                               id="biaya_aktual"
                                               name="biaya_aktual"
                                               value="{{ old('biaya_aktual') }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="0.00"
                                               onchange="calculateDeviation()"
                                               required>
                                        @error('biaya_aktual')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Deviation Percentage -->
                                <div class="col-md-6 mb-3">
                                    <label for="deviasi_persen" class="form-label">
                                        <i class="fas fa-percentage me-1"></i>
                                        % Deviasi *
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control @error('deviasi_persen') is-invalid @enderror"
                                               id="deviasi_persen"
                                               name="deviasi_persen"
                                               value="{{ old('deviasi_persen') }}"
                                               step="0.01"
                                               placeholder="0.00"
                                               readonly>
                                        <span class="input-group-text">%</span>
                                        @error('deviasi_persen')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">
                                        Deviasi dihitung otomatis berdasarkan selisih biaya aktual dan estimasi
                                    </small>
                                </div>

                                <!-- File Upload -->
                                <div class="col-md-12 mb-3">
                                    <label for="bukti" class="form-label">
                                        <i class="fas fa-upload me-1"></i>
                                        Upload Bukti Pembayaran
                                    </label>
                                    <input type="file"
                                           class="form-control @error('bukti') is-invalid @enderror"
                                           id="bukti"
                                           name="bukti"
                                           accept="image/*,.pdf"
                                           onchange="previewFile(this)">
                                    @error('bukti')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Format yang didukung: JPG, PNG, PDF. Maksimal 5MB.
                                    </small>

                                    <!-- File Preview -->
                                    <div id="filePreview" class="mt-2" style="display: none;">
                                        <img id="imagePreview" src="" alt="Preview" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                                        <p id="fileInfo" class="mt-1 text-muted"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Simpan Data
                                        </button>
                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>
                                            Kembali
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Tidak ada data maintenance yang perlu diinput biayanya.
                            Semua maintenance sudah memiliki data biaya lengkap.
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-1"></i>
                            Kembali ke Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
function calculateDeviation() {
    const biayaEstimasi = parseFloat(document.getElementById('biaya_estimasi').value) || 0;
    const biayaAktual = parseFloat(document.getElementById('biaya_aktual').value) || 0;

    if (biayaEstimasi > 0) {
        const deviasi = ((biayaAktual - biayaEstimasi) / biayaEstimasi) * 100;
        document.getElementById('deviasi_persen').value = deviasi.toFixed(2);

        // Color coding for deviation
        const deviationInput = document.getElementById('deviasi_persen');
        deviationInput.classList.remove('text-success', 'text-warning', 'text-danger');

        if (Math.abs(deviasi) <= 5) {
            deviationInput.classList.add('text-success');
        } else if (Math.abs(deviasi) <= 15) {
            deviationInput.classList.add('text-warning');
        } else {
            deviationInput.classList.add('text-danger');
        }
    } else {
        document.getElementById('deviasi_persen').value = '';
    }
}

function previewFile(input) {
    const file = input.files[0];
    const preview = document.getElementById('filePreview');
    const imagePreview = document.getElementById('imagePreview');
    const fileInfo = document.getElementById('fileInfo');

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            if (file.type.startsWith('image/')) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            } else {
                imagePreview.style.display = 'none';
            }

            fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

function loadMaintenanceDetails(maintenanceId) {
    if (maintenanceId) {
        // Here you could add AJAX call to load more details about the selected maintenance
        console.log('Selected maintenance:', maintenanceId);
    }
}

// Form validation before submit
document.getElementById('biayaForm').addEventListener('submit', function(e) {
    const maintenanceId = document.getElementById('maintenance_id').value;
    const biayaEstimasi = document.getElementById('biaya_estimasi').value;
    const biayaAktual = document.getElementById('biaya_aktual').value;

    if (!maintenanceId || !biayaEstimasi || !biayaAktual) {
        e.preventDefault();
        alert('Mohon lengkapi semua field yang wajib diisi.');
        return false;
    }

    // Confirmation for large deviations
    const deviasi = parseFloat(document.getElementById('deviasi_persen').value) || 0;
    if (Math.abs(deviasi) > 20) {
        if (!confirm(`Deviasi biaya cukup besar (${deviasi.toFixed(2)}%). Apakah Anda yakin ingin melanjutkan?`)) {
            e.preventDefault();
            return false;
        }
    }
});
</script>
@endsection

@section('styles')
<style>
.form-label {
    font-weight: 600;
    color: #495057;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

.text-success {
    color: #28a745 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.btn {
    border-radius: 5px;
}
</style>
@endsection
@endsection
