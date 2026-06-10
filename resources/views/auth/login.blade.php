<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="AgriMessage - Sistem Komunikasi dan Siaran Pesan untuk Petani">
    <title>Masuk - AgriMessage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .login-bg {
            background-color: #064e3b;
            background-image:
                radial-gradient(ellipse 80% 60% at 50% 0%, rgba(16, 185, 129, 0.25), transparent),
                radial-gradient(ellipse 60% 50% at 80% 100%, rgba(5, 150, 105, 0.15), transparent);
        }

        .leaf-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5c0 0-15 10-15 25s15 25 15 25' stroke='rgba(255,255,255,0.03)' fill='none' stroke-width='1'/%3E%3Cpath d='M30 5c0 0 15 10 15 25s-15 25-15 25' stroke='rgba(255,255,255,0.03)' fill='none' stroke-width='1'/%3E%3C/svg%3E");
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .input-focus {
            transition: all 0.2s ease;
        }

        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-12px) rotate(3deg);
            }
        }

        @keyframes float-medium {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-8px) rotate(-2deg);
            }
        }

        .float-slow {
            animation: float-slow 6s ease-in-out infinite;
        }

        .float-medium {
            animation: float-medium 4.5s ease-in-out infinite 0.5s;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            50% {
                transform: scale(1);
                opacity: 0;
            }

            100% {
                transform: scale(0.8);
                opacity: 0.5;
            }
        }

        .pulse-ring {
            animation: pulse-ring 3s ease-in-out infinite;
        }
    </style>
</head>

<body class="h-full" x-data="{ showPassword: false }">
    <div class="min-h-full flex">

        <!-- Panel Kiri: Branding / Ilustrasi (tersembunyi di mobile) -->
        <div
            class="hidden lg:flex lg:w-1/2 xl:w-[55%] login-bg leaf-pattern relative overflow-hidden items-center justify-center p-12">
            <!-- Elemen dekoratif -->
            <div class="absolute top-10 left-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-teal-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/4 right-1/4 w-2 h-2 bg-emerald-400/40 rounded-full pulse-ring"></div>
            <div class="absolute bottom-1/3 left-1/3 w-1.5 h-1.5 bg-green-300/50 rounded-full pulse-ring"
                style="animation-delay: 1s;"></div>

            <div class="relative z-10 max-w-lg text-center">
                <!-- Logo animasi -->
                <div class="mb-10 flex justify-center">
                    <div class="relative">
                        <div
                            class="w-28 h-28 bg-white/10 rounded-3xl flex items-center justify-center border border-white/10 float-slow shadow-2xl shadow-black/10">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>
                        <!-- Satelit kecil -->
                        <div
                            class="absolute -top-3 -right-3 w-8 h-8 bg-emerald-400/20 rounded-lg border border-emerald-400/20 flex items-center justify-center float-medium">
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-2 -left-4 w-7 h-7 bg-teal-400/20 rounded-lg border border-teal-400/20 flex items-center justify-center float-medium"
                            style="animation-delay: 1.2s;">
                            <svg class="w-3.5 h-3.5 text-teal-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight tracking-tight">
                    Agri<span class="text-emerald-300">Message</span>
                </h1>
                <p class="mt-4 text-lg text-emerald-100/80 leading-relaxed max-w-md mx-auto">
                    Platform komunikasi terpadu untuk menjangkau dan memberdayakan petani melalui pesan WhatsApp yang
                    terstruktur.
                </p>
            </div>

            <!-- Footer pada panel kiri -->
            <div class="absolute bottom-6 left-0 right-0 text-center">
                <p class="text-xs text-emerald-200/40">&copy; {{ date('Y') }} AgriMessage &middot; Dinas Pertanian</p>
            </div>
        </div>

        <!-- Panel Kanan: Form Login -->
        <div class="w-full lg:w-1/2 xl:w-[45%] flex items-center justify-center p-6 sm:p-12 bg-gray-50">
            <div class="w-full max-w-md">
                <!-- Logo mobile only -->
                <div class="lg:hidden text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 bg-emerald-600 rounded-2xl shadow-lg shadow-emerald-500/30 mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-gray-900">Agri<span class="text-emerald-600">Message</span>
                    </h1>
                </div>

                <!-- Heading -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Selamat Datang 👋</h2>
                    <p class="mt-2 text-sm text-gray-500">Masukkan kredensial akun Anda untuk mengakses dashboard.</p>
                </div>

                <!-- Error Alert -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
                        <div class="flex-shrink-0 w-5 h-5 mt-0.5">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-sm text-red-700 font-medium">{{ $errors->first() }}</p>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                class="input-focus block w-full pl-11 pr-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Kata
                            Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password" required
                                class="input-focus block w-full pl-11 pr-12 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                placeholder="Masukkan kata sandi">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input name="remember" type="checkbox"
                                class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 transition-colors">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Masuk ke Dashboard
                    </button>
                </form>

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-gray-400">
                    Hanya pengguna terdaftar yang dapat mengakses sistem ini.<br>
                    Hubungi administrator untuk mendaftarkan akun baru.
                </p>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>