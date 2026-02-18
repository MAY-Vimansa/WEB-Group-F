function validateForm() {
      
    if (document.form1.firstName.value.trim() === '') {
        alert("Please enter your first name");
        return false;
    }

    if (document.form1.lastName.value.trim() === '') {
        alert("Please enter your last name");
        return false;
    }


    if (document.form1.email.value.trim() === '') {
        alert("Please enter your email");
        return false;
    }

    if (document.form1.phonenumber.value.trim() === '') {
        alert("Please enter your phone number");
        return false;
    }

    if (document.form1.phonenumber.value.length != 10) {
        alert("Phone number must be exactly 10 digits");
        return false;
    }

    if (document.form1.dob.value === '') {
        alert("Please select your date of birth");
        return false;
    }

    if (document.form1.gender.value === '') {
        alert("Please select your gender");
        return false;
    }


    if (document.form1.password.value.trim() === '') {
        alert("Please enter a password");
        return false;
    }

    if (document.form1.password.value.length < 8) {
        alert("Password must contain at least 8 characters");
        return false;
    }


    if (document.form1.confirmPassword.value.trim() === '') {
        alert("Please confirm your password");
        return false;
    }

    if (document.form1.confirmPassword.value.length < 8) {
        alert("Confirm password must contain at least 8 characters");
        return false;
    }

    if (document.form1.password.value !== document.form1.confirmPassword.value) {
        alert("Passwords do not match");
        return false;
    }

    if (!document.form1.terms.checked) {
        alert("Please accept the Terms & Conditions");
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    var messageEl = document.getElementById('formMessage');
    var params = new URLSearchParams(window.location.search);
    var error = params.get('error');
    if (messageEl && error) {
        var text = '';
        if (error === 'exists') {
            text = 'An account with this email already exists. Please sign in.';
        } else if (error === 'invalid') {
            text = 'Please fill all required fields correctly.';
        }
        if (text) {
            messageEl.textContent = text;
            messageEl.classList.add('error');
        }
    }

    var toggles = document.querySelectorAll('.toggle-password');
    toggles.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = btn.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (!input) return;

            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.textContent = isPassword ? 'Hide' : 'Show';
            btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    });
});
