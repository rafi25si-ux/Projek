<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('reservasis')
            ->where('role', 'pelanggan')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        abort_if($user->role === 'admin', 403, 'Akun Admin tidak dapat dinonaktifkan.');

        $statusBaru = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->update(['status' => $statusBaru]);

        return back()->with('success', 'Status akun ' . $user->name . ' diubah menjadi ' . $statusBaru . '.');
    }
}