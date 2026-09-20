const themeKey = 'ecommerce-theme';
const root = document.documentElement;

function setTheme(theme) {
    const dark = theme === 'dark';
    root.dataset.theme = dark ? 'dark' : 'light';

    document.querySelectorAll('.theme-toggle').forEach((button) => {
        button.setAttribute('aria-label', dark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
        button.setAttribute('title', dark ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
        button.querySelector('.theme-icon-dark')?.classList.toggle('d-none', dark);
        button.querySelector('.theme-icon-light')?.classList.toggle('d-none', !dark);
    });
}

setTheme(localStorage.getItem(themeKey) === 'dark' ? 'dark' : 'light');

document.addEventListener('click', (event) => {
    if (!event.target.closest('.theme-toggle')) return;
    const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
    localStorage.setItem(themeKey, nextTheme);
    setTheme(nextTheme);
});
