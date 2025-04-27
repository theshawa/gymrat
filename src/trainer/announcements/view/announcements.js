// Announcement page scripts
document.addEventListener('DOMContentLoaded', function() {
    // Get form and notification elements
    const form = document.getElementById('announcement-form');
    const notification = document.getElementById('edit-time-notification');

    // Check if we should show the notification on page load (after redirect)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('posted') && urlParams.get('posted') === 'true') {
        // Show notification with animation
        notification.style.display = 'flex';
        
        // Remove query parameter from URL without reloading the page
        const url = new URL(window.location);
        url.searchParams.delete('posted');
        window.history.replaceState({}, document.title, url);
        
        // Smooth scroll to the notification
        setTimeout(() => {
            notification.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }

    // Add form submission handler
    if (form) {
        form.addEventListener('submit', function(e) {
            // Store a flag in localStorage to indicate form was submitted
            localStorage.setItem('announcement_submitted', 'true');
        });
    }
});