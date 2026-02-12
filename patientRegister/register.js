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

    alert("YOU ARE REGISTERED SUCCESSFULLY!");
    return true;
}

document.addEventListener('DOMContentLoaded', function () {
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
