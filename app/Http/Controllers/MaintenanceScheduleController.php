<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MaintenanceScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('maintenance_schedule as ms')
            ->leftJoin('unit as u', 'ms.unit_id', '=', 'u.id_unit')
            ->leftJoin('karyawan as k', 'ms.operator_id', '=', 'k.id_karyawan')
            ->leftJoin('sparepart as sp', 'ms.Sparepart_id', '=', 'sp.id_sparepart')
            ->select(
                'ms.*',
                'u.nama_unit',
                'u.kode_unit',
                'k.nama_karyawan as operator_name',
                'sp.nama_sparepart'
            );

        // Filter berdasarkan jenis maintenance
        if ($request->filled('jenis_maintenance')) {
            $query->where('ms.jenis_maintenance', 'like', '%' . $request->jenis_maintenance . '%');
        }

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('ms.unit_id', $request->unit_id);
        }

        // Filter berdasarkan operator
        if ($request->filled('operator_id')) {
            $query->where('ms.operator_id', $request->operator_id);
        }

        // Filter berdasarkan status aktif
        if ($request->filled('is_active')) {
            $query->where('ms.is_active', $request->is_active);
        }

        // Filter berdasarkan tanggal mulai
        if ($request->filled('tanggal_dari')) {
            $query->where('ms.Mulai_dikerjakan', '>=', $request->tanggal_dari);
        }

        // Filter berdasarkan tanggal selesai
        if ($request->filled('tanggal_sampai')) {
            $query->where('ms.Selesai_dikerjakan', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ms.jenis_maintenance', 'like', '%' . $search . '%')
                  ->orWhere('ms.deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('u.nama_unit', 'like', '%' . $search . '%')
                  ->orWhere('k.nama_karyawan', 'like', '%' . $search . '%');
            });
        }

        $schedules = $query->orderBy('ms.created_at', 'desc')->paginate(15);

        // Get data untuk dropdown
        $units = DB::table('unit')->where('is_active', 1)->get();
        $operators = DB::table('karyawan')->where('Status', 'Aktif')->get();
        $spareparts = DB::table('sparepart')->where('is_active', 1)->get();

        // Statistics
        $stats = [
            'total_schedule' => DB::table('maintenance_schedule')->count(),
            'active_schedule' => DB::table('maintenance_schedule')->where('is_active', 1)->count(),
            'completed_schedule' => DB::table('maintenance_schedule')
                ->whereNotNull('Selesai_dikerjakan')
                ->where('is_active', 1)
                ->count(),
            'pending_schedule' => DB::table('maintenance_schedule')
                ->whereNull('Selesai_dikerjakan')
                ->where('is_active', 1)
                ->count(),
        ];

        return view('maintenance_schedule.index', compact('schedules', 'units', 'operators', 'spareparts', 'stats'));
    }

    // public function create()
    // {
    //     $units = DB::table('unit')->where('is_active', 1)->get();
    //     $operators = DB::table('karyawan')->where('Status', 'Aktif')->get();
    //     $spareparts = DB::table('sparepart')->where('is_active', 1)->get();

    //     return view('maintenance_schedule.create', compact('units', 'operators', 'spareparts'));
    // }

    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:unit,id_unit',
            'jenis_maintenance' => 'required|max:100',
            'deskripsi' => 'nullable',
            'Mulai_dikerjakan' => 'nullable|date',
            'Selesai_dikerjakan' => 'nullable|date|after_or_equal:Mulai_dikerjakan',
            'Sparepart_id' => 'required|exists:sparepart,id_sparepart',
            'operator_id' => 'required|exists:karyawan,id_karyawan',
            'is_active' => 'nullable|boolean'
        ]);

        $data = [
            'unit_id' => $request->unit_id,
            'jenis_maintenance' => $request->jenis_maintenance,
            'deskripsi' => $request->deskripsi,
            'Mulai_dikerjakan' => $request->Mulai_dikerjakan,
            'Selesai_dikerjakan' => $request->Selesai_dikerjakan,
            'Sparepart_id' => $request->Sparepart_id,
            'operator_id' => $request->operator_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('maintenance_schedule')->insert($data);

        return redirect()->route('maintenance-schedule.index')
            ->with('success', 'Jadwal maintenance berhasil ditambahkan!');
    }

    public function show($id)
    {
        $schedule = DB::table('maintenance_schedule as ms')
            ->leftJoin('unit as u', 'ms.unit_id', '=', 'u.id_unit')
            ->leftJoin('karyawan as k', 'ms.operator_id', '=', 'k.id_karyawan')
            ->leftJoin('sparepart as sp', 'ms.Sparepart_id', '=', 'sp.id_sparepart')
            ->leftJoin('jenis_unit as ju', 'u.jenis_unit_id', '=', 'ju.id_jenis_unit')
            ->select(
                'ms.*',
                'u.nama_unit',
                'u.kode_unit',
                'u.merk',
                'u.model',
                'u.status_kondisi',
                'u.status_operasional',
                'k.nama_karyawan as operator_name',
                'k.no_handphone',
                'sp.nama_sparepart',
                'sp.kode_sparepart',
                'sp.stok_saat_ini',
                'ju.nama_jenis_unit'
            )
            ->where('ms.id_schedule', $id)
            ->first();

        if (!$schedule) {
            return redirect()->route('maintenance-schedule.index')
                ->with('error', 'Data jadwal maintenance tidak ditemukan!');
        }

        return view('maintenance_schedule.show', compact('schedule'));
    }

    // public function edit($id)
    // {
    //     $schedule = DB::table('maintenance_schedule')->where('id_schedule', $id)->first();

    //     if (!$schedule) {
    //         return redirect()->route('maintenance-schedule.index')
    //             ->with('error', 'Data jadwal maintenance tidak ditemukan!');
    //     }

    //     $units = DB::table('unit')->where('is_active', 1)->get();
    //     $operators = DB::table('karyawan')->where('Status', 'Aktif')->get();
    //     $spareparts = DB::table('sparepart')->where('is_active', 1)->get();

    //     return view('maintenance_schedule.edit', compact('schedule', 'units', 'operators', 'spareparts'));
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'unit_id' => 'required|exists:unit,id_unit',
            'jenis_maintenance' => 'required|max:100',
            'deskripsi' => 'nullable',
            'Mulai_dikerjakan' => 'nullable|date',
            'Selesai_dikerjakan' => 'nullable|date|after_or_equal:Mulai_dikerjakan',
            'Sparepart_id' => 'required|exists:sparepart,id_sparepart',
            'operator_id' => 'required|exists:karyawan,id_karyawan',
            'is_active' => 'nullable|boolean'
        ]);

        $data = [
            'unit_id' => $request->unit_id,
            'jenis_maintenance' => $request->jenis_maintenance,
            'deskripsi' => $request->deskripsi,
            'Mulai_dikerjakan' => $request->Mulai_dikerjakan,
            'Selesai_dikerjakan' => $request->Selesai_dikerjakan,
            'Sparepart_id' => $request->Sparepart_id,
            'operator_id' => $request->operator_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'updated_at' => now()
        ];

        $updated = DB::table('maintenance_schedule')
            ->where('id_schedule', $id)
            ->update($data);

        if (!$updated) {
            return redirect()->route('maintenance-schedule.index')
                ->with('error', 'Data jadwal maintenance tidak ditemukan!');
        }

        return redirect()->route('maintenance-schedule.index')
            ->with('success', 'Jadwal maintenance berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $deleted = DB::table('maintenance_schedule')
            ->where('id_schedule', $id)
            ->delete();

        if (!$deleted) {
            return redirect()->route('maintenance-schedule.index')
                ->with('error', 'Data jadwal maintenance tidak ditemukan!');
        }

        return redirect()->route('maintenance-schedule.index')
            ->with('success', 'Jadwal maintenance berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $updated = DB::table('maintenance_schedule')
            ->where('id_schedule', $id)
            ->update([
                'is_active' => $request->is_active,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        $status = $request->is_active ? 'aktif' : 'nonaktif';
        return response()->json(['success' => "Status berhasil diubah menjadi {$status}"]);
    }
}
