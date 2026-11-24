<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'totalContents' => Content::count(),
            'totalSettings' => Setting::count(),
            'totalTransactions' => Transaction::count(),
            'pendingTransactions' => Transaction::where('status', 'pending')->count(),
            'latestUsers' => User::latest()->limit(5)->get(),
            'latestTransactions' => Transaction::latest()->limit(5)->get(),
        ]);
    }
}
