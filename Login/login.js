// Wait for the DOM to be fully loaded before running the script
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');

    // Add a 'submit' event listener to the form
    loginForm.addEventListener('submit', (event) => {
        // Prevent the default form submission behavior (page reload)
        event.preventDefault();

        // Get the values from the email and password input fields
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Check the credentials against the demo data
        if ((email === 'doctor@test.com' && password === '123') || 
            (email === 'patient@test.com' && password === '123')) {
            
            // If credentials are correct:
            // 1. Hide the error message if it was visible
            errorMessage.style.display = 'none';
            // 2. Show a success message (you can replace this with redirection)
            alert('Login successful! Redirecting...');
            // For a real application, you would redirect here, e.g.:
            // window.location.href = 'dashboard.html';
            
        } else {
            // If credentials are incorrect:
            // 1. Show the error message
            errorMessage.style.display = 'block';
            // 2. Set the text of the error message
            errorMessage.textContent = 'Invalid email or password.';
        }
    });
});