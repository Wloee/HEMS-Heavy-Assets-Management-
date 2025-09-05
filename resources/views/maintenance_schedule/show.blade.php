@extends('layouts.app')

@section('content')
<div class="content">
    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Detail Jadwal Maintenance</h2>
            <a href="{{ route('maintenance-schedule.index') }}"
               style="padding:10px 16px; background:#6b7280; color:#fff; font-weight:600;
                      border-radius:10px; text-decoration:none; transition:0.3s;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row">
            <!-- Informasi Maintenance -->
            <div class="col-md-8">
                <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Informasi Maintenance</h5>
                    </div>
                    <div class="card-body" style="padding: 25px;">
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">ID Schedule:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span class="badge" style="background: #667eea; color: white; font-size: 12px; padding: 6px 12px; border-radius: 8px;">
                                    #{{ $schedule->id_schedule }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">Jenis Maintenance:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span style="font-weight: 600; color: #1f2937;">{{ $schedule->jenis_maintenance }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">Deskripsi:</strong>
                            </div>
                            <div class="col-sm-8">
                                <div style="background: #f9fafb; padding: 12px; border-radius: 8px; border-left: 4px solid #667eea;">
                                    {{ $schedule->deskripsi ?? 'Tidak ada deskripsi' }}
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">Tanggal Mulai:</strong>
                            </div>
                            <div class="col-sm-8">
                                @if($schedule->Mulai_dikerjakan)
                                    <span style="color: #059669; font-weight: 500;">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($schedule->Mulai_dikerjakan)->format('d F Y') }}
                                    </span>
                                @else
                                    <span style="color: #6b7280; font-style: italic;">Belum ditentukan</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">Tanggal Selesai:</strong>
                            </div>
                            <div class="col-sm-8">
                                @if($schedule->Selesai_dikerjakan)
                                    <span style="color: #dc2626; font-weight: 500;">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        {{ \Carbon\Carbon::parse($schedule->Selesai_dikerjakan)->format('d F Y') }}
                                    </span>
                                @else
                                    <span style="color: #6b7280; font-style: italic;">Belum selesai</span>
                                @endif
                            </div>
                        </div>

                        @if($schedule->Mulai_dikerjakan && $schedule->Selesai_dikerjakan)
                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">Durasi:</strong>
                            </div>
                            <div class="col-sm-8">
                                @php
                                    $durasi = \Carbon\Carbon::parse($schedule->Mulai_dikerjakan)->diffInDays(\Carbon\Carbon::parse($schedule->Selesai_dikerjakan));
                                @endphp
                                <span style="color: #7c3aed; font-weight: 500;">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $durasi }} hari
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">Status:</strong>
                            </div>
                            <div class="col-sm-8">
                                @php
                                    $statusColor = $schedule->is_active ? '#10b981' : '#ef4444';
                                    $statusText = $schedule->is_active ? 'Aktif' : 'Nonaktif';
                                @endphp
                                <span style="padding: 6px 12px; background: {{ $statusColor }}20; color: {{ $statusColor }};
                                             border-radius: 12px; font-size: 12px; font-weight: 600; text-transform: uppercase;">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-sm-4">
                                <strong style="color: #374151;">Dibuat:</strong>
                            </div>
                            <div class="col-sm-8">
                                <span style="color: #6b7280;">
                                    {{ \Carbon\Carbon::parse($schedule->created_at)->format('d F Y, H:i') }} WIB
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Unit -->
            <div class="col-md-4">
                <div class="card mb-3" style="border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background: linear-gradient(135deg, #fbbf24, #f59e0b); color: white; border-radius: 15px 15px 0 0;">
                        <h6 class="mb-0"><i class="fas fa-truck me-2"></i>Informasi Unit</h6>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Nama Unit</small>
                            <div style="font-weight: 600; color: #1f2937; margin-top: 4px;">
                                {{ $schedule->nama_unit ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Kode Unit</small>
                            <div style="font-weight: 500; color: #374151; margin-top: 4px;">
                                {{ $schedule->kode_unit ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Merk & Model</small>
                            <div style="font-weight: 500; color: #374151; margin-top: 4px;">
                                {{ $schedule->merk ?? '-' }} {{ $schedule->model ?? '' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Unit</small>
                            <div style="font-weight: 500; color: #374151; margin-top: 4px;">
                                {{ $schedule->nama_jenis_unit ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Status Kondisi</small>
                            <div style="margin-top: 4px;">
                                @php
                                    $kondisiColor = match($schedule->status_kondisi ?? '') {
                                        'baik' => '#10b981',
                                        'perlu_maintenance' => '#f59e0b',
                                        'rusak' => '#ef4444',
                                        default => '#6b7280'
                                    };
                                    $kondisiText = match($schedule->status_kondisi ?? '') {
                                        'baik' => 'Baik',
                                        'perlu_maintenance' => 'Perlu Maintenance',
                                        'rusak' => 'Rusak',
                                        default => '-'
                                    };
                                @endphp
                                <span style="padding: 4px 8px; background: {{ $kondisiColor }}20; color: {{ $kondisiColor }};
                                             border-radius: 8px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
                                    {{ $kondisiText }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-0">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Status Operasional</small>
                            <div style="margin-top: 4px;">
                                @php
                                    $operasionalColor = match($schedule->status_operasional ?? '') {
                                        'operasional' => '#10b981',
                                        'maintenance' => '#f59e0b',
                                        'standby' => '#3b82f6',
                                        'tidak_aktif' => '#6b7280',
                                        default => '#6b7280'
                                    };
                                    $operasionalText = match($schedule->status_operasional ?? '') {
                                        'operasional' => 'Operasional',
                                        'maintenance' => 'Maintenance',
                                        'standby' => 'Standby',
                                        'tidak_aktif' => 'Tidak Aktif',
                                        default => '-'
                                    };
                                @endphp
                                <span style="padding: 4px 8px; background: {{ $operasionalColor }}20; color: {{ $operasionalColor }};
                                             border-radius: 8px; font-size: 11px; font-weight: 600; text-transform: uppercase;">
                                    {{ $operasionalText }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Operator -->
                <div class="card mb-3" style="border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border-radius: 15px 15px 0 0;">
                        <h6 class="mb-0"><i class="fas fa-user-cog me-2"></i>Operator</h6>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Nama Operator</small>
                            <div style="font-weight: 600; color: #1f2937; margin-top: 4px;">
                                {{ $schedule->operator_name ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-0">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">No. Handphone</small>
                            <div style="font-weight: 500; color: #374151; margin-top: 4px;">
                                @if($schedule->no_handphone)
                                    <a href="tel:{{ $schedule->no_handphone }}" style="color: #3b82f6; text-decoration: none;">
                                        <i class="fas fa-phone me-1"></i>{{ $schedule->no_handphone }}
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Sparepart -->
                <div class="card" style="border-radius: 15px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div class="card-header" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 15px 15px 0 0;">
                        <h6 class="mb-0"><i class="fas fa-cogs me-2"></i>Sparepart</h6>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Nama Sparepart</small>
                            <div style="font-weight: 600; color: #1f2937; margin-top: 4px;">
                                {{ $schedule->nama_sparepart ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Kode Sparepart</small>
                            <div style="font-weight: 500; color: #374151; margin-top: 4px;">
                                {{ $schedule->kode_sparepart ?? '-' }}
                            </div>
                        </div>

                        <div class="mb-0">
                            <small style="color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Stok Saat Ini</small>
                            <div style="margin-top: 4px;">
                                @php
                                    $stok = $schedule->stok_saat_ini ?? 0;
                                    $stokColor = $stok > 10 ? '#10b981' : ($stok > 5 ? '#f59e0b' : '#ef4444');
                                @endphp
                                <span style="padding: 4px 8px; background: {{ $stokColor }}20; color: {{ $stokColor }};
                                             border-radius: 8px; font-size: 12px; font-weight: 600;">
                                    {{ number_format($stok) }} unit
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
