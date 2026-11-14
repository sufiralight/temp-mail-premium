const system = window.matchMedia('(prefers-color-scheme: dark)');
const root = document.documentElement;
const modes = ['light', 'dark', 'system'];
const key = 'appearance';
let mode = root.dataset.appearance || 'system';

function apply() {
    const dark = mode === 'dark' || (mode === 'system' && system.matches);
    root.classList.toggle('dark', dark);
    root.dataset.appearance = mode;
    document.querySelectorAll('[data-appearance-mode]').forEach(button => {
        button.setAttribute('aria-pressed', String(button.dataset.appearanceMode === mode));
    });
}

document.querySelectorAll('[data-appearance-mode]').forEach(button => {
    button.addEventListener('click', () => {
        mode = button.dataset.appearanceMode;
        try { localStorage.setItem(key, mode); } catch {}
        apply();
    });
});

system.addEventListener('change', apply);
window.addEventListener('storage', event => {
    if (event.key === key || event.key === null) {
        mode = modes.includes(event.newValue) ? event.newValue : 'system';
        apply();
    }
});
apply();

document.querySelectorAll('[data-password-toggle]').forEach(button => {
    button.hidden = false;
    button.addEventListener('click', () => {
        const input = document.getElementById(button.getAttribute('aria-controls'));
        const visible = input.type === 'password';
        input.type = visible ? 'text' : 'password';
        button.setAttribute('aria-label', visible ? button.dataset.hide : button.dataset.show);
        button.querySelector('[data-password-eye]').toggleAttribute('hidden', visible);
        button.querySelector('[data-password-eye-off]').toggleAttribute('hidden', !visible);
    });
});

document.querySelectorAll('[data-auth-form]').forEach(form => {
    const button = form.querySelector('button[type="submit"]');
    const label = button.textContent;
    form.addEventListener('submit', event => {
        if (button.disabled) {
            event.preventDefault();
            return;
        }
        button.disabled = true;
        button.textContent = button.dataset.pending;
    });
    window.addEventListener('pageshow', () => {
        button.disabled = false;
        button.textContent = label;
    });
});
