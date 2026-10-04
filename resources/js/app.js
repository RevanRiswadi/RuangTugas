/* ── Page loading indicator ───────────────────────────────────────────────── */
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

/* ── Theme toggle ─────────────────────────────────────────────────────────── */
const themeButtons = document.querySelectorAll('[data-theme-toggle]');
const root = document.documentElement;

function updateThemeControls(theme) {
    const nextTheme = theme === 'dark' ? 'terang' : 'gelap';
    themeButtons.forEach((button) => {
        // Update only the label spans — preserves SVG icons inside the button
        const darkLabel = button.querySelector('.theme-label-dark');
        const lightLabel = button.querySelector('.theme-label-light');
        if (darkLabel || lightLabel) {
            // Icon-based buttons — CSS handles show/hide via data-theme on <html>
            button.setAttribute('aria-label', `Ganti ke tema ${nextTheme}`);
        } else {
            // Fallback for plain-text-only buttons
            button.textContent = `Tema ${nextTheme}`;
            button.setAttribute('aria-label', `Ganti ke tema ${nextTheme}`);
        }
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

/* ── Command Palette (Ctrl+K) ─────────────────────────────────────────────── */
const cmdOverlay = document.querySelector('#cmd-overlay');
const cmdModal = document.querySelector('#cmd-modal');
const cmdInput = document.querySelector('#cmd-input');
const cmdResults = document.querySelector('#cmd-results');

if (cmdOverlay && cmdModal && cmdInput && cmdResults) {
    // Gather all searchable links on the page once
    function buildIndex() {
        const items = [];
        // Navigation links from sidebar / mobile nav
        document.querySelectorAll('[data-cmd-item]').forEach((el) => {
            items.push({
                label: el.dataset.cmdLabel || el.textContent.trim(),
                href: el.dataset.cmdHref || el.getAttribute('href') || '#',
                type: el.dataset.cmdType || 'halaman',
            });
        });
        return items;
    }

    function openPalette() {
        cmdOverlay.hidden = false;
        cmdOverlay.setAttribute('aria-hidden', 'false');
        cmdInput.value = '';
        renderResults('');
        requestAnimationFrame(() => cmdInput.focus());
    }

    function closePalette() {
        cmdOverlay.hidden = true;
        cmdOverlay.setAttribute('aria-hidden', 'true');
        cmdInput.value = '';
    }

    function renderResults(query) {
        const index = buildIndex();
        const q = query.toLowerCase().trim();
        const filtered = q
            ? index.filter((item) => item.label.toLowerCase().includes(q))
            : index;

        if (filtered.length === 0) {
            cmdResults.innerHTML = '<li class="cmd-empty">Tidak ada hasil untuk <strong>' + query + '</strong></li>';
            return;
        }

        cmdResults.innerHTML = filtered
            .map((item, i) =>
                `<li role="option" id="cmd-opt-${i}">
                    <a href="${item.href}" class="cmd-result-item" tabindex="-1">
                        <span class="cmd-result-type">${item.type}</span>
                        <span class="cmd-result-label">${item.label}</span>
                    </a>
                </li>`
            )
            .join('');
    }

    // Open on Ctrl+K or Cmd+K
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            cmdOverlay.hidden ? openPalette() : closePalette();
        }
        if (e.key === 'Escape' && !cmdOverlay.hidden) {
            closePalette();
        }
    });

    // Open via the trigger button
    document.querySelectorAll('[data-cmd-trigger]').forEach((btn) => {
        btn.addEventListener('click', () => openPalette());
    });

    // Close on backdrop click
    cmdOverlay.addEventListener('click', (e) => {
        if (e.target === cmdOverlay) closePalette();
    });

    // Search input live filtering
    cmdInput.addEventListener('input', () => renderResults(cmdInput.value));

    // Arrow-key navigation within results
    cmdInput.addEventListener('keydown', (e) => {
        const items = cmdResults.querySelectorAll('a.cmd-result-item');
        const current = cmdResults.querySelector('a.cmd-result-item:focus');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!current) items[0]?.focus();
            else {
                const idx = [...items].indexOf(current);
                items[idx + 1]?.focus();
            }
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (!current) items[items.length - 1]?.focus();
            else {
                const idx = [...items].indexOf(current);
                if (idx === 0) cmdInput.focus();
                else items[idx - 1]?.focus();
            }
        }
    });

    cmdResults.addEventListener('keydown', (e) => {
        const items = cmdResults.querySelectorAll('a.cmd-result-item');
        const current = document.activeElement;
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const idx = [...items].indexOf(current);
            items[idx + 1]?.focus();
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            const idx = [...items].indexOf(current);
            if (idx === 0) cmdInput.focus();
            else items[idx - 1]?.focus();
        }
    });
}
