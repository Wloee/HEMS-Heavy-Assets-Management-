@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-4">
        {{ isset($user) ? 'Edit User' : 'Tambah User' }}
    </h4>

    <div class="card p-4">
        <form action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <!-- Data User -->
            <h5 class="fw-semibold mb-3">Informasi User</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama User*</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           placeholder="Masukkan nama user"
                           value="{{ old('name', isset($user) ? $user->name : '') }}"
                           required maxlength="50">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Maksimal 50 karakter, hanya huruf, angka, spasi, dan tanda baca dasar</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email*</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder="user@example.com"
                           value="{{ old('email', isset($user) ? $user->email : '') }}"
                           required maxlength="100">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password{{ isset($user) ? '' : '*' }}</label>
                    <div class="input-group">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 8 karakter' }}"
                               {{ isset($user) ? '' : 'required' }} minlength="8" id="password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Minimal 8 karakter, harus ada huruf besar, kecil, dan angka</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Konfirmasi Password{{ isset($user) ? '' : '*' }}</label>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Ulangi password"
                           {{ isset($user) ? '' : 'required' }} minlength="8">
                </div>
            </div>

            <!-- Data Role & Status -->
            <h5 class="fw-semibold mb-3 mt-4">Role & Status</h5>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Role*</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="" disabled {{ !old('role') && !isset($user) ? 'selected' : '' }}>Pilih Role</option>
                        @foreach(\App\Models\User::getRoles() as $key => $label)
                            <option value="{{ $key }}"
                                    {{ old('role', isset($user) ? $user->role : '') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Karyawan (Opsional)</label>
                    <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror">
                        <option value="">Tidak dikaitkan dengan karyawan</option>
                        @if(isset($karyawans))
                            @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id_karyawan }}"
                                        {{ old('karyawan_id', isset($user) ? $user->karyawan_id : '') == $karyawan->id_karyawan ? 'selected' : '' }}>
                                    {{ $karyawan->nama_karyawan }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('karyawan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Opsional: Kaitkan user dengan data karyawan</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                               id="is_active"
                               {{ old('is_active', isset($user) ? $user->is_active : true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <span class="badge bg-success" id="statusBadge">Aktif</span>
                        </label>
                    </div>
                    <small class="text-muted">User aktif dapat login ke sistem</small>
                </div>
            </div>

            <!-- Info Tambahan untuk Edit -->
            @if(isset($user))
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle"></i> Informasi User
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Dibuat:</strong><br>
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Terakhir Diupdate:</strong><br>
                                    {{ $user->updated_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Login Terakhir:</strong><br>
                                    {{ $user->last_login_formatted }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('user.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <div>
                    @if(isset($user))
                        <button type="button" class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                    @endif
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> {{ isset($user) ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reset Password Modal -->
@if(isset($user))
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin reset password untuk user <strong>{{ $user->name }}</strong>?</p>
                <p class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Password akan direset ke: <code>password123</code>
                </p>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });

    // Update status badge
    document.getElementById('is_active').addEventListener('change', function() {
        const statusBadge = document.getElementById('statusBadge');
        if (this.checked) {
            statusBadge.textContent = 'Aktif';
            statusBadge.className = 'badge bg-success';
        } else {
            statusBadge.textContent = 'Nonaktif';
            statusBadge.className = 'badge bg-danger';
        }
    });

    // Set initial badge state
    document.addEventListener('DOMContentLoaded', function() {
        const isActive = document.getElementById('is_active');
        const statusBadge = document.getElementById('statusBadge');

        if (!isActive.checked) {
            statusBadge.textContent = 'Nonaktif';
            statusBadge.className = 'badge bg-danger';
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]').value;
        const passwordConfirmation = document.querySelector('input[name="password_confirmation"]').value;

        // Check if password is filled but confirmation is not (for edit mode)
        if (password && !passwordConfirmation) {
            e.preventDefault();
            alert('Harap konfirmasi password yang baru.');
            return false;
        }

        // Check if passwords match
        if (password && password !== passwordConfirmation) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok.');
            return false;
        }

        // Check password strength (if new password is provided)
        if (password) {
            const hasLower = /[a-z]/.test(password);
            const hasUpper = /[A-Z]/.test(password);
            const hasNumber = /\d/.test(password);
            const minLength = password.length >= 8;

            if (!hasLower || !hasUpper || !hasNumber || !minLength) {
                e.preventDefault();
                alert('Password harus mengandung minimal 8 karakter dengan kombinasi huruf besar, huruf kecil, dan angka.');
                return false;
            }
        }
    });

    // Real-time validation feedback
    const nameInput = document.querySelector('input[name="name"]');
    const emailInput = document.querySelector('input[name="email"]');
    const passwordInput = document.querySelector('input[name="password"]');

    // Name validation
    nameInput.addEventListener('input', function() {
        const value = this.value;
        const regex = /^[a-zA-Z0-9\s\-_.]+$/;

        if (value && !regex.test(value)) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Nama hanya boleh mengandung huruf, angka, spasi, dan tanda baca dasar.';
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Email validation
    emailInput.addEventListener('input', function() {
        const value = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (value && !emailRegex.test(value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const value = this.value;
        if (!value) return;

        const hasLower = /[a-z]/.test(value);
        const hasUpper = /[A-Z]/.test(value);
        const hasNumber = /\d/.test(value);
        const minLength = value.length >= 8;

        let strength = 0;
        if (hasLower) strength++;
        if (hasUpper) strength++;
        if (hasNumber) strength++;
        if (minLength) strength++;

        // Remove existing strength indicator
        const existingIndicator = this.parentNode.parentNode.querySelector('.password-strength');
        if (existingIndicator) {
            existingIndicator.remove();
        }

        // Add strength indicator
        const strengthDiv = document.createElement('div');
        strengthDiv.className = 'password-strength mt-1';

        let strengthText = '';
        let strengthClass = '';

        switch(strength) {
            case 1:
            case 2:
                strengthText = 'Lemah';
                strengthClass = 'text-danger';
                break;
            case 3:
                strengthText = 'Sedang';
                strengthClass = 'text-warning';
                break;
            case 4:
                strengthText = 'Kuat';
                strengthClass = 'text-success';
                break;
        }

        strengthDiv.innerHTML = `<small class="${strengthClass}">Kekuatan password: ${strengthText}</small>`;
        this.parentNode.parentNode.appendChild(strengthDiv);
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

    // Show success/error messages
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
</script>
@endsection
