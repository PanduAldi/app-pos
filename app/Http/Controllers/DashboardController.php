<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Metrics
        $todaySales = Transaksi::whereDate('tanggal', $today)->sum('total');
        $todayTransactions = Transaksi::whereDate('tanggal', $today)->count();

        // Recent Transactions
        $recentTransactions = Transaksi::with('user')
            ->orderBy('tanggal', 'desc')
            ->limit(5)
            ->get();

        return view('content.dashboard', [
            'title' => "Dashboard",
            'todaySales' => $todaySales,
            'todayTransactions' => $todayTransactions,

            'recentTransactions' => $recentTransactions
        ]);
    }
}
