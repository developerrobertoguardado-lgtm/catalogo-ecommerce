function bloquearFormulario(form) {
    if (form.dataset.submitting === 'true') {
        return;
    }

    form.dataset.submitting = 'true';
    form.setAttribute('aria-busy', 'true');

    form.querySelectorAll('input:not([type="hidden"]), select, textarea, button').forEach((control) => {
        if (control.type === 'file') {
            control.classList.add('form-control-locked');
            return;
        }

        if (!control.name || ((control.type === 'checkbox' || control.type === 'radio') && !control.checked)) {
            control.disabled = true;
            return;
        }

        const mirror = document.createElement('input');
        mirror.type = 'hidden';
        mirror.name = control.name;
        mirror.value = control.value;
        mirror.dataset.lockMirror = 'true';
        form.appendChild(mirror);
        control.disabled = true;
    });

    form.querySelectorAll('button[type="submit"]').forEach((button) => {
        button.dataset.originalText = button.textContent;
        button.textContent = 'Guardando...';
    });
}

document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-lock-on-submit]');

    if (form) {
        bloquearFormulario(form);
    }
});
