const editors = new Map();

async function loadQuill() {
    const { default: Quill } = await import('quill');
    return Quill;
}

async function initQuillEditors(root = document) {
    const elements = root.querySelectorAll('.quill-editor:not(.ql-toolbar)');
    if (!elements.length) return;

    const Quill = await loadQuill();

    elements.forEach((el) => {
        const id = el.id;
        if (editors.has(id)) return;

        const textarea = el.parentElement.querySelector('textarea');
        if (!textarea) return;

        const quill = new Quill(`#${id}`, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote', 'code-block'],
                    ['link', 'image'],
                    [{ align: [] }],
                    ['clean'],
                ],
            },
            placeholder: 'Descripción detallada del producto...',
        });

        if (textarea.value) {
            quill.root.innerHTML = textarea.value;
        }

        quill.on('text-change', () => {
            textarea.value = quill.root.innerHTML;
        });

        editors.set(id, quill);
    });
}

function syncAllEditors() {
    editors.forEach((quill, id) => {
        const el = document.getElementById(id);
        if (!el) return;
        const textarea = el.parentElement.querySelector('textarea');
        if (textarea) textarea.value = quill.root.innerHTML;
    });
}

document.addEventListener('DOMContentLoaded', () => initQuillEditors());

document.addEventListener('shown.bs.modal', (e) => {
    initQuillEditors(e.target);
});

document.querySelectorAll('form[data-lock-on-submit]').forEach((form) => {
    form.addEventListener('submit', syncAllEditors);
});
