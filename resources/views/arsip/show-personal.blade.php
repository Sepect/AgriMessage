@extends('layouts.app')

@section('header_title', 'Transkrip Pesan Personal')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Transkrip Pesan Personal</h2>
            <p class="text-sm text-gray-500 mt-1">Riwayat percakapan dengan petani secara personal.</p>
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
                <div class="p-4 border-b border-gray-100 flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-lg">
                        {{ substr($item->farmer->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->farmer->name ?? 'Anonim' }}</h3>
                        <p class="text-sm text-gray-500">{{ $item->phone }}</p>
                    </div>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Percakapan Terakhir</div>
                        <div class="font-medium text-gray-900 mt-1">
                            {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y H:i') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase tracking-wider">Total Balasan</div>
                        <div class="font-medium text-gray-900 mt-1">{{ $item->replies->count() }} Balasan</div>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="lg:col-span-2">
            <x-card class="flex flex-col" style="height: 600px;">
                <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center">
                    <h3 class="font-semibold text-gray-900">Riwayat Percakapan</h3>
                </div>

                <div class="flex-1 p-4 overflow-y-auto bg-slate-50 space-y-4">
                    <!-- Initial Message from Farmer -->
                    <div class="flex items-end justify-start">
                        <div class="bg-white px-4 py-3 rounded-2xl rounded-bl-sm shadow-sm max-w-[80%]">
                            <p class="text-sm text-gray-800">{{ $item->last_message }}</p>
                            <span
                                class="text-[10px] text-gray-400 mt-1 block">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</span>
                        </div>
                    </div>

                    <!-- Replies -->
                    @foreach($item->replies as $reply)
                        @if($reply->sender_type === 'admin')
                            <div class="flex items-end justify-end">
                                <div class="bg-green-600 px-4 py-3 rounded-2xl rounded-br-sm shadow-sm max-w-[80%]">
                                    <p class="text-sm text-white">{{ $reply->message }}</p>
                                    <span
                                        class="text-[10px] text-green-200 mt-1 block text-right">{{ \Carbon\Carbon::parse($reply->created_at)->format('H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex items-end justify-start">
                                <div class="bg-white px-4 py-3 rounded-2xl rounded-bl-sm shadow-sm max-w-[80%]">
                                    <p class="text-sm text-gray-800">{{ $reply->message }}</p>
                                    <span
                                        class="text-[10px] text-gray-400 mt-1 block">{{ \Carbon\Carbon::parse($reply->created_at)->format('H:i') }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </x-card>
        </div>
    </div>
@endsection