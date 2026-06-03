<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show student profile
     */
    public function show()
    {
        return view('profile');
    }

    /**
     * Update student avatar
     */
    public function updateAvatar(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Ambil data user yang sedang login
        $user = Auth::user();

        $file = $request->file('avatar');

        // 3. Buat nama file super unik
        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();

        // 4. Hapus foto lama
        if ($user->avatar && Storage::disk('s3')->exists($user->avatar)) {
            Storage::disk('s3')->delete($user->avatar);
        }

        // 5. Upload foto baru ke Supabase
        Storage::disk('s3')->putFileAs('/', $file, $filename, 'public');

        // 6. Update database
        $user->update([
            'avatar' => $filename
        ]);

        return back()->with('status', 'Foto profil berhasil diperbarui!');
    }

    // =========================================================
    // KONEKSI ORANG TUA
    // =========================================================

    /**
     * Approve parent connection request
     */
    public function approveKoneksi($parentId)
    {
        $parent = User::findOrFail($parentId);

        // Security: Verify ownership (IDOR fix)
        if ($parent->child_id_code == Auth::user()->user_code) {
            $parent->update(['child_connection_status' => 'approved']);
        }

        return back();
    }

    /**
     * Reject parent connection request
     */
    public function rejectKoneksi($parentId)
    {
        $parent = User::findOrFail($parentId);

        // Security: Verify ownership (IDOR fix)
        if ($parent->child_id_code == Auth::user()->user_code) {
            $parent->update([
                'child_id_code' => null,
                'child_connection_status' => null
            ]);
        }

        return back();
    }

    /**
     * Revoke/remove parent connection
     */
    public function revokeKoneksi($parentId)
    {
        $parent = User::findOrFail($parentId);

        // Security: Verify ownership (IDOR fix)
        if ($parent->child_id_code == Auth::user()->user_code) {
            $parent->update(['child_id_code' => null]);
        }

        return back()->with('success', 'Koneksi dilepaskan.');
    }
}