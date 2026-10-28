<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Hanya notifikasi milik pengguna yang login, diurutkan dari yang terbaru.
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->get();

        return view('notifications.index', compact('notifications'));
    }

    // Dicari lewat relasi user, jadi notifikasi milik orang lain menghasilkan 404.
    public function markAsRead(string $id)
    {
        Auth::user()->notifications()->where('id', $id)->firstOrFail()->markAsRead();

        return redirect()->route('notifications.index');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->route('notifications.index')
            ->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}