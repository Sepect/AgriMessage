@extends('layouts.app')

@section('header_title', 'Wilayah')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Wilayah</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data kecamatan dan desa/kelurahan cakupan area.</p>
        </div>
        <button x-data x-on:click="$dispatch('open-modal', 'form-wilayah-create')" class="inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 px-4 py-2 text-sm">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Wilayah
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <x-card class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <form method="GET" action="{{ route('wilayah.index') }}" class="flex-1 w-full flex flex-col sm:flex-row items-center gap-2">
                <div class="relative flex-1 w-full sm:max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors" placeholder="Cari nama wilayah...">
                    <button type="submit" class="hidden"></button>
                </div>
                
                <select name="type" onchange="this.form.submit()" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option value="">Semua Tingkat</option>
                    <option value="kecamatan" {{ request('type') == 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                    <option value="desa" {{ request('type') == 'desa' ? 'selected' : '' }}>Desa/Kelurahan</option>
                </select>
            </form>
        </div>
    </x-card>

    <x-table>
        <x-slot name="head">
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Wilayah</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tingkat</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Induk Wilayah</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jml. Petani</th>
            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
        </x-slot>

        @forelse($regions as $region)
        <tr>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ $region->name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($region->type == 'kecamatan')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Kecamatan</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Desa</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $region->parent ? $region->parent->name : '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $region->farmers_count ?? 0 }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" x-data>
                <button x-on:click="$dispatch('open-modal', 'form-wilayah-edit'); $dispatch('set-wilayah-edit', {{ $region }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                <button x-on:click="$dispatch('open-modal', 'confirm-wilayah-delete'); $dispatch('set-wilayah-delete', {{ $region->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data wilayah.</td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            <div class="mt-4">
                {{ $regions->links() }}
            </div>
        </x-slot>
    </x-table>

    <!-- Modal Tambah Wilayah -->
    <x-modal name="form-wilayah-create" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Tambah Wilayah Baru</h2>
            
            <form action="{{ route('wilayah.store') }}" method="POST" class="space-y-4" x-data="{ tingkat: 'desa' }">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="tingkat === 'kecamatan' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="type" value="kecamatan" class="sr-only" x-model="tingkat">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Kecamatan</span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="tingkat === 'desa' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="type" value="desa" class="sr-only" x-model="tingkat">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Desa/Kelurahan</span>
                    </label>
                </div>

                <div x-show="tingkat === 'desa'" x-cloak>
                    <x-input type="select" name="parent_id" label="Induk Kecamatan">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}">{{ $kec->name }}</option>
                        @endforeach
                    </x-input>
                </div>
                
                <x-input type="text" name="name" label="Nama Wilayah" placeholder="Masukkan nama wilayah" required />

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-wilayah-create')">Batal</x-button>
                    <x-button type="submit">Simpan Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Edit Wilayah -->
    <x-modal name="form-wilayah-edit" maxWidth="md">
        <div class="p-6" x-data="{ 
            wilayah: null, 
            tingkat: 'desa',
            actionUrl: ''
        }"
        x-on:set-wilayah-edit.window="
            wilayah = $event.detail; 
            tingkat = wilayah.type;
            actionUrl = '/wilayah/' + wilayah.id;
        ">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Ubah Wilayah</h2>
            
            <form :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="tingkat === 'kecamatan' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="type" value="kecamatan" class="sr-only" x-model="tingkat">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Kecamatan</span>
                    </label>
                    <label class="relative flex cursor-pointer rounded-lg border bg-white p-3 shadow-sm focus:outline-none" :class="tingkat === 'desa' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                        <input type="radio" name="type" value="desa" class="sr-only" x-model="tingkat">
                        <span class="block text-sm font-medium text-gray-900 text-center w-full">Desa/Kelurahan</span>
                    </label>
                </div>

                <div x-show="tingkat === 'desa'" x-cloak>
                    <x-input type="select" name="parent_id" label="Induk Kecamatan" ::value="wilayah?.parent_id">
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}">{{ $kec->name }}</option>
                        @endforeach
                    </x-input>
                </div>
                
                <x-input type="text" name="name" label="Nama Wilayah" ::value="wilayah?.name" required />

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-wilayah-edit')">Batal</x-button>
                    <x-button type="submit">Simpan Perubahan</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Konfirmasi Hapus Wilayah -->
    <x-modal name="confirm-wilayah-delete" maxWidth="sm">
        <div class="p-6 text-center" x-data="{ actionUrl: '' }" x-on:set-wilayah-delete.window="actionUrl = '/wilayah/' + $event.detail">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Wilayah?</h3>
            <p class="text-sm text-gray-500 mb-6">Peringatan: Menghapus wilayah akan berdampak pada data petani yang terkait. Pastikan wilayah ini tidak digunakan sebelum dihapus.</p>
            
            <form :action="actionUrl" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-wilayah-delete')">Batal</x-button>
                    <x-button type="submit" variant="danger">Ya, Hapus Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>
@endsection
