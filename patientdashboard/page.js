document.querySelector('.logout-btn').addEventListener('click', function(event) {
    if (!confirm('Are you sure you want to logout?')) {
        event.preventDefault();
    }
});
