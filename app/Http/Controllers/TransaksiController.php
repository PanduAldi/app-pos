<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    //
    public function index()
    {
        $kategori = Kategori::all();
        $produk = Produk::with('kategori')->get();

        return view('content.transaksi', [
            'title' => 'Transaksi',
            'kategori' => $kategori,
            'produk' => $produk
        ]);
    }

    public function laporan(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $transaksi = Transaksi::with('user')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Data for Chart
        $chartData = Transaksi::select(
            DB::raw('DATE(tanggal) as date'),
            DB::raw('SUM(total) as total_sales')
        )
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $labels = $chartData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });
        $values = $chartData->pluck('total_sales');

        $totalRevenue = $transaksi->sum('total');
        $totalTransactions = $transaksi->count();

        return view('content.laporan', [
            'title' => 'Laporan Transaksi',
            'transaksi' => $transaksi,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'labels' => $labels,
            'values' => $values,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions
        ]);
    }

    public function detailLaporan($id)
    {
        $transaksi = Transaksi::with(['user', 'detailTransaksi.produk.kategori'])->find($id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json($transaksi);
    }
}
