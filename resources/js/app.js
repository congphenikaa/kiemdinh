import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('appShell', () => ({
    sidebarOpen: false,

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },

    closeSidebar() {
        this.sidebarOpen = false;
    },
}));

Alpine.data('flashMessages', () => ({
    visible: true,

    init() {
        setTimeout(() => {
            this.visible = false;
        }, 5000);
    },
}));

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.menu-parent .parent-item').forEach((item) => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const parentMenu = item.closest('.menu-parent');
            if (!parentMenu) return;

            document.querySelectorAll('.menu-parent').forEach((menu) => {
                if (menu !== parentMenu) {
                    menu.classList.remove('open');
                    const child = menu.querySelector('.child-menu');
                    const icon = menu.querySelector('.dropdown-icon');
                    if (child) child.style.maxHeight = '0';
                    if (icon) icon.classList.remove('rotate-180');
                }
            });

            parentMenu.classList.toggle('open');
            const childMenu = parentMenu.querySelector('.child-menu');
            const dropdownIcon = parentMenu.querySelector('.dropdown-icon');

            if (parentMenu.classList.contains('open')) {
                if (childMenu) childMenu.style.maxHeight = childMenu.scrollHeight + 'px';
                if (dropdownIcon) dropdownIcon.classList.add('rotate-180');
            } else {
                if (childMenu) childMenu.style.maxHeight = '0';
                if (dropdownIcon) dropdownIcon.classList.remove('rotate-180');
            }
        });
    });

    document.querySelectorAll('.menu-parent.active').forEach((menu) => {
        menu.classList.add('open');
        const childMenu = menu.querySelector('.child-menu');
        if (childMenu) childMenu.style.maxHeight = childMenu.scrollHeight + 'px';
        const dropdownIcon = menu.querySelector('.dropdown-icon');
        if (dropdownIcon) dropdownIcon.classList.add('rotate-180');
    });

    setupSearch();
});

window.showConfirmModal = function (title, message, confirmCallback) {
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-message').textContent = message;
    const confirmBtn = document.getElementById('modal-confirm');
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.addEventListener('click', () => {
        confirmCallback();
        document.getElementById('confirm-modal').classList.add('hidden');
    });

    document.getElementById('confirm-modal').classList.remove('hidden');
};

function setupSearch() {
    document.querySelectorAll('.search-input').forEach((input) => {
        let rowsCache = null;
        let timeout;

        input.addEventListener('focus', () => {
            if (!rowsCache) {
                const table =
                    input.closest('.content-section')?.querySelector('table') ||
                    document.querySelector('.data-table');
                rowsCache = table ? Array.from(table.querySelectorAll('tbody tr')) : [];
            }
        });

        input.addEventListener('input', function () {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const searchTerm = this.value.toLowerCase();
                if (rowsCache) {
                    rowsCache.forEach((row) => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                }
            }, 200);
        });
    });
}

Alpine.start();
