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

        .btn-print {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-print:hover {
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
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

        .keluhan-section {
            height: 150px;
            vertical-align: top;
        }

        .diagnosa-section {
            height: 120px;
            vertical-align: top;
        }

        .sparepart-section {
            height: 100px;
            vertical-align: top;
        }

        .team-section {
            height: 80px;
            vertical-align: top;
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

        /* Loading animation */
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
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="header">
            <div class="company-logo">PT. NAMA PERUSAHAAN ANDA</div>
            <h1>FORM KELUHAN UNIT/KENDARAAN</h1>
            <p class="subtitle">Sistem Pelaporan & Manajemen Keluhan</p>
            <p class="department">Divisi Maintenance & Operations</p>
        </div>

        <div class="content">
            <!-- Form Input Section -->
            <div class="form-section">
                <h2 class="section-title">
                    📝 Input Data
                </h2>

                <form id="complaintForm" method="POST" action="">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="noForm">No. Form:</label>
                            <input type="text" id="noForm" name="no_form" placeholder="Auto-generated" readonly>
                        </div>

                        <div class="form-group">
                            <label for="tanggal">Tanggal:</label>
                            <input type="date" id="tanggal" name="tanggal" required>
                        </div>

                        <div class="form-group">
                            <label for="site">Site:</label>
                            <input type="text" id="site" name="site" placeholder="Masukkan nama site" required>
                        </div>

                        <div class="form-group">
                            <label for="proyek">Proyek:</label>
                            <input type="text" id="proyek" name="proyek" placeholder="Masukkan nama proyek" required>
                        </div>

                        <div class="form-group">
                            <label for="idSeriUnit">ID/No. Seri Unit:</label>
                            <input type="text" id="idSeriUnit" name="id_seri_unit" placeholder="Masukkan ID/No. Seri Unit" required>
                        </div>

                        <div class="form-group">
                            <label for="namaUnit">Nama Unit:</label>
                            <input type="text" id="namaUnit" name="nama_unit" placeholder="Masukkan nama unit" required>
                        </div>

                        <div class="form-group">
                            <label for="kmHm">KM / HM:</label>
                            <input type="text" id="kmHm" name="km_hm" placeholder="Masukkan KM/HM">
                        </div>

                        <div class="form-group">
                            <label for="namaOperator">Nama Operator/PIC:</label>
                            <input type="text" id="namaOperator" name="nama_operator" placeholder="Masukkan nama operator/PIC" required>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="keluhan">Keluhan-keluhan:</label>
                        <textarea id="keluhan" name="keluhan" placeholder="Deskripsikan keluhan secara detail..." required></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="diagnosa">Diagnosa Mekanik:</label>
                        <textarea id="diagnosa" name="diagnosa" placeholder="Deskripsikan diagnosa secara detail..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="sparepart">Sparepart:</label>
                        <textarea id="sparepart" name="sparepart" placeholder="Deskripsikan sparepart yang dibutuhkan..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="teamMekanik">Team Mekanik:</label>
                        <textarea id="teamMekanik" name="team_mekanik" placeholder="Nama-nama anggota team mekanik..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="admin">Admin:</label>
                        <textarea id="admin" name="admin" placeholder="Informasi admin yang menangani..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="penanggungJawab">Penanggung Jawab:</label>
                        <textarea id="penanggungJawab" name="penanggung_jawab" placeholder="Nama penanggung jawab proyek..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="operatorUnit">Operator Unit:</label>
                        <textarea id="operatorUnit" name="operator_unit" placeholder="Detail operator unit..."></textarea>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn btn-save" id="saveBtn">💾 Simpan & Cetak Form</button>
                    </div>

                    <div class="success-message" id="successMessage">
                        ✅ Data berhasil disimpan!
                    </div>

                    <div class="error-message" id="errorMessage">
                        ❌ Terjadi kesalahan saat menyimpan data.
                    </div>
                </form>
            </div>

            <!-- Preview Section -->
            <div class="preview-section">
                <h2 class="section-title">
                    👁️ Preview Cetak
                </h2>

                <div class="print-preview" id="printPreview">
                    <table class="print-form">
                        <tr>
                            <td colspan="4" class="header-cell">
                                <div style="text-align: center; line-height: 1.4;">
                                    <div style="font-size: 14px; margin-bottom: 8px;">PT. NAMA PERUSAHAAN ANDA</div>
                                    <div style="font-size: 18px; font-weight: bold; margin-bottom: 5px;">FORM KELUHAN UNIT/KENDARAAN</div>
                                    <div style="font-size: 12px; opacity: 0.9;">Divisi Maintenance & Operations</div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">No. Form</td>
                            <td class="field-value" id="preview-noForm">-</td>
                            <td class="field-label">Tanggal</td>
                            <td class="field-value" id="preview-tanggal">-</td>
                        </tr>
                        <tr>
                            <td class="field-label">Site</td>
                            <td class="field-value" id="preview-site">-</td>
                            <td class="field-label">Proyek</td>
                            <td class="field-value" id="preview-proyek">-</td>
                        </tr>
                        <tr>
                            <td class="field-label">ID/No. Seri Unit</td>
                            <td class="field-value" id="preview-idSeriUnit">-</td>
                            <td class="field-label">Nama Unit</td>
                            <td class="field-value" id="preview-namaUnit">-</td>
                        </tr>
                        <tr>
                            <td class="field-label">KM / HM</td>
                            <td class="field-value" id="preview-kmHm">-</td>
                            <td class="field-label">Nama Operator/PIC</td>
                            <td class="field-value" id="preview-namaOperator">-</td>
                        </tr>
                        <tr>
                            <td class="field-label keluhan-section">Keluhan-Keluhan</td>
                            <td colspan="3" class="field-value keluhan-section" id="preview-keluhan">-</td>
                        </tr>
                        <tr>
                            <td class="field-label diagnosa-section">Diagnosa Mekanik</td>
                            <td colspan="3" class="field-value diagnosa-section" id="preview-diagnosa">-</td>
                        </tr>
                        <tr>
                            <td class="field-label sparepart-section">Sparepart</td>
                            <td colspan="3" class="field-value sparepart-section" id="preview-sparepart">-</td>
                        </tr>
                        <tr>
                            <td class="field-label team-section">Team Mekanik</td>
                            <td colspan="3" class="field-value team-section" id="preview-teamMekanik">-</td>
                        </tr>
                        <tr>
                            <td class="field-label">Admin</td>
                            <td class="field-value" id="preview-admin">-</td>
                            <td class="field-label">Penanggung Jawab</td>
                            <td class="field-value" id="preview-penanggungJawab">-</td>
                        </tr>
                        <tr>
                            <td class="field-label">Operator Unit</td>
                            <td colspan="3" class="field-value" id="preview-operatorUnit">-</td>
                        </tr>
                    </table>
                </div>

                <div class="instructions">
                    <h3>📋 Petunjuk Penggunaan</h3>
                    <ul>
                        <li>Isi semua field yang diperlukan pada form</li>
                        <li>Data akan otomatis tersimpan dan form akan dicetak</li>
                        <li>Pastikan printer sudah siap sebelum klik tombol</li>
                        <li>Form akan tersimpan di database secara otomatis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Set tanggal hari ini sebagai default
        document.getElementById('tanggal').value = new Date().toISOString().split('T')[0];

        // Update preview secara real-time
        function updatePreview() {
            const fields = [
                'noForm', 'tanggal', 'site', 'proyek',
                'idSeriUnit', 'namaUnit', 'kmHm', 'namaOperator',
                'keluhan', 'diagnosa', 'sparepart', 'teamMekanik',
                'admin', 'penanggungJawab', 'operatorUnit'
            ];

            fields.forEach(field => {
                const inputValue = document.getElementById(field).value;
                const previewElement = document.getElementById('preview-' + field);

                if (inputValue.trim() !== '') {
                    previewElement.textContent = inputValue;
                    previewElement.className = previewElement.className.replace('empty-value', 'filled-value');
                } else {
                    previewElement.textContent = '-';
                    previewElement.className = previewElement.className.replace('filled-value', 'empty-value');
                }
            });

            // Format tanggal untuk preview
            const tanggalInput = document.getElementById('tanggal').value;
            if (tanggalInput) {
                const date = new Date(tanggalInput);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                document.getElementById('preview-tanggal').textContent =
                    date.toLocaleDateString('id-ID', options);
            }
        }

        // Fungsi untuk mencetak
        function printForm() {
            updatePreview();

            const requiredFields = ['site', 'proyek', 'namaUnit', 'namaOperator', 'keluhan'];
            const emptyFields = [];

            requiredFields.forEach(field => {
                if (document.getElementById(field).value.trim() === '') {
                    emptyFields.push(field);
                }
            });

            if (emptyFields.length > 0) {
                alert('Harap isi field yang wajib: ' + emptyFields.join(', '));
                return;
            }

            if (confirm('Apakah Anda yakin ingin mencetak form ini?')) {
                window.print();
            }
        }

        // Handle form submission with save and print
        document.getElementById('complaintForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const saveBtn = document.getElementById('saveBtn');
            const successMsg = document.getElementById('successMessage');
            const errorMsg = document.getElementById('errorMessage');

            // Reset messages
            successMsg.style.display = 'none';
            errorMsg.style.display = 'none';

            // Validate required fields
            const requiredFields = ['site', 'proyek', 'idSeriUnit', 'namaUnit', 'namaOperator', 'keluhan'];
            const emptyFields = [];

            requiredFields.forEach(field => {
                if (document.getElementById(field).value.trim() === '') {
                    emptyFields.push(document.querySelector(`label[for="${field}"]`).textContent.replace(':', ''));
                }
            });

            if (emptyFields.length > 0) {
                errorMsg.textContent = 'Harap isi field yang wajib: ' + emptyFields.join(', ');
                errorMsg.style.display = 'block';
                return;
            }

            // Add loading state
            saveBtn.innerHTML = '💾 Menyimpan... <span class="loading"></span>';
            saveBtn.disabled = true;

            // Update preview before saving
            updatePreview();

            // Submit to controller
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    no_form: document.getElementById('noForm').value,
                    tanggal: document.getElementById('tanggal').value,
                    site: document.getElementById('site').value,
                    proyek: document.getElementById('proyek').value,
                    id_seri_unit: document.getElementById('idSeriUnit').value,
                    nama_unit: document.getElementById('namaUnit').value,
                    km_hm: document.getElementById('kmHm').value,
                    nama_operator: document.getElementById('namaOperator').value,
                    keluhan: document.getElementById('keluhan').value,
                    diagnosa: document.getElementById('diagnosa').value,
                    sparepart: document.getElementById('sparepart').value,
                    team_mekanik: document.getElementById('teamMekanik').value,
                    admin: document.getElementById('admin').value,
                    penanggung_jawab: document.getElementById('penanggungJawab').value,
                    operator_unit: document.getElementById('operatorUnit').value
                })
            })
            .then(response => {
                if (response.ok) {
                    successMsg.textContent = '✅ Data berhasil disimpan! Mencetak form...';
                    successMsg.style.display = 'block';
                    clearAutoSave();

                    // Print form after successful save
                    setTimeout(() => {
                        window.print();
                    }, 1000);

                } else {
                    throw new Error('Network response was not ok');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMsg.textContent = 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.';
                errorMsg.style.display = 'block';
            })
            .finally(() => {
                saveBtn.innerHTML = '💾 Simpan & Cetak Form';
                saveBtn.disabled = false;
            });
        });

        // Auto-update preview saat mengetik
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.addEventListener('input', updatePreview);
            });

            // Initial preview update
            updatePreview();
        });

        // Generate nomor form otomatis
        function generateFormNumber() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');

            return `FKU-${year}${month}${day}-${random}`;
        }

        // Set nomor form otomatis saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('noForm').value = generateFormNumber();
            updatePreview();
        });

        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const requiredInputs = document.querySelectorAll('input[required], textarea[required]');

            requiredInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = '#dc3545';
                        this.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
                    } else {
                        this.style.borderColor = '#28a745';
                        this.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.borderColor = '#28a745';
                        this.style.boxShadow = '0 0 0 3px rgba(40, 167, 69, 0.1)';
                    }
                });
            });
        });

        // Add smooth scroll to preview when updating
        function scrollToPreview() {
            document.querySelector('.preview-section').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // Enhanced update preview with animation
        function updatePreviewAnimated() {
            updatePreview();

            // Add pulse animation to preview
            const preview = document.getElementById('printPreview');
            preview.style.transform = 'scale(0.98)';
            preview.style.transition = 'transform 0.2s ease';

            setTimeout(() => {
                preview.style.transform = 'scale(1)';
            }, 200);
        }

        // Auto-save to localStorage for recovery
        function autoSave() {
            const formData = {};
            const inputs = document.querySelectorAll('input, textarea');

            inputs.forEach(input => {
                if (input.id) {
                    formData[input.id] = input.value;
                }
            });

            localStorage.setItem('complaintFormData', JSON.stringify(formData));
        }

        // Restore from localStorage
        function restoreFormData() {
            const savedData = localStorage.getItem('complaintFormData');
            if (savedData) {
                const formData = JSON.parse(savedData);

                Object.keys(formData).forEach(key => {
                    const element = document.getElementById(key);
                    if (element && formData[key]) {
                        element.value = formData[key];
                    }
                });

                updatePreview();
            }
        }

        // Clear auto-save data after successful submission
        function clearAutoSave() {
            localStorage.removeItem('complaintFormData');
        }

        // Initialize auto-save
        document.addEventListener('DOMColntentLoaded', function() {
            // Restore previous data if available
            restoreFormData();

            // Set up auto-save on input changes
            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', autoSave);
            });
        });
    </script>
@endsection
