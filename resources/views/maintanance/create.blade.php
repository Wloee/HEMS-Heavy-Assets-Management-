@extends('layouts.app')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 4s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.3; }
    }

    .header .company-logo {
        font-size: 1.2em;
        font-weight: 600;
        margin-bottom: 15px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .header h1 {
        font-size: 2.8em;
        margin-bottom: 10px;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        position: relative;
        z-index: 1;
    }

    .header .subtitle {
        font-size: 1.1em;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        margin-bottom: 5px;
    }

    .header .department {
        font-size: 0.95em;
        opacity: 0.8;
        position: relative;
        z-index: 1;
    }

    .content {
        display: flex;
        min-height: 85vh;
        gap: 0;
    }

    .form-section {
        flex: 1;
        padding: 40px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        position: relative;
    }

    .form-section::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 1px;
        background: linear-gradient(to bottom, transparent, #e0e6ff, transparent);
    }

    .preview-section {
        flex: 1;
        padding: 40px;
        background: linear-gradient(135deg, #fafbff 0%, #f0f2ff 100%);
    }

    .section-title {
        font-size: 1.5em;
        color: #667eea;
        margin-bottom: 30px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title::before {
        content: '';
        width: 4px;
        height: 25px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 40px;
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }

    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.2s; }
    .form-group:nth-child(3) { animation-delay: 0.3s; }
    .form-group:nth-child(4) { animation-delay: 0.4s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }
    .form-group:nth-child(6) { animation-delay: 0.6s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
        from {
            opacity: 0;
            transform: translateY(20px);
        }
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #444;
        font-size: 0.95em;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e1e8ff;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
        font-family: inherit;
    }

    .btn-group {
        display: flex;
        gap: 15px;
        margin-top: 40px;
        justify-content: center;
    }

    .btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 35px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn:active {
        transform: translateY(-1px);
    }

    .btn-save {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    .btn-save:hover {
        box-shadow: 0 8px 25px rgba(23, 162, 184, 0.4);
    }

    .sparepart-section {
        border: 2px solid #e1e8ff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    }

    .sparepart-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
    }

    .add-sparepart-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }

    .sparepart-item {
        display: grid;
        grid-template-columns: 2fr 1fr 2fr 80px;
        gap: 15px;
        align-items: end;
        margin-bottom: 15px;
        padding: 15px;
        border: 1px solid #e1e8ff;
        border-radius: 8px;
        background: white;
    }

    .remove-sparepart-btn {
        background: #dc3545;
        color: white;
        border: none;
        padding: 8px;
        border-radius: 6px;
        cursor: pointer;
        height: fit-content;
    }

    /* Print Preview Styles */
    .print-preview {
        background: white;
        border: 3px solid #e1e8ff;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .print-preview:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .print-form {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        font-family: 'Arial', sans-serif;
    }

    .print-form td,
    .print-form th {
        border: 2px solid #333;
        padding: 12px;
        text-align: left;
        vertical-align: top;
    }

    .print-form .header-cell {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: bold;
        text-align: center;
        font-size: 18px;
        padding: 20px;
    }

    .print-form .field-label {
        background: linear-gradient(135deg, #f8f9ff 0%, #e8ebff 100%);
        font-weight: 600;
        width: 25%;
        color: #444;
    }

    .print-form .field-value {
        width: 25%;
        min-height: 35px;
        background: white;
    }

    .filled-value {
        color: #333;
        font-weight: 500;
    }

    .empty-value {
        color: #bbb;
        font-style: italic;
    }

    .instructions {
        margin-top: 30px;
        padding: 25px;
        background: linear-gradient(135deg, #e3f2fd 0%, #e8f5e8 100%);
        border-radius: 15px;
        border-left: 5px solid #667eea;
    }

    .instructions h3 {
        color: #667eea;
        margin-bottom: 15px;
        font-size: 1.2em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .instructions ul {
        color: #666;
        line-height: 1.8;
        list-style: none;
        padding: 0;
    }

    .instructions li {
        padding: 8px 0;
        position: relative;
        padding-left: 25px;
    }

    .instructions li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #28a745;
        font-weight: bold;
    }

    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    /* Print Styles */
    @media print {
        body {
            background: white;
            padding: 0;
        }

        .container {
            box-shadow: none;
            border-radius: 0;
            max-width: none;
        }

        .form-section {
            display: none;
        }

        .preview-section {
            padding: 0;
            background: white;
        }

        .btn, .btn-group, .section-title, .instructions {
            display: none !important;
        }

        .print-preview {
            border: none;
            padding: 0;
            box-shadow: none;
            margin: 0;
        }

        .print-form {
            font-size: 12px;
        }

        .print-form .header-cell {
            background: #333 !important;
            color: white !important;
        }

        .print-form .field-label {
            background: #f0f0f0 !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="header">
        <div class="company-logo">PT. NAMA PERUSAHAAN ANDA</div>
        <h1>FORM MAINTENANCE & KELUHAN UNIT</h1>
        <p class="subtitle">Sistem Pencatatan Keluhan dan Log Maintenance</p>
        <p class="department">Divisi Operations & Maintenance</p>
    </div>

    <div class="content">
        <!-- Form Input Section -->
        <div class="form-section">
            <h2 class="section-title">
                Input Data Keluhan & Maintenance
            </h2>

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding: 0; list-style: none;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            <form id="maintenanceForm" method="POST" action="{{ route('Maintanance.store') }}">
                @csrf

                <!-- Keluhan Section -->
                <h3 style="color: #667eea; margin-bottom: 20px; font-size: 1.3em;">Data Keluhan</h3>

                <div class="form-grid">
                    <!-- Site -->
                    <div class="form-group">
                        <label for="site">Site:</label>
                        <input type="text" id="site" name="site" value="{{ old('site') }}" required>
                    </div>

                    <!-- Proyek -->
                    <div class="form-group">
                        <label for="proyek_id">Proyek:</label>
                        <select id="proyek_id" name="proyek_id" required>
                            <option value="">Pilih Proyek</option>
                            @foreach($proyeks as $proyek)
                                <option value="{{ $proyek->id_proyek }}" {{ old('proyek_id') == $proyek->id_proyek ? 'selected' : '' }}>
                                    {{ $proyek->nama_proyek }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Unit -->
                    <div class="form-group">
                        <label for="unit_id">Unit:</label>
                        <select id="unit_id" name="unit_id" required>
                            <option value="">Pilih Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id_unit }}" {{ old('unit_id') == $unit->id_unit ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }} - {{ $unit->model }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Keluhan -->
                    <div class="form-group">
                        <label for="tanggal_keluhan">Tanggal Keluhan:</label>
                        <input type="date" id="tanggal_keluhan" name="tanggal_keluhan" value="{{ old('tanggal_keluhan', date('Y-m-d')) }}" required>
                    </div>

                    <!-- KM/HM -->
                    <div class="form-group">
                        <label for="km_hm">KM/HM:</label>
                        <input type="number" id="km_hm" name="km_hm" value="{{ old('km_hm') }}" min="0" required>
                    </div>
                </div>

                <!-- Deskripsi Keluhan -->
                <div class="form-group full-width">
                    <label for="deskripsi_keluhan">Deskripsi Keluhan:</label>
                    <textarea id="deskripsi_keluhan" name="deskripsi_keluhan" required placeholder="Deskripsikan keluhan unit secara detail...">{{ old('deskripsi_keluhan') }}</textarea>
                </div>

                <!-- Maintenance Section -->
                <h3 style="color: #667eea; margin: 30px 0 20px 0; font-size: 1.3em;">Data Maintenance</h3>

                <div class="form-grid">
                    <!-- Diagnosa -->
                    <div class="form-group full-width">
                        <label for="diagnosa">Diagnosa:</label>
                        <textarea id="diagnosa" name="diagnosa" required placeholder="Diagnosa kerusakan dan tindakan yang akan dilakukan...">{{ old('diagnosa') }}</textarea>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="form-group">
                        <label for="mulai_dikerjakan">Mulai Dikerjakan:</label>
                        <input type="date" id="mulai_dikerjakan" name="mulai_dikerjakan" value="{{ old('mulai_dikerjakan', date('Y-m-d')) }}" required>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="form-group">
                        <label for="selesai_dikerjakan">Selesai Dikerjakan:</label>
                        <input type="date" id="selesai_dikerjakan" name="selesai_dikerjakan" value="{{ old('selesai_dikerjakan') }}" required>
                    </div>

                    <!-- Operator -->
                    <div class="form-group">
                        <label for="operator_id">Operator:</label>
                        <select id="operator_id" name="operator_id" required>
                            <option value="">Pilih Operator</option>
                            @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id_karyawan }}" {{ old('operator_id') == $karyawan->id_karyawan ? 'selected' : '' }}>
                                    {{ $karyawan->nama_karyawan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Admin -->
                    <div class="form-group">
                        <label for="admin_id">Admin:</label>
                        <select id="admin_id" name="admin_id" required>
                            <option value="">Pilih Admin</option>
                            @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id_karyawan }}" {{ old('admin_id') == $karyawan->id_karyawan ? 'selected' : '' }}>
                                    {{ $karyawan->nama_karyawan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mekanik Utama -->
                    <div class="form-group">
                        <label for="mekanik_id">Mekanik Utama:</label>
                        <select id="mekanik_id" name="mekanik_id" required>
                            <option value="">Pilih Mekanik</option>
                            @foreach($mechanics as $mechanic)
                                <option value="{{ $mechanic->id_karyawan }}" {{ old('mekanik_id') == $mechanic->id_karyawan ? 'selected' : '' }}>
                                    {{ $mechanic->nama_karyawan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Maintenance -->
                    <div class="form-group">
                        <label for="status_maintenance">Status:</label>
                        <select id="status_maintenance" name="status_maintenance" required>
                            <option value="">Pilih Status</option>
                            <option value="baik" {{ old('status_maintenance') == 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="proses" {{ old('status_maintenance') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="selesai" {{ old('status_maintenance') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>

                <!-- Spareparts Section -->
                <div class="sparepart-section">
                    <div class="sparepart-header">
                        <h3 style="color: #667eea; margin: 0; font-size: 1.2em;">Sparepart yang Digunakan (Opsional)</h3>
                        <button type="button" class="add-sparepart-btn" onclick="addSparepartItem()">+ Tambah Sparepart</button>
                    </div>

                    <div id="sparepart-container">
                        <!-- Sparepart items will be added here -->
                    </div>
                </div>

                <!-- Additional Mechanics Section -->
                <div class="form-group">
                    <label for="additional_mechanics">Tim Mekanik Tambahan (Opsional):</label>
                    <select id="additional_mechanics" name="additional_mechanics[]" multiple>
                        @foreach($mechanics as $mechanic)
                            <option value="{{ $mechanic->id_karyawan }}">{{ $mechanic->nama_karyawan }}</option>
                        @endforeach
                    </select>
                    <small style="color: #666; font-size: 0.85em; margin-top: 5px; display: block;">
                        Tahan Ctrl (Windows) atau Cmd (Mac) untuk memilih multiple mekanik
                    </small>
                </div>

                <!-- Tombol Simpan -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-save" id="saveBtn">Simpan Maintenance & Keluhan</button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="preview-section">
            <h2 class="section-title">
                Preview Form
            </h2>

            <div class="print-preview" id="printPreview">
                <table class="print-form">
                    <tr>
                        <td colspan="4" class="header-cell">
                            <div style="text-align: center; line-height: 1.4;">
                                <div style="font-size: 14px; margin-bottom: 8px;">PT. NAMA PERUSAHAAN ANDA</div>
                                <div style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">FORM MAINTENANCE & KELUHAN UNIT</div>
                                <div style="font-size: 12px; opacity: 0.9;">Divisi Operations & Maintenance</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="field-label">No. Keluhan</td>
                        <td class="field-value" id="preview-no_keluhan">Auto-generated</td>
                        <td class="field-label">Tanggal Keluhan</td>
                        <td class="field-value" id="preview-tanggal_keluhan">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Site</td>
                        <td class="field-value" id="preview-site">-</td>
                        <td class="field-label">KM/HM</td>
                        <td class="field-value" id="preview-km_hm">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Proyek</td>
                        <td class="field-value" id="preview-proyek">-</td>
                        <td class="field-label">Unit</td>
                        <td class="field-value" id="preview-unit">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Deskripsi Keluhan</td>
                        <td colspan="3" class="field-value" id="preview-deskripsi_keluhan">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Diagnosa</td>
                        <td colspan="3" class="field-value" id="preview-diagnosa">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Mulai Dikerjakan</td>
                        <td class="field-value" id="preview-mulai_dikerjakan">-</td>
                        <td class="field-label">Selesai Dikerjakan</td>
                        <td class="field-value" id="preview-selesai_dikerjakan">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Operator</td>
                        <td class="field-value" id="preview-operator">-</td>
                        <td class="field-label">Admin</td>
                        <td class="field-value" id="preview-admin">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Mekanik Utama</td>
                        <td class="field-value" id="preview-mekanik">-</td>
                        <td class="field-label">Status</td>
                        <td class="field-value" id="preview-status">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Dibuat Pada</td>
                        <td class="field-value" id="preview-created_at">-</td>
                        <td class="field-label"></td>
                        <td class="field-value"></td>
                    </tr>
    
                </table>
            </div>

            <div class="instructions">
                <h3>Petunjuk Penggunaan</h3>
                <ul>
                    <li>Isi data keluhan terlebih dahulu sebelum data maintenance</li>
                    <li>Pastikan unit dan proyek sesuai dengan keluhan yang dilaporkan</li>
                    <li>Diagnosa harus detail untuk memudahkan proses maintenance</li>
                    <li>Sparepart yang digunakan akan otomatis mengurangi stock</li>
                    <li>Status maintenance akan mempengaruhi status keluhan</li>
                    <li>Form dapat dicetak sebagai dokumentasi maintenance</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    'use strict';

    let sparepartIndex = 0;
    const spareparts = @json($spareparts);

    // Update preview when form changes
    function updatePreview() {
        const fieldMappings = {
            'site': 'preview-site',
            'proyek_id': 'preview-proyek',
            'unit_id': 'preview-unit',
            'tanggal_keluhan': 'preview-tanggal_keluhan',
            'km_hm': 'preview-km_hm',
            'deskripsi_keluhan': 'preview-deskripsi_keluhan',
            'diagnosa': 'preview-diagnosa',
            'mulai_dikerjakan': 'preview-mulai_dikerjakan',
            'selesai_dikerjakan': 'preview-selesai_dikerjakan',
            'operator_id': 'preview-operator',
            'admin_id': 'preview-admin',
            'mekanik_id': 'preview-mekanik',
            'status_maintenance': 'preview-status'
        };

        Object.keys(fieldMappings).forEach(fieldId => {
            const element = document.getElementById(fieldId);
            const previewElement = document.getElementById(fieldMappings[fieldId]);

            if (!element || !previewElement) return;

            let displayValue = '';

            if (element.tagName === 'SELECT') {
                displayValue = element.selectedOptions[0] ? element.selectedOptions[0].text : '';
            } else {
                displayValue = element.value;
            }

            // Special formatting for certain fields
            if (fieldId === 'km_hm' && displayValue) {
                displayValue = displayValue + ' KM/HM';
            }

            if (displayValue.trim() !== '') {
                previewElement.textContent = displayValue;
                previewElement.className = 'field-value filled-value';
            } else {
                previewElement.textContent = '-';
                previewElement.className = 'field-value empty-value';
            }
        });

        // Update created at timestamp
        document.getElementById('preview-created_at').textContent = new Date().toLocaleString('id-ID');
    }

    // Add sparepart item
    window.addSparepartItem = function() {
        const container = document.getElementById('sparepart-container');
        const itemDiv = document.createElement('div');
        itemDiv.className = 'sparepart-item';
        itemDiv.innerHTML = `
            <div class="form-group" style="margin-bottom: 0;  width: 200px;">
                <label>Sparepart:</label>
                <select name="spareparts[${sparepartIndex}][sparepart_id]" required>
                    <option value="">Pilih Sparepart</option>
                    ${spareparts.map(sp => `<option value="${sp.id_sparepart}">${sp.nama_sparepart} (Stock: ${sp.stock})</option>`).join('')}
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Qty:</label>
                <input type="number" name="spareparts[${sparepartIndex}][qty]" min="1" required style ="width: 80px">
            </div>
            <div class="form-group" style="margin-bottom: 0;">
                <label>Keterangan:</label>
                <input type="text" name="spareparts[${sparepartIndex}][keterangan]" placeholder="Keterangan sparepart... "style ="width: 200px">
            </div>
            <button type="button" class="remove-sparepart-btn" onclick="removeSparepartItem(this)">Hapus</button>
        `;

        container.appendChild(itemDiv);
        sparepartIndex++;
    };

    // Remove sparepart item
    window.removeSparepartItem = function(button) {
        button.parentElement.remove();
    };

    // Get units by project (AJAX)
    function getUnitsByProject(projectId) {
        if (!projectId) {
            document.getElementById('unit_id').innerHTML = '<option value="">Pilih Unit</option>';
            return;
        }

        fetch(`/maintenance/units/${projectId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const unitSelect = document.getElementById('unit_id');
                    unitSelect.innerHTML = '<option value="">Pilih Unit</option>';

                    data.data.forEach(unit => {
                        const option = document.createElement('option');
                        option.value = unit.id_unit;
                        option.textContent = `${unit.nama_unit} - ${unit.model_unit}`;
                        unitSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error fetching units:', error));
    }

    // Form submission
    function handleFormSubmission(e) {
        e.preventDefault();

        const saveBtn = document.getElementById('saveBtn');

        // Add loading state
        saveBtn.innerHTML = 'Menyimpan... <div class="loading"></div>';
        saveBtn.disabled = true;

        // Submit form after short delay
        setTimeout(() => {
            e.target.submit();
        }, 500);
    }

    // Print function
    function printForm() {
        updatePreview();
        if (confirm('Apakah Anda yakin ingin mencetak form maintenance ini?')) {
            window.print();
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Setup event listeners
        const form = document.getElementById('maintenanceForm');
        if (form) {
            form.addEventListener('submit', handleFormSubmission);
        }

        // Update preview on input changes
        const inputs = document.querySelectorAll('#maintenanceForm input, #maintenanceForm textarea, #maintenanceForm select');
        inputs.forEach(input => {
            input.addEventListener('input', updatePreview);
            input.addEventListener('change', updatePreview);
        });

        // Project change handler
        const proyekSelect = document.getElementById('proyek_id');
        if (proyekSelect) {
            proyekSelect.addEventListener('change', function() {
                getUnitsByProject(this.value);
                updatePreview();
            });
        }

        // Add print button
        const btnGroup = document.querySelector('.btn-group');
        if (btnGroup) {
            const printBtn = document.createElement('button');
            printBtn.type = 'button';
            printBtn.className = 'btn';
            printBtn.style.cssText = `
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            `;
            printBtn.innerHTML = 'Cetak Form';
            printBtn.addEventListener('click', printForm);
            btnGroup.appendChild(printBtn);
        }

        // Initial preview update
        updatePreview();

        // Add one sparepart item by default (optional)
        // addSparepartItem();
    });

    // Date validation
    document.getElementById('selesai_dikerjakan').addEventListener('change', function() {
        const startDate = new Date(document.getElementById('mulai_dikerjakan').value);
        const endDate = new Date(this.value);

        if (endDate < startDate) {
            alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
            this.value = '';
        }
    });

})();
</script>
@endsection
