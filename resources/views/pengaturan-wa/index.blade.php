@extends('layouts.app')

@section('header_title', 'Pengaturan WhatsApp')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Koneksi WhatsApp Gateway</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola koneksi perangkat WhatsApp yang digunakan untuk mengirim pesan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Status & QR Code (Kolom Kiri) -->
        <div class="lg:col-span-1 space-y-6">
            <x-card class="text-center pb-8">
                <div class="flex justify-center mb-4 mt-2">
                    <div class="relative">
                        <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center border-4 border-green-100">
                            <svg class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 2.164.693 4.167 1.865 5.795L2.5 21.5l3.856-1.306A9.962 9.962 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm4.318 14.195c-.215.606-1.258 1.157-1.745 1.205-.444.044-.999.167-2.923-.627-2.316-.957-3.805-3.32-3.921-3.475-.115-.154-.937-1.252-.937-2.392 0-1.14.59-1.705.8-1.927.206-.217.447-.271.597-.271.149 0 .3 0 .428.006.133.007.316-.051.492.373.18.435.617 1.512.673 1.626.056.114.093.247.018.397-.075.149-.115.242-.228.363-.115.122-.243.266-.346.363-.116.11-.237.23-.105.457.132.227.587.97 1.264 1.573.87.777 1.6 1.025 1.815 1.127.215.101.341.085.468-.06.126-.145.545-.634.691-.852.146-.218.293-.182.492-.107.199.075 1.261.597 1.478.705.217.108.362.162.414.252.052.09.052.523-.163 1.129z" clip-rule="evenodd" /></svg>
                        </div>
                        <!-- Indicator -->
                        <span class="absolute bottom-1 right-2 block h-5 w-5 rounded-full ring-4 ring-white bg-green-500"></span>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold text-gray-900">Connected</h3>
                <p class="text-sm text-gray-500 mt-1 mb-6">+62 812-XXXX-9999</p>
                
                <div class="inline-flex items-center justify-center p-4 bg-gray-50 border border-gray-200 rounded-xl mb-6">
                    <!-- Fake QR Code Image -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=agrimessage-connected" alt="QR Code" class="w-32 h-32 opacity-20" />
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-sm font-medium text-gray-600 bg-white/60 backdrop-blur-[2px] rounded-xl border border-gray-100">
                        <svg class="w-8 h-8 text-green-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Telah Terhubung
                    </div>
                </div>
                
                <div class="px-6 flex flex-col gap-3">
                    <x-button variant="danger" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Putuskan Koneksi
                    </x-button>
                    <x-button variant="secondary" class="w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Restart Service
                    </x-button>
                </div>
            </x-card>
        </div>

        <!-- Settings (Kolom Kanan) -->
        <div class="lg:col-span-2 space-y-6">
            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Server Gateway</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status Service</p>
                        <div class="mt-1 flex items-center">
                            <span class="flex h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-sm font-medium text-gray-900">Running (Online)</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Uptime</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">14 hari, 3 jam, 45 menit</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Baterai Perangkat</p>
                        <div class="mt-1 flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                            <span class="text-sm font-medium text-gray-900">85% (Charging)</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Versi API</p>
                        <p class="mt-1 text-sm font-medium text-gray-900">Baileys v6.4.1</p>
                    </div>
                </div>
            </x-card>

            <x-card>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Pengaturan Pesan Otomatis</h3>
                
                <form class="space-y-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Auto-Reply Diluar Jam Kerja</h4>
                            <p class="text-sm text-gray-500">Balas otomatis ketika ada pesan masuk di luar jam kerja penyuluh.</p>
                        </div>
                        <!-- Toggle -->
                        <button type="button" class="bg-green-500 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <span aria-hidden="true" class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    
                    <x-input type="textarea" rows="3" label="Isi Pesan Auto-Reply" value="Halo! Terima kasih telah menghubungi AgriMessage. Saat ini kami sedang di luar jam operasional. Kami akan membalas pesan Anda pada jam kerja (Senin-Jumat, 08:00 - 16:00 WIB)." />

                    <div class="border-t border-gray-100 pt-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Webhook URL</h4>
                                <p class="text-sm text-gray-500">Endpoint untuk menerima notifikasi pesan masuk secara real-time.</p>
                            </div>
                        </div>
                        <div class="mt-3 flex gap-3">
                            <x-input type="text" value="https://agrimessage.com/api/webhook/whatsapp" disabled class="bg-gray-50" />
                            <x-button type="button" variant="secondary" class="mt-6 whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Copy
                            </x-button>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <x-button type="button">Simpan Pengaturan</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
