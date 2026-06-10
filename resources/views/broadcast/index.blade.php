@extends('layouts.app')

@section('header_title', 'Broadcast Pesan')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Riwayat Broadcast</h2>
            <p class="text-sm text-gray-500 mt-1">Pantau status pengiriman pesan massal ke petani.</p>
        </div>
        <a href="{{ route('broadcast.create') }}" class="inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 px-4 py-2 text-sm">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Broadcast Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Cards Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-card class="bg-gradient-to-br from-green-50 to-white border-green-100">
            <div class="text-green-600 text-sm font-medium mb-1">Total Broadcast</div>
            <div class="text-2xl font-bold text-gray-900">{{ $broadcasts->total() }}</div>
        </x-card>
        <x-card class="bg-gradient-to-br from-blue-50 to-white border-blue-100">
            <div class="text-blue-600 text-sm font-medium mb-1">Pesan Terkirim (Bulan ini)</div>
            <div class="text-2xl font-bold text-gray-900">{{ $pesanTerkirim }}</div>
        </x-card>
        <x-card class="bg-gradient-to-br from-yellow-50 to-white border-yellow-100">
            <div class="text-yellow-600 text-sm font-medium mb-1">Sedang Proses</div>
            <div class="text-2xl font-bold text-gray-900">{{ $broadcasts->where('status', 'processing')->count() }}</div>
        </x-card>
        <x-card class="bg-gradient-to-br from-red-50 to-white border-red-100">
            <div class="text-red-600 text-sm font-medium mb-1">Gagal Terkirim</div>
            <div class="text-2xl font-bold text-gray-900">{{ $pesanGagal }}</div>
        </x-card>
    </div>

    <x-card class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <form method="GET" action="{{ route('broadcast.index') }}" class="flex-1 w-full flex flex-col sm:flex-row items-center gap-2">
                <div class="relative flex-1 w-full sm:max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors" placeholder="Cari judul broadcast...">
                    <button type="submit" class="hidden"></button>
                </div>
                
                <select name="status" onchange="this.form.submit()" class="block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Proses</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </form>
        </div>
    </x-card>

    <x-table>
        <x-slot name="head">
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul / Konten</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Target</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jadwal</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
        </x-slot>

        @forelse($broadcasts as $broadcast)
        <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='#'">
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ $broadcast->title }}</div>
                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs">{{ \Illuminate\Support\Str::limit($broadcast->content, 50) }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                    @if(isset($broadcast->target_segment['type']))
                        @if($broadcast->target_segment['type'] == 'all')
                            Semua Petani
                        @elseif($broadcast->target_segment['type'] == 'region')
                            Wilayah ID: {{ $broadcast->target_segment['id'] }}
                        @elseif($broadcast->target_segment['type'] == 'group')
                            Kelompok ID: {{ $broadcast->target_segment['id'] }}
                        @endif
                    @endif
                </div>
                <div class="text-xs text-gray-500">{{ $broadcast->recipients_count }} Penerima</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $broadcast->scheduled_at ? $broadcast->scheduled_at->format('d M Y, H:i') : $broadcast->created_at->format('d M Y, H:i') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                @if($broadcast->status == 'completed')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                @elseif($broadcast->status == 'processing')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Diproses</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($broadcast->status) }}</span>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    @php
                        $progress = $broadcast->progress_percentage ?? 0;
                    @endphp
                    <div class="bg-green-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
                <div class="text-xs text-gray-500 mt-1 text-right">{{ $progress }}%</div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada riwayat broadcast.</td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            <div class="mt-4">
                {{ $broadcasts->links() }}
            </div>
        </x-slot>
    </x-table>
@endsection
