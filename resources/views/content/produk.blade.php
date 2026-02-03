@extends('layout.layout')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex flex-col md:flex-row md:items-center gap-4 flex-1">
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white shrink-0">Daftar Produk</h1>

            <form action="{{ route('produk') }}" method="GET" class="relative w-full md:max-w-xs">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 material-symbols-outlined text-[20px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                    class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-sm focus:ring-primary focus:border-primary dark:text-white">
            </form>
        </div>

        <button onclick="openAddModal()" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2 shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-[20px]">add_circle</span>
            Tambah Produk
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-slate-800 dark:text-green-400 border border-green-200 dark:border-green-900/30 flex items-center gap-2" role="alert">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-slate-800 dark:text-red-400 border border-red-200 dark:border-red-900/30 flex items-center gap-2" role="alert">
        <span class="material-symbols-outlined text-[20px]">error</span>
        {{ session('error') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">inventory_2</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Produk</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ $produk->total() }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">warning</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Stok Menipis</p>
                <p class="text-2xl font-bold text-slate-800 dark:text-white">{{ \App\Models\Produk::where('stok', '<', 5)->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Harga</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Stok</th>
                        <th class="px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($produk as $p)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-slate-800 dark:text-white">{{ $p->nama_produk }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                {{ $p->kategori->nama_kategori ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-800 dark:text-white text-right">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm @if($p->stok < 5) text-red-600 font-bold @else text-slate-600 dark:text-slate-300 @endif">
                                {{ $p->stok }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick='openEditModal(@json($p))' class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                @if($p->detailTransaksi->count() > 0)
                                <button onclick="alert('Produk tidak bisa dihapus karena sudah memiliki riwayat transaksi. Untuk menonaktifkan, silakan atur stok menjadi 0.')" class="p-2 text-slate-300 cursor-not-allowed" title="Terikat transaksi">
                                    <span class="material-symbols-outlined text-[20px]">delete_forever</span>
                                </button>
                                @else
                                <form action="{{ route('produk.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                            <div class="flex flex-col items-center gap-2">
                                <span class="material-symbols-outlined text-4xl opacity-20">inventory_2</span>
                                <p>Belum ada data produk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($produk->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $produk->appends(['search' => request('search')])->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Produk -->
<div id="produkModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeModal()"></div>
        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700">
            <form id="produkForm" method="POST">
                @csrf
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white" id="modalTitle">Tambah Produk</h3>
                    <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" required
                            class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary focus:border-primary text-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Kategori</label>
                        <select name="id_kategori" id="id_kategori" required
                            class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary focus:border-primary text-sm">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Harga</label>
                            <input type="number" name="harga" id="harga" required
                                class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary focus:border-primary text-sm">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Stok</label>
                            <input type="number" name="stok" id="stok" required
                                class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary focus:border-primary text-sm">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors shadow-lg shadow-primary/20">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const modal = document.getElementById('produkModal');
    const form = document.getElementById('produkForm');
    const modalTitle = document.getElementById('modalTitle');

    function openAddModal() {
        modalTitle.innerText = 'Tambah Produk';
        form.action = "{{ route('produk.store') }}";
        form.reset();
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(produk) {
        modalTitle.innerText = 'Edit Produk';
        form.action = `/produk/update/${produk.id}`;
        document.getElementById('nama_produk').value = produk.nama_produk;
        document.getElementById('id_kategori').value = produk.id_kategori;
        document.getElementById('harga').value = produk.harga;
        document.getElementById('stok').value = produk.stok;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection