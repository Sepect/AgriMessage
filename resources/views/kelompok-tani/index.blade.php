@extends('layouts.app')

@section('header_title', 'Kelompok Tani')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Kelompok Tani</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data kelompok tani dan anggotanya.</p>
        </div>
        <button x-data x-on:click="$dispatch('open-modal', 'form-kelompok-create')" class="inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 px-4 py-2 text-sm">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Kelompok
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
            <form method="GET" action="{{ route('kelompok-tani.index') }}" class="flex-1 w-full flex flex-col sm:flex-row items-center gap-2">
                <div class="relative flex-1 w-full sm:max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors" placeholder="Cari nama kelompok atau ketua...">
                    <button type="submit" class="hidden"></button>
                </div>
                
                <select name="region_id" onchange="this.form.submit()" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option value="">Semua Wilayah</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-card>

    <x-table>
        <x-slot name="head">
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Kelompok</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ketua</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah (Desa)</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jml. Anggota</th>
            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
        </x-slot>

        @forelse($groups as $group)
        <tr>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    @if($group->leader)
                    <div class="flex-shrink-0 h-8 w-8">
                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($group->leader->name) }}&background=random" alt="">
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">{{ $group->leader->name }}</div>
                    </div>
                    @else
                    <div class="text-sm text-gray-500 italic">Belum ada ketua</div>
                    @endif
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $group->region ? $group->region->name : '-' }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ $group->members->count() }} Orang
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" x-data>
                <button x-on:click="$dispatch('open-modal', 'form-kelompok-edit'); $dispatch('set-kelompok-edit', {{ json_encode([
                    'id' => $group->id,
                    'name' => $group->name,
                    'leader_id' => $group->leader_id,
                    'region_id' => $group->region_id
                ]) }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                <button x-on:click="$dispatch('open-modal', 'confirm-kelompok-delete'); $dispatch('set-kelompok-delete', {{ $group->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data kelompok tani.</td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            <div class="mt-4">
                {{ $groups->links() }}
            </div>
        </x-slot>
    </x-table>

    <!-- Modal Tambah Kelompok -->
    <x-modal name="form-kelompok-create" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Tambah Kelompok Tani</h2>
            
            <form action="{{ route('kelompok-tani.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input type="text" name="name" label="Nama Kelompok" placeholder="Contoh: Maju Bersama" required />
                
                <x-input type="select" name="region_id" label="Wilayah (Desa)" required>
                    <option value="">-- Pilih Desa --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </x-input>

                <x-input type="select" name="leader_id" label="Ketua Kelompok (Opsional)">
                    <option value="">-- Pilih Ketua --</option>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}">{{ $farmer->name }} - {{ $farmer->nik }}</option>
                    @endforeach
                </x-input>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-kelompok-create')">Batal</x-button>
                    <x-button type="submit">Simpan Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Edit Kelompok -->
    <x-modal name="form-kelompok-edit" maxWidth="md">
        <div class="p-6" x-data="{ 
            kelompok: {},
            actionUrl: ''
        }"
        x-on:set-kelompok-edit.window="
            kelompok = $event.detail;
            actionUrl = '/kelompok-tani/' + kelompok.id;
        ">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Ubah Kelompok Tani</h2>
            
            <form :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input type="text" name="name" label="Nama Kelompok" ::value="kelompok.name" required />
                
                <x-input type="select" name="region_id" label="Wilayah (Desa)" ::value="kelompok.region_id" required>
                    <option value="">-- Pilih Desa --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </x-input>

                <x-input type="select" name="leader_id" label="Ketua Kelompok (Opsional)" ::value="kelompok.leader_id">
                    <option value="">-- Pilih Ketua --</option>
                    @foreach($farmers as $farmer)
                        <option value="{{ $farmer->id }}">{{ $farmer->name }} - {{ $farmer->nik }}</option>
                    @endforeach
                </x-input>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-kelompok-edit')">Batal</x-button>
                    <x-button type="submit">Simpan Perubahan</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Konfirmasi Hapus Kelompok -->
    <x-modal name="confirm-kelompok-delete" maxWidth="sm">
        <div class="p-6 text-center" x-data="{ actionUrl: '' }" x-on:set-kelompok-delete.window="actionUrl = '/kelompok-tani/' + $event.detail">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Kelompok Tani?</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus kelompok tani ini? Anggota di dalamnya tidak akan terhapus, namun tidak lagi memiliki kelompok.</p>
            
            <form :action="actionUrl" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-kelompok-delete')">Batal</x-button>
                    <x-button type="submit" variant="danger">Ya, Hapus Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>
@endsection
