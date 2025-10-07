function toggleMenu(id) {
    let submenu = document.getElementById(id);
    submenu.classList.toggle("active");
}

function goBack(url) {
    window.location = url;
}

function setActiveMenu() {
    const currentUrl = window.location.pathname;

    // --- Sidebar LEFT ---
    const menuLeftSubmenu = document.querySelectorAll('.sidebar-left .submenu li a');
    menuLeftSubmenu.forEach(link => {
        const href = link.getAttribute('href');
        if (currentUrl.startsWith(href)) {
            link.classList.add('active');
            const submenu = link.closest('.submenu');
            if (submenu) submenu.classList.add('active');
            const sectionButton = submenu?.previousElementSibling;
            if (sectionButton) sectionButton.classList.add('active');
        }
    });

    const menuLeftMenulinks = document.querySelectorAll('.sidebar-left .menu-link a');
    menuLeftMenulinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentUrl.startsWith(href)) {
            link.classList.add('active');
        }
    });

    // --- Sidebar RIGHT ---
    const menuRightSubmenu = document.querySelectorAll('.sidebar-right .submenu li a');
    menuRightSubmenu.forEach(link => {
        const href = link.getAttribute('href');
        if (currentUrl.startsWith(href)) {
            link.classList.add('active');
            const submenu = link.closest('.submenu');
            if (submenu) submenu.classList.add('active');
            const sectionButton = submenu?.previousElementSibling;
            if (sectionButton) sectionButton.classList.add('active');
        }
    });

    const menuRightMenulinks = document.querySelectorAll('.sidebar-right .menu-link a');
    menuRightMenulinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentUrl.startsWith(href)) {
            link.classList.add('active');
        }
    });
}

window.onload = setActiveMenu;
