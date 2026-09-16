document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('darkModeToggle');
    const htmlEl = document.documentElement;

    // Load saved preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlEl.setAttribute('data-theme', savedTheme);
    toggle.checked = savedTheme === 'dark';

    toggle.addEventListener('change', function () {
        const newTheme = toggle.checked ? 'dark' : 'light';
        htmlEl.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
});