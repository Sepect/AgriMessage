@extends('layouts.app')

@section('header_title', 'Kotak Masuk')

@section('content')
    <div class="h-[calc(100vh-8rem)] flex flex-col md:flex-row gap-6 -mx-4 sm:mx-0">
        <!-- Sidebar Daftar Obrolan -->
        <div class="w-full md:w-1/3 lg:w-1/4 bg-white md:rounded-xl shadow-sm border border-gray-100 flex flex-col h-[50vh] md:h-full">
            <div class="p-4 border-b border-gray-100 flex flex-col gap-3">
                <h2 class="text-lg font-bold text-gray-900">Pesan Masuk</h2>
                <form method="GET" action="{{ route('inbox.index') }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500 transition-colors" placeholder="Cari nama atau nomor...">
                    <button type="submit" class="hidden"></button>
                </form>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <ul class="divide-y divide-gray-50">
                    @forelse($chats as $c)
                    <!-- Item Obrolan -->
                    <li>
                        <a href="{{ route('inbox.index', ['chat' => $c->id]) }}" class="block hover:bg-green-50 transition-colors {{ (isset($activeChat) && $activeChat->id == $c->id) ? 'bg-green-50 border-l-4 border-green-500' : '' }}">
                            <div class="px-4 py-4 flex items-center">
                                <div class="flex-shrink-0 relative">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($c->farmer ? $c->farmer->name : $c->phone) }}&background=random" alt="">
                                    @if(!$c->is_read)
                                        <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-white"></span>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1 overflow-hidden">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $c->farmer ? $c->farmer->name : $c->phone }}</p>
                                        <p class="text-xs text-gray-500 whitespace-nowrap">{{ $c->updated_at->diffForHumans(null, true, true) }}</p>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate mt-0.5 {{ !$c->is_read ? 'font-medium text-gray-900' : '' }}">{{ $c->last_message }}</p>
                                </div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li class="px-4 py-8 text-center text-sm text-gray-500">
                        Belum ada pesan masuk.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Area Obrolan Utama -->
        <div class="flex-1 bg-white md:rounded-xl shadow-sm border border-gray-100 flex flex-col h-[60vh] md:h-full">
            @if(isset($activeChat))
            <!-- Header Obrolan Aktif -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-white md:rounded-t-xl shrink-0">
                <div class="flex items-center">
                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($activeChat->farmer ? $activeChat->farmer->name : $activeChat->phone) }}&background=random" alt="">
                    <div class="ml-3">
                        <p class="text-sm font-bold text-gray-900">{{ $activeChat->farmer ? $activeChat->farmer->name : $activeChat->phone }}</p>
                        <p class="text-xs text-green-600 flex items-center">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                            Terhubung via WhatsApp
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button class="text-gray-400 hover:text-gray-600 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></button>
                    <button class="text-gray-400 hover:text-gray-600 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
                </div>
            </div>

            <!-- Ruang Pesan -->
            <div id="chat-messages-container" class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col gap-4"
                 x-data="chatBox({{ $activeChat->replies->last()->id ?? 0 }}, {{ $activeChat->id }})">
                <div class="text-center">
                    <span class="text-xs font-medium text-gray-400 bg-gray-200 px-2 py-1 rounded-full">Awal Percakapan</span>
                </div>

                @foreach($activeChat->replies as $reply)
                    @if($reply->sender_type == 'farmer')
                    <!-- Pesan Masuk (Petani) -->
                    <div class="flex justify-start">
                        <div class="bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-sm px-4 py-2.5 max-w-[85%] md:max-w-md shadow-sm">
                            <p class="text-sm">{{ $reply->message }}</p>
                            <span class="text-[10px] text-gray-400 block mt-1">{{ $reply->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                    @else
                    <!-- Pesan Keluar (Admin) -->
                    <div class="flex justify-end">
                        <div class="bg-green-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 max-w-[85%] md:max-w-md shadow-sm">
                            <p class="text-sm">{{ $reply->message }}</p>
                            <div class="flex items-center justify-end mt-1 gap-1">
                                <span class="text-[10px] text-green-200">{{ $reply->created_at->format('H:i') }}</span>
                                <svg class="w-3 h-3 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-gray-100 md:rounded-b-xl shrink-0">
                <form action="{{ route('inbox.reply', $activeChat->id) }}" method="POST" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea name="message" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm p-3 pr-10 resize-none max-h-32 bg-gray-50" rows="1" placeholder="Ketik balasan pesan..." required oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center p-3 border border-transparent rounded-xl shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors h-11 w-11 shrink-0">
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
            @else
            <!-- State Kosong / Belum Memilih Obrolan -->
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center bg-gray-50 md:rounded-xl">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Obrolan</h3>
                <p class="text-sm text-gray-500 max-w-sm">Pilih salah satu pesan di samping kiri untuk mulai membaca dan membalas pertanyaan dari petani.</p>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatBox', (initialLastId, chatId) => ({
            lastId: initialLastId,
            chatId: chatId,
            isPolling: false,
            init() {
                this.scrollToBottom();
                setInterval(() => {
                    if (!this.isPolling) {
                        this.fetchNewMessages();
                    }
                }, 3000);
            },
            scrollToBottom() {
                this.$el.scrollTop = this.$el.scrollHeight;
            },
            async fetchNewMessages() {
                this.isPolling = true;
                try {
                    const response = await fetch(`/api/inbox/${this.chatId}/updates?last_id=${this.lastId}`);
                    const data = await response.json();
                    
                    if (data.success && data.replies.length > 0) {
                        data.replies.forEach(reply => {
                            this.lastId = reply.id;
                            this.appendMessage(reply);
                        });
                        this.scrollToBottom();
                    }
                } catch (error) {
                    console.error('Error fetching messages:', error);
                } finally {
                    this.isPolling = false;
                }
            },
            appendMessage(reply) {
                const template = document.createElement('template');
                if (reply.sender_type === 'farmer') {
                    template.innerHTML = `
                    <div class="flex justify-start">
                        <div class="bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-sm px-4 py-2.5 max-w-[85%] md:max-w-md shadow-sm">
                            <p class="text-sm">${reply.message}</p>
                            <span class="text-[10px] text-gray-400 block mt-1">${reply.time}</span>
                        </div>
                    </div>`.trim();
                } else {
                    template.innerHTML = `
                    <div class="flex justify-end">
                        <div class="bg-green-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 max-w-[85%] md:max-w-md shadow-sm">
                            <p class="text-sm">${reply.message}</p>
                            <div class="flex items-center justify-end mt-1 gap-1">
                                <span class="text-[10px] text-green-200">${reply.time}</span>
                                <svg class="w-3 h-3 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>`.trim();
                }
                this.$el.appendChild(template.content.firstChild);
            }
        }));
    });
</script>
@endpush
