@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-4">
        {{ isset($karyawan) ? 'Edit Data Karyawan' : 'Tambah Data Karyawan' }}
    </h4>
    <div class="card p-4">
        <!-- Form yang fleksibel untuk insert dan edit -->
        <form action="{{ isset($karyawan) ? route('karyawan_update', $karyawan->id_karyawan) : route('karyawan_store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($karyawan))
                @method('PUT')
            @endif

            <!-- Data Pribadi -->
            <h5 class="fw-semibold mb-3">Data Pribadi</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Nama Lengkap*</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama lengkap"
                           value="{{ old('nama_lengkap', isset($karyawan) ? $karyawan->nama_lengkap : '') }}" required>
                    @error('nama_lengkap')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir*</label>
                    <input type="date" name="tanggal_lahir" class="form-control"
                           value="{{ old('tanggal_lahir', isset($karyawan) ? $karyawan->tanggal_lahir : '') }}" required>
                    @error('tanggal_lahir')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nomor HP/Wa*</label>
                    <input type="text" name="nomor_hp" class="form-control" placeholder="08xxxxxxxxx"
                           value="{{ old('nomor_hp', isset($karyawan) ? $karyawan->no_handphone : '') }}" required>
                    @error('nomor_hp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">NIK*</label>
                    <input type="text" name="no_nik" class="form-control" placeholder="NIK"
                           value="{{ old('no_nik', isset($karyawan) ? $karyawan->no_nik : '') }}" required>
                    @error('no_nik')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Scan KTP{{ isset($karyawan) ? '' : '*' }}</label>
                    <input type="file" name="scan_ktp" class="form-control"
                           {{ isset($karyawan) ? '' : 'required' }} accept=".jpg,.jpeg,.png,.pdf">
                    @if(isset($karyawan) && $karyawan->scan_ktp)
                        <small class="text-muted">File saat ini: {{ basename($karyawan->scan_ktp) }}</small>
                    @endif
                    @error('scan_ktp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Surat Lamaran{{ isset($karyawan) ? '' : '*' }}</label>
                    <input type="file" name="surat_lamaran" class="form-control"
                           {{ isset($karyawan) ? '' : 'required' }} accept=".jpg,.jpeg,.png,.pdf">
                    @if(isset($karyawan) && $karyawan->surat_lamaran)
                        <small class="text-muted">File saat ini: {{ basename($karyawan->surat_lamaran) }}</small>
                    @endif
                    @error('surat_lamaran')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data Pekerjaan -->
            <h5 class="fw-semibold mb-3">Data Pekerjaan</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Posisi*</label>
                    <div class="input-group">
                        <select name="id_posisi" class="form-select" id="posisiSelect" required>
                            <option value="" disabled {{ !old('id_posisi') && !isset($karyawan) ? 'selected' : '' }}>Pilih posisi</option>
                            @if(isset($posisi))
                                @foreach($posisi as $p)
                                    <option value="{{ $p->id_posisi }}"
                                            {{ old('id_posisi', isset($karyawan) ? $karyawan->id_posisi : '') == $p->id_posisi ? 'selected' : '' }}>
                                        {{ $p->nama_posisi }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#posisiModal">+</button>
                    </div>
                    @error('id_posisi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Departemen*</label>
                    <div class="input-group">
                        <select name="id_departemen" class="form-select" id="departemenSelect" required>
                            <option value="" disabled {{ !old('id_departemen') && !isset($karyawan) ? 'selected' : '' }}>Pilih departemen</option>
                            @if(isset($departemen))
                                @foreach($departemen as $d)
                                    <option value="{{ $d->id_departemen }}"
                                            {{ old('id_departemen', isset($karyawan) ? $karyawan->id_departemen : '') == $d->id_departemen ? 'selected' : '' }}>
                                        {{ $d->nama_departemen }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#departemenModal">+</button>
                    </div>
                    @error('id_departemen')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Bergabung*</label>
                    <input type="date" name="tanggal_bergabung" class="form-control"
                           value="{{ old('tanggal_bergabung', isset($karyawan) ? $karyawan->tanggal_bergabung : '') }}" required>
                    @error('tanggal_bergabung')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Data Penggajian -->
            <h5 class="fw-semibold mb-3">Data Penggajian</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Gaji*</label>
                    <input type="number" name="gaji" class="form-control" placeholder="0"
                           value="{{ old('gaji', isset($karyawan) ? $karyawan->Gaji : '') }}" step="1" min="0" required>
                    @error('gaji')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tunjangan</label>
                    <input type="number" name="tunjangan" class="form-control" placeholder="0"
                           value="{{ old('tunjangan', isset($karyawan) ? $karyawan->Tunjangan : '') }}" step="1" min="0">
                    @error('tunjangan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Insentif</label>
                    <input type="number" name="insentif" class="form-control" placeholder="0"
                           value="{{ old('insentif', isset($karyawan) ? $karyawan->Intensif : '') }}" step="1" min="0">
                    @error('insentif')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('karyawan_data') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> {{ isset($karyawan) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Posisi Modal -->
<div class="modal fade" id="posisiModal" tabindex="-1" aria-labelledby="posisiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="posisiModalLabel">Tambah Posisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="posisiForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_posisi" class="form-label">Nama Posisi</label>
                        <input type="text" class="form-control" id="nama_posisi" name="nama_posisi" required>
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

<!-- Departemen Modal -->
<div class="modal fade" id="departemenModal" tabindex="-1" aria-labelledby="departemenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departemenModalLabel">Tambah Departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="departemenForm">
                    @csrf
                    <div class="mb-3">
                        <label for="nama_departemen" class="form-label">Nama Departemen</label>
                        <input type="text" class="form-control" id="nama_departemen" name="nama_departemen" required>
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

@push('scripts')
<script>
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

    // Script untuk menangani form submit posisi
    document.getElementById('posisiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showLoading(this);

        const formData = new FormData(this);

        fetch('{{ route("posisi.store") }}', {
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
                // Tambahkan posisi baru ke dropdown
                const select = document.getElementById('posisiSelect');
                const option = document.createElement('option');
                option.value = data.posisi.id_posisi;
                option.textContent = data.posisi.nama_posisi;
                option.selected = true;
                select.appendChild(option);

                // Reset form dan tutup modal
                this.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('posisiModal'));
                modal.hide();

                // Tampilkan pesan sukses
                showToast('Posisi berhasil ditambahkan!');
            } else {
                showToast('Terjadi kesalahan saat menambahkan posisi.', 'error');
            }
        })
        .catch(error => {
            hideLoading(this);
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menambahkan posisi.', 'error');
        });
    });

    // Script untuk menangani form submit departemen
    document.getElementById('departemenForm').addEventListener('submit', function(e) {
        e.preventDefault();
        showLoading(this);

        const formData = new FormData(this);

        fetch('{{ route("departemen.store") }}', {
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
                // Tambahkan departemen baru ke dropdown
                const select = document.getElementById('departemenSelect');
                const option = document.createElement('option');
                option.value = data.departemen.id_departemen;
                option.textContent = data.departemen.nama_departemen;
                option.selected = true;
                select.appendChild(option);

                // Reset form dan tutup modal
                this.reset();
                const modal = bootstrap.Modal.getInstance(document.getElementById('departemenModal'));
                modal.hide();

                // Tampilkan pesan sukses
                showToast('Departemen berhasil ditambahkan!');
            } else {
                showToast('Terjadi kesalahan saat menambahkan departemen.', 'error');
            }
        })
        .catch(error => {
            hideLoading(this);
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menambahkan departemen.', 'error');
        });
    });

    // Format input nomor HP
    document.querySelector('input[name="nomor_hp"]').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, ''); // Hapus semua karakter non-digit

        if (value.startsWith('0')) {
            value = '62' + value.substring(1);
        } else if (!value.startsWith('62')) {
            value = '62' + value;
        }

        this.value = value;
    });

    // Format input NIK (hanya angka, maksimal 16 digit)
    document.querySelector('input[name="no_nik"]').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 16);
    });

    // Format input gaji dengan pemisah ribuan
    function formatRupiah(input) {
        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            this.value = value;
        });
    }

    formatRupiah(document.querySelector('input[name="gaji"]'));
    formatRupiah(document.querySelector('input[name="tunjangan"]'));
    formatRupiah(document.querySelector('input[name="insentif"]'));

    // Validasi file upload
    function validateFile(input, maxSize = 2048) { // 2MB default
        const file = input.files[0];
        if (file) {
            const fileSize = Math.round((file.size / 1024));
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];

            if (!allowedTypes.includes(file.type)) {
                showToast('Hanya file JPG, PNG, atau PDF yang diizinkan.', 'error');
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

    document.querySelector('input[name="scan_ktp"]').addEventListener('change', function() {
        validateFile(this);
    });

    document.querySelector('input[name="surat_lamaran"]').addEventListener('change', function() {
        validateFile(this);
    });
</script>
@endpush
