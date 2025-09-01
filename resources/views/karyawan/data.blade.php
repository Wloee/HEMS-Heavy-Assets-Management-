@extends('layouts.app')

@section('content')
<div class="content">
    <div class="demo-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight:700; color:#1e293b;">Data Karyawan</h2>

            <a href="{{ route('input_karyawan') }}"
               style="padding:10px 16px; background:linear-gradient(135deg,#667eea,#764ba2);
                      color:#fff; font-weight:600; border-radius:10px; text-decoration:none;
                      box-shadow:0 4px 12px rgba(102,126,234,0.3); transition:0.3s;">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
        </div>

         <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <div style="flex:1;">
                <form action="{{ route('data_karyawan') }}" method="GET" style="display:flex; gap:10px; align-items:center;">
                    <input type="text" name="search" placeholder="Cari nama atau email..."
                           value="{{ request('search') }}"
                           style="padding:10px 15px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; width:300px;">
                    <button type="submit"
                            style="padding:10px 20px; background:#667eea; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('data_karyawan') }}"
                           style="padding:10px 15px; background:#6b7280; color:#fff; text-decoration:none; border-radius:8px;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                    <select name="role" id="role" class="form-select">
                        <option value="" selected disabled>Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="Teknisi">Teknisi</option>
                        <option value="Operator">Operator</option>
                        <option value="Supervisor">Supervisor</option>
                    </select>
                    <select name="role" id="role" class="form-select">
                        <option value="" selected disabled>Status</option>
                        <option value="admin">Aktif</option>
                        <option value="manager">Cuti</option>
                        <option value="operator">Tidak</option>
                    </select>
                </form>
            </div>
        </div>
          {{-- {{ old('id_departemen', isset($karyawan) ? $karyawan->id_departemen : '') == $d->id_departemen ? 'selected' : '' }}>
                                        {{ $d->nama_departemen }} --}}
        {{-- disabled {{ !old('id_departemen') && !isset($karyawan) ? 'selected' : '' }} --}}


        <div style="overflow-x:auto;">
            <table id="karyawanTable"
                   style="width:100%; border-collapse:collapse; font-family:'Inter',sans-serif;
                          background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,0.05);
                          min-width: 900px; heigh: 200px;">
                <thead>
                    <tr style="background:#f5f7fa; color:#334155; text-transform:uppercase; font-size:12px; letter-spacing:0.5px;">
                        <th style="padding:15px 12px; text-align:center; width:60px;">No</th>
                        <th style="padding:15px 12px; width:150px;">Nama Lengkap</th>
                        <th style="padding:15px 12px; width:120px;">NIK</th>
                        <th style="padding:15px 12px; width:100px;">Posisi</th>
                        <th style="padding:15px 12px; width:100px; text-align:right;">Gaji</th>
                        <th style="padding:15px 12px; width:100px; text-align:right;">Tunjangan</th>
                        <th style="padding:15px 12px; width:100px; text-align:right;">Intensif</th>
                        <th style="padding:15px 12px; width:100px; text-align:right;">Status</th>
                        <th style="padding:15px 12px; width:100px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan as $index => $item)
                        <tr style="border-bottom:1px solid #e2e8f0; transition:background 0.3s;"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:15px 12px; text-align:center; font-weight:500;">{{ $index + 1 }}</td>
                            <td style="padding:15px 12px; font-weight:500;">{{ $item->nama_karyawan }}</td>
                            <td style="padding:15px 12px;">{{ $item->no_nik ?? '-' }}</td>
                            <td style="padding:15px 12px;">{{ $item->posisi->nama_posisi ?? '-' }}</td>
                            <td style="padding:15px 12px; text-align:right; font-weight:500; color:#059669;">
                                Rp {{ number_format($item->Gaji ?? 0, 0, ',', '.') }}
                            </td>
                            <td style="padding:15px 12px; text-align:right; font-weight:500; color:#0891b2;">
                                Rp {{ number_format($item->Tunjangan ?? 0, 0, ',', '.') }}
                            </td>
                            <td style="padding:15px 12px; text-align:right; font-weight:500; color:#7c3aed;">
                                Rp {{ number_format($item->Intensif ?? 0, 0, ',', '.') }}
                            </td>
                            @php
                                 $color = $item->status == 'Aktif' ? '#bee414' : ($item->status == 'Cuti' ? '#ff0000' : '#37e414');
                            @endphp

                            <td style="padding:15px 12px; text-align:right; font-weight:500; color: {{ $color }};">
                                 {{$item->Status}}
                            </td>
                            <td style="padding:15px 12px; text-align:center;">
                                <div style="display:flex; gap:6px; justify-content:center; align-items:center;">
                                    <a href="{{ route('karyawan_edit', $item->id_karyawan) }}"
                                       style="background:#fbbf24; padding:8px 10px; border-radius:6px; color:#fff; font-size:12px;
                                              text-decoration:none; display:inline-flex; align-items:center; justify-content:center;
                                              transition:all 0.2s; min-width:32px; height:32px;"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('karyawan_destroy', $item->id_karyawan) }}" method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
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
                                <i class="fas fa-users" style="font-size:48px; color:#d1d5db; margin-bottom:16px; display:block;"></i>
                                Tidak ada data karyawan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
{{ $karyawan->links() }}

@push('scripts')
<script>
    $(document).ready(function() {
        $('#karyawanTable').DataTable({
            responsive: true,
            autoWidth: false,
            scrollX: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            columnDefs: [
                { targets: [0], orderable: false },
                { targets: [8,9,10], className: 'text-right' },
                { targets: [11], orderable: false, className: 'text-center' }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
    });
</script>
@endpush
