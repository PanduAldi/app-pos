<aside class="w-20 lg:w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex-shrink-0 flex flex-col justify-between transition-all duration-300">
    <div>
        <!-- Branding -->
        <div class="h-16 flex items-center justify-center lg:justify-start lg:px-6 border-b border-slate-100 dark:border-slate-800">
            <div class="size-8 rounded-lg bg-amber-600 flex items-center justify-center text-white shrink-0">
                <span class="material-symbols-outlined text-xl">local_cafe</span>
            </div>
            <h1 class="hidden lg:block ml-3 text-lg font-bold text-slate-800 dark:text-white">BarisKode Cafe</h1>
        </div>
        <!-- Navigation Links -->
        <nav class="p-4 space-y-2">
            <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 group transition-colors" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">dashboard</span>
                <span class="hidden lg:block text-sm font-medium">Dashboard</span>
            </a>
            @if(auth()->user()->role == "kasir")
            <a class="flex items-center gap-3 px-3 py-3 rounded-lg bg-primary/10 text-primary" href="{{ route('transaksi') }}">
                <span class="material-symbols-outlined text-2xl fill-1">receipt_long</span>
                <span class="hidden lg:block text-sm font-medium">Transactions</span>
            </a>
            @endif

            @if(auth()->user()->role == "admin")
            <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 group transition-colors" href="#">
                <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">inventory_2</span>
                <span class="hidden lg:block text-sm font-medium">Inventory</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-3 rounded-lg text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 dark:text-slate-400 group transition-colors" href="{{ route('laporan') }}">
                <span class="material-symbols-outlined text-2xl group-hover:text-primary transition-colors">assessment</span>
                <span class="hidden lg:block text-sm font-medium">Laporan</span>
            </a>
            @endif
        </nav>
    </div>
    <!-- User Profile -->
    <div class="p-4 border-t border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-3">
            <div class="size-10 rounded-full bg-cover bg-center shrink-0" data-alt="Portrait of cashier Jane Doe" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAemE2pF60BSKCaeyg2Wryy7WWW6oFxOFxvIpvgYPltEq1fHfM3yyqjE1bAidInjwGOjZqZtf9NiwXRKhazA1Vzu5GvaqeqeU13fk6TwH-tIcXIMiudrHyzcy3dGleM6yBWXSU2JiRrXbe-HEfrJuYRpy_UJiJIGUB-iVHFJaJtc_q40PNB6pewpL5SEOQm_fAyP12pkC8lmrKnbKk1DomxrK4LRt5bh1ifcI3q4vG0n2hqfsbnHNq4vx7TTN58iAEgPuXphEDCFlQ");'>
            </div>
            <div class="hidden lg:flex flex-col overflow-hidden">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->role }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ml-auto">
                @csrf
                <button type="submit" class="hidden lg:block text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <span class="material-symbols-outlined text-xl">logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>