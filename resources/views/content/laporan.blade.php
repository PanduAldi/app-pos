@extends('layout.layout')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header & Filter -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Laporan Transaksi</h1>
            <p class="text-slate-500 dark:text-slate-400">Pantau performa penjualan anda di sini.</p>
        </div>

        <form action="{{ route('laporan') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-3 bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
            <div class="grid grid-cols-2 gap-3 w-full sm:w-auto">
                <div class="space-y-1">
                    <label for="start_date" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary focus:border-primary text-sm">
                </div>
                <div class="space-y-1">
                    <label for="end_date" class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                        class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary focus:border-primary text-sm">
                </div>
            </div>
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">payments</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Pendapatan</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">receipt_long</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Transaksi</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $totalTransactions }} Transaksi</p>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">trending_up</span>
                Tren Penjualan
            </h2>
        </div>
        <div class="relative h-[350px] w-full">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Riwayat Transaksi</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($transaksi as $t)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                            {{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="showDetail('{{ $t->id }}')" class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-md text-xs font-mono font-medium hover:bg-primary hover:text-white transition-all cursor-pointer">
                                {{ $t->kode_transaksi }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                            {{ $t->user->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full 
                                @if($t->metode_pembayaran == 'cash') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                @elseif($t->metode_pembayaran == 'qris') bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400
                                @else bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 @endif">
                                {{ ucfirst($t->metode_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800 dark:text-white text-right">
                            Rp {{ number_format($t->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-4xl opacity-20">inventory_2</span>
                                <p>Tidak ada data transaksi pada rentang tanggal ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Transaction Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeModal()"></div>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white" id="modal-title">Detail Transaksi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400" id="modal-kode">-</p>
                </div>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="px-6 py-6 space-y-6">
                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Tanggal Transaksi</p>
                        <p class="font-semibold text-slate-800 dark:text-white" id="modal-tanggal">-</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Kasir</p>
                        <p class="font-semibold text-slate-800 dark:text-white" id="modal-kasir">-</p>
                    </div>
                    <div>
                        <p class="text-slate-500 dark:text-slate-400">Metode Pembayaran</p>
                        <p class="font-semibold text-slate-800 dark:text-white" id="modal-metode">-</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th class="px-4 py-2 font-semibold text-slate-600 dark:text-slate-300">Produk</th>
                                <th class="px-4 py-2 font-semibold text-slate-600 dark:text-slate-300 text-center">Qty</th>
                                <th class="px-4 py-2 font-semibold text-slate-600 dark:text-slate-300 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items-body" class="divide-y divide-slate-100 dark:divide-slate-700">
                            <!-- Items will be injected here -->
                        </tbody>
                        <tfoot class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <td colspan="2" class="px-4 py-3 font-bold text-slate-800 dark:text-white">Total</td>
                                <td class="px-4 py-3 font-bold text-slate-800 dark:text-white text-right" id="modal-total">Rp 0</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-slate-500 dark:text-slate-400">Bayar</td>
                                <td class="px-4 py-2 text-slate-800 dark:text-white text-right" id="modal-bayar">Rp 0</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="px-4 py-2 text-slate-500 dark:text-slate-400">Kembali</td>
                                <td class="px-4 py-2 text-emerald-600 dark:text-emerald-400 text-right font-medium" id="modal-kembali">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 flex justify-end">
                <button onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initChart();
    });

    function initChart() {
        const ctx = document.getElementById('salesChart');
        if (!ctx) return;

        const chartLabels = @json($labels);
        const chartValues = @json($values);

        if (chartLabels.length === 0) {
            ctx.parentNode.innerHTML = '<div class="h-full flex items-center justify-center text-slate-400 italic">Tidak ada data untuk grafik.</div>';
            return;
        }

        const context = ctx.getContext('2d');
        const gradient = context.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Penjualan',
                    data: chartValues,
                    borderColor: '#3b82f6',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        callbacks: {
                            label: (context) => 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw)
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(203, 213, 225, 0.1)'
                        },
                        ticks: {
                            callback: (value) => {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'k';
                                return 'Rp ' + value;
                            },
                            color: '#64748b',
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    }

    // Modal variable in global scope
    const modal = document.getElementById('detailModal');

    // showDetail in global scope
    window.showDetail = function(id) {
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        fetch(`/laporan/detail/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modal-kode').textContent = data.kode_transaksi;
                document.getElementById('modal-tanggal').textContent = new Date(data.tanggal).toLocaleString('id-ID', {
                    dateStyle: 'long',
                    timeStyle: 'short'
                });
                document.getElementById('modal-kasir').textContent = data.user ? data.user.name : 'System';
                document.getElementById('modal-metode').textContent = data.metode_pembayaran.toUpperCase();
                document.getElementById('modal-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.total);
                document.getElementById('modal-bayar').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.bayar);
                document.getElementById('modal-kembali').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.kembali);

                const body = document.getElementById('modal-items-body');
                body.innerHTML = '';

                data.detail_transaksi.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-3">
                            <div class="font-medium text-slate-800 dark:text-white">${item.produk.nama_produk}</div>
                            <div class="text-xs text-slate-500">${item.produk.kategori.nama_kategori}</div>
                        </td>
                        <td class="px-4 py-3 text-center text-slate-600 dark:text-slate-300">${item.qty}</td>
                        <td class="px-4 py-3 text-right font-medium text-slate-800 dark:text-white">
                            Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}
                        </td>
                    `;
                    body.appendChild(tr);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil detail transaksi');
                closeModal();
            });
    };

    window.closeModal = function() {
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };
</script>
@endsection