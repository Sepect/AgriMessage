@extends('layouts.app')

@section('header_title', 'Buat Broadcast')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Kirim Pesan Massal</h2>
            <p class="text-sm text-gray-500 mt-1">Buat dan kirim pesan broadcast ke petani berdasarkan segmentasi.</p>
        </div>
        <a href="{{ route('broadcast.index') }}" class="inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-green-500 px-4 py-2 text-sm">
            <svg class="w-5 h-5 mr-2 -ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="broadcastForm" action="{{ route('broadcast.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Utama: Konten Pesan -->
            <div class="lg:col-span-2 space-y-6">
                <x-card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Informasi Dasar</h3>
                    
                    <div class="space-y-4">
                        <x-input type="text" name="title" label="Judul Broadcast" placeholder="Contoh: Info Pupuk Subsidi Periode Juli" required />
                        
                        <div x-data="{
                            selectedTemplate: '',
                            templates: {{ $templates->mapWithKeys(function($t) { return [$t->id => $t->content]; })->toJson() }},
                            updateContent() {
                                if (this.selectedTemplate && this.templates[this.selectedTemplate]) {
                                    document.getElementById('broadcast_content').value = this.templates[this.selectedTemplate];
                                }
                            }
                        }">
                            <x-input type="select" name="template_id" label="Gunakan Template (Opsional)" x-model="selectedTemplate" x-on:change="updateContent()">
                                <option value="">-- Pilih Template --</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </x-input>
                        </div>
                    </div>
                </x-card>

                <x-card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Konten Pesan</h3>
                    
                    <div>
                        <textarea id="broadcast_content" name="content" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm border p-4 transition-colors" rows="10" placeholder="Ketik pesan Anda di sini..." required></textarea>
                        <div class="mt-3 flex flex-wrap gap-2 items-center text-sm text-gray-500">
                            <span class="font-medium mr-2">Variabel Tersedia:</span>
                            <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50" onclick="document.getElementById('broadcast_content').value += '[Nama]'">[Nama]</button>
                            <p class="w-full mt-2 text-xs text-gray-400">Variabel akan digantikan secara otomatis dengan data masing-masing petani saat pesan dikirim.</p>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Kolom Samping: Pengaturan -->
            <div class="space-y-6">
                <x-card x-data="{ targetType: 'all' }">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Segmentasi Penerima</h3>
                    
                    <div class="space-y-4">
                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" :class="targetType === 'all' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                            <input type="radio" name="target_type" value="all" class="sr-only" x-model="targetType">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">Semua Petani</span>
                                    <span class="mt-1 flex items-center text-xs text-gray-500">Kirim ke seluruh kontak aktif</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-green-600" :class="targetType === 'all' ? 'block' : 'hidden'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </label>

                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" :class="targetType === 'region' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                            <input type="radio" name="target_type" value="region" class="sr-only" x-model="targetType">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">Per Wilayah</span>
                                    <span class="mt-1 flex items-center text-xs text-gray-500">Spesifik ke satu desa/kecamatan</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-green-600" :class="targetType === 'region' ? 'block' : 'hidden'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </label>
                        
                        <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none" :class="targetType === 'group' ? 'border-green-500 ring-1 ring-green-500' : 'border-gray-300'">
                            <input type="radio" name="target_type" value="group" class="sr-only" x-model="targetType">
                            <span class="flex flex-1">
                                <span class="flex flex-col">
                                    <span class="block text-sm font-medium text-gray-900">Kelompok Tani</span>
                                    <span class="mt-1 flex items-center text-xs text-gray-500">Kirim ke anggota kelompok tertentu</span>
                                </span>
                            </span>
                            <svg class="h-5 w-5 text-green-600" :class="targetType === 'group' ? 'block' : 'hidden'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </label>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100" x-show="targetType === 'region'" x-cloak>
                        <x-input type="select" name="target_id_region" label="Pilih Wilayah">
                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}</option>
                            @endforeach
                        </x-input>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100" x-show="targetType === 'group'" x-cloak>
                        <x-input type="select" name="target_id_group" label="Pilih Kelompok">
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </x-input>
                    </div>
                    
                    <!-- Hidden field to consolidate target_id (populated by JS on submit) -->
                    <input type="hidden" name="target_id" value="" />
                </x-card>

                <x-card>
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Penjadwalan</h3>
                    
                    <div class="space-y-4">
                        <label class="flex items-center">
                            <input type="radio" name="schedule_type" value="now" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300" checked>
                            <span class="ml-3 block text-sm font-medium text-gray-700">Kirim Sekarang</span>
                        </label>
                    </div>
                </x-card>

                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Proses Pengiriman
                    </button>
                    <a href="{{ route('broadcast.index') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
    
    <script>
        document.getElementById('broadcastForm').addEventListener('submit', function(e) {
            let targetType = document.querySelector('input[name="target_type"]:checked').value;
            let targetIdInput = document.querySelector('input[name="target_id"]');
            
            if (targetType === 'region') {
                targetIdInput.value = document.querySelector('select[name="target_id_region"]').value;
            } else if (targetType === 'group') {
                targetIdInput.value = document.querySelector('select[name="target_id_group"]').value;
            } else {
                targetIdInput.value = '';
            }
        });
    </script>
@endsection
