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
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
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
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
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
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
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
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Charts & Map Area -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Tren Pengiriman Pesan (14 Hari Terakhir)</h3>
            </x-slot>
            <div class="h-80 relative w-full pt-4">
                <canvas id="trendChart"></canvas>
            </div>
        </x-card>

        <!-- Map/Distribution -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Distribusi Wilayah Petani</h3>
            </x-slot>
            <div class="h-80 relative w-full flex justify-center items-center pb-4">
                <canvas id="regionChart"></canvas>
            </div>
        </x-card>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tren Pengiriman Pesan
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendGradient = trendCtx.createLinearGradient(0, 0, 0, 400);
            trendGradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
            trendGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendDates) !!},
                    datasets: [{
                        label: 'Pesan Terkirim',
                        data: {!! json_encode($trendTotals) !!},
                        borderColor: '#10B981',
                        backgroundColor: trendGradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10B981',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleFont: { size: 13, family: "'Inter', sans-serif" },
                            bodyFont: { size: 13, family: "'Inter', sans-serif" },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                font: { family: "'Inter', sans-serif", size: 12 },
                                color: '#6B7280'
                            }
                        },
                        y: {
                            grid: {
                                color: '#F3F4F6',
                                drawBorder: false,
                            },
                            ticks: {
                                font: { family: "'Inter', sans-serif", size: 12 },
                                color: '#6B7280',
                                stepSize: 1
                            },
                            beginAtZero: true
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });

            // Distribusi Wilayah
            const regionCtx = document.getElementById('regionChart').getContext('2d');
            const regionLabels = {!! json_encode($regionLabels) !!};
            const regionData = {!! json_encode($regionData) !!};

            const bgColors = [
                '#10B981', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444', '#14B8A6', '#F43F5E', '#EC4899', '#06B6D4'
            ];

            new Chart(regionCtx, {
                type: 'doughnut',
                data: {
                    labels: regionLabels,
                    datasets: [{
                        data: regionData,
                        backgroundColor: bgColors.slice(0, regionLabels.length),
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { family: "'Inter', sans-serif", size: 12 },
                                color: '#4B5563'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            titleFont: { size: 13, family: "'Inter', sans-serif" },
                            bodyFont: { size: 13, family: "'Inter', sans-serif" },
                            padding: 10,
                            cornerRadius: 8,
                        }
                    }
                }
            });
        });
    </script>
@endpush