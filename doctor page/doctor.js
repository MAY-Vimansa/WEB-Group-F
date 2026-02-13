document.addEventListener('DOMContentLoaded', () => {
    // Simple status filter
    const statusFilter = document.querySelectorAll('.filter-select')[1];
    const dateFilter = document.querySelectorAll('.filter-select')[0];
    const appointments = Array.from(document.querySelectorAll('.appointment-card'));

    function applyFilters() {
        const statusVal = statusFilter ? statusFilter.value : 'all';
        const dateVal = dateFilter ? dateFilter.value : 'all';

        appointments.forEach(card => {
            const statusEl = card.querySelector('.appointment-status');
            const dateEl = card.querySelector('.appointment-details .detail-item span');
            const statusText = statusEl ? statusEl.textContent.trim().toLowerCase() : '';
            const dateText = dateEl ? dateEl.textContent.trim().toLowerCase() : '';

            let statusMatch = statusVal === 'all' || statusText.includes(statusVal);
            let dateMatch = true;
            if (dateVal === 'today') {
                dateMatch = dateText.includes('feb 13, 2026');
            } else if (dateVal === 'tomorrow') {
                dateMatch = dateText.includes('feb 14, 2026');
            } else if (dateVal === 'week') {
                dateMatch = true;
            }

            card.style.display = statusMatch && dateMatch ? '' : 'none';
        });
    }

    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    if (dateFilter) dateFilter.addEventListener('change', applyFilters);

    // Action buttons
    document.body.addEventListener('click', (e) => {
        const btn = e.target.closest('.action-btn');
        if (!btn) return;
        const card = btn.closest('.appointment-card');
        const statusEl = card.querySelector('.appointment-status');

        if (btn.classList.contains('accept-btn')) {
            statusEl.textContent = 'Confirmed';
            statusEl.className = 'appointment-status status-confirmed';
        } else if (btn.classList.contains('decline-btn')) {
            statusEl.textContent = 'Cancelled';
            statusEl.className = 'appointment-status status-cancelled';
        } else if (btn.classList.contains('complete-btn')) {
            statusEl.textContent = 'Completed';
            statusEl.className = 'appointment-status status-completed';
        } else if (btn.classList.contains('view-btn')) {
            const name = card.querySelector('.patient-details h3').textContent.trim();
            alert('View details for ' + name);
        }

        applyFilters();
    });

    // Logout logic
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                alert('Logged out successfully!');
                window.location.href = '/Homepage/home.html';
            }
        });
    }
});
