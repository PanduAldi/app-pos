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

    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric',
            'bayar' => 'required|numeric',
            'kembali' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // Generate Kode Transaksi: TR-YYYYMMDD-XXXX
            $date = Carbon::now()->format('Ymd');
            $latest = Transaksi::whereDate('tanggal', Carbon::now())->latest()->first();
            $number = $latest ? (int) substr($latest->kode_transaksi, -4) + 1 : 1;
            $kode = 'TR-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kode,
                'tanggal' => Carbon::now(),
                'id_user' => auth()->id(),
                'total' => $request->total,
                'bayar' => $request->bayar,
                'kembali' => $request->kembali,
                'metode_pembayaran' => $request->metode_pembayaran
            ]);

            foreach ($request->items as $item) {
                // Save Detail
                $transaksi->detailTransaksi()->create([
                    'id_produk' => $item['id'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty']
                ]);

                // Update Stock
                $produk = Produk::findOrFail($item['id']);
                if ($produk->stok < $item['qty']) {
                    throw new \Exception("Stok produk '{$produk->nama_produk}' tidak mencukupi.");
                }
                $produk->decrement('stok', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'kode_transaksi' => $kode
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
