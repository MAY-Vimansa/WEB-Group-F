document.querySelector('.edit-profile-btn').addEventListener('click', function() {
alert('Edit Profile feature coming soon!');
});

document.querySelector('.logout-btn').addEventListener('click', function() {
if(confirm('Are you sure you want to logout?')) {
alert('Logged out successfully!');
window.location.href = 'login.html';
}
});
