@extends('layouts.app')

@section('header_title', 'Arsip Pesan')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Arsip Riwayat Pesan</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar pesan broadcast dan percakapan lama yang telah diarsipkan untuk menghemat ruang.</p>
        </div>
    </div>

    <x-card class="mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full flex items-center gap-2">
                <div class="relative flex-1 max-w-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm transition-colors" placeholder="Pencarian arsip...">
                </div>
                
                <select class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option>Semua Kategori</option>
                    <option>Broadcast</option>
                    <option>Pesan Personal</option>
                </select>
                
                <select class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-lg">
                    <option>Tahun 2025</option>
                    <option>Tahun 2026</option>
                </select>
            </div>
            
            <x-button variant="danger">
                <svg class="w-5 h-5 mr-2 -ml-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Hapus Semua Arsip
            </x-button>
        </div>
    </x-card>

    <x-table>
        <x-slot name="head">
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Konten Pesan / Judul</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Penerima</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Diarsipkan</th>
            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
        </x-slot>

        <!-- Dummy Row 1 -->
        <tr>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">Himbauan Musim Kemarau 2025</div>
                <div class="text-sm text-gray-500 truncate max-w-xs mt-1">Kepada yth Bapak/Ibu, mari kita antisipasi kekeringan...</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Broadcast
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">Semua Wilayah</div>
                <div class="text-xs text-gray-500">1,200 Terkirim</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">31 Des 2025</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button class="text-blue-600 hover:text-blue-900 mr-3">Lihat Detail</button>
                <button class="text-red-600 hover:text-red-900">Hapus Permanen</button>
            </td>
        </tr>

        <!-- Dummy Row 2 -->
        <tr>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">Pertanyaan Bantuan Bibit Jagung</div>
                <div class="text-sm text-gray-500 truncate max-w-xs mt-1">Pak, bantuan bibit jagung untuk desa kami kapan turun?</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    Pesan Personal
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">Budi Santoso</div>
                <div class="text-xs text-gray-500">+62 812-3456-7890</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">15 Nov 2025</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button class="text-blue-600 hover:text-blue-900 mr-3">Lihat Transkrip</button>
                <button class="text-red-600 hover:text-red-900">Hapus Permanen</button>
            </td>
        </tr>

        <x-slot name="pagination">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">1</span> sampai <span class="font-medium">2</span> dari <span class="font-medium">4,521</span> data
                </div>
                <div class="flex gap-2">
                    <x-button variant="secondary" size="sm" disabled>Sebelumnya</x-button>
                    <x-button variant="secondary" size="sm">Selanjutnya</x-button>
                </div>
            </div>
        </x-slot>
    </x-table>
@endsection
