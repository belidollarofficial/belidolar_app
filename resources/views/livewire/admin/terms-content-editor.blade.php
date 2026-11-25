<div class="space-y-4">
    <div>
        <label for="termsServiceSelect" class="block text-sm font-semibold text-white mb-1.5">
            Jenis Transaksi
        </label>
        <select id="termsServiceSelect" wire:model.live="service" class="form-select">
            @foreach ($services as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
        @error('service') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        <p class="text-xs text-[#94a3b8] mt-1">
            Pilih jenis transaksi untuk memuat teks syarat &amp; ketentuan yang akan diedit.
        </p>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-2">
        <label class="block text-sm font-semibold text-white">
            Isi Syarat &amp; Ketentuan — {{ $services[$service] ?? $service }}
        </label>
        @if ($defaultContent !== null)
            <button type="button" wire:click="resetToDefault"
                wire:confirm="Kembalikan syarat & ketentuan layanan ini ke nilai default?"
                class="btn-reset"
                @disabled($content === $defaultContent)>
                <i class="fas fa-rotate-left"></i> Reset ke Default
            </button>
        @endif
    </div>

    <div class="rte-wrapper">
        <div class="rte-toolbar" data-terms-toolbar>
            <button type="button" data-command="bold" title="Tebal"><i class="fas fa-bold"></i></button>
            <button type="button" data-command="italic" title="Miring"><i class="fas fa-italic"></i></button>
            <button type="button" data-command="underline" title="Garis Bawah"><i class="fas fa-underline"></i></button>
            <span class="rte-sep"></span>
            <select data-command="formatBlock" title="Format Paragraf">
                <option value="p">Paragraf</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="h4">Heading 4</option>
                <option value="blockquote">Kutipan</option>
            </select>
            <span class="rte-sep"></span>
            <button type="button" data-command="insertUnorderedList" title="Daftar Poin"><i class="fas fa-list-ul"></i></button>
            <button type="button" data-command="insertOrderedList" title="Daftar Angka"><i class="fas fa-list-ol"></i></button>
            <button type="button" data-command="createLink" title="Tautan"><i class="fas fa-link"></i></button>
            <button type="button" data-command="unlink" title="Hapus Tautan"><i class="fas fa-link-slash"></i></button>
            <span class="rte-sep"></span>
            <button type="button" data-command="removeFormat" title="Hapus Format"><i class="fas fa-eraser"></i></button>
        </div>

        <div data-terms-editor class="rte-content" contenteditable="true" role="textbox" aria-multiline="true" wire:ignore></div>
        <textarea data-terms-input wire:model="content" class="hidden">{{ $content }}</textarea>
    </div>

    @error('content') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
    <p class="text-xs text-[#94a3b8]">
        Pergantian jenis transaksi memuat teks untuk jenis tersebut tanpa me-refresh halaman.
        HTML berbahaya akan dibersihkan otomatis saat disimpan.
    </p>

    <div class="pt-1">
        <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save"
            class="btn-primary inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold">
            <i class="fas fa-save"></i>
            <span wire:loading.remove wire:target="save">Simpan Syarat &amp; Ketentuan</span>
            <span wire:loading wire:target="save">Menyimpan...</span>
        </button>
    </div>
</div>

@script
<script>
    const root = $wire.$el;
    const editor = root.querySelector('[data-terms-editor]');
    const input = root.querySelector('[data-terms-input]');
    const toolbar = root.querySelector('[data-terms-toolbar]');

    if (editor && input && toolbar) {
        editor.innerHTML = input.value || '<p><br></p>';

        const syncEditor = () => {
            input.value = editor.innerHTML;
            input.dispatchEvent(new Event('input', { bubbles: true }));
        };

        editor.addEventListener('input', syncEditor);
        editor.addEventListener('blur', syncEditor);

        toolbar.querySelectorAll('button[data-command]').forEach((button) => {
            button.addEventListener('click', () => {
                const command = button.getAttribute('data-command');
                editor.focus();

                if (command === 'createLink') {
                    const url = window.prompt('Masukkan URL tautan:');
                    if (url) document.execCommand('createLink', false, url);
                } else {
                    document.execCommand(command, false, null);
                }

                syncEditor();
            });
        });

        const formatSelect = toolbar.querySelector('select[data-command="formatBlock"]');
        formatSelect?.addEventListener('change', () => {
            editor.focus();
            document.execCommand('formatBlock', false, '<' + formatSelect.value + '>');
            formatSelect.selectedIndex = 0;
            syncEditor();
        });

        $wire.on('terms-editor-content-loaded', ({ content }) => {
            input.value = content || '';
            editor.innerHTML = content || '<p><br></p>';
            input.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }
</script>
@endscript
