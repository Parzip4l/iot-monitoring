<?php

namespace App\Helpers;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    /**
     * Catat log ke database
     */
    public static function addToLog(string $action): void
    {
        try {
            SystemLog::create([
                'user_id'   => Auth::id(),
                'action'    => $action,
                'ip_address'=> request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Jangan sampai aplikasi error gara-gara logging
            \Log::error("Gagal simpan log: " . $e->getMessage());
        }
    }
}
