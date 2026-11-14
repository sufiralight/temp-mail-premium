(() => {
    let mode = 'system';
    try {
        const stored = localStorage.getItem('appearance');
        if (['light', 'dark', 'system'].includes(stored)) mode = stored;
    } catch {}
    document.documentElement.dataset.appearance = mode;
    document.documentElement.classList.toggle('dark',
        mode === 'dark' || (mode === 'system' && matchMedia('(prefers-color-scheme: dark)').matches));
})();
