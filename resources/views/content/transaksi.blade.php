@extends('layout.layout')

@section('css')
<style>
    /* Styling for the receipt preview in SweetAlert */
    .receipt-preview {
        font-family: 'Courier New', Courier, monospace;
        text-align: left;
        font-size: 13px;
        line-height: 1.2;
        color: #000;
        background: #fff;
        padding: 20px;
        border: 1px solid #eee;
        max-width: 320px;
        margin: 0 auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .receipt-header {
        text-align: center;
        margin-bottom: 10px;
    }

    .receipt-title {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 2px;
    }

    .receipt-divider {
        border-top: 1px dashed #000;
        margin: 8px 0;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    .receipt-item-details {
        padding-left: 10px;
        font-size: 12px;
        font-style: italic;
    }

    /* Print styles */
    @media print {
        body>*:not(#receipt-print) {
            display: none !important;
        }

        #receipt-print {
            display: block !important;
            width: 80mm;
            padding: 0;
            margin: 0 auto;
            background: #fff;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }
    }
</style>
@endsection

@section('content')
<!-- Receipt Template (Hidden from screen view) -->
<div id="receipt-print" class="hidden">
    <div class="receipt-preview" style="width: 100%; border: none; box-shadow: none;">
        <div class="receipt-header">
            <div class="receipt-title">CAFE BARISKODE</div>
            <div class="receipt-address">Jl. Code No. 123, Indonesia</div>
            <div class="receipt-phone">0812-3456-7890</div>
        </div>
        <div class="receipt-divider"></div>
        <div class="receipt-info">
            <div class="receipt-row">
                <span>No Trans:</span>
                <span id="p-kode-transaksi">-</span>
            </div>
            <div class="receipt-row">
                <span>Tanggal:</span>
                <span id="p-tanggal">-</span>
            </div>
            <div class="receipt-row">
                <span>Kasir:</span>
                <span id="p-kasir">{{ auth()->user()->name }}</span>
            </div>
        </div>
        <div class="receipt-divider"></div>
        <div id="p-items">
            <!-- Items will be injected here -->
        </div>
        <div class="receipt-divider"></div>
        <div class="receipt-totals">
            <div class="receipt-row">
                <span>Subtotal</span>
                <span id="p-subtotal">Rp 0</span>
            </div>
            <div class="receipt-row">
                <span>Pajak (2.5%)</span>
                <span id="p-tax">Rp 0</span>
            </div>
            <div class="receipt-row font-bold" style="font-weight: bold;">
                <span>TOTAL</span>
                <span id="p-total">Rp 0</span>
            </div>
        </div>
        <div class="receipt-divider"></div>
        <div class="receipt-payment">
            <div class="receipt-row">
                <span>Bayar (<span id="p-metode">-</span>)</span>
                <span id="p-bayar">Rp 0</span>
            </div>
            <div class="receipt-row">
                <span>Kembali</span>
                <span id="p-kembali">Rp 0</span>
            </div>
        </div>
        <div class="receipt-divider"></div>
        <div class="receipt-footer" style="text-align: center; margin-top: 10px;">
            <p>Terima Kasih</p>
            <p>Silahkan Datang Kembali</p>
        </div>
    </div>
</div>

<!-- Split View Content -->
<div class="flex flex-1 overflow-hidden h-full">
    <!-- Left Side: Product Catalog -->
    <div class="flex-1 flex flex-col min-w-0 bg-background-light dark:bg-background-dark relative">
        <!-- Search & Categories Bar -->
        <div class="p-6 pb-2 flex flex-col gap-4">
            <!-- Search -->
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input id="search-input" class="block w-full rounded-xl border-none bg-white dark:bg-slate-800 py-3 pl-10 pr-4 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-200 dark:ring-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Cari" type="text" />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-primary">
                    <span class="material-symbols-outlined">qr_code_scanner</span>
                </div>
            </div>
            <!-- Categories Chips -->
            <div class="flex gap-2 overflow-x-auto custom-scrollbar pb-2" id="category-filters">
                <button data-category="all" class="category-btn flex-shrink-0 px-4 py-2 rounded-lg bg-primary text-white text-sm font-medium transition-all shadow-sm shadow-primary/30"> Semua </button>
                @foreach($kategori as $kat)
                <button data-category="{{ $kat->nama_kategori }}" class="category-btn flex-shrink-0 px-4 py-2 rounded-lg bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-sm font-medium transition-all"> {{ $kat->nama_kategori }} </button>
                @endforeach
            </div>
        </div>
        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto p-6 pt-2 custom-scrollbar">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4" id="product-grid">
                @foreach($produk as $p)
                <!-- Product Card -->
                <div class="product-card group cursor-pointer flex flex-col rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-primary/50 transition-all active:scale-95"
                    data-id="{{ $p->id }}" data-name="{{ $p->nama_produk }}" data-price="{{ $p->harga }}" data-category="{{ $p->kategori->nama_kategori }}" data-stock="{{ $p->stok }}">
                    <div class="aspect-[4/3] w-full overflow-hidden rounded-t-xl relative">
                        {{-- Image placeholder --}}
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                            <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-5xl">image</span>
                        </div>
                        <div class="absolute top-2 right-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm rounded-md px-2 py-0.5 text-xs font-bold shadow-sm text-slate-900 dark:text-white"> Rp {{ number_format($p->harga, 0, ',', '.') }} </div>
                    </div>
                    <div class="p-3 flex flex-col gap-1">
                        <h3 class="product-name text-sm font-semibold text-slate-900 dark:text-white leading-tight">{{ $p->nama_produk }}</h3>
                        <div class="flex justify-between items-center">
                            <p class="text-[10px] text-slate-500 dark:text-slate-400">{{ $p->kategori->nama_kategori }}</p>
                            <p class="text-[10px] font-bold {{ $p->stok < 10 ? 'text-red-500' : 'text-slate-400' }}">Stok: {{ $p->stok }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Empty State Search -->
            <div id="empty-search" class="hidden flex-col items-center justify-center py-20 text-slate-400">
                <span class="material-symbols-outlined text-6xl mb-4">search_off</span>
                <p class="text-lg font-medium">Produk tidak ditemukan</p>
                <p class="text-sm">Coba gunakan kata kunci atau kategori lain</p>
            </div>
        </div>
    </div>
    <!-- Right Side: Cart / Transaction Panel -->
    <aside class="w-[420px] bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 flex flex-col shadow-xl z-10">
        <!-- Cart Header -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h3 class="font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">shopping_cart</span> Pesanan Saat Ini
            </h3>
            <button id="clear-cart" class="text-xs font-medium text-red-500 hover:text-red-600 bg-red-50 dark:bg-red-900/20 px-3 py-1.5 rounded-md transition-colors"> Hapus Semua </button>
        </div>
        <!-- Cart Items Table -->
        <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50 sticky top-0 z-10 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 w-5/12">Item</th>
                        <th class="px-2 py-3 w-3/12 text-center">Jumlah</th>
                        <th class="px-4 py-3 w-4/12 text-right">Total</th>
                    </tr>
                </thead>
                <tbody id="cart-body" class="divide-y divide-slate-100 dark:divide-slate-800">
                    <!-- Cart items will be injected here -->
                </tbody>
            </table>
            <!-- Empty State Cart -->
            <div id="cart-empty-state" class="flex flex-col items-center justify-center py-20 text-slate-400">
                <span class="material-symbols-outlined text-6xl mb-4">shopping_cart_checkout</span>
                <p class="text-base font-medium">Keranjang kosong</p>
                <p class="text-xs">Tambah produk untuk memulai</p>
            </div>
        </div>
        <!-- Calculations & Payment Footer -->
        <div class="p-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 flex flex-col gap-4">
            <!-- Subtotals -->
            <div class="flex flex-col gap-2 pb-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                    <span>Subtotal</span>
                    <span id="subtotal-display" class="font-medium text-slate-900 dark:text-white">Rp 0</span>
                </div>
                <div class="flex justify-between text-sm text-slate-500 dark:text-slate-400">
                    <span>Pajak (2.5%)</span>
                    <span id="tax-display" class="font-medium text-slate-900 dark:text-white">Rp 0</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-base font-bold text-slate-800 dark:text-white">Total</span>
                    <span id="total-display" class="text-2xl font-bold text-primary">Rp 0</span>
                </div>
            </div>
            <!-- Payment Input -->
            <div class="flex flex-col gap-3">
                <label class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Metode Pembayaran</label>
                <div class="flex gap-2" id="payment-methods">
                    <button data-method="cash" class="payment-method-btn flex-1 py-2 rounded-lg bg-white dark:bg-slate-800 border-2 border-primary text-primary text-sm font-medium flex justify-center items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-lg">payments</span> Tunai </button>
                    <button data-method="transfer" class="payment-method-btn flex-1 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-sm font-medium flex justify-center items-center gap-2 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <span class="material-symbols-outlined text-lg">account_balance</span> Transfer </button>
                    <button data-method="qris" class="payment-method-btn flex-1 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-sm font-medium flex justify-center items-center gap-2 hover:bg-slate-50 dark:hover:bg-slate-700">
                        <span class="material-symbols-outlined text-lg">qr_code</span> QRIS </button>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-1">
                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-slate-500">Uang Diterima</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rp</span>
                            <input id="cash-received" class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 pl-8 pr-3 py-2 text-sm font-medium focus:ring-primary focus:border-primary dark:text-white" type="number" step="1" value="0" />
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-slate-500">Kembalian</label>
                        <div id="change-display" class="w-full rounded-lg bg-slate-100 dark:bg-slate-800 border border-transparent px-3 py-2 text-sm font-bold text-slate-700 dark:text-slate-300"> Rp 0 </div>
                    </div>
                </div>
            </div>
            <!-- Action Button -->
            <button id="save-transaction" class="w-full mt-2 bg-primary hover:bg-blue-600 text-white font-semibold py-4 rounded-xl shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transition-all active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <span class="material-symbols-outlined">check_circle</span> Simpan Transaksi </button>
        </div>
    </aside>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // POS State
        let cart = [];
        let currentCategory = 'all';
        let searchQuery = '';
        let taxRate = 0.025;
        let paymentMethod = 'cash';

        // DOM Elements
        const productCards = document.querySelectorAll('.product-card');
        const searchInput = document.getElementById('search-input');
        const categoryBtns = document.querySelectorAll('.category-btn');
        const cartBody = document.getElementById('cart-body');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const taxDisplay = document.getElementById('tax-display');
        const totalDisplay = document.getElementById('total-display');
        const cashReceivedInput = document.getElementById('cash-received');
        const changeDisplay = document.getElementById('change-display');
        const clearCartBtn = document.getElementById('clear-cart');
        const saveBtn = document.getElementById('save-transaction');
        const cartEmptyState = document.getElementById('cart-empty-state');
        const emptySearch = document.getElementById('empty-search');

        // --- Core Functions ---

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number).replace('Rp', 'Rp ');
        }

        function updateCartUI() {
            cartBody.innerHTML = '';
            if (cart.length === 0) {
                cartEmptyState.classList.remove('hidden');
                saveBtn.disabled = true;
            } else {
                cartEmptyState.classList.add('hidden');
                saveBtn.disabled = false;

                cart.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors animate-in fade-in slide-in-from-right-4 duration-300';
                    row.innerHTML = `
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">${item.name}</span>
                            <span class="text-xs text-slate-400">@ ${formatRupiah(item.price)}</span>
                        </div>
                    </td>
                    <td class="px-2 py-3">
                        <div class="flex items-center justify-center gap-2 bg-slate-100 dark:bg-slate-800 rounded-lg p-1">
                            <button onclick="updateQty(${item.id}, -1)" class="size-6 flex items-center justify-center bg-white dark:bg-slate-700 rounded shadow-sm text-slate-600 dark:text-slate-300 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">remove</span>
                            </button>
                            <span class="text-sm font-medium w-4 text-center">${item.qty}</span>
                            <button onclick="updateQty(${item.id}, 1)" class="size-6 flex items-center justify-center bg-white dark:bg-slate-700 rounded shadow-sm text-slate-600 dark:text-slate-300 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">add</span>
                            </button>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex flex-col items-end gap-1">
                            <span class="text-sm font-medium text-slate-900 dark:text-white">${formatRupiah(item.price * item.qty)}</span>
                            <button onclick="removeFromCart(${item.id})" class="text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all duration-200">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </td>
                `;
                    cartBody.appendChild(row);
                });
            }
            calculateTotals();
        }

        function calculateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const tax = subtotal * taxRate;
            const total = subtotal + tax;

            subtotalDisplay.textContent = formatRupiah(subtotal);
            taxDisplay.textContent = formatRupiah(tax);
            totalDisplay.textContent = formatRupiah(total);

            if (paymentMethod !== 'cash') {
                cashReceivedInput.value = total;
            }

            updateChange();
        }

        function updateChange() {
            const totalText = totalDisplay.textContent.replace(/[^\d]/g, '');
            const total = parseInt(totalText) || 0;
            const received = parseFloat(cashReceivedInput.value) || 0;
            const change = received - total;

            changeDisplay.textContent = formatRupiah(change >= 0 ? change : 0);

            if (change < 0 && received > 0) {
                changeDisplay.classList.add('text-red-500');
                changeDisplay.classList.remove('text-slate-700', 'dark:text-slate-300');
            } else {
                changeDisplay.classList.remove('text-red-500');
                changeDisplay.classList.add('text-slate-700', 'dark:text-slate-300');
            }
        }

        window.updateQty = function(id, delta) {
            const index = cart.findIndex(item => item.id === id);
            if (index !== -1) {
                if (delta > 0) {
                    const card = document.querySelector(`.product-card[data-id="${id}"]`);
                    const stock = parseInt(card.dataset.stock);
                    if (cart[index].qty >= stock) {
                        Swal.fire({
                            title: 'Stok Terbatas',
                            text: 'Jumlah melebihi stok yang tersedia',
                            icon: 'warning',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        return;
                    }
                }

                cart[index].qty += delta;
                if (cart[index].qty <= 0) {
                    cart.splice(index, 1);
                }
                updateCartUI();
            }
        };

        window.removeFromCart = function(id) {
            cart = cart.filter(item => item.id !== id);
            updateCartUI();
        };

        function filterProducts() {
            let visibleCount = 0;
            productCards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const category = card.dataset.category;
                const matchesSearch = name.includes(searchQuery.toLowerCase());
                const matchesCategory = currentCategory === 'all' || category === currentCategory;

                if (matchesSearch && matchesCategory) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (visibleCount === 0) {
                emptySearch.classList.remove('hidden');
            } else {
                emptySearch.classList.add('hidden');
            }
        }

        // --- Event Listeners ---

        // Add to Cart
        productCards.forEach(card => {
            card.addEventListener('click', () => {
                const id = parseInt(card.dataset.id);
                const name = card.dataset.name;
                const price = parseFloat(card.dataset.price);
                const stock = parseInt(card.dataset.stock);

                if (stock <= 0) {
                    Swal.fire({
                        title: 'Stok Habis',
                        text: 'Produk ini sedang tidak tersedia',
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }

                const existingCount = cart.find(item => item.id === id)?.qty || 0;
                if (existingCount >= stock) {
                    Swal.fire({
                        title: 'Stok Terbatas',
                        text: 'Jumlah di keranjang sudah mencapai batas stok',
                        icon: 'warning',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return;
                }

                const existing = cart.find(item => item.id === id);
                if (existing) {
                    existing.qty++;
                } else {
                    cart.push({
                        id,
                        name,
                        price,
                        qty: 1
                    });
                }

                // Visual feedback on card
                card.classList.add('ring-2', 'ring-primary');
                setTimeout(() => card.classList.remove('ring-2', 'ring-primary'), 300);

                updateCartUI();
            });
        });

        // Clear Cart
        clearCartBtn.addEventListener('click', () => {
            if (cart.length > 0) {
                Swal.fire({
                    title: 'Clear order?',
                    text: 'This will remove all items from your current order.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, clear it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        updateCartUI();
                    }
                });
            }
        });

        // Search Input
        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value;
            filterProducts();
        });

        // Category Filters
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // UI state
                categoryBtns.forEach(b => {
                    b.classList.remove('bg-primary', 'text-white', 'shadow-primary/30');
                    b.classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');
                });
                btn.classList.add('bg-primary', 'text-white', 'shadow-primary/30');
                btn.classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-600', 'dark:text-slate-300');

                currentCategory = btn.dataset.category;
                filterProducts();
            });
        });

        // Payment Methods
        const paymentBtns = document.querySelectorAll('.payment-method-btn');
        paymentBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                paymentBtns.forEach(b => {
                    b.classList.remove('border-primary', 'text-primary', 'border-2');
                    b.classList.add('border-slate-200', 'dark:border-slate-700', 'text-slate-600', 'dark:text-slate-300', 'border');
                });
                btn.classList.add('border-primary', 'text-primary', 'border-2');
                btn.classList.remove('border-slate-200', 'dark:border-slate-700', 'text-slate-600', 'dark:text-slate-300', 'border');
                paymentMethod = btn.dataset.method;

                if (paymentMethod !== 'cash') {
                    const totalText = totalDisplay.textContent.replace(/[^\d]/g, '');
                    const total = parseInt(totalText) || 0;
                    cashReceivedInput.value = total;
                    cashReceivedInput.readOnly = true;
                    cashReceivedInput.classList.add('bg-slate-50', 'dark:bg-slate-900/50', 'cursor-not-allowed');
                } else {
                    cashReceivedInput.readOnly = false;
                    cashReceivedInput.classList.remove('bg-slate-50', 'dark:bg-slate-900/50', 'cursor-not-allowed');
                }
                updateChange();
            });
        });

        // Cash Received
        cashReceivedInput.addEventListener('input', updateChange);

        // --- Transaction Logic ---

        function handleSaveTransaction(total, received, paymentMethod) {
            fetch('/transaksi', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        total: total,
                        bayar: paymentMethod === 'cash' ? received : total,
                        kembali: paymentMethod === 'cash' ? (received - total) : 0,
                        metode_pembayaran: paymentMethod,
                        items: cart
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        populateReceipt(data, total, received, paymentMethod);

                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3b82f6',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Cetak Struk',
                            cancelButtonText: 'Selesai',
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                showReceiptPreview();
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                });
        }

        function populateReceipt(data, total, received, paymentMethod) {
            document.getElementById('p-kode-transaksi').textContent = data.kode_transaksi;
            document.getElementById('p-tanggal').textContent = new Date().toLocaleString('id-ID');
            document.getElementById('p-metode').textContent = paymentMethod.toUpperCase();
            document.getElementById('p-subtotal').textContent = subtotalDisplay.textContent;
            document.getElementById('p-tax').textContent = taxDisplay.textContent;
            document.getElementById('p-total').textContent = totalDisplay.textContent;
            document.getElementById('p-bayar').textContent = formatRupiah(paymentMethod === 'cash' ? received : total);
            document.getElementById('p-kembali').textContent = changeDisplay.textContent;

            const itemsContainer = document.getElementById('p-items');
            itemsContainer.innerHTML = '';
            cart.forEach(item => {
                const itemRow = document.createElement('div');
                itemRow.className = 'receipt-row';
                itemRow.innerHTML = `<span>${item.name} x${item.qty}</span><span>${formatRupiah(item.price * item.qty)}</span>`;
                itemsContainer.appendChild(itemRow);
            });
        }

        function showReceiptPreview() {
            const receiptContent = document.getElementById('receipt-print').innerHTML;
            Swal.fire({
                title: 'Preview Struk',
                html: `<div class="receipt-preview-container" style="background: #f1f5f9; padding: 10px; border-radius: 8px;">${receiptContent}</div>`,
                width: '400px',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Cetak Sekarang',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    printReceipt();
                } else {
                    window.location.reload();
                }
            });
        }

        function printReceipt() {
            const receipt = document.getElementById('receipt-print');
            // Move receipt to body root before printing to avoid "blank page" issues
            // caused by nested elements being hidden in print media
            if (receipt.parentElement !== document.body) {
                document.body.appendChild(receipt);
            }
            window.print();
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }

        // Save Transaction Event
        saveBtn.addEventListener('click', () => {
            const totalText = totalDisplay.textContent.replace(/[^\d]/g, '');
            const total = parseInt(totalText);
            const received = parseFloat(cashReceivedInput.value);

            if (paymentMethod === 'cash' && received < total) {
                Swal.fire({
                    title: 'Pembayaran Kurang',
                    text: 'Uang yang diterima kurang dari total tagihan.',
                    icon: 'error',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            let confirmHtml = `Total: <b>${formatRupiah(total)}</b><br>Metode: <b>${paymentMethod.toUpperCase()}</b>`;

            if (paymentMethod === 'qris') {
                confirmHtml += `
                            <div class="mt-4 p-4 bg-white border rounded-xl flex flex-col items-center gap-2">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=POS-CAFE-${Date.now()}" alt="QRIS" class="w-128 h-128">
                                <p class="text-[10px] text-slate-400 font-mono">Scan untuk QRIS</p>
                            </div>
                        `;
            }

            Swal.fire({
                title: 'Konfirmasi Transaksi',
                html: confirmHtml,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Simpan Transaksi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    handleSaveTransaction(total, received, paymentMethod);
                }
            });
        });

        // Initial State
        updateCartUI();
    });
</script>
@endsection