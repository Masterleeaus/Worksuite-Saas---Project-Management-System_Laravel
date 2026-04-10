{{-- PASS 12c: Quill "Create" assist (replaces rephrase) --}}
<script>
(function () {
    function hasQuill() {
        return !!(window.Quill || document.querySelector('.ql-toolbar') || document.querySelector('.ql-editor'));
    }

    function getSelectedText() {
        try {
            // If Quill instance is available globally, selection extraction varies by app; fallback to DOM selection.
            var sel = window.getSelection ? window.getSelection() : null;
            if (sel && sel.toString) return sel.toString().trim();
        } catch (e) {}
        return '';
    }

    function ensureButton() {
        if (!hasQuill()) return;
        if (document.getElementById('aitools-create-btn')) return;

        var btn = document.createElement('button');
        btn.id = 'aitools-create-btn';
        btn.type = 'button';
        btn.className = 'btn btn-primary aitools-create-btn';
        btn.innerText = 'Create (Zero)';
        btn.title = 'Turn selected text into a Prompt or Tool in AI Tools';

        btn.addEventListener('click', function () {
            var text = getSelectedText();
            if (!text) {
                alert('Select some text in the editor first.');
                return;
            }
            // Simple chooser
            var choice = confirm('OK = Create Prompt\nCancel = Create Tool');
            var url = choice
                ? "{{ route('ai-tools.prompts.create') }}?draft=" + encodeURIComponent(text)
                : "{{ route('ai-tools.tools.create') }}?draft=" + encodeURIComponent(text);
            window.location.href = url;
        });

        document.body.appendChild(btn);
    }

    // Try on load + periodically (Quill editors often load after page render)
    document.addEventListener('DOMContentLoaded', function () {
        ensureButton();
        setInterval(ensureButton, 1500);
    });
})();
</script>