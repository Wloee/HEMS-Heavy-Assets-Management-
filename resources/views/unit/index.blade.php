@extends('layouts.app')

@section('content')
<div class="content">
    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Data Unit</h2>

            <a href="{{ route('create_Unit') }}"
               style="padding:10px 16px; background:linear-gradient(135deg,#667eea,#764ba2);
                      color:#fff; font-weight:600; border-radius:10px; text-decoration:none;
                      box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:0.3s;">
                <i class="fas fa-plus"></i> Tambah Unit
            </a>
        </div>

         <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('data_Unit') }}" method="GET" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                    <input type="text" name="search" placeholder="Cari kode unit, nama unit, atau no polisi..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:300px;">

                    <select name="jenis_unit_id" class="form-select" style="width:150px;">
                        <option value="" {{ !request('jenis_unit_id') ? 'selected' : '' }}>Semua Jenis Unit</option>
                        @foreach($jenisUnits as $jenis)
                            <option value="{{ $jenis->id_jenis_unit }}" {{ request('jenis_unit_id') == $jenis->id_jenis_unit ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status_operasional" class="form-select" style="width:150px;">
                        <option value="" {{ !request('status_operasional') ? 'selected' : '' }}>Semua Status</option>
                        <option value="operasional" {{ request('status_operasional') == 'operasional' ? 'selected' : '' }}>Operasional</option>
                        <option value="maintenance" {{ request('status_operasional') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="standby" {{ request('status_operasional') == 'standby' ? 'selected' : '' }}>Standby</option>
                        <option value="tidak_aktif" {{ request('status_operasional') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>

                    <select name="status_kondisi" class="form-select" style="width:150px;">
                        <option value="" {{ !request('status_kondisi') ? 'selected' : '' }}>Semua Kondisi</option>
                        <option value="baik" {{ request('status_kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="perlu_maintenance" {{ request('status_kondisi') == 'perlu_maintenance' ? 'selected' : '' }}>Perlu Maintenance</option>
                        <option value="rusak" {{ request('status_kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                    </select>

                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>

                    @if(request('search') || request('jenis_unit_id') || request('status_operasional') || request('status_kondisi'))
                        <a href="{{ route('data_Unit') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table id="unitTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.05);
                          min-width: 1200px;">
                <thead>
                    <tr style="background:#f5f7fa; color:#334155; text-transform:uppercase; font-size:12px; letter-spacing:0.5px;">
                        <th style="padding:15px 12px; text-align:center; width:50px;">No</th>
                        <th style="padding:15px 12px; width:100px;">Kode Unit</th>
                        <th style="padding:15px 12px; width:150px;">Nama Unit</th>
                        <th style="padding:15px 12px; width:120px;">Jenis Unit</th>
                        <th style="padding:15px 12px; width:100px;">Merk/Model</th>
                        <th style="padding:15px 12px; width:80px;">Tahun</th>
                        <th style="padding:15px 12px; width:100px;">No Polisi</th>
                        <th style="padding:15px 12px; width:100px;">Pemilik</th>
                        <th style="padding:15px 12px; width:100px; text-align:right;">Jam Operasi</th>
                        <th style="padding:15px 12px; width:100px; text-align:center;">Status Operasional</th>
                        <th style="padding:15px 12px; width:100px; text-align:center;">Kondisi</th>
                        <th style="padding:15px 12px; width:100px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $index => $item)
                        <tr style="border-bottom:1px solid #e2e8f0; transition:background 0.3s;"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:15px 12px; text-align:center; font-weight:500;">{{ $index + 1 }}</td>
                            <td style="padding:15px 12px; font-weight:500;">{{ $item->kode_unit ?? '-' }}</td>
                            <td style="padding:15px 12px; font-weight:500;">{{ $item->nama_unit }}</td>
                            <td style="padding:15px 12px;">{{ $item->nama_jenis ?? '-' }}</td>
                            <td style="padding:15px 12px;">
                                {{ $item->merk ? $item->merk : '-' }}{{ $item->model ? ' / '.$item->model : '' }}
                            </td>
                            <td style="padding:15px 12px; text-align:center;">{{ $item->tahun_pembuatan ?? '-' }}</td>
                            <td style="padding:15px 12px; font-weight:500;">{{ $item->no_polisi ?? '-' }}</td>
                            <td style="padding:15px 12px;">{{ $item->nama_pemilik ?? '-' }}</td>
                            <td style="padding:15px 12px; text-align:right; font-weight:500; color:#059669;">
                                {{ number_format($item->jam_operasi, 0, ',', '.') }} jam
                            </td>

                            @php
                                $statusOperasionalColor = match($item->status_operasional) {
                                    'operasional' => '#10b981',
                                    'maintenance' => '#f59e0b',
                                    'standby' => '#6b7280',
                                    'tidak_aktif' => '#ef4444',
                                    default => '#6b7280'
                                };

                                $statusOperasionalText = match($item->status_operasional) {
                                    'operasional' => 'Operasional',
                                    'maintenance' => 'Maintenance',
                                    'standby' => 'Standby',
                                    'tidak_aktif' => 'Tidak Aktif',
                                    default => $item->status_operasional
                                };
                            @endphp

                            <td style="padding:15px 12px; text-align:center;">
                                <span style="padding:4px 8px; background:{{ $statusOperasionalColor }}20; color:{{ $statusOperasionalColor }};
                                             border-radius:12px; font-size:11px; font-weight:600; text-transform:uppercase;">
                                    {{ $statusOperasionalText }}
                                </span>
                            </td>

                            @php
                                $statusKondisiColor = match($item->status_kondisi) {
                                    'baik' => '#10b981',
                                    'perlu_maintenance' => '#f59e0b',
                                    'rusak' => '#ef4444',
                                    default => '#6b7280'
                                };

                                $statusKondisiText = match($item->status_kondisi) {
                                    'baik' => 'Baik',
                                    'perlu_maintenance' => 'Perlu Maintenance',
                                    'rusak' => 'Rusak',
                                    default => $item->status_kondisi
                                };
                            @endphp

                            <td style="padding:15px 12px; text-align:center;">
                                <span style="padding:4px 8px; background:{{ $statusKondisiColor }}20; color:{{ $statusKondisiColor }};
                                             border-radius:12px; font-size:11px; font-weight:600; text-transform:uppercase;">
                                    {{ $statusKondisiText }}
                                </span>
                            </td>

                            <td style="padding:15px 12px; text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    <a href="{{ route('edit_Unit', $item->id_unit) }}"
                                       style="background:#fbbf24; padding:8px 10px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:32px; height:32px;"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('delete_Unit', $item->id_unit) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data unit ini?')"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                style="background:#ef4444; padding:8px 10px; border:none; border-radius:6px;
                                                       color:#fff; font-size:12px; cursor:pointer; transition:all 0.2s;
                                                       min-width:32px; height:32px; display:flex; align-items:center; justify-content:center;"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="text-align:center; padding:40px 20px; color:#6b7280; font-style:italic;">
                                <i class="fas fa-truck" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                Tidak ada data unit
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

{{ $units->links() }}

@section('scripts')
<script>
    $(document).ready(function() {
        $('#unitTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0], orderable: false },
                { targets: [8], className: 'text-right' },
                { targets: [9, 10, 11], orderable: false, className: 'text-center' }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'asc']] // Order by kode_unit
        });
    });
</script>
@endsection
