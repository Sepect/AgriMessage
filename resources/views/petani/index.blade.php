@extends('layouts.app')

@section('header_title', 'Data Petani')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Data Petani</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data petani, kontak WhatsApp, dan informasi terkait lainnya.</p>
        </div>
        <button x-data x-on:click="$dispatch('open-modal', 'form-petani-create')" class="inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 px-4 py-2 text-sm">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Petani
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
            <form method="GET" action="{{ route('petani.index') }}" class="flex-1 w-full flex flex-col sm:flex-row items-center gap-2">
                <div class="relative flex-1 w-full sm:max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors" placeholder="Cari NIK, Nama, atau No. WA...">
                    <button type="submit" class="hidden"></button>
                </div>
                
                <select name="group_id" onchange="this.form.submit()" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option value="">Semua Kelompok</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
                
                <select name="status" onchange="this.form.submit()" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </form>
            
            <x-button variant="secondary">
                <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Export Excel
            </x-button>
        </div>
    </x-card>

    <x-table>
        <x-slot name="head">
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Identitas Petani</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak WhatsApp</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelompok Tani</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
        </x-slot>

        @forelse($farmers as $farmer)
        <tr>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($farmer->name) }}&background=random" alt="">
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">{{ $farmer->name }}</div>
                        <div class="text-xs text-gray-500">NIK: {{ $farmer->nik }}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $farmer->phone }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($farmer->groups->isNotEmpty())
                    <div class="text-sm text-gray-900">{{ $farmer->groups->first()->name }}</div>
                @else
                    <div class="text-sm text-gray-500 italic">Belum ada kelompok</div>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $farmer->region ? $farmer->region->name : '-' }}</div>
                <div class="text-xs text-gray-500">{{ $farmer->region && $farmer->region->parent ? $farmer->region->parent->name : '-' }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <x-badge :status="$farmer->status === 'active' ? 'active' : 'inactive'" />
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" x-data>
                <button x-on:click="$dispatch('open-modal', 'form-petani-edit'); $dispatch('set-petani-edit', {{ json_encode([
                    'id' => $farmer->id,
                    'nik' => $farmer->nik,
                    'name' => $farmer->name,
                    'phone' => $farmer->phone,
                    'region_id' => $farmer->region_id,
                    'group_id' => $farmer->groups->first()->id ?? ''
                ]) }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                <button x-on:click="$dispatch('open-modal', 'confirm-petani-delete'); $dispatch('set-petani-delete', {{ $farmer->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data petani.</td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            <div class="mt-4">
                {{ $farmers->links() }}
            </div>
        </x-slot>
    </x-table>

    <!-- Modal Tambah Petani -->
    <x-modal name="form-petani-create" maxWidth="2xl">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Tambah Data Petani Baru</h2>
            
            <form action="{{ route('petani.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input type="text" name="nik" label="Nomor Induk Kependudukan (NIK)" placeholder="16 digit NIK" required />
                    <x-input type="text" name="name" label="Nama Lengkap" placeholder="Nama sesuai KTP" required />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input type="text" name="phone" label="Nomor WhatsApp" placeholder="e.g. 0812..." required />
                    <x-input type="select" name="group_id" label="Kelompok Tani">
                        <option value="">-- Pilih Kelompok Tani --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </x-input>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <x-input type="select" name="region_id" label="Desa/Kelurahan" required>
                        <option value="">-- Pilih Desa --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }} {{ $region->parent ? '('.$region->parent->name.')' : '' }}</option>
                        @endforeach
                    </x-input>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-petani-create')">Batal</x-button>
                    <x-button type="submit">Simpan Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Edit Petani -->
    <x-modal name="form-petani-edit" maxWidth="2xl">
        <div class="p-6" x-data="{ 
            petani: {},
            actionUrl: ''
        }"
        x-on:set-petani-edit.window="
            petani = $event.detail;
            actionUrl = '/petani/' + petani.id;
        ">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Ubah Data Petani</h2>
            
            <form :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input type="text" name="nik" label="Nomor Induk Kependudukan (NIK)" ::value="petani.nik" required />
                    <x-input type="text" name="name" label="Nama Lengkap" ::value="petani.name" required />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input type="text" name="phone" label="Nomor WhatsApp" ::value="petani.phone" required />
                    <x-input type="select" name="group_id" label="Kelompok Tani" ::value="petani.group_id">
                        <option value="">-- Pilih Kelompok Tani --</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </x-input>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <x-input type="select" name="region_id" label="Desa/Kelurahan" ::value="petani.region_id" required>
                        <option value="">-- Pilih Desa --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }} {{ $region->parent ? '('.$region->parent->name.')' : '' }}</option>
                        @endforeach
                    </x-input>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-petani-edit')">Batal</x-button>
                    <x-button type="submit">Simpan Perubahan</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Konfirmasi Hapus Petani -->
    <x-modal name="confirm-petani-delete" maxWidth="sm">
        <div class="p-6 text-center" x-data="{ actionUrl: '' }" x-on:set-petani-delete.window="actionUrl = '/petani/' + $event.detail">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Data Petani?</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus data petani ini? Semua riwayat chat dan data yang terkait akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.</p>
            
            <form :action="actionUrl" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-petani-delete')">Batal</x-button>
                    <x-button type="submit" variant="danger">Ya, Hapus Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>
@endsection
