@extends('layouts.app')

@section('header_title', 'Dashboard Analytics')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <x-card class="bg-gradient-to-br from-green-500 to-green-600 text-white border-0 shadow-lg shadow-green-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-50 text-sm font-medium">Total Petani</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($totalPetani ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
            </div>
        </x-card>

        <!-- Card 2 -->
        <x-card class="bg-gradient-to-br from-blue-500 to-blue-600 text-white border-0 shadow-lg shadow-blue-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-50 text-sm font-medium">Kelompok Tani</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($totalKelompok ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </x-card>

        <!-- Card 3 -->
        <x-card class="bg-gradient-to-br from-purple-500 to-purple-600 text-white border-0 shadow-lg shadow-purple-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-50 text-sm font-medium">Pesan Terkirim</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($pesanTerkirim ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
            </div>
        </x-card>

        <!-- Card 4 -->
        <x-card class="bg-gradient-to-br from-red-500 to-red-600 text-white border-0 shadow-lg shadow-red-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-50 text-sm font-medium">Pesan Gagal</p>
                    <h3 class="text-3xl font-bold mt-1">{{ number_format($pesanGagal ?? 0) }}</h3>
                </div>
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Charts & Map Area -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Tren Pengiriman Pesan</h3>
            </x-slot>
            <div class="h-80 flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                <p class="text-gray-500 font-medium">Grafik Tren Pengiriman (Placeholder)</p>
                <p class="text-sm text-gray-400 mt-1">Area untuk integrasi Chart.js atau ApexCharts</p>
            </div>
        </x-card>

        <!-- Map -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Distribusi Wilayah Petani</h3>
            </x-slot>
            <div class="h-80 flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                <p class="text-gray-500 font-medium">Peta Sebaran (Placeholder)</p>
                <p class="text-sm text-gray-400 mt-1">Area untuk integrasi Leaflet atau Google Maps</p>
            </div>
        </x-card>
    </div>
@endsection
