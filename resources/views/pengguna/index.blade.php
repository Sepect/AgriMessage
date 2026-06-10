@extends('layouts.app')

@section('header_title', 'Pengguna Sistem')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Pengguna</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola akses admin dan penyuluh lapangan.</p>
        </div>
        <button x-data x-on:click="$dispatch('open-modal', 'form-pengguna-create')" class="inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 px-4 py-2 text-sm">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Pengguna
        </button>
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

    <x-card class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full flex items-center gap-2">
                <form method="GET" action="{{ route('pengguna.index') }}" class="relative flex-1 w-full sm:max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors" placeholder="Cari nama atau email...">
                    <button type="submit" class="hidden"></button>
                </form>
            </div>
        </div>
    </x-card>

    <x-table>
        <x-slot name="head">
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran (Role)</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah Tugas</th>
            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
        </x-slot>

        @forelse($users as $user)
        <tr>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="">
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($user->role == 'admin')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Admin Humas</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Penyuluh Lapangan</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $user->region ? $user->region->name : 'Semua Wilayah' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" x-data>
                <button x-on:click="$dispatch('open-modal', 'form-pengguna-edit'); $dispatch('set-pengguna-edit', {{ json_encode([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'region_id' => $user->region_id
                ]) }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                <button x-on:click="$dispatch('open-modal', 'confirm-pengguna-delete'); $dispatch('set-pengguna-delete', {{ $user->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pengguna.</td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </x-slot>
    </x-table>

    <!-- Modal Tambah Pengguna -->
    <x-modal name="form-pengguna-create" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Tambah Pengguna Baru</h2>
            
            <form action="{{ route('pengguna.store') }}" method="POST" class="space-y-4" x-data="{ role: 'penyuluh' }">
                @csrf
                <x-input type="text" name="name" label="Nama Lengkap" placeholder="Nama pengguna" required />
                <x-input type="email" name="email" label="Alamat Email" placeholder="email@contoh.com" required />
                <x-input type="password" name="password" label="Kata Sandi Sementara" required />
                
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="role === 'penyuluh' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="role" value="penyuluh" class="sr-only" x-model="role">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Penyuluh</span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="role === 'admin' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="role" value="admin" class="sr-only" x-model="role">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Admin Humas</span>
                    </label>
                </div>

                <div x-show="role === 'penyuluh'" x-cloak>
                    <x-input type="select" name="region_id" label="Wilayah Tugas (Opsional)">
                        <option value="">-- Semua Wilayah / Pilih Wilayah --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </x-input>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-pengguna-create')">Batal</x-button>
                    <x-button type="submit">Simpan Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Edit Pengguna -->
    <x-modal name="form-pengguna-edit" maxWidth="md">
        <div class="p-6" x-data="{ 
            user: {},
            role: 'penyuluh',
            actionUrl: ''
        }"
        x-on:set-pengguna-edit.window="
            user = $event.detail;
            role = user.role;
            actionUrl = '/pengguna/' + user.id;
        ">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Ubah Pengguna</h2>
            
            <form :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input type="text" name="name" label="Nama Lengkap" ::value="user.name" required />
                <x-input type="email" name="email" label="Alamat Email" ::value="user.email" required />
                <x-input type="password" name="password" label="Ubah Kata Sandi (Kosongkan jika tidak diubah)" />
                
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="role === 'penyuluh' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="role" value="penyuluh" class="sr-only" x-model="role">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Penyuluh</span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="role === 'admin' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="role" value="admin" class="sr-only" x-model="role">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Admin Humas</span>
                    </label>
                </div>

                <div x-show="role === 'penyuluh'" x-cloak>
                    <x-input type="select" name="region_id" label="Wilayah Tugas (Opsional)" ::value="user.region_id">
                        <option value="">-- Semua Wilayah / Pilih Wilayah --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </x-input>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-pengguna-edit')">Batal</x-button>
                    <x-button type="submit">Simpan Perubahan</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Konfirmasi Hapus Pengguna -->
    <x-modal name="confirm-pengguna-delete" maxWidth="sm">
        <div class="p-6 text-center" x-data="{ actionUrl: '' }" x-on:set-pengguna-delete.window="actionUrl = '/pengguna/' + $event.detail">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Pengguna?</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin mencabut akses pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            <form :action="actionUrl" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-pengguna-delete')">Batal</x-button>
                    <x-button type="submit" variant="danger">Ya, Hapus Akun</x-button>
                </div>
            </form>
        </div>
    </x-modal>
@endsection
