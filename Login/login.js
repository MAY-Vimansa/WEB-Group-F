function validateLogin() {
    

    if (document.loginForm.email.value.trim() === '') {
        alert("Please enter your email");
        return false;
    }

    if (document.loginForm.password.value.trim() === '') {
        alert("Please enter your password");
        return false;
    }

    if (document.loginForm.password.value.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }

    alert("Login Successful!");
        return true;    
}

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
