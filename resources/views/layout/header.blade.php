<header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-6 shrink-0">
    <h2 class="text-xl font-semibold text-slate-800 dark:text-white">Transaksi Baru</h2>
    <div class="flex items-center gap-4">
        <div class="text-sm font-medium px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full flex items-center gap-1">
            <span class="material-symbols-outlined text-base">wifi</span> Online
        </div>
        <span class="text-slate-400 dark:text-slate-600">|</span>
        <p class="text-sm text-slate-500 dark:text-slate-400" id="current-date">{{ now()->format('d M Y, H:i') }}</p>
    </div>
</header>