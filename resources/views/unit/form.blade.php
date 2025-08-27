@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-4">
        {{ isset($Unit) ? 'Edit Data Unit' : 'Tambah Data Unit' }}
    </h4>
    <div class="card p-4">
        <!-- Form yang fleksibel untuk insert dan edit -->
        <form action="{{ isset($Unit) ? route('Unit_update', $Unit->id_unit) : route('store_Unit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($Unit))
                @method('PUT')
            @endif

            <!-- Data Unit -->
            <h5 class="fw-semibold mb-3">Data Unit</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Unit</label>
                    <input type="text" name="kode_unit" class="form-control" placeholder="Masukkan Kode Unit"
                           value="{{ old('kode_unit', isset($Unit) ? $Unit->kode_unit : '') }}">
                    @error('kode_unit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Unit*</label>
                    <input type="text" name="nama_unit" class="form-control" placeholder="Masukkan Nama Unit"
                           value="{{ old('nama_unit', isset($Unit) ? $Unit->nama_unit : '') }}" required>
                    @error('nama_unit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Unit*</label>
                    <div class="input-group">
                        <select name="jenis_unit_id" class="form-select" id="jenisUnitSelect" required>
                            <option value="" disabled {{ !old('jenis_unit_id') && !isset($jenisUnits) ? 'selected' : '' }}>Pilih jenis unit</option>
                            @if(isset($jenisUnits))
                                @foreach($jenisUnits as $j)
                                    <option value="{{ $j->id_jenis_unit }}">
                                        {{ $j->nama_jenis }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @error('jenis_unit_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Merk</label>
                    <input type="text" name="merk" class="form-control" placeholder="Masukkan Merk"
                           value="{{ old('merk', isset($Unit) ? $Unit->merk : '') }}">
                    @error('merk')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" placeholder="Masukkan Model"
                           value="{{ old('model', isset($Unit) ? $Unit->model : '') }}">
                    @error('model')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun Pembuatan</label>
                    <input type="number" name="tahun_pembuatan" class="form-control" placeholder="YYYY" min="1900" max="{{ date('Y') }}"
                           value="{{ old('tahun_pembuatan', isset($Unit) ? $Unit->tahun_pembuatan : '') }}">
                    @error('tahun_pembuatan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data Teknis -->
            <h5 class="fw-semibold mb-3">Data Teknis</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Nomor Rangka</label>
                    <input type="text" name="no_rangka" class="form-control" placeholder="Masukkan Nomor Rangka"
                           value="{{ old('no_rangka', isset($Unit) ? $Unit->no_rangka : '') }}">
                    @error('no_rangka')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Mesin</label>
                    <input type="text" name="no_mesin" class="form-control" placeholder="Masukkan Nomor Mesin"
                           value="{{ old('no_mesin', isset($Unit) ? $Unit->no_mesin : '') }}">
                    @error('no_mesin')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor Polisi</label>
                    <input type="text" name="no_polisi" class="form-control" placeholder="B 1234 ABC"
                           value="{{ old('no_polisi', isset($Unit) ? $Unit->no_polisi : '') }}">
                    @error('no_polisi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data Lokasi -->
            <h5 class="fw-semibold mb-3">Data Lokasi</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Pemilik</label>
                    <input type="text" name="pemilik" class="form-control" value = "{{ old('nama_pemilik', isset($Unit) ? $Unit->nama_pemilik : '') }}" placeholder="Masukkan nama pemilik">
                    @error('pemilik_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kota</label>
                    <input type="text" name="kota" class="form-control" placeholder="Masukkan Kota"
                           value="{{ old('kota', isset($Unit) ? $Unit->kota : '') }}">
                    @error('kota')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Provinsi</label>
                    <input type="text" name="provinsi" class="form-control" placeholder="Masukkan Provinsi"
                           value="{{ old('provinsi', isset($Unit) ? $Unit->provinsi : '') }}">
                    @error('provinsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Alamat Unit</label>
                    <textarea name="alamat_unit" class="form-control" rows="3" placeholder="Masukkan alamat lengkap unit">{{ old('alamat_unit', isset($Unit) ? $Unit->alamat_unit : '') }}</textarea>
                    @error('alamat_unit')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data Status -->
            <h5 class="fw-semibold mb-3">Data Status</h5>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Status Kepemilikan*</label>
                    <select name="status_kepemilikan" class="form-select" required>
                        <option value="milik_sendiri" {{ old('status_kepemilikan', isset($Unit) ? $Unit->status_kepemilikan : 'milik_sendiri') == 'milik_sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                        <option value="sewa" {{ old('status_kepemilikan', isset($Unit) ? $Unit->status_kepemilikan : '') == 'sewa' ? 'selected' : '' }}>Sewa</option>
                        <option value="kontrak" {{ old('status_kepemilikan', isset($Unit) ? $Unit->status_kepemilikan : '') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                    </select>
                    @error('status_kepemilikan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Kondisi*</label>
                    <select name="status_kondisi" class="form-select" required>
                        <option value="baik" {{ old('status_kondisi', isset($Unit) ? $Unit->status_kondisi : 'baik') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="perlu_maintenance" {{ old('status_kondisi', isset($Unit) ? $Unit->status_kondisi : '') == 'perlu_maintenance' ? 'selected' : '' }}>Perlu Maintenance</option>
                        <option value="rusak" {{ old('status_kondisi', isset($Unit) ? $Unit->status_kondisi : '') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>
                    @error('status_kondisi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status Operasional*</label>
                    <select name="status_operasional" class="form-select" required>
                        <option value="operasional" {{ old('status_operasional', isset($Unit) ? $Unit->status_operasional : '') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                        <option value="maintenance" {{ old('status_operasional', isset($Unit) ? $Unit->status_operasional : '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="standby" {{ old('status_operasional', isset($Unit) ? $Unit->status_operasional : 'standby') == 'standby' ? 'selected' : '' }}>Standby</option>
                        <option value="tidak_aktif" {{ old('status_operasional', isset($Unit) ? $Unit->status_operasional : '') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status_operasional')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jam Operasi*</label>
                    <input type="number" name="jam_operasi" class="form-control" placeholder="0" min="0"
                           value="{{ old('jam_operasi', isset($Unit) ? $Unit->jam_operasi : '0') }}" required>
                    @error('jam_operasi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

                <!-- Upload Foto -->
            <h5 class="fw-semibold mb-3">Upload Foto Unit</h5>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label class="form-label">Foto Depan</label>
                    <input type="file" name="gambar_depan" class="form-control" accept=".jpg,.jpeg,.png" onchange="previewImage(this, 'preview-depan')">

                    <!-- Preview gambar -->
                    <div class="mt-2">
                        @if(old('gambar_depan') || (isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_depan))
                            <img id="preview-depan"
                                src="{{ old('gambar_depan') ? '#' : (isset($gambar_unit) && $gambar_unit->gambar_depan ? asset('storage/unit-images/' . basename($gambar_unit->gambar_depan)) : '#') }}"
                                alt="Preview Foto Depan"
                                class="img-thumbnail"
                                style="max-width: 150px; max-height: 150px; {{ old('gambar_depan') && !isset($gambar_unit) ? 'display: none;' : '' }}">
                        @else
                            <img id="preview-depan" src="#" alt="Preview Foto Depan" class="img-thumbnail" style="max-width: 150px; max-height: 150px; display: none;">
                        @endif
                    </div>

                    @if(isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_depan)
                        <small class="text-muted">File saat ini: {{ basename($gambar_unit->gambar_depan) }}</small>
                    @endif
                    @error('gambar_depan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Foto Belakang</label>
                    <input type="file" name="gambar_belakang" class="form-control" accept=".jpg,.jpeg,.png" onchange="previewImage(this, 'preview-belakang')">

                    <!-- Preview gambar -->
                    <div class="mt-2">
                        @if(old('gambar_belakang') || (isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_belakang))
                            <img id="preview-belakang"
                                src="{{ old('gambar_belakang') ? '#' : (isset($gambar_unit) && $gambar_unit->gambar_belakang ? asset('storage/unit-images/' . basename($gambar_unit->gambar_belakang)) : '#') }}"
                                alt="Preview Foto Belakang"
                                class="img-thumbnail"
                                style="max-width: 150px; max-height: 150px; {{ old('gambar_belakang') && !isset($gambar_unit) ? 'display: none;' : '' }}">
                        @else
                            <img id="preview-belakang" src="#" alt="Preview Foto Belakang" class="img-thumbnail" style="max-width: 150px; max-height: 150px; display: none;">
                        @endif
                    </div>

                    @if(isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_belakang)
                        <small class="text-muted">File saat ini: {{ basename($gambar_unit->gambar_belakang) }}</small>
                    @endif
                    @error('gambar_belakang')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Foto Kiri</label>
                    <input type="file" name="gambar_kiri" class="form-control" accept=".jpg,.jpeg,.png" onchange="previewImage(this, 'preview-kiri')">

                    <!-- Preview gambar -->
                    <div class="mt-2">
                        @if(old('gambar_kiri') || (isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_kiri))
                            <img id="preview-kiri"
                                src="{{ old('gambar_kiri') ? '#' : (isset($gambar_unit) && $gambar_unit->gambar_kiri ? asset('storage/unit-images/' . basename($gambar_unit->gambar_kiri)) : '#') }}"
                                alt="Preview Foto Kiri"
                                class="img-thumbnail"
                                style="max-width: 150px; max-height: 150px; {{ old('gambar_kiri') && !isset($gambar_unit) ? 'display: none;' : '' }}">
                        @else
                            <img id="preview-kiri" src="#" alt="Preview Foto Kiri" class="img-thumbnail" style="max-width: 150px; max-height: 150px; display: none;">
                        @endif
                    </div>

                    @if(isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_kiri)
                        <small class="text-muted">File saat ini: {{ basename($gambar_unit->gambar_kiri) }}</small>
                    @endif
                    @error('gambar_kiri')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Foto Kanan</label>
                    <input type="file" name="gambar_kanan" class="form-control" accept=".jpg,.jpeg,.png" onchange="previewImage(this, 'preview-kanan')">

                    <!-- Preview gambar -->
                    <div class="mt-2">
                        @if(old('gambar_kanan') || (isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_kanan))
                            <img id="preview-kanan"
                                src="{{ old('gambar_kanan') ? '#' : (isset($gambar_unit) && $gambar_unit->gambar_kanan ? asset('storage/' . $gambar_unit->gambar_kanan) : '#') }}"
                                alt="Preview Foto Kanan"
                                class="img-thumbnail"
                                style="max-width: 150px; max-height: 150px; {{ old('gambar_kanan') && !isset($gambar_unit) ? 'display: none;' : '' }}">
                        @else
                            <img id="preview-kanan" src="#" alt="Preview Foto Kanan" class="img-thumbnail" style="max-width: 150px; max-height: 150px; display: none;">
                        @endif
                    </div>

                    @if(isset($gambar_unit) && $gambar_unit && $gambar_unit->gambar_kanan)
                        <small class="text-muted">File saat ini: {{ basename($gambar_unit->gambar_kanan) }}</small>
                    @endif
                    @error('gambar_kanan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Status Aktif -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="hidden" name="is_active" value="1" id="is_active"
                               {{ old('is_active', isset($Unit) ? $Unit->is_active : '1') == '1' ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('data_Unit') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ isset($Unit) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Jenis Unit Modal -->
<div class="modal fade" id="jenisUnitModal" tabindex="-1" aria-labelledby="jenisUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jenisUnitModalLabel">Tambah Jenis Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="jenisUnitForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_jenis_unit" class="form-label">Nama Jenis Unit</label>
                        <input type="text" class="form-control" id="nama_jenis_unit" name="nama_jenis_unit" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pemilik Modal -->
<div class="modal fade" id="pemilikModal" tabindex="-1" aria-labelledby="pemilikModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pemilikModalLabel">Tambah Pemilik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="pemilikForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    //funsgi untuk menampilkan image untuk update
    function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        preview.src = '#';
    }
}
    // Fungsi untuk menampilkan loading pada tombol
    function showLoading(form) {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        button.disabled = true;
        spinner.classList.remove('d-none');
    }

    // Fungsi untuk menyembunyikan loading pada tombol
    function hideLoading(form) {
        const button = form.querySelector('button[type="submit"]');
        const spinner = button.querySelector('.spinner-border');
        button.disabled = false;
        spinner.classList.add('d-none');
    }

    // Fungsi untuk menampilkan pesan toast
    function showToast(message, type = 'success') {
        // Buat elemen toast jika belum ada
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

        // Hapus toast setelah selesai
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Script untuk menangani form submit jenis unit
    document.getElementById('jenisUnitForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showLoading(this);

        const formData = new FormData(this);

        fetch('{{ route("jenis-unit.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading(this);

            if (data.success) {
                // Tambahkan jenis unit baru ke dropdown
                const select = document.getElementById('jenisUnitSelect');
                const option = document.createElement('option');
                option.value = data.jenisUnit.id_jenis_unit;
                option.textContent = data.jenisUnit.nama_jenis_unit;
                option.selected = true;
                select.appendChild(option);

                // Reset form dan tutup modal
                this.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('jenisUnitModal'));
                modal.hide();

                // Tampilkan pesan sukses
                showToast('Jenis unit berhasil ditambahkan!');
            } else {
                showToast('Terjadi kesalahan saat menambahkan jenis unit.', 'error');
            }
        })
        .catch(error => {
            hideLoading(this);
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menambahkan jenis unit.', 'error');
        });
    });


    // Format input nomor polisi
    document.querySelector('input[name="no_polisi"]').addEventListener('input', function() {
        let value = this.value.toUpperCase();
        // Basic formatting untuk nomor polisi Indonesia
        this.value = value;
    });

    // Format input kode unit
    document.querySelector('input[name="kode_unit"]').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Format input nomor rangka dan nomor mesin
    document.querySelector('input[name="no_rangka"]').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    document.querySelector('input[name="no_mesin"]').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Validasi file upload untuk gambar
    function validateImageFile(input, maxSize = 2048) { // 2MB default
        const file = input.files[0];
        if (file) {
            const fileSize = Math.round((file.size / 1024));
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];

            if (!allowedTypes.includes(file.type)) {
                showToast('Hanya file JPG atau PNG yang diizinkan.', 'error');
                input.value = '';
                return false;
            }

            if (fileSize > maxSize) {
                showToast(`Ukuran file tidak boleh lebih dari ${maxSize/1024}MB.`, 'error');
                input.value = '';
                return false;
            }
        }
        return true;
    }

    // Event listener untuk validasi file upload
    document.querySelector('input[name="gambar_depan"]').addEventListener('change', function() {
        validateImageFile(this);
    });

    document.querySelector('input[name="gambar_belakang"]').addEventListener('change', function() {
        validateImageFile(this);
    });

    document.querySelector('input[name="gambar_kiri"]').addEventListener('change', function() {
        validateImageFile(this);
    });

    document.querySelector('input[name="gambar_kanan"]').addEventListener('change', function() {
        validateImageFile(this);
    });

    // Validasi input numeric untuk jam operasi
    document.querySelector('input[name="jam_operasi"]').addEventListener('input', function() {
        let value = parseInt(this.value);
        let maxValue = 999999; // Reasonable max for operating hours

        if (value > maxValue) {
            this.value = maxValue;
            showToast('Nilai maksimal jam operasi adalah 999,999 jam', 'error');
        }
    });

    // Validasi tahun pembuatan
    document.querySelector('input[name="tahun_pembuatan"]').addEventListener('input', function() {
        const currentYear = new Date().getFullYear();
        const value = parseInt(this.value);

        if (value > currentYear) {
            this.value = currentYear;
            showToast('Tahun pembuatan tidak boleh lebih dari tahun sekarang', 'error');
        }
    });
</script>
@endsection
