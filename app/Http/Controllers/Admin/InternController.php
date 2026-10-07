<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InternController extends Controller
{
    public function index()
    {
        $interns = User::with('internProfile')
            ->whereHas('role', function ($q) {
                $q->where('slug', 'intern');
            })
            ->latest()
            ->paginate(10);

        return view('admin.interns.index', compact('interns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nim' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::where('slug', 'intern')->first();

            $user = User::create([
                'role_id' => $role->id,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->username.'@absensi.local', // Dummy email required by DB
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            InternProfile::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'university' => $request->university,
                'division' => $request->division,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            DB::commit();

            return back()->with('success', 'Akun anak magang berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'nim' => 'required|string|max:255',
            'university' => 'required|string|max:255',
            'division' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->username.'@absensi.local',
                'is_active' => $request->is_active,
            ];

            // Update password ONLY if filled
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            $profile = InternProfile::firstOrCreate(['user_id' => $user->id]);
            $profile->update([
                'nim' => $request->nim,
                'university' => $request->university,
                'division' => $request->division,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            DB::commit();

            return back()->with('success', 'Data magang berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            // Optionally, delete profile or let it be. SoftDeletes is on User.
            $user->delete();

            return back()->with('success', 'Anak magang berhasil dihapus dari sistem.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: '.$e->getMessage());
        }
    }
}
