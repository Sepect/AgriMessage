@extends('layouts.app')

@section('header_title', 'Template Pesan')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Template Pesan</h2>
            <p class="text-sm text-gray-500 mt-1">Buat template untuk mempercepat pengiriman pesan broadcast.</p>
        </div>
        <button x-data x-on:click="$dispatch('open-modal', 'form-template-create')" class="inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 px-4 py-2 text-sm">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Template
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Grid Template Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
        <!-- Template Card -->
        <x-card class="flex flex-col h-full hover:border-green-300 transition-colors cursor-pointer group">
            <div class="flex-1">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="text-lg font-bold text-gray-900">{{ $template->name }}</h3>
                    <div class="flex opacity-0 group-hover:opacity-100 transition-opacity" x-data>
                        <button x-on:click="$dispatch('open-modal', 'form-template-edit'); $dispatch('set-template-edit', {{ json_encode($template) }})" class="text-blue-500 hover:text-blue-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                        <button x-on:click="$dispatch('open-modal', 'confirm-template-delete'); $dispatch('set-template-delete', {{ $template->id }})" class="text-red-500 hover:text-red-700 p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 font-mono whitespace-pre-wrap h-32 overflow-hidden relative">{{ Str::limit($template->content, 100) }}
                    <div class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-gray-50 to-transparent"></div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                <span>Diperbarui: {{ $template->updated_at->format('d M Y') }}</span>
            </div>
        </x-card>
        @empty
            <div class="col-span-full py-8 text-center text-gray-500 bg-white rounded-xl border border-dashed border-gray-300">
                Belum ada template.
            </div>
        @endforelse
    </div>

    <!-- Modal Tambah Template -->
    <x-modal name="form-template-create" maxWidth="2xl">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Buat Template Pesan</h2>
            
            <form action="{{ route('template.store') }}" method="POST" class="space-y-4">
                @csrf
                <x-input type="text" name="name" label="Nama Template" placeholder="Contoh: Info Cuaca" required />
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pesan</label>
                    <textarea name="content" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm border p-2.5 transition-colors" rows="6" required placeholder="Ketik isi template di sini..."></textarea>
                    <div class="mt-2 flex items-center justify-between">
                        <div class="flex gap-2">
                            <span class="text-xs text-gray-500">Variabel: </span>
                            <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50" onclick="document.querySelector('textarea[name=content]').value += '[Nama]'">
                                [Nama]
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-template-create')">Batal</x-button>
                    <x-button type="submit">Simpan Template</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Edit Template -->
    <x-modal name="form-template-edit" maxWidth="2xl">
        <div class="p-6" x-data="{ 
            template: null,
            actionUrl: ''
        }"
        x-on:set-template-edit.window="
            template = $event.detail;
            actionUrl = '/template/' + template.id;
            setTimeout(() => { document.querySelector('#edit_content').value = template.content; }, 50);
        ">
            <h2 class="text-lg font-semibold text-gray-900 mb-5 pb-2 border-b border-gray-100">Ubah Template Pesan</h2>
            
            <form :action="actionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <x-input type="text" name="name" label="Nama Template" ::value="template?.name" required />
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pesan</label>
                    <textarea id="edit_content" name="content" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm border p-2.5 transition-colors" rows="6" required></textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                    <x-button type="button" variant="ghost" x-on:click="$dispatch('close-modal', 'form-template-edit')">Batal</x-button>
                    <x-button type="submit">Simpan Perubahan</x-button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Modal Konfirmasi Hapus Template -->
    <x-modal name="confirm-template-delete" maxWidth="sm">
        <div class="p-6 text-center" x-data="{ actionUrl: '' }" x-on:set-template-delete.window="actionUrl = '/template/' + $event.detail">
            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Hapus Template?</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus template ini? Tindakan ini tidak dapat dibatalkan.</p>
            
            <form :action="actionUrl" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-3">
                    <x-button type="button" variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-template-delete')">Batal</x-button>
                    <x-button type="submit" variant="danger">Ya, Hapus Data</x-button>
                </div>
            </form>
        </div>
    </x-modal>
@endsection
