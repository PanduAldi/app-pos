@extends('layout.layout')

@section('content')

<div class="p-4">
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard BarisKode Cafe</h1>
        <p class="text-slate-500 dark:text-slate-400 text-base font-normal">{{ auth()->user()->name }}. Selamat datang di POS Apps BarisKoode Cafe.</p>
    </div>
    <div class="flex items-center gap-3 bg-white dark:bg-[#1A2633] px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm mb-2">
        <span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">calendar_today</span>
        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
    </div>

    <!-- Stats Widgets -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <div class="flex flex-col gap-1 p-6 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#1A2633] shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-primary text-6xl">payments</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">Penjualan Hari Ini</p>
            <div class="flex items-baseline gap-2 mt-2">
                <p class="text-slate-900 dark:text-white text-3xl font-bold tracking-tight">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
                <!-- <span class="inline-flex items-center text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded text-xs font-bold">+12%</span> -->
            </div>
        </div>
        <div class="flex flex-col gap-1 p-6 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-[#1A2633] shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-primary text-6xl">receipt_long</span>
            </div>
            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium uppercase tracking-wider">Total Transaksi</p>
            <div class="flex items-baseline gap-2 mt-2">
                <p class="text-slate-900 dark:text-white text-3xl font-bold tracking-tight">{{ $todayTransactions }}</p>
                <!-- <span class="inline-flex items-center text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded text-xs font-bold">+5%</span> -->
            </div>
        </div>

    </div>
    <!-- Quick Actions Title -->

    <!-- Quick Action Buttons -->
    <div class="flex flex-wrap gap-4 mb-8">
        @if(auth()->user()->role == 'kasir')
        <button class="flex items-center justify-center gap-3 px-8 py-4 bg-primary hover:bg-blue-600 text-white rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 w-full sm:w-auto min-w-[200px]">
            <span class="material-symbols-outlined" style="font-size: 28px;">add_circle</span>
            <span class="text-lg font-bold">Mulai Transaksi Baru</span>
        </button>
        @else
        <button class="flex items-center justify-center gap-3 px-8 py-4 bg-white dark:bg-[#1A2633] border border-slate-200 dark:border-slate-700 hover:border-primary/50 text-slate-700 dark:text-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all w-full sm:w-auto min-w-[200px]">
            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400" style="font-size: 28px;">add_box</span>
            <span class="text-lg font-medium">Tambah Produk</span>
        </button>
        @endif

    </div>
    <!-- Recent Activity Table -->
    <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
            <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight">Transaksi Terakhir</h2>
            <a class="text-primary text-sm font-medium hover:underline" href="#">Lihat Semua</a>
        </div>
        <div class="bg-white dark:bg-[#1A2633] rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700/50 bg-slate-50 dark:bg-slate-800/50">
                            <th class="p-4 text-xs font-semibold tracking-wide text-slate-500 dark:text-slate-400 uppercase">ID Struk</th>
                            <th class="p-4 text-xs font-semibold tracking-wide text-slate-500 dark:text-slate-400 uppercase">Tanggal &amp; Waktu</th>
                            <th class="p-4 text-xs font-semibold tracking-wide text-slate-500 dark:text-slate-400 uppercase">Metode</th>
                            <th class="p-4 text-xs font-semibold tracking-wide text-slate-500 dark:text-slate-400 uppercase">Jumlah Item</th>
                            <th class="p-4 text-xs font-semibold tracking-wide text-slate-500 dark:text-slate-400 uppercase text-right">Total Harga</th>
                            <th class="p-4 text-xs font-semibold tracking-wide text-slate-500 dark:text-slate-400 uppercase text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($recentTransactions as $trx)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="p-4 text-sm font-medium text-slate-900 dark:text-white">#{{ $trx->kode_transaksi }}</td>
                            <td class="p-4 text-sm text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d M, H:i') }}</td>
                            <td class="p-4 text-sm text-slate-600 dark:text-slate-300">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-slate-400 text-lg">
                                        @if($trx->metode_pembayaran == 'cash') payments
                                        @elseif($trx->metode_pembayaran == 'qris') qr_code_scanner
                                        @else credit_card @endif
                                    </span>
                                    {{ ucfirst($trx->metode_pembayaran) }}
                                </div>
                            </td>
                            <td class="p-4 text-sm text-slate-600 dark:text-slate-300">{{ $trx->detailTransaksi->sum('qty') }}</td>
                            <td class="p-4 text-sm font-bold text-slate-900 dark:text-white text-right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Selesai</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">Belum ada transaksi tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 p-3 flex justify-center border-t border-slate-100 dark:border-slate-700/50">
                <button class="text-sm text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary font-medium transition-colors">Tampilkan lebih banyak transaksi</button>
            </div>
        </div>
    </div>
</div>

@endsection