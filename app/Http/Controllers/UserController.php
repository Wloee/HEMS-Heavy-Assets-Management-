<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Karyawan;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request): View
{
    try {
        // First, let's check what columns actually exist in the karyawan table
        $karyawanColumns = DB::getSchemaBuilder()->getColumnListing('karyawan');
        Log::info('Available columns in karyawan table:', $karyawanColumns);

        // Build the base query with available columns
        $query = DB::table('users')
            ->leftJoin('karyawan', 'users.karyawan_id', '=', 'karyawan.id_karyawan');

        // Dynamic select based on available columns
        $selectFields = ['users.*'];

        // Check for common name column variations
        $nameColumns = ['nama_karyawan', 'nama', 'name', 'full_name', 'employee_name'];
        $foundNameColumn = null;

        foreach ($nameColumns as $nameCol) {
            if (in_array($nameCol, $karyawanColumns)) {
                $foundNameColumn = $nameCol;
                $selectFields[] = "karyawan.{$nameCol} as nama_karyawan";
                break;
            }
        }

        // Add other fields if they exist
        if (in_array('departemen_id', $karyawanColumns)) {
            $selectFields[] = 'karyawan.departemen_id';
        }
        if (in_array('posisi_id', $karyawanColumns)) {
            $selectFields[] = 'karyawan.posisi_id';
        }

        $query->select($selectFields);

        // Search functionality - adjust based on available columns
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search, $foundNameColumn) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");

                // Only search in karyawan name if column exists
                if ($foundNameColumn) {
                    $q->orWhere("karyawan.{$foundNameColumn}", 'like', "%{$search}%");
                }
            });
        }

        // Filter berdasarkan role
        if ($request->has('role') && $request->input('role') !== '') {
            $query->where('users.role', $request->input('role'));
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->input('status') !== '') {
            $status = $request->input('status');
            if ($status === 'active' || $status === '1') {
                $query->where('users.is_active', 1);
            } elseif ($status === 'inactive' || $status === '0') {
                $query->where('users.is_active', 0);
            }
        }

        // Get paginated results
        $users = $query->orderBy('users.name', 'asc')
                      ->paginate(10)
                      ->appends($request->query());

        // Untuk dropdown karyawan - hanya jika kolom nama ditemukan
        $karyawan = collect();
        if ($foundNameColumn) {
            $karyawan = DB::table('karyawan')
                ->select('id_karyawan', "{$foundNameColumn} as nama_karyawan")
                ->whereNotNull($foundNameColumn)
                ->distinct()
                ->orderBy($foundNameColumn, 'asc')
                ->get();
        }

        // Statistik users menggunakan DB query
        $totalUsers = DB::table('users')->count();
        $activeUsers = DB::table('users')->where('is_active', 1)->count();
        $inactiveUsers = DB::table('users')->where('is_active', 0)->count();

        return view('Setting.User', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers', 'karyawan'))
            ->with('i', ($request->input('page', 1) - 1) * 10);

    } catch (\Exception $e) {
        Log::error('Error loading users data: ' . $e->getMessage(), [
            'user_id' => Auth::user()->id ?? null,
            'request_data' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);

        // Return view dengan data kosong dan error message
        $emptyPagination = new \Illuminate\Pagination\LengthAwarePaginator(
            collect(), 0, 10, 1, ['path' => $request->url()]
        );

        return view('Setting.User', [
            'users' => $emptyPagination,
            'totalUsers' => 0,
            'activeUsers' => 0,
            'inactiveUsers' => 0,
            'karyawan' => collect(),
            'error' => 'Gagal memuat data users: ' . $e->getMessage()
        ]);
    }
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            // Menggunakan Query Builder untuk ambil karyawan yang belum punya user
            $karyawans = DB::table('karyawan')
                ->leftJoin('users', 'karyawan.id_karyawan', '=', 'users.karyawan_id')
                ->whereNull('users.karyawan_id')
                ->select([
                    'karyawan.id_karyawan',
                    'karyawan.nama_karyawan',
                    'karyawan.departemen_id',
                    'karyawan.posisi_id'
                ])
                ->orderBy('karyawan.nama_karyawan', 'asc')
                ->get();

            if ($karyawans->isEmpty()) {
                return redirect()->route('user.index')
                    ->with('warning', 'Semua karyawan sudah memiliki akun user.');
            }

            return view('Setting.form', compact('karyawans'));

        } catch (\Exception $e) {
            Log::error('Error loading create user form: ' . $e->getMessage());

            return redirect()->route('user.index')
                ->with('error', 'Gagal memuat form: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Insert user menggunakan Query Builder
            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'karyawan_id' => $request->karyawan_id,
                'is_active' => $request->is_active ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            Log::info('User created successfully', [
                'user_id' => $userId,
                'created_by' => Auth::user()->id ?? null
            ]);

            return redirect()->route('user.index')
                           ->with('success', 'User berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error creating user: ' . $e->getMessage(), [
                'request_data' => $request->except('password')
            ]);

            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        try {
            // Menggunakan Query Builder untuk get user detail dengan karyawan
            $userDetail = DB::table('users')
                ->leftJoin('karyawan', 'users.karyawan_id', '=', 'karyawan.id_karyawan')
                ->leftJoin('departemen', 'karyawan.departemen_id', '=', 'departemen.id_departemen')
                ->leftJoin('posisi', 'karyawan.posisi_id', '=', 'posisi.id_posisi')
                ->where('users.id', $user->id)
                ->select([
                    'users.*',
                    'karyawan.nama_karyawan',
                    'karyawan.no_handphone',
                    'karyawan.tanggal_bergabung',
                    'departemen.nama_departemen',
                    'posisi.nama_posisi'
                ])
                ->first();

            return view('Setting.User-detail', compact('userDetail'));

        } catch (\Exception $e) {
            Log::error('Error showing user: ' . $e->getMessage(), ['user_id' => $user->id]);

            return view('Setting.User-detail', [
                'userDetail' => null,
                'error' => 'Gagal memuat detail user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            // Menggunakan Query Builder untuk ambil karyawan yang available
            $karyawans = DB::table('karyawan')
                ->leftJoin('users', function($join) use ($user) {
                    $join->on('karyawan.id_karyawan', '=', 'users.karyawan_id')
                         ->where('users.id', '!=', $user->id);
                })
                ->whereNull('users.karyawan_id')
                ->orWhere('karyawan.id_karyawan', $user->karyawan_id)
                ->select([
                    'karyawan.id_karyawan',
                    'karyawan.nama_karyawan',
                    'karyawan.departemen_id',
                    'karyawan.posisi_id'
                ])
                ->orderBy('karyawan.nama_karyawan', 'asc')
                ->get();

            return view('Setting.form', compact('user', 'karyawans'));

        } catch (\Exception $e) {
            Log::error('Error loading edit user form: ' . $e->getMessage(), ['user_id' => $user->id]);

            return redirect()->route('user.index')
                ->with('error', 'Gagal memuat form edit: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */

public function update(UpdateUserRequest $request, User $user): RedirectResponse
{
    DB::beginTransaction();

    try {
        // Validate that user exists (extra safety check)
        if (!$user->exists) {
            throw new \Exception('User not found');
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active'), // Use boolean helper
            'updated_at' => now(),
        ];

        // Handle karyawan_id - allow null values
        if ($request->has('karyawan_id')) {
            $updateData['karyawan_id'] = $request->karyawan_id ?: null;
        }

        // Update password hanya jika diisi dan tidak kosong
        if ($request->filled('password') && !empty(trim($request->password))) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Validate email uniqueness (excluding current user)
        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            throw new \Exception('Email sudah digunakan oleh user lain');
        }

        // Validate karyawan_id exists if provided
        if (!empty($updateData['karyawan_id'])) {
            $karyawanExists = DB::table('karyawan')
                ->where('id_karyawan', $updateData['karyawan_id'])
                ->exists();

             
        }

        // Update using Eloquent model (more reliable than Query Builder)
        $user->fill($updateData);
        $user->save();

        // Alternative using Query Builder (if you prefer):
        /*
        $affected = DB::table('users')
            ->where('id', $user->id)
            ->update($updateData);

        if ($affected === 0) {
            throw new \Exception('No rows were updated. User may not exist.');
        }
        */

        DB::commit();

        Log::info('User updated successfully', [
            'user_id' => $user->id,
            'updated_by' => Auth::user()->id ?? null,
            'changes' => $user->getChanges() // Log what actually changed
        ]);

        return redirect()->route('user.index')
                       ->with('success', 'User berhasil diperbarui.');

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollback();

        // Handle specific database errors
        $errorMessage = 'Gagal memperbarui user: ';

        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            $errorMessage .= 'Email sudah digunakan.';
        } elseif (str_contains($e->getMessage(), 'foreign key constraint')) {
            $errorMessage .= 'Karyawan ID tidak valid.';
        } else {
            $errorMessage .= 'Terjadi kesalahan database.';
        }

        Log::error('Database error updating user: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'request_data' => $request->except('password'),
            'sql_error' => $e->errorInfo ?? null
        ]);

        return redirect()->back()
                       ->withInput($request->except('password'))
                       ->with('error', $errorMessage);

    } catch (\Exception $e) {
        DB::rollback();

        Log::error('Error updating user: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'request_data' => $request->except('password'),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
                       ->withInput($request->except('password'))
                       ->with('error', 'Gagal memperbarui user: ' . $e->getMessage());
    }
}

// Helper method untuk validasi tambahan (opsional)
private function validateUserUpdate(UpdateUserRequest $request, User $user): void
{
    // Check if trying to deactivate the last admin
    if ($user->role === 'admin' && !$request->boolean('is_active')) {
        $activeAdminCount = DB::table('users')
            ->where('role', 'admin')
            ->where('is_active', 1)
            ->where('id', '!=', $user->id)
            ->count();

        if ($activeAdminCount === 0) {
            throw new \Exception('Tidak dapat menonaktifkan admin terakhir.');
        }
    }

    // Check if trying to change own role (prevent privilege escalation)
    if (Auth::id() === $user->id && $request->role !== $user->role) {
        throw new \Exception('Tidak dapat mengubah role sendiri.');
    }
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $currentUserId = Auth::user()->id ?? null;

            // Cek apakah user sedang login
            if ($currentUserId === $user->id) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
            }

            // Simpan nama user untuk pesan sukses
            $userName = $user->name;

            // Delete menggunakan Query Builder
            DB::table('users')->where('id', $user->id)->delete();

            DB::commit();

            Log::info('User deleted successfully', [
                'deleted_user_id' => $user->id,
                'deleted_by' => $currentUserId
            ]);

            return redirect()->route('user.index')
                           ->with('success', "User '{$userName}' berhasil dihapus.");

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting user: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);

            return redirect()->back()
                           ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus(Request $request, User $user): JsonResponse|RedirectResponse
    {
        try {
            $currentUserId = Auth::user()->id ?? null;

            // Cek apakah user sedang login
            if ($currentUserId === $user->id) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menonaktifkan akun yang sedang digunakan.'
                    ], 422);
                }

                return redirect()->back()
                    ->with('error', 'Tidak dapat menonaktifkan akun yang sedang digunakan.');
            }

            $newStatus = !$user->is_active;

            // Update status menggunakan Query Builder
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now()
                ]);

            $status = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
            $message = "User '{$user->name}' berhasil {$status}";

            Log::info('User status toggled', [
                'user_id' => $user->id,
                'new_status' => $newStatus,
                'changed_by' => $currentUserId
            ]);

            // Jika request AJAX, return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'is_active' => $newStatus
                ]);
            }

            return redirect()->back()
                           ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengubah status user.'
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Gagal mengubah status user: ' . $e->getMessage());
        }
    }

    /**
     * Update user's last login timestamp
     */
    public function updateLastLogin(User $user): JsonResponse
    {
        try {
            // Update last login menggunakan Query Builder
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'last_login' => now(),
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Error updating last login: ' . $e->getMessage(), [
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users for AJAX requests (for select2, etc.)
     */
    public function getUsersAjax(Request $request): JsonResponse
    {
        try {
            // Menggunakan Query Builder untuk AJAX request
            $query = DB::table('users')
                ->select(['id', 'name', 'email'])
                ->where('is_active', 1);

            if ($search = $request->input('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->orderBy('name', 'asc')
                          ->limit(20)
                          ->get();

            return response()->json($users);

        } catch (\Exception $e) {
            Log::error('Error loading users via AJAX: ' . $e->getMessage());

            return response()->json([
                'error' => 'Gagal memuat data users'
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $stats = [
                'total_users' => DB::table('users')->count(),
                'active_users' => DB::table('users')->where('is_active', 1)->count(),
                'inactive_users' => DB::table('users')->where('is_active', 0)->count(),
                'users_by_role' => DB::table('users')
                    ->select('role', DB::raw('count(*) as total'))
                    ->groupBy('role')
                    ->get(),
                'recent_logins' => DB::table('users')
                    ->whereNotNull('last_login')
                    ->where('last_login', '>=', now()->subDays(7))
                    ->count()
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Error getting user statistics: ' . $e->getMessage());

            return response()->json([
                'error' => 'Gagal memuat statistik users'
            ], 500);
        }
    }
}
