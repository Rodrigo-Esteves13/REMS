document.addEventListener('DOMContentLoaded', function () {
    // Function to toggle the mode
    function toggleMode(theme) {
        const body = document.body;
        body.classList.remove('dark-mode', 'light-mode');
        body.classList.add(theme + '-mode');

        // Store the theme preference in local storage
        localStorage.setItem('theme', theme);

        // Update theme classes for various elements
        const selectorsToUpdate = [
            '.header', '.sidebar', '.logos', '.logos.linkedin', '.logo-text', '.buyCoffee', '.black-section', '.fa-coffee', '.profile-info', '.app-name', '.app-name.linkedin', '.auth-links a', '.sidebar-item', '.auth-links', '.dropdown-toggle', '.modal-content', '.profile-dropdown', '.profile-table', '.project-item', '.project-title', '.project-description', '.news-item', '.news-title', '.news-description', '.change-password-table', '.desktop-text', '.card', '.card-header', '.step2-card', '.step2-card-header'
        ];

        selectorsToUpdate.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                element.classList.remove('dark-mode', 'light-mode');
                element.classList.add(theme + '-mode');
            }
        });

        // Update theme classes for .news-item elements
        const newsItems = document.querySelectorAll('.news-item');
        newsItems.forEach(item => {
            item.classList.remove('dark-mode', 'light-mode');
            item.classList.add(theme + '-mode');
        });

        // Update theme classes for .projects-details elements
        const projectItem = document.querySelectorAll('.project-item');
        projectItem.forEach(item => {
            item.classList.remove('dark-mode', 'light-mode');
            item.classList.add(theme + '-mode');
        });
    }

    // Function to set the initial mode and theme classes
    function setInitialToggleState() {
        const body = document.body;
        const storedTheme = localStorage.getItem('theme');
        const toggleCheckbox = document.getElementById('modeSwitch');

        if (storedTheme === 'dark' || storedTheme === null){
            toggleMode('dark');
            toggleCheckbox.checked = true;
        } else {
            toggleMode('light');
            toggleCheckbox.checked = false; // Uncheck the toggle for light mode
        }
    }

    // Call the function to set the initial toggle state
    setInitialToggleState();

    // Event listener for the toggle button
    document.getElementById('modeSwitch').addEventListener('change', function () {
        toggleMode(this.checked ? 'dark' : 'light');
    });

    // Set the initial mode and theme classes when the page loads
    window.addEventListener('DOMContentLoaded', setInitialToggleState);
});
