function toggleMenu(id) {
    let submenu = document.getElementById(id);
    submenu.classList.toggle("active");
}

function goBack(url) {
    window.location = url;
}

// Function to open the correct section and highlight the active link
function setActiveMenu() {
    // Get the current URL path
    const currentUrl = window.location.pathname;

    // Get all the sidebar left
    const menuLeft = document.querySelectorAll('.sidebar-left .submenu li a');

    // Loop through all the links in the sidebar
    menuLeft.forEach(link => {
        // Check if the link's href matches the current URL
        if (link.getAttribute('href') === currentUrl) {
            // Add active class to the matching link
            link.classList.add('active');

            // Find the parent submenu (ul) and open it
            const submenu = link.closest('.submenu');
            if (submenu) {
                submenu.classList.add('active');
            }

            // Find the parent button and mark it active
            const sectionButton = submenu.previousElementSibling;
            if (sectionButton) {
                sectionButton.classList.add('active');
            }
        }
    });

    const menuRight = document.querySelectorAll('.sidebar-right .submenu li a');

    // Loop through all the links in the sidebar
    menuRight.forEach(link => {
        // Check if the link's href matches the current URL
        if (link.getAttribute('href') === currentUrl) {
            // Add active class to the matching link
            link.classList.add('active');

            // Find the parent submenu (ul) and open it
            const submenu = link.closest('.submenu');
            if (submenu) {
                submenu.classList.add('active');
            }

            // Find the parent button and mark it active
            const sectionButton = submenu.previousElementSibling;
            if (sectionButton) {
                sectionButton.classList.add('active');
            }
        }
    });
}

// Call the function to set the active menu when the page loads
window.onload = setActiveMenu;