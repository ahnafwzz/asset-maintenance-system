<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RoleRequestedNotification extends Notification
{
    use Queueable;

    protected $user;

    // Menangkap data user yang mengajukan role
    public function __construct($user)
    {
        $this->user = $user;
    }

    // Beri tahu Laravel untuk menyimpan notif ini ke Database
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Format data yang akan disimpan ke tabel dan ditampilkan di Lonceng
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Hak Akses',
            'message' => $this->user->name . ' mengajukan peran sebagai ' . $this->user->requested_role,
            'url' => route('users.index'), // Nanti kalau diklik, loncat ke halaman User Approvals
            'icon' => '🛡️'
        ];
    }
}