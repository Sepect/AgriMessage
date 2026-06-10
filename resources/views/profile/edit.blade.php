@extends('layouts.app')

@section('header_title', 'Profil Saya')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Pengaturan Profil</h2>
            <p class="text-sm text-gray-500 mt-1">Perbarui informasi profil dan kata sandi akun Anda.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Menu Profil -->
        <div class="lg:col-span-1">
            <x-card class="sticky top-6">
                <div class="text-center pb-6 border-b border-gray-100">
                    <div class="relative inline-block mt-4 mb-4">
                        <img class="w-24 h-24 rounded-full border-4 border-white shadow-md object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0D8ABC&color=fff&size=128" alt="Profile avatar">
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                    <div class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->role == 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ auth()->user()->role == 'admin' ? 'Admin Humas' : 'Penyuluh Lapangan' }}
                    </div>
                </div>
                
                <nav class="flex flex-col gap-1 mt-6">
                    <a href="#informasi" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors bg-green-50 text-green-700 text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Informasi Dasar
                    </a>
                    <a href="#password" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Keamanan & Password
                    </a>
                </nav>
            </x-card>
        </div>

        <!-- Form Area -->
        <div class="lg:col-span-2 space-y-6">
            <x-card id="informasi">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Dasar</h3>
                
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input type="text" name="name" label="Nama Lengkap" value="{{ auth()->user()->name }}" required />
                        <x-input type="email" name="email" label="Alamat Email" value="{{ auth()->user()->email }}" required />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input type="text" label="Jabatan/Role" value="{{ auth()->user()->role == 'admin' ? 'Admin Humas' : 'Penyuluh Lapangan' }}" disabled />
                        <x-input type="text" label="Wilayah Tugas" value="{{ auth()->user()->region ? auth()->user()->region->name : 'Semua Wilayah' }}" disabled />
                    </div>

                    <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                        <x-button type="submit">Simpan Perubahan</x-button>
                    </div>
                </form>
            </x-card>

            <x-card id="password">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Ubah Password</h3>
                
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="max-w-md">
                        <x-input type="password" name="current_password" label="Password Saat Ini" placeholder="••••••••" required />
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input type="password" name="password" label="Password Baru" placeholder="••••••••" required />
                        <x-input type="password" name="password_confirmation" label="Konfirmasi Password Baru" placeholder="••••••••" required />
                    </div>
                    
                    <div class="pt-4 mt-6">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Persyaratan Password:</h4>
                        <ul class="text-xs text-gray-500 space-y-1 list-disc list-inside">
                            <li>Minimal 8 karakter</li>
                        </ul>
                    </div>

                    <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                        <x-button type="submit">Perbarui Password</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
