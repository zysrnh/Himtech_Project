// Kasly Interactive Neumorphic Navigation & Dropdown Accordion
function initSidebarDropdowns() {
    const groups = document.querySelectorAll('[data-sidebar-dropdown]');
    if (!groups.length) return;

    groups.forEach((group) => {
        const toggle = group.querySelector('[data-sidebar-toggle]');
        const content = group.querySelector('[data-sidebar-content]');
        const icon = group.querySelector('[data-sidebar-icon]');
        if (!toggle || !content) return;

        // Auto-expand if any active subitem exists inside
        const hasActiveSubitem = content.querySelector('.is-active') !== null;
        if (hasActiveSubitem) {
            content.style.maxHeight = content.scrollHeight + 'px';
            content.style.opacity = '1';
            group.setAttribute('data-open', 'true');
            if (icon) icon.style.transform = 'rotate(180deg)';
        } else {
            content.style.maxHeight = '0px';
            content.style.opacity = '0';
            group.setAttribute('data-open', 'false');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }

        // Click event listener
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpen = group.getAttribute('data-open') === 'true';

            if (isOpen) {
                content.style.maxHeight = '0px';
                content.style.opacity = '0';
                group.setAttribute('data-open', 'false');
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.opacity = '1';
                group.setAttribute('data-open', 'true');
                if (icon) icon.style.transform = 'rotate(180deg)';
            }
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebarDropdowns);
} else {
    initSidebarDropdowns();
}
