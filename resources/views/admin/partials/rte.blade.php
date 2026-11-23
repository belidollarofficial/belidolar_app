@php
    $editorId = $id.'Editor';
    $inputId = $id.'Input';
    $toolbarId = $id.'Toolbar';
@endphp

<div class="rte-wrapper" id="{{ $id }}Wrapper">
    <div class="rte-toolbar" id="{{ $toolbarId }}">
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

    <div id="{{ $editorId }}" class="rte-content" contenteditable="true" role="textbox" aria-multiline="true"></div>

    <textarea name="{{ $name }}" id="{{ $inputId }}" class="hidden">{{ $value }}</textarea>
</div>

<script>
    (function () {
        var editor = document.getElementById(@json($editorId));
        var input = document.getElementById(@json($inputId));
        var toolbar = document.getElementById(@json($toolbarId));

        if (!editor || !input || !toolbar) return;

        editor.innerHTML = input.value || '<p><br></p>';

        function sync() {
            input.value = editor.innerHTML;
        }

        editor.addEventListener('input', sync);
        editor.addEventListener('blur', sync);

        var form = editor.closest('form');

        if (form) {
            form.addEventListener('submit', sync);
        }

        toolbar.querySelectorAll('button[data-command]').forEach(function (button) {
            button.addEventListener('click', function () {
                var command = button.getAttribute('data-command');

                editor.focus();

                if (command === 'createLink') {
                    var url = window.prompt('Masukkan URL tautan:');

                    if (url) {
                        document.execCommand('createLink', false, url);
                    }
                } else {
                    document.execCommand(command, false, null);
                }

                sync();
            });
        });

        var formatSelect = toolbar.querySelector('select[data-command="formatBlock"]');

        if (formatSelect) {
            formatSelect.addEventListener('change', function () {
                editor.focus();
                document.execCommand('formatBlock', false, '<' + formatSelect.value + '>');
                formatSelect.selectedIndex = 0;
                sync();
            });
        }
    })();
</script>
