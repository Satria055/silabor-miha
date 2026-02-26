<?php

namespace App\Controllers;

class Notification extends BaseController
{
    public function read($id)
    {
        $db = \Config\Database::connect();
        
        // Ambil data notifikasi
        $notif = $db->table('notifications')->where('id', $id)->where('user_id', session()->get('id'))->get()->getRow();
        
        if ($notif) {
            // Tandai sudah dibaca
            $db->table('notifications')->where('id', $id)->update(['is_read' => 1]);
            return redirect()->to($notif->link);
        }
        
        return redirect()->back();
    }
}