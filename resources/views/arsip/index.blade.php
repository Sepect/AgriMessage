@extends('layouts.app')

@section('header_title', 'Arsip Pesan')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Arsip Riwayat Pesan</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar pesan broadcast dan percakapan lama yang telah diarsipkan untuk menghemat ruang.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-start gap-3">
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <x-card class="mb-6">
        <form method="GET" action="{{ route('arsip.index') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full flex items-center gap-2">
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors" placeholder="Pencarian arsip...">
                </div>
                
                <select name="kategori" onchange="this.form.submit()" class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option value="Semua Kategori" {{ request('kategori') == 'Semua Kategori' ? 'selected' : '' }}>Semua Kategori</option>
                    <option value="Broadcast" {{ request('kategori') == 'Broadcast' ? 'selected' : '' }}>Broadcast</option>
                    <option value="Pesan Personal" {{ request('kategori') == 'Pesan Personal' ? 'selected' : '' }}>Pesan Personal</option>
                </select>
                
                <select name="tahun" onchange="this.form.submit()" class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option value="Semua Tahun" {{ request('tahun') == 'Semua Tahun' ? 'selected' : '' }}>Semua Tahun</option>
                    @for($y = date('Y'); $y >= 2024; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                    @endfor
                </select>
            </div>
        </form>
        
        <form method="POST" action="{{ route('arsip.destroy-all') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua arsip secara permanen?');" class="mt-4 sm:mt-0 flex justify-end">
            @csrf
            <x-button type="submit" variant="danger">
                <svg class="w-5 h-5 mr-2 -ml-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus Semua Arsip
            </x-button>
        </form>
    </x-card>

    <x-table>
        <x-slot name="head">
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Konten Pesan / Judul</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Penerima</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Diarsipkan</th>
            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
        </x-slot>

        @forelse($arsips as $arsip)
        <tr>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ $arsip->title }}</div>
                <div class="text-sm text-gray-500 truncate max-w-xs mt-1">{{ \Illuminate\Support\Str::limit($arsip->content, 60) }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($arsip->type === 'Broadcast')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Broadcast
                </span>
                @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    Pesan Personal
                </span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $arsip->target }}</div>
                <div class="text-xs text-gray-500">{{ $arsip->target_detail }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($arsip->date)->translatedFormat('d M Y') }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-3">
                    @php
                        $routeType = $arsip->type === 'Broadcast' ? 'broadcast' : 'personal';
                    @endphp
                    <a href="{{ route('arsip.show', ['type' => $routeType, 'id' => $arsip->id]) }}" class="text-blue-600 hover:text-blue-900">
                        @if($arsip->type === 'Broadcast')
                            Lihat Detail
                        @else
                            Lihat Transkrip
                        @endif
                    </a>
                    
                    <form method="POST" action="{{ route('arsip.destroy', ['type' => $routeType, 'id' => $arsip->id]) }}" onsubmit="return confirm('Yakin ingin menghapus arsip ini permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus Permanen</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                Tidak ada arsip yang ditemukan.
            </td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            <div class="mt-4">
                {{ $arsips->links() }}
            </div>
        </x-slot>
    </x-table>
@endsection
