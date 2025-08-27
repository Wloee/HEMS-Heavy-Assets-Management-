@extends('layouts.app')

@section('title', 'Permintaan Sparepart')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1d4ed8;
        --success-color: #16a34a;
        --danger-color: #dc2626;
        --warning-color: #d97706;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    .main-card {
        border: none;
        box-shadow: var(--shadow-lg);
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        padding: 1.5rem 2rem;
        border: none;
    }

    .card-body-custom {
        padding: 2rem;
        background: white;
    }

    .section-divider {
        margin: 2rem 0;
        border: none;
        height: 1px;
        background: linear-gradient(to right, transparent, var(--gray-300), transparent);
    }

    .form-section {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .item-card {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        position: relative;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .item-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }

    .item-header {
        background: var(--gray-50);
        margin: -1.5rem -1.5rem 1rem -1.5rem;
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .item-number {
        background: var(--primary-color);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .btn-remove-item {
        position: absolute;
        top: 0.75rem;
        right: 1rem;
        z-index: 10;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-control, .form-select {
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary-custom:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .btn-add-item {
        background: linear-gradient(135deg, var(--success-color) 0%, #15803d 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .btn-add-item:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
        color: white;
    }

    .required::after {
        content: " *";
        color: var(--danger-color);
        font-weight: bold;
    }

    .empty-state {
        padding: 3rem 2rem;
        text-align: center;
        color: var(--gray-300);
        border: 1px dashed var(--gray-300);
        border-radius: 8px;
        background: var(--gray-50);
    }

    .stok-badge {
        background: #dcfce7;
        color: #166534;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .alert-custom {
        border: none;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
    }

    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        min-height: calc(2.25rem + 2px);
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    @media (max-width: 768px) {
        .card-body-custom {
            padding: 1.5rem;
        }

        .item-card {
            padding: 1rem;
        }

        .form-section {
            padding: 1rem;
        }

        .item-header {
            margin: -1rem -1rem 1rem -1rem;
        }
    }

    @media (max-width: 576px) {
        .btn-primary-custom, .btn-add-item, .btn-outline-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex.justify-content-end.gap-3 {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card main-card">
                <div class="card-header card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1 text-white fw-bold">
                                <i class="fas fa-clipboard-list me-2"></i>Form Permintaan Sparepart
                            </h3>
                            <p class="mb-0 text-white-50">Ajukan permintaan sparepart yang dibutuhkan</p>
                        </div>
                        <a href="{{ route('permintaan.index') }}" class="btn btn-light btn-sm px-3">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body card-body-custom">
                    @if($errors->any())
                        <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 fw-bold">Terdapat kesalahan dalam form:</h6>
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('permintaan.store') }}" method="POST" id="form-permintaan">
                        @csrf

                        <!-- Informasi Umum -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Informasi Permintaan
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_permintaan" class="form-label required">Tanggal Permintaan</label>
                                        <input type="date" class="form-control @error('tanggal_permintaan') is-invalid @enderror"
                                               id="tanggal_permintaan" name="tanggal_permintaan"
                                               value="{{ old('tanggal_permintaan', date('Y-m-d')) }}" required>
                                        @error('tanggal_permintaan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <textarea class="form-control @error('keterangan') is-invalid @enderror"
                                                  id="keterangan" name="keterangan" rows="3"
                                                  placeholder="Masukkan keterangan atau alasan permintaan (opsional)">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="section-divider">

                        <!-- Detail Permintaan -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="section-title mb-0">
                                <i class="fas fa-list"></i>
                                Detail Permintaan
                            </h5>
                            <button type="button" class="btn btn-add-item" id="btn-tambah-item">
                                <i class="fas fa-plus me-1"></i>Tambah Item
                            </button>
                        </div>

                        <div id="detail-permintaan-container">
                            <div class="empty-state" id="empty-state">
                                <i class="fas fa-box-open fa-3x mb-3"></i>
                                <p class="text-muted mb-0">Belum ada item yang ditambahkan.<br>Klik tombol "Tambah Item" untuk menambah sparepart.</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-3">
                                    <a href="{{ route('permintaan.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-1"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary-custom px-4" id="btn-simpan">
                                        <i class="fas fa-save me-1"></i>Simpan Permintaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template Item Detail -->
<template id="item-template">
    <div class="item-card" data-index="">
        <div class="item-header">
            <div class="d-flex align-items-center">
                <div class="item-number"></div>
                <span class="ms-2 fw-medium">Item Sparepart</span>
            </div>
            <button type="button" class="btn btn-danger btn-sm btn-remove-item">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                    <label class="form-label required">Jenis Sparepart</label>
                   <input type="text" class="form-control sparepart-select @error('items[INDEX][sparepart_id]') is-invalid @enderror"
                           name="items[INDEX][nama_sparepart]" required>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="mb-3">
                    <label class="form-label required">Kode Sparepart</label>
                   <input type="text" class="form-control sparepart-select @error('items[INDEX][sparepart_id]') is-invalid @enderror"
                           name="items[INDEX][kode_sparepart]" required>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <div class="mb-3">
                    <label class="form-label required">Jumlah</label>
                    <input type="number" class="form-control jumlah-input @error('items[INDEX][jumlah]') is-invalid @enderror"
                           name="items[INDEX][jumlah]" min="1" step="1" required placeholder="0">
                    @error('items[INDEX][jumlah]')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="mb-3">
                    <label class="form-label required">Satuan</label>
                    <select class="form-select satuan-select @error('items[INDEX][satuan_id]') is-invalid @enderror"
                            name="items[INDEX][satuan_id]" required>
                        <option value="">- Pilih Satuan -</option>
                        @foreach($satuanList as $satuan)
                            <option value="{{ $satuan->id_satuan }}">{{ $satuan->nama_satuan }}</option>
                        @endforeach
                    </select>
                    @error('items[INDEX][satuan_id]')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="mb-3">
                    <label class="form-label">Stok Info</label>
                    <div class="form-control-plaintext">
                        <span class="stok-badge stok-info">Pilih sparepart</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let itemIndex = 0;

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || 'Pilih...';
        }
    });

    // Update item numbers
    function updateItemNumbers() {
        $('.item-card').each(function(index) {
            $(this).find('.item-number').text(index + 1);
        });
    }

    // Initialize sparepart select2
    function initSparepartSelect($item) {
        $item.find('.sparepart-select').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih Sparepart'
        });
    }

    // Tambah item baru
    $('#btn-tambah-item').click(function() {
        $('#empty-state').hide();

        let template = $('#item-template').html();
        template = template.replace(/INDEX/g, itemIndex);

        let newItem = $(template);
        newItem.attr('data-index', itemIndex);

        $('#detail-permintaan-container').append(newItem);

        // Initialize select2 untuk item baru
        initSparepartSelect(newItem);

        itemIndex++;
        updateItemNumbers();

        // Animate new item
        newItem.hide().slideDown(300);
    });

    // Hapus item
    $(document).on('click', '.btn-remove-item', function() {
        let $item = $(this).closest('.item-card');
        $item.slideUp(300, function() {
            $(this).remove();
            updateItemNumbers();

            if ($('#detail-permintaan-container .item-card').length === 0) {
                $('#empty-state').slideDown(300);
            }
        });
    });

    // Event ketika sparepart dipilih
    $(document).on('change', '.sparepart-select', function() {
        let $row = $(this).closest('.item-card');
        let selectedOption = $(this).find('option:selected');
        let stok = selectedOption.data('stok') || 0;
        let satuan = selectedOption.data('satuan') || '';

        // Update info stok
        if ($(this).val()) {
            $row.find('.stok-info').removeClass('stok-badge').addClass('stok-badge')
                .text('Stok: ' + stok + ' ' + satuan);

            // Set default satuan jika ada
            if (satuan) {
                $row.find('.satuan-select option').each(function() {
                    if ($(this).text() === satuan) {
                        $row.find('.satuan-select').val($(this).val());
                        return false;
                    }
                });
            }
        } else {
            $row.find('.stok-info').text('Pilih sparepart');
        }
    });

    // Validasi form sebelum submit
    $('#form-permintaan').submit(function(e) {
        let itemCount = $('#detail-permintaan-container .item-card').length;

        if (itemCount === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Harap tambahkan minimal satu item sparepart!',
                confirmButtonColor: '#2563eb'
            });
            return false;
        }

        let isValid = true;
        let errorMessage = '';

        $('.item-card').each(function(index) {
            let sparepart = $(this).find('.sparepart-select').val();
            let jumlah = parseInt($(this).find('.jumlah-input').val());
            let satuan = $(this).find('.satuan-select').val();

            if (!sparepart || !jumlah || !satuan || jumlah <= 0) {
                isValid = false;
                errorMessage = `Item ke-${index + 1} belum lengkap atau tidak valid!`;
                return false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Valid',
                text: errorMessage,
                confirmButtonColor: '#2563eb'
            });
            return false;
        }

        $('#btn-simpan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');
        Swal.fire({
            icon: 'info',
            title: 'Menyimpan Data...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
    });

    // Add first item automatically
    $('#btn-tambah-item').click();
});
</script>
@endsection
