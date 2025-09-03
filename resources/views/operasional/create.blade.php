```blade
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
        margin-bottom: 25px;
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

    .success-message {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 20px;
        display: none;
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .error-message {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 20px;
        display: none;
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
        <h1>FORM LOG OPERASIONAL UNIT</h1>
        <p class="subtitle">Sistem Pencatatan Operasional Harian</p>
        <p class="department">Divisi Operations & Maintenance</p>
    </div>

    <div class="content">
        <!-- Form Input Section -->
        <div class="form-section">
            <h2 class="section-title">
                📝 Input Data Operasional
            </h2>

            <form id="logForm" method="POST" action="{{ route('log-operasional.store') }}">
                @csrf
                <div class="form-grid">
                    <!-- Unit Proyek -->
                    <div class="form-group">
                        <label for="unitProyekId">Unit Proyek:</label>
                        <select id="unitProyekId" name="unit_proyek_id" required>
                            <option value="">Pilih Unit Proyek</option>
                            @foreach($unitProyek as $unit)
                                <option value="{{ $unit->id_unit_proyek }}">
                                    {{ $unit->unit_display }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Operasi -->
                    <div class="form-group">
                        <label for="tanggalOperasi">Tanggal Operasi:</label>
                        <input type="date" id="tanggalOperasi" name="tanggal_operasi" value="{{ old('tanggal_operasi') }}" required>
                    </div>

                    <!-- Jam Mulai -->
                    <div class="form-group">
                        <label for="jamMulai">Jam Mulai:</label>
                        <input type="time" id="jamMulai" name="jam_mulai" value="{{ old('jam_mulai') }}">
                    </div>

                    <!-- Jam Selesai -->
                    <div class="form-group">
                        <label for="jamSelesai">Jam Selesai:</label>
                        <input type="time" id="jamSelesai" name="jam_selesai" value="{{ old('jam_selesai') }}">
                    </div>

                    <!-- Jam Operasi -->
                    <div class="form-group">
                        <label for="jamOperasi">Jam Operasi (otomatis):</label>
                        <input type="number" id="jamOperasi" name="jam_operasi" step="0.01" placeholder="Auto-calculated" value="{{ old('jam_operasi') }}" readonly>
                    </div>

                    <!-- Biaya Harian Operasional -->
                    <div class="form-group">
                        <label for="biaya_operasional">Biaya Operasional:</label>
                        <input type="number" id="biaya_operasional" name="biaya_operasional" step="0.01" placeholder="Dihitung otomatis" value="{{ old('biaya_operasional') }}" readonly>
                    </div>

                    <!-- Operator -->
                    <div class="form-group">
                        <label for="operatorId">Operator:</label>
                        <select id="operatorId" name="operator_id" required>
                            <option value="">Pilih Operator</option>
                            @foreach($operators as $op)
                                <option value="{{ $op->id_karyawan }}">
                                    {{ $op->operator_display }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Lokasi Kerja -->
                <div class="form-group full-width">
                    <label for="lokasiKerja">Lokasi Kerja:</label>
                    <input type="text" id="lokasiKerja" name="lokasi_kerja" value="{{ old('lokasi_kerja') }}" placeholder="Masukkan lokasi kerja...">
                </div>

                <!-- Jenis Pekerjaan -->
                <div class="form-group full-width">
                    <label for="jenisPekerjaan">Jenis Pekerjaan:</label>
                    <input type="text" id="jenisPekerjaan" name="jenis_pekerjaan" value="{{ old('jenis_pekerjaan') }}" placeholder="Masukkan jenis pekerjaan...">
                </div>

                <!-- Keterangan -->
                <div class="form-group full-width">
                    <label for="keterangan">Keterangan:</label>
                    <textarea id="keterangan" name="keterangan" placeholder="Masukkan keterangan detail operasional...">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Tombol Simpan -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-save" id="saveBtn">💾 Simpan Log Operasional</button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="preview-section">
            <h2 class="section-title">
                👁️ Preview Log Operasional
            </h2>

            <div class="print-preview" id="printPreview">
                <table class="print-form">
                    <tr>
                        <td colspan="4" class="header-cell">
                            <div style="text-align: center; line-height: 1.4;">
                                <div style="font-size: 14px; margin-bottom: 8px;">PT. NAMA PERUSAHAAN ANDA</div>
                                <div style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">LOG OPERASIONAL UNIT</div>
                                <div style="font-size: 12px; opacity: 0.9;">Divisi Operations & Maintenance</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="field-label">Unit Proyek</td>
                        <td class="field-value" id="preview-unitProyekId">-</td>
                        <td class="field-label">Tanggal Operasi</td>
                        <td class="field-value" id="preview-tanggalOperasi">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Jam Mulai</td>
                        <td class="field-value" id="preview-jamMulai">-</td>
                        <td class="field-label">Jam Selesai</td>
                        <td class="field-value" id="preview-jamSelesai">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Total Jam Operasi</td>
                        <td class="field-value" id="preview-jamOperasi">-</td>
                        <td class="field-label">Biaya Operasional</td>
                        <td class="field-value" id="preview-biaya_operasional">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Operator</td>
                        <td class="field-value" id="preview-operatorId">-</td>
                        <td class="field-label"></td>
                        <td class="field-value"></td>
                    </tr>
                    <tr>
                        <td class="field-label">Lokasi Kerja</td>
                        <td colspan="3" class="field-value" id="preview-lokasiKerja">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Jenis Pekerjaan</td>
                        <td colspan="3" class="field-value" id="preview-jenisPekerjaan">-</td>
                    </tr>
                    <tr>
                        <td class="field-label" style="height: 120px; vertical-align: top;">Keterangan</td>
                        <td colspan="3" class="field-value" style="height: 120px; vertical-align: top;" id="preview-keterangan">-</td>
                    </tr>
                    <tr>
                        <td class="field-label">Dibuat Pada</td>
                        <td class="field-value" id="preview-createdAt">-</td>
                        <td class="field-label">Status</td>
                        <td class="field-value" style="color: #28a745; font-weight: bold;">✓ Aktif</td>
                    </tr>
                </table>
            </div>

            <div class="instructions">
                <h3>📋 Petunjuk Penggunaan</h3>
                <ul>
                    <li>Pilih unit proyek dan operator yang sesuai</li>
                    <li>Isi waktu mulai dan selesai untuk kalkulasi otomatis jam operasi</li>
                    <li>Deskripsikan lokasi kerja dan jenis pekerjaan dengan jelas</li>
                    <li>Data akan tersimpan ke database log_operasional</li>
                    <li>Form dapat dicetak sebagai dokumentasi operasional</li>
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

    // Global configuration
    const CONFIG = {
        ratePerHour: 50000,
        autoSaveKey: 'logOperasionalData'
    };

    // Utility functions
    const Utils = {
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        },

        formatDate: function(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return date.toLocaleDateString('id-ID', options);
        },

        getElementById: function(id) {
            const element = document.getElementById(id);
            if (!element) {
                console.warn(`Element with id '${id}' not found`);
            }
            return element;
        },

        createElement: function(tag, className, innerHTML) {
            const element = document.createElement(tag);
            if (className) element.className = className;
            if (innerHTML) element.innerHTML = innerHTML;
            return element;
        }
    };

    // Main Application Class
    class LogOperasionalApp {
        constructor() {
            this.isInitialized = false;
            this.elements = {};
            this.init();
        }

        init() {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.initApp());
            } else {
                this.initApp();
            }
        }

        initApp() {
            try {
                this.cacheElements();
                this.setDefaultValues();
                this.setupEventListeners();
                this.restoreFormData();
                this.setupValidation();
                this.setupTooltips();
                this.addPrintButton();
                this.createMessageElements();
                this.updatePreview();
                this.isInitialized = true;
                console.log('Log Operasional App initialized successfully');
            } catch (error) {
                console.error('Error initializing app:', error);
            }
        }

        cacheElements() {
            const elementIds = [
                'logForm', 'unitProyekId', 'tanggalOperasi', 'jamMulai', 'jamSelesai',
                'jamOperasi', 'biaya_operasional', 'operatorId', 'lokasiKerja',
                'jenisPekerjaan', 'keterangan', 'saveBtn', 'printPreview'
            ];

            elementIds.forEach(id => {
                this.elements[id] = Utils.getElementById(id);
            });

            // Cache preview elements
            const previewIds = [
                'preview-unitProyekId', 'preview-tanggalOperasi', 'preview-jamMulai',
                'preview-jamSelesai', 'preview-jamOperasi', 'preview-biaya_operasional',
                'preview-operatorId', 'preview-lokasiKerja', 'preview-jenisPekerjaan',
                'preview-keterangan', 'preview-createdAt'
            ];

            previewIds.forEach(id => {
                this.elements[id] = Utils.getElementById(id);
            });
        }

        setDefaultValues() {
            // Set today's date as default
            if (this.elements.tanggalOperasi) {
                this.elements.tanggalOperasi.value = new Date().toISOString().split('T')[0];
            }
        }

        setupEventListeners() {
            // Form submission
            if (this.elements.logForm) {
                this.elements.logForm.addEventListener('submit', (e) => this.handleFormSubmission(e));
            }

            // Input change listeners for all form elements
            const inputs = document.querySelectorAll('#logForm input, #logForm textarea, #logForm select');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    this.updatePreview();
                    this.autoSave();
                });

                input.addEventListener('change', () => {
                    this.updatePreview();
                    this.autoSave();
                });
            });

            // Special listeners for time calculation
            if (this.elements.jamMulai) {
                this.elements.jamMulai.addEventListener('change', () => this.calculateJamOperasi());
            }

            if (this.elements.jamSelesai) {
                this.elements.jamSelesai.addEventListener('change', () => this.handleJamSelesaiChange());
            }
        }

        handleJamSelesaiChange() {
            const jamMulai = this.elements.jamMulai?.value;
            const jamSelesai = this.elements.jamSelesai?.value;

            if (jamMulai && jamSelesai) {
                const startTime = new Date(`2000-01-01 ${jamMulai}`);
                const endTime = new Date(`2000-01-01 ${jamSelesai}`);

                if (endTime <= startTime) {
                    const nextDay = confirm('Jam selesai lebih awal dari jam mulai. Apakah operasi berlanjut ke hari berikutnya?');
                    if (!nextDay) {
                        this.elements.jamSelesai.value = '';
                        if (this.elements.jamOperasi) {
                            this.elements.jamOperasi.value = '';
                        }
                        if (this.elements.biaya_operasional) {
                            this.elements.biaya_operasional.value = '';
                        }
                        this.updatePreview();
                        return;
                    }
                }
            }
            this.calculateJamOperasi();
        }

        calculateJamOperasi() {
            const jamMulai = this.elements.jamMulai?.value;
            const jamSelesai = this.elements.jamSelesai?.value;

            if (!jamMulai || !jamSelesai) {
                if (this.elements.jamOperasi) this.elements.jamOperasi.value = '';
                if (this.elements.biaya_operasional) this.elements.biaya_operasional.value = '';
                this.updatePreview();
                return;
            }

            try {
                const startTime = new Date(`2000-01-01 ${jamMulai}`);
                let endTime = new Date(`2000-01-01 ${jamSelesai}`);

                // If end time is earlier than start time, assume next day
                if (endTime <= startTime) {
                    endTime.setDate(endTime.getDate() + 1);
                }

                const diffMs = endTime - startTime;
                const diffHours = diffMs / (1000 * 60 * 60);

                if (this.elements.jamOperasi) {
                    this.elements.jamOperasi.value = diffHours.toFixed(2);
                }

                this.calculateBiayaOperasional(diffHours);
                this.updatePreview();
            } catch (error) {
                console.error('Error calculating jam operasi:', error);
            }
        }

        calculateBiayaOperasional(jamOperasi) {
            if (!this.elements.biaya_operasional) return;

            if (jamOperasi && jamOperasi > 0) {
                const biaya = jamOperasi * CONFIG.ratePerHour;
                this.elements.biaya_operasional.value = biaya.toFixed(2);
            } else {
                this.elements.biaya_operasional.value = '';
            }
        }

        updatePreview() {
            const fieldMappings = {
                'unitProyekId': 'preview-unitProyekId',
                'tanggalOperasi': 'preview-tanggalOperasi',
                'jamMulai': 'preview-jamMulai',
                'jamSelesai': 'preview-jamSelesai',
                'jamOperasi': 'preview-jamOperasi',
                'biaya_operasional': 'preview-biaya_operasional',
                'operatorId': 'preview-operatorId',
                'lokasiKerja': 'preview-lokasiKerja',
                'jenisPekerjaan': 'preview-jenisPekerjaan',
                'keterangan': 'preview-keterangan'
            };

            Object.keys(fieldMappings).forEach(fieldId => {
                const element = this.elements[fieldId];
                const previewElement = this.elements[fieldMappings[fieldId]];

                if (!element || !previewElement) return;

                let displayValue = '';

                if (element.tagName === 'SELECT') {
                    displayValue = element.selectedOptions[0] ? element.selectedOptions[0].text : '';
                } else {
                    displayValue = element.value;
                }

                // Special formatting for certain fields
                if (fieldId === 'biaya_operasional' && displayValue) {
                    displayValue = Utils.formatCurrency(parseFloat(displayValue));
                } else if (fieldId === 'jamOperasi' && displayValue) {
                    displayValue = displayValue + ' jam';
                }

                if (displayValue.trim() !== '') {
                    previewElement.textContent = displayValue;
                    previewElement.className = 'field-value filled-value';
                } else {
                    previewElement.textContent = '-';
                    previewElement.className = 'field-value empty-value';
                }
            });

            // Special handling for formatted date
            if (this.elements.tanggalOperasi?.value && this.elements['preview-tanggalOperasi']) {
                this.elements['preview-tanggalOperasi'].textContent = Utils.formatDate(this.elements.tanggalOperasi.value);
                this.elements['preview-tanggalOperasi'].className = 'field-value filled-value';
            }

            // Update created at timestamp
            if (this.elements['preview-createdAt']) {
                this.elements['preview-createdAt'].textContent = new Date().toLocaleString('id-ID');
            }
        }

        handleFormSubmission(e) {
    e.preventDefault();

    const saveBtn = this.elements.saveBtn;
    let successMsg = Utils.getElementById('successMessage');
    let errorMsg = Utils.getElementById('errorMessage');

    // Reset messages
    if (successMsg) successMsg.style.display = 'none';
    if (errorMsg) errorMsg.style.display = 'none';

    // Validate required fields
    const validation = this.validateForm();
    if (!validation.isValid) {
        if (errorMsg) {
            errorMsg.textContent = 'Harap isi field yang wajib: ' + validation.emptyFields.join(', ');
            errorMsg.style.display = 'block';
            errorMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        return;
    }

    // Add loading state
    if (saveBtn) {
        saveBtn.innerHTML = '💾 Menyimpan... <div class="loading"></div>';
        saveBtn.disabled = true;
    }

    // Recalculate before saving to ensure values are up-to-date
    this.calculateJamOperasi();

    // Submit form to Laravel normally
    setTimeout(() => {
        this.elements.logForm.submit();
    }, 500); // Small delay to show loading state
}

        simulateFormSubmission() {
            return new Promise((resolve, reject) => {
                // Simulate API call - remove this in production
                setTimeout(() => {
                    try {
                        const formData = this.prepareFormData();
                        console.log('Form data to be submitted:', formData);
                        resolve();
                    } catch (error) {
                        reject(error);
                    }
                }, 1500);
            });
        }

        validateForm() {
            const requiredFields = [
                { id: 'unitProyekId', label: 'Unit Proyek' },
                { id: 'tanggalOperasi', label: 'Tanggal Operasi' },
                { id: 'operatorId', label: 'Operator' }
            ];

            const emptyFields = [];

            requiredFields.forEach(field => {
                const element = this.elements[field.id];
                if (!element || element.value.trim() === '') {
                    emptyFields.push(field.label);
                }
            });

            return {
                isValid: emptyFields.length === 0,
                emptyFields: emptyFields
            };
        }

        prepareFormData() {
            return {
                unit_proyek_id: this.elements.unitProyekId?.value || '',
                tanggal_operasi: this.elements.tanggalOperasi?.value || '',
                jam_mulai: this.elements.jamMulai?.value || null,
                jam_selesai: this.elements.jamSelesai?.value || null,
                jam_operasi: this.elements.jamOperasi?.value || null,
                biaya_operasional: this.elements.biaya_operasional?.value || null,
                operator_id: this.elements.operatorId?.value || null,
                jenis_pekerjaan: this.elements.jenisPekerjaan?.value || null,
                lokasi_kerja: this.elements.lokasiKerja?.value || null,
                keterangan: this.elements.keterangan?.value || null
            };
        }

        autoSave() {
            if (!this.isInitialized) return;

            try {
                const formData = {};
                const inputs = document.querySelectorAll('#logForm input, #logForm textarea, #logForm select');

                inputs.forEach(input => {
                    // Don't save calculated fields
                    if (input.id && !['biaya_operasional', 'jamOperasi'].includes(input.id)) {
                        formData[input.id] = input.value;
                    }
                });

                localStorage.setItem(CONFIG.autoSaveKey, JSON.stringify(formData));
            } catch (error) {
                console.error('Error auto-saving:', error);
            }
        }

        restoreFormData() {
            try {
                const savedData = localStorage.getItem(CONFIG.autoSaveKey);
                if (!savedData) return;

                const formData = JSON.parse(savedData);

                Object.keys(formData).forEach(key => {
                    const element = this.elements[key];
                    if (element && formData[key]) {
                        element.value = formData[key];
                    }
                });

                // Recalculate after restoration
                this.calculateJamOperasi();
            } catch (error) {
                console.error('Error restoring form data:', error);
                this.clearAutoSave();
            }
        }

        clearAutoSave() {
            localStorage.removeItem(CONFIG.autoSaveKey);
        }

        setupValidation() {
            const requiredInputs = document.querySelectorAll('#logForm input[required], #logForm select[required]');

            requiredInputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateField(input);
                });

                input.addEventListener('input', () => {
                    if (input.value.trim() !== '') {
                        this.clearFieldValidation(input);
                    }
                });
            });
        }

        validateField(field) {
            if (field.value.trim() === '') {
                field.style.borderColor = '#dc3545';
                field.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
            } else {
                this.clearFieldValidation(field);
            }
        }

        clearFieldValidation(field) {
            field.style.borderColor = '#28a745';
            field.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
        }

        setupTooltips() {
            const tooltips = {
                'unitProyekId': 'Pilih unit/kendaraan yang dioperasikan',
                'tanggalOperasi': 'Tanggal pelaksanaan operasi',
                'jamMulai': 'Waktu mulai operasi (format 24 jam)',
                'jamSelesai': 'Waktu selesai operasi (format 24 jam)',
                'jamOperasi': 'Total jam operasi (dihitung otomatis)',
                'biaya_operasional': 'Biaya operasional (dihitung otomatis berdasarkan jam operasi)',
                'operatorId': 'Pilih operator yang menjalankan unit',
                'lokasiKerja': 'Lokasi spesifik tempat unit beroperasi',
                'jenisPekerjaan': 'Jenis pekerjaan yang dilakukan unit',
                'keterangan': 'Catatan detail mengenai operasi atau kejadian khusus'
            };

            Object.keys(tooltips).forEach(id => {
                const element = this.elements[id];
                if (element) {
                    element.title = tooltips[id];
                }
            });
        }

        addPrintButton() {
            const btnGroup = document.querySelector('.btn-group');
            if (!btnGroup || Utils.getElementById('printBtn')) return;

            const printBtn = Utils.createElement('button', 'btn');
            printBtn.id = 'printBtn';
            printBtn.type = 'button';
            printBtn.style.cssText = `
                background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            `;
            printBtn.innerHTML = '🖨️ Cetak Log';
            printBtn.addEventListener('click', () => this.printLog());

            btnGroup.appendChild(printBtn);
        }

        printLog() {
            this.updatePreview();

            if (confirm('Apakah Anda yakin ingin mencetak log operasional ini?')) {
                window.print();
            }
        }

        createMessageElements() {
            if (!this.elements.logForm) return;

            // Create success message element
            if (!Utils.getElementById('successMessage')) {
                const successMsg = Utils.createElement('div', 'success-message');
                successMsg.id = 'successMessage';
                successMsg.style.display = 'none';
                this.elements.logForm.appendChild(successMsg);
            }

            // Create error message element
            if (!Utils.getElementById('errorMessage')) {
                const errorMsg = Utils.createElement('div', 'error-message');
                errorMsg.id = 'errorMessage';
                errorMsg.style.display = 'none';
                this.elements.logForm.appendChild(errorMsg);
            }
        }
    }

    // Initialize the application
    window.LogOperasionalApp = new LogOperasionalApp();

    // Expose some functions globally if needed
    window.printLogOperasional = () => {
        if (window.LogOperasionalApp) {
            window.LogOperasionalApp.printLog();
        }
    };

})();
</script>
@endsection
