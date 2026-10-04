const pageStatus = document.querySelector('#page-loading');

function showPageStatus(message) {
    if (pageStatus instanceof HTMLElement) {
        pageStatus.textContent = message;
        pageStatus.hidden = false;
    }
}

window.addEventListener('pageshow', () => {
    if (pageStatus instanceof HTMLElement) {
        pageStatus.hidden = true;
    }
});

document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element) || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
    }

    const link = event.target.closest('a');

    if (!(link instanceof HTMLAnchorElement) || link.target || link.hasAttribute('download')) {
        return;
    }

    const destination = new URL(link.href, window.location.href);

    if (destination.origin === window.location.origin && (destination.pathname !== window.location.pathname || destination.search !== window.location.search)) {
        showPageStatus('Memuat halaman...');
    }
});

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    if (form.hasAttribute('data-confirm') && !window.confirm(form.dataset.confirm)) {
        event.preventDefault();
        return;
    }

    if (form.method.toLowerCase() === 'get') {
        showPageStatus('Memuat data...');
        return;
    }

    const submitButton = form.querySelector('button[type="submit"]');

    if (!(submitButton instanceof HTMLButtonElement) || submitButton.disabled) {
        return;
    }

    showPageStatus('Menyimpan perubahan...');
    submitButton.disabled = true;
    submitButton.setAttribute('aria-busy', 'true');

    const loadingText = submitButton.dataset.loadingText;

    if (loadingText) {
        submitButton.textContent = loadingText;
    }
});

const themeButtons = document.querySelectorAll('[data-theme-toggle]');
const root = document.documentElement;

function updateThemeControls(theme) {
    themeButtons.forEach((button) => {
        const nextTheme = theme === 'dark' ? 'terang' : 'gelap';
        button.textContent = `Tema ${nextTheme}`;
        button.setAttribute('aria-label', `Ganti ke tema ${nextTheme}`);
    });
}

updateThemeControls(root.dataset.theme === 'dark' ? 'dark' : 'light');

themeButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const theme = root.dataset.theme === 'dark' ? 'light' : 'dark';
        root.dataset.theme = theme;
        updateThemeControls(theme);

        try {
            localStorage.setItem('praktikum.theme', theme);
        } catch (error) {
            console.error('Preferensi tema tidak dapat disimpan.', error);
        }
    });
});
