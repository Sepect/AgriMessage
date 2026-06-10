<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AgriMessage') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- AlpineJS for interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900/50 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-72 bg-white border-r border-gray-200 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 shadow-sm flex flex-col">
        <div class="flex items-center justify-between lg:justify-center h-16 border-b border-gray-100 px-6">
            <a href="/dashboard" class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-xl font-bold text-gray-900 tracking-tight">AgriMessage</span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="overflow-y-auto flex-1 pb-4">
            <nav class="px-4 py-4 space-y-1">
                @php
                    $role = auth()->user() ? auth()->user()->role : 'admin';
                @endphp
                
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Menu Utama</p>
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('dashboard') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                
                <a href="/broadcast" class="flex items-center justify-between px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('broadcast*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->is('broadcast*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        Broadcast
                    </div>
                </a>

                <a href="/inbox" class="flex items-center justify-between px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('inbox*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 {{ request()->is('inbox*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Kotak Masuk
                    </div>
                    @php
                        $unreadCount = \App\Models\IncomingChat::where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
                
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Master Data</p>
                <a href="/petani" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('petani*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('petani*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Data Petani
                </a>
                <a href="/kelompok-tani" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('kelompok-tani*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('kelompok-tani*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Kelompok Tani
                </a>
                <a href="/wilayah" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('wilayah*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('wilayah*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Wilayah
                </a>
                <a href="/template" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('template*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('template*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
                    Template Pesan
                </a>
                
                @if($role === 'admin')
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Pengaturan</p>
                <a href="/pengguna" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('pengguna*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('pengguna*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manajemen Pengguna
                </a>
                <a href="/pengaturan-wa" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-colors {{ request()->is('pengaturan-wa*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('pengaturan-wa*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan WA
                </a>
                @endif
                
                <a href="/arsip" class="flex items-center gap-3 px-3 py-2.5 rounded-lg mt-2 font-medium transition-colors {{ request()->is('arsip*') ? 'bg-green-50 text-green-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->is('arsip*') ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110-4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Arsip
                </a>
            </nav>
        </div>
        
        <!-- User info at bottom of sidebar -->
        <div class="border-t border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <img class="w-10 h-10 rounded-full bg-gray-100 ring-2 ring-white" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user() ? auth()->user()->name : 'User') }}&background=0D8ABC&color=fff" alt="User Avatar">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user() ? auth()->user()->name : 'User' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ ucfirst($role) }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50/50">
        <!-- Top header -->
        <header class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-xl font-semibold text-gray-900 tracking-tight">@yield('header_title', 'Dashboard')</h1>
            </div>
            
            <div class="flex items-center gap-5">
                <!-- Notifications -->
                <div class="relative" x-data="{ 
                    openNotif: false, 
                    unreadCount: 0,
                    notifications: [],
                    init() {
                        this.fetchNotifications();
                        setInterval(() => this.fetchNotifications(), 5000);
                    },
                    async fetchNotifications() {
                        try {
                            const res = await fetch('/api/notifications');
                            const data = await res.json();
                            if (data.success) {
                                this.unreadCount = data.unread_count;
                                this.notifications = data.notifications;
                            }
                        } catch (e) {}
                    }
                }">
                    <button @click="openNotif = !openNotif" class="text-gray-400 hover:text-green-600 transition-colors relative focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount" x-cloak class="absolute -top-1 -right-1 flex items-center justify-center min-w-4 h-4 px-1 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    
                    <div x-show="openNotif" @click.away="openNotif = false" x-transition class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg shadow-gray-200/50 py-2 border border-gray-100 z-50" x-cloak>
                        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                            <span x-show="unreadCount > 0" class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-medium" x-text="unreadCount + ' Baru'"></span>
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            <template x-if="notifications.length > 0">
                                <div>
                                    <template x-for="notif in notifications" :key="notif.id">
                                        <a :href="notif.link" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0 transition-colors">
                                            <p class="text-sm font-medium text-gray-900" x-text="notif.title"></p>
                                            <p class="text-xs text-gray-500 mt-0.5 truncate" x-text="notif.message"></p>
                                            <p class="text-[10px] text-gray-400 mt-1" x-text="notif.time"></p>
                                        </a>
                                    </template>
                                </div>
                            </template>
                            <template x-if="notifications.length === 0">
                                <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                    Belum ada notifikasi baru.
                                </div>
                            </template>
                        </div>
                        <div class="px-4 py-2 border-t border-gray-100 text-center">
                            <a href="/inbox" class="text-xs font-medium text-green-600 hover:text-green-700">Lihat Semua Pesan</a>
                        </div>
                    </div>
                </div>
                
                <!-- Profile dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none hover:bg-gray-50 py-1 px-2 rounded-lg transition-colors">
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user() ? auth()->user()->name : 'User' }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg shadow-gray-200/50 py-1 border border-gray-100 z-50" x-cloak>
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user() ? auth()->user()->name : 'User' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user() ? auth()->user()->email : '' }}</p>
                        </div>
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">Profil Saya</a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
