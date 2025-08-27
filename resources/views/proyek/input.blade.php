@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section with Gradient -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="header-card position-relative overflow-hidden">
                <div class="gradient-bg"></div>
                <div class="position-relative z-index-2 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="text-white fw-bold mb-2 display-6">
                                <i class="fas fa-project-diagram me-3"></i>
                                {{ isset($proyek) ? 'Edit Proyek' : 'Proyek Baru' }}
                            </h2>
                            <p class="text-white-50 mb-0 fs-5">
                                {{ isset($proyek) ? 'Perbarui informasi proyek' : 'Buat proyek baru dengan detail lengkap' }}
                            </p>
                        </div>
                        <div class="header-icon">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ isset($proyek) ? route('proyek.update', $proyek->id_proyek) : route('proyek.store') }}" method="POST" enctype="multipart/form-data" class="modern-form">
        @csrf
        @if(isset($proyek))
            @method('PUT')
        @endif

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Informasi Proyek Card -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <div class="icon-wrapper bg-primary">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h5 class="mb-0">Informasi Proyek</h5>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="nama_proyek"
                                           class="form-control modern-input @error('nama_proyek') is-invalid @enderror"
                                           id="nama_proyek"
                                           placeholder="Nama Proyek"
                                           value="{{ old('nama_proyek', isset($proyek) ? $proyek->nama_proyek : '') }}"
                                           required maxlength="255">
                                    <label for="nama_proyek">
                                        <i class="fas fa-signature me-2"></i>Nama Proyek*
                                    </label>
                                    @error('nama_proyek')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="nama_client"
                                           class="form-control modern-input @error('nama_client') is-invalid @enderror"
                                           id="nama_client"
                                           placeholder="Nama Client"
                                           value="{{ old('nama_client', isset($proyek) ? $proyek->nama_client : '') }}"
                                           required maxlength="255">
                                    <label for="nama_client">
                                        <i class="fas fa-user-tie me-2"></i>Nama Client*
                                    </label>
                                    @error('nama_client')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="tanggal_mulai"
                                           class="form-control modern-input @error('tanggal_mulai') is-invalid @enderror"
                                           id="tanggal_mulai"
                                           value="{{ old('tanggal_mulai', isset($proyek) ? $proyek->tanggal_mulai : '') }}"
                                           required>
                                    <label for="tanggal_mulai">
                                        <i class="fas fa-calendar-start me-2"></i>Tanggal Mulai*
                                    </label>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="tanggal_selesai_aktual"
                                           class="form-control modern-input @error('tanggal_selesai_aktual') is-invalid @enderror"
                                           id="tanggal_selesai_aktual"
                                           value="{{ old('tanggal_selesai_aktual', isset($proyek) ? $proyek->tanggal_selesai_aktual : '') }}">
                                    <label for="tanggal_selesai_aktual">
                                        <i class="fas fa-calendar-check me-2"></i>Tanggal Selesai Aktual
                                    </label>
                                    @error('tanggal_selesai_aktual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="status"
                                            class="form-select modern-input @error('status') is-invalid @enderror"
                                            id="status" required>
                                        <option value="draft" {{ old('status', isset($proyek) ? $proyek->status : 'draft') == 'draft' ? 'selected' : '' }}>
                                            Draft
                                        </option>
                                        <option value="aktif" {{ old('status', isset($proyek) ? $proyek->status : '') == 'aktif' ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="selesai" {{ old('status', isset($proyek) ? $proyek->status : '') == 'selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                        <option value="ditunda" {{ old('status', isset($proyek) ? $proyek->status : '') == 'ditunda' ? 'selected' : '' }}>
                                            Ditunda
                                        </option>
                                        <option value="dibatalkan" {{ old('status', isset($proyek) ? $proyek->status : '') == 'dibatalkan' ? 'selected' : '' }}>
                                            Dibatalkan
                                        </option>
                                    </select>
                                    <label for="status">
                                        <i class="fas fa-flag me-2"></i>Status*
                                    </label>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="deskripsi"
                                              class="form-control modern-textarea @error('deskripsi') is-invalid @enderror"
                                              id="deskripsi"
                                              placeholder="Deskripsi proyek..."
                                              style="height: 120px">{{ old('deskripsi', isset($proyek) ? $proyek->deskripsi : '') }}</textarea>
                                    <label for="deskripsi">
                                        <i class="fas fa-clipboard-list me-2"></i>Deskripsi Proyek
                                    </label>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="lokasi_proyek"
                                              class="form-control modern-textarea @error('lokasi_proyek') is-invalid @enderror"
                                              id="lokasi_proyek"
                                              placeholder="Lokasi proyek..."
                                              style="height: 100px">{{ old('lokasi_proyek', isset($proyek) ? $proyek->lokasi_proyek : '') }}</textarea>
                                    <label for="lokasi_proyek">
                                        <i class="fas fa-map-marker-alt me-2"></i>Lokasi Proyek*
                                    </label>
                                    @error('lokasi_proyek')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Biaya Pekerjaan Card -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <div class="icon-wrapper bg-success">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h5 class="mb-0">Detail Biaya Pekerjaan</h5>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-2">
                            <div class="col-md-4">
                            <div class="form-floating">
                                <select name="biaya_jenis_pekerjaan_id"
                                        class="form-select modern-input @error('biaya_jenis_pekerjaan_id') is-invalid @enderror"
                                        required>
                                    <option value="" disabled {{ !old('biaya_jenis_pekerjaan_id') ? 'selected' : '' }}>
                                        Pilih Jenis Pekerjaan
                                    </option>
                                    @foreach($jenis_pekerjaan as $jenis)
                                        <option value="{{ $jenis->id_jenis_pekerjaan }}"
                                                {{ old('biaya_jenis_pekerjaan_id', isset($biaya_proyek) && $biaya_proyek->first() ? $biaya_proyek->first()->jenis_pekerjaan_id : '') == $jenis->id_jenis_pekerjaan ? 'selected' : '' }}>
                                            {{ $jenis->nama_jenis_pekerjaan }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Jenis Pekerjaan*</label>
                                @error('biaya_jenis_pekerjaan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" name="biaya_total"
                                           class="form-control modern-input @error('biaya_total') is-invalid @enderror"
                                           placeholder="0" step="0.01" min="0"
                                           value="{{ old('biaya_total', isset($proyek) && $proyek->detailBiayaPekerjaan->first() ? $proyek->detailBiayaPekerjaan->first()->biaya_total : '') }}"
                                           required>
                                    <label>Biaya Total (Rp)*</label>
                                    @error('biaya_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <textarea name="biaya_deskripsi"
                                              class="form-control modern-textarea @error('biaya_deskripsi') is-invalid @enderror"
                                              style="height: 58px"
                                              placeholder="Deskripsi...">{{ old('biaya_deskripsi', isset($proyek) && $proyek->detailBiayaPekerjaan->first() ? $proyek->detailBiayaPekerjaan->first()->deskripsi : '') }}</textarea>
                                    <label>Deskripsi</label>
                                    @error('biaya_deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <div class="icon-wrapper bg-warning">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h5 class="mb-0">Invoice (Opsional)</h5>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="date" name="invoice_tanggal_invoice"
                                           class="form-control modern-input @error('invoice_tanggal_invoice') is-invalid @enderror"
                                           value="{{ old('invoice_tanggal_invoice', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->tanggal_invoice : '') }}">
                                    <label>Tanggal Invoice</label>
                                    @error('invoice_tanggal_invoice')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="date" name="invoice_tanggal_jatuh_tempo"
                                           class="form-control modern-input @error('invoice_tanggal_jatuh_tempo') is-invalid @enderror"
                                           value="{{ old('invoice_tanggal_jatuh_tempo', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->tanggal_jatuh_tempo : '') }}">
                                    <label>Jatuh Tempo</label>
                                    @error('invoice_tanggal_jatuh_tempo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" name="invoice_jumlah_tagihan"
                                           class="form-control modern-input @error('invoice_jumlah_tagihan') is-invalid @enderror"
                                           placeholder="0" step="0.01" min="0"
                                           value="{{ old('invoice_jumlah_tagihan', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->jumlah_tagihan : '') }}">
                                    <label>Jumlah Tagihan (Rp)</label>
                                    @error('invoice_jumlah_tagihan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <input type="number" name="sisa_piutang"
                                           class="form-control modern-input @error('invoice_sisa_piutang') is-invalid @enderror"
                                           placeholder="0" step="0.01" min="0"
                                           value="{{ old('invoice_sisa_piutang', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->sisa_piutang : '') }}">
                                    <label>Jumlah Tagihan (Rp)</label>
                                    @error('invoice_sisa_piutang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating">
                                    <select name="invoice_status"
                                            class="form-select modern-input @error('invoice_status') is-invalid @enderror">
                                        <option value="" {{ !old('invoice_status') && (!isset($proyek) || !$proyek->invoices->first()) ? 'selected' : '' }}>
                                            Pilih Status
                                        </option>
                                        <option value="draft" {{ old('invoice_status', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->status : '') == 'draft' ? 'selected' : '' }}>
                                            Draft
                                        </option>
                                        <option value="terkirim" {{ old('invoice_status', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->status : '') == 'terkirim' ? 'selected' : '' }}>
                                            Terkirim
                                        </option>
                                        <option value="dibayar_sebagian" {{ old('invoice_status', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->status : '') == 'dibayar_sebagian' ? 'selected' : '' }}>
                                            Dibayar Sebagian
                                        </option>
                                        <option value="lunas" {{ old('invoice_status', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->status : '') == 'lunas' ? 'selected' : '' }}>
                                            Lunas
                                        </option>
                                        <option value="jatuh_tempo" {{ old('invoice_status', isset($proyek) && $proyek->invoices->first() ? $proyek->invoices->first()->status : '') == 'jatuh_tempo' ? 'selected' : '' }}>
                                            Jatuh Tempo
                                        </option>
                                    </select>
                                    <label>Status</label>
                                    @error('invoice_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <!-- Action Card -->
                <div class="sticky-top" style="top: 2rem;">
                    <div class="form-card mb-4">
                        <div class="form-card-header">
                            <div class="icon-wrapper bg-info">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <h5 class="mb-0">Actions</h5>
                        </div>
                        <div class="form-card-body">
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-primary btn-lg modern-btn">
                                    <i class="fas fa-save me-2"></i>
                                    {{ isset($proyek) ? 'Update Proyek' : 'Simpan Proyek' }}
                                </button>
                                <a href="{{ route('proyek.index') }}" class="btn btn-outline-secondary btn-lg modern-btn">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge Card -->
                    <div class="form-card mb-4">
                        <div class="form-card-header">
                            <div class="icon-wrapper bg-secondary">
                                <i class="fas fa-flag"></i>
                            </div>
                            <h5 class="mb-0">Status Guide</h5>
                        </div>
                        <div class="form-card-body">
                            <div class="status-guide">
                                <div class="status-item">
                                    <span class="badge bg-secondary">Draft</span>
                                    <small>Proyek dalam tahap perencanaan</small>
                                </div>
                                <div class="status-item">
                                    <span class="badge bg-success">Aktif</span>
                                    <small>Proyek sedang berjalan</small>
                                </div>
                                <div class="status-item">
                                    <span class="badge bg-primary">Selesai</span>
                                    <small>Proyek telah selesai</small>
                                </div>
                                <div class="status-item">
                                    <span class="badge bg-warning">Ditunda</span>
                                    <small>Proyek sementara ditunda</small>
                                </div>
                                <div class="status-item">
                                    <span class="badge bg-danger">Dibatalkan</span>
                                    <small>Proyek dibatalkan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Card (for edit mode) -->
                    @if(isset($proyek))
                        <div class="form-card">
                            <div class="form-card-header">
                                <div class="icon-wrapper bg-secondary">
                                    <i class="fas fa-info"></i>
                                </div>
                                <h5 class="mb-0">Informasi</h5>
                            </div>
                            <div class="form-card-body">
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-plus-circle me-2 text-muted"></i>
                                        Dibuat
                                    </div>
                                    <div class="info-value">
                                        {{ $proyek->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-edit me-2 text-muted"></i>
                                        Terakhir Update
                                    </div>
                                    <div class="info-value">
                                        {{ $proyek->updated_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                                @if($proyek->detailBiayaPekerjaan->count() > 0)
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="fas fa-calculator me-2 text-muted"></i>
                                            Total Biaya
                                        </div>
                                        <div class="info-value text-success">
                                            Rp {{ number_format($proyek->detailBiayaPekerjaan->sum('biaya_total'), 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Custom Styles -->
<style>
.container-fluid {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.header-card {
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.gradient-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.header-icon {
    font-size: 4rem;
    color: rgba(255,255,255,0.3);
}

.z-index-2 {
    z-index: 2;
}

.form-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    border: 1px solid rgba(255,255,255,0.8);
    overflow: hidden;
    transition: all 0.3s ease;
}

.form-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 48px rgba(0,0,0,0.12);
}

.form-card-header {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #f8f9ff 0%, #e8ecff 100%);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.form-card-body {
    padding: 2rem;
}

.icon-wrapper {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.modern-input, .modern-textarea {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
}

.modern-input:focus, .modern-textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.form-floating > label {
    color: #6c757d;
    font-weight: 500;
}

.modern-btn {
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    position: relative;
    overflow: hidden;
}

.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

.btn-primary.modern-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.info-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
}

.info-value {
    font-weight: 600;
    color: #495057;
}

.status-guide .status-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.75rem;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-card {
    animation: fadeInUp 0.6s ease-out;
}

.form-card:nth-child(2) { animation-delay: 0.1s; }
.form-card:nth-child(3) { animation-delay: 0.2s; }
.form-card:nth-child(4) { animation-delay: 0.3s; }

/* Responsive */
@media (max-width: 768px) {
    .header-icon {
        display: none;
    }

    .form-card-body {
        padding: 1.5rem;
    }

    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
    // Form submission validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const tanggalMulai = document.querySelector('input[name="tanggal_mulai"]').value;
        const tanggalSelesai = document.querySelector('input[name="tanggal_selesai_aktual"]').value;
        const status = document.querySelector('select[name="status"]').value;
        const invoiceTanggalInvoice = document.querySelector('input[name="invoice_tanggal_invoice"]').value;
        const invoiceTanggalJatuhTempo = document.querySelector('input[name="invoice_tanggal_jatuh_tempo"]').value;
        const invoiceJumlahTagihan = document.querySelector('input[name="invoice_jumlah_tagihan"]').value;
        const invoiceStatus = document.querySelector('select[name="invoice_status"]').value;

        // Check if tanggal selesai is not before tanggal mulai
        if (tanggalMulai && tanggalSelesai && new Date(tanggalSelesai) < new Date(tanggalMulai)) {
            e.preventDefault();
            showToast('Tanggal selesai aktual tidak boleh sebelum tanggal mulai.', 'error');
            return false;
        }

        // Check if status selesai has tanggal selesai
        if (status === 'selesai' && !tanggalSelesai) {
            e.preventDefault();
            showToast('Tanggal selesai aktual wajib diisi untuk status "Selesai".', 'error');
            return false;
        }

        // Check invoice fields: all or none
        if (invoiceTanggalInvoice || invoiceTanggalJatuhTempo || invoiceJumlahTagihan || invoiceStatus) {
            if (!invoiceTanggalInvoice || !invoiceJumlahTagihan || !invoiceStatus) {
                e.preventDefault();
                showToast('Semua field invoice harus diisi jika salah satu diisi.', 'error');
                return false;
            }
            if (invoiceTanggalJatuhTempo && new Date(invoiceTanggalJatuhTempo) < new Date(invoiceTanggalInvoice)) {
                e.preventDefault();
                showToast('Tanggal jatuh tempo invoice tidak boleh sebelum tanggal invoice.', 'error');
                return false;
            }
        }

        // Show loading state
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.disabled = true;
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const iconClass = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';

        const toastHTML = `
            <div id="${toastId}" class="toast ${bgClass} text-white border-0 modern-toast" role="alert" style="min-width: 300px;">
                <div class="toast-header ${bgClass} text-white border-0">
                    <i class="fas ${iconClass} me-2"></i>
                    <strong class="me-auto">${type === 'success' ? 'Berhasil' : type === 'error' ? 'Error' : 'Info'}</strong>
                    <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body p-3">
                    ${message}
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            delay: 5000
        });
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Show success/error messages from session
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
</script>

<style>
.modern-toast {
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    backdrop-filter: blur(10px);
}
</style>
@endsection

