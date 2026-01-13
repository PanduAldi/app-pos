<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{$title}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark min-h-screen flex flex-col justify-center items-center p-4">
    <!-- Main Card Container -->
    <div class="w-full max-w-[480px] bg-white dark:bg-[#1e293b] rounded-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden border border-slate-100 dark:border-slate-700">
        <!-- Header Section -->
        <div class="pt-10 pb-6 px-8 flex flex-col items-center">
            <!-- Logo -->
            <img src="/logo.png" alt="Logo" class="w-16 h-16 mb-6 object-contain">
            <h2 class="text-slate-900 dark:text-white tracking-tight text-[28px] font-bold leading-tight text-center">Cafe BarisKode</h2>
            <p class="text-slate-500 dark:text-slate-400 text-base font-normal leading-normal pt-2 text-center">Silahkan login</p>
        </div>
        <!-- Form Section -->
        <div class="px-8 pb-10">
            <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <!-- Username Field -->
                <div class="flex flex-col gap-2">
                    <label class="text-slate-900 dark:text-white text-sm font-medium leading-normal">
                        Username
                    </label>
                    <div class="relative">
                        <input class="form-input w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white h-12 px-4 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="cth: kasir" type="text" />
                    </div>
                </div>
                <!-- Password Field -->
                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center">
                        <label class="text-slate-900 dark:text-white text-sm font-medium leading-normal">
                            Password
                        </label>
                    </div>
                    <div class="relative flex items-center">
                        <input class="form-input w-full rounded-lg border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white h-12 pl-4 pr-12 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200" placeholder="••••••••" type="password" />
                        <button class="absolute right-0 top-0 bottom-0 px-3 flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" type="button">
                            <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                        </button>
                    </div>
                    <div class="flex justify-end mt-1">
                        <a class="text-primary text-sm font-medium hover:text-blue-600 transition-colors" href="#">Forgot Password?</a>
                    </div>
                </div>
                <!-- Submit Button -->
                <button onclick="window.location.href='/dashboard'" class="mt-2 w-full bg-primary hover:bg-blue-600 text-white font-medium h-12 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">login</span>
                    Log In
                </button>
            </form>
        </div>

    </div>


</body>

</html>