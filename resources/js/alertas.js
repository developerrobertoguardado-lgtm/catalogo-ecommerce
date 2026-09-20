import Swal from 'sweetalert2';

const defaults = {
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar',
    buttonsStyling: true,
};

const alertas = {
    success(title, text = '', options = {}) {
        return Swal.fire({ ...defaults, icon: 'success', title, text, ...options });
    },

    error(title, text = '', options = {}) {
        return Swal.fire({ ...defaults, icon: 'error', title, text, ...options });
    },

    info(title, text = '', options = {}) {
        return Swal.fire({ ...defaults, icon: 'info', title, text, ...options });
    },

    warning(title, text = '', options = {}) {
        return Swal.fire({ ...defaults, icon: 'warning', title, text, ...options });
    },

    confirm(title, text = '', options = {}) {
        return Swal.fire({
            ...defaults,
            icon: 'warning',
            title,
            text,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            ...options,
        }).then((result) => result.isConfirmed);
    },
};

function showStoredAlerts() {
    const dataElement = document.getElementById('app-alerts-data');

    if (!dataElement) {
        return;
    }

    const data = JSON.parse(dataElement.textContent || '{}');

    if (data.success) {
        alertas.success('Operación completada', data.success, { timer: 2200, showConfirmButton: false });
    }

    if (data.info) {
        alertas.info('Información', data.info);
    }

    if (data.warning) {
        alertas.warning('Advertencia', data.warning);
    }

    if (data.error) {
        alertas.error('Ocurrió un error', data.error);
    }

    if (data.errors?.length) {
        alertas.error('Revisa los datos', data.errors.join('\n'));
    }
}

function setupConfirmations() {
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('form[data-confirm]');

        if (!form || form.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();
        const confirmed = await alertas.confirm(
            form.dataset.confirmTitle || '¿Confirmar operación?',
            form.dataset.confirm,
        );

        if (confirmed) {
            form.dataset.confirmed = 'true';
            form.submit();
        }
    });
}

window.alertas = alertas;
showStoredAlerts();
setupConfirmations();
