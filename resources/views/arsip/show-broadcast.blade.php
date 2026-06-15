@extends('layouts.app')

@section('header_title', 'Detail Arsip Broadcast')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Detail Arsip Broadcast</h2>
            <p class="text-sm text-gray-500 mt-1">Melihat rincian pesan broadcast yang pernah dikirim.</p>
        </div>
        <a href="{{ route('arsip.index') }}"
            class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-green-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali ke Arsip
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <x-card>
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Broadcast</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Judul Broadcast</div>
                        <div class="font-medium text-gray-900 mt-1">{{ $item->title }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Waktu Dikirim</div>
                        <div class="font-medium text-gray-900 mt-1">
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Target Penerima</div>
                        <div class="font-medium text-gray-900 mt-1">
                            @if(!empty($item->target_segment['type']))
                                @if($item->target_segment['type'] === 'all')
                                    Semua Wilayah
                                @elseif($item->target_segment['type'] === 'region')
                                    {{ \App\Models\Region::find($item->target_segment['id'])?->name ?? 'Wilayah' }}
                                @elseif($item->target_segment['type'] === 'group')
                                    {{ \App\Models\FarmerGroup::find($item->target_segment['id'])?->name ?? 'Kelompok' }}
                                @endif
                            @else
                                Semua Wilayah
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Status</div>
                        <div class="font-medium text-gray-900 mt-1 uppercase text-green-600">{{ $item->status }}</div>
                    </div>
                </div>
            </x-card>

            <x-card class="mt-6">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Konten Pesan</h3>
                </div>
                <div class="p-4">
                    <div class="bg-gray-50 p-4 rounded-lg whitespace-pre-wrap text-sm text-gray-700 font-mono">
                        {{ $item->content }}</div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2">
            <x-card>
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Log Pengiriman ({{ $logs->count() }} Pesan)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nomor HP</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($logs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->phone }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(in_array($log->status, ['sent', 'delivered']))
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($log->status) }}</span>
                                        @elseif($log->status === 'failed')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Gagal</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($log->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->updated_at->format('H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada log
                                        pengiriman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
@endsection