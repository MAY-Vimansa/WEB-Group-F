document.addEventListener('DOMContentLoaded', () => {
    // Get references to the form and the message area element
    const registerForm = document.getElementById('register-form');
    const messageArea = document.getElementById('message-area');

    // Helper function to display an error message
    function showError(message) {
        messageArea.textContent = message;
        // Reset classes and add error-specific class
        messageArea.className = 'message-area message-error';
    }

    // Helper function to display a success message
    function showSuccess(message) {
        messageArea.textContent = message;
        // Reset classes and add success-specific class
        messageArea.className = 'message-area message-success';
    }

    // Helper function to clear the message area
    function clearMessage() {
        messageArea.textContent = '';
        // Hide the message area by resetting to base class
        messageArea.className = 'message-area';
    }

    // Add a submit event listener to the registration form
    registerForm.addEventListener('submit', (event) => {
        // 1. Prevent the default form submission behavior (page reload)
        event.preventDefault();
        
        // 2. Clear any existing messages from previous attempts
        clearMessage();

        // 3. Get values from the input fields
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        // --- Basic Validation Logic ---

        // Check if password is too short (a common security rule)
        if (password.length < 6) {
            showError('Password must be at least 6 characters long.');
            return; // Stop further execution
        }

        // Check if the password and confirm password fields match
        if (password !== confirmPassword) {
            showError('Passwords do not match.');
            return; // Stop further execution
        }

        // --- Simulation of Successful Registration ---
        // If all validations pass, we simulate sending data to a server.
        
        // Display a success message to the user
        showSuccess('Registration successful! ');
        
        // Optional: Reset the form fields to blank
        registerForm.reset();
    });
});