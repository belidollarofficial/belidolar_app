<!-- ===== WHATSAPP MODAL ===== -->
    <div class="modal-wa" id="waModal" data-wa-number="{{ $waNumber }}">
        <div class="p-4 border-b border-[#334155] flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-[#25d366] flex items-center justify-center text-white">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-[#1e293b] rounded-full"></span>
                </div>
                <div>
                    <p class="font-bold text-sm text-white">{{ $siteName }} Support</p>
                    <p class="text-xs text-gray-400">Online &bull; Biasanya membalas dalam 1 menit</p>
                </div>
            </div>
            <button type="button" onclick="toggleWAModal()" class="text-gray-400 hover:text-gray-200" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4">
            <div class="bg-[#0f1724] rounded-xl p-3 mb-3">
                <p class="text-sm text-white">{{ $greeting }}</p>
                <span class="text-xs text-gray-400">{{ now()->format('H:i') }}</span>
            </div>

            <div class="flex flex-wrap gap-2 mb-3">
                @foreach ($quickReplies as $reply)
                    <button type="button" onclick="setWAMessage(this)" data-message="{{ $reply }}"
                            class="text-xs px-3 py-1.5 rounded-full border border-[#334155] text-gray-300 hover:border-[#25d366] hover:text-white transition">
                        {{ $reply }}
                    </button>
                @endforeach
            </div>

            <div class="flex gap-2">
                <input type="text" id="waMessageInput" placeholder="Ketik pesan..." autocomplete="off"
                       onkeydown="if (event.key === 'Enter') { event.preventDefault(); sendWAModal(); }"
                       class="flex-1 border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-[#25d366] transition" />
                <button type="button" onclick="sendWAModal()" aria-label="Kirim pesan"
                        class="bg-[#25d366] text-white px-4 py-2 rounded-xl hover:bg-[#1da851] transition">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <p class="text-xs text-center text-gray-400 mt-2">Pesan akan dibuka di aplikasi WhatsApp</p>
        </div>
    </div>
