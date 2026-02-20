

    function togglePassword() {
        var passwordField = document.getElementById("password");
        var showButton = document.querySelector(".show-password");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            showButton.textContent = "Hide";
        } else {
            passwordField.type = "password";
            showButton.textContent = "Show";
        }
}

const params = new URLSearchParams(window.location.search);
const error = params.get('error');
if (error === 'invalid') {
    alert('Invalid email or password.');
} else if (error === 'missing') {
    alert('Please enter your email and password.');
}
