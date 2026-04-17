<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function logAktivitas()
    {
        $logs = LogAktivitas::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.log-aktivitas', compact('logs'));
    }
}
