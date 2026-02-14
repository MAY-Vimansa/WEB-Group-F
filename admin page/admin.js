// Tab Functionality
function openTab(evt, tabName) {
    var i, tabcontent, tabbuttons;
    
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }
    
    tabbuttons = document.getElementsByClassName("tab-button");
    for (i = 0; i < tabbuttons.length; i++) {
        tabbuttons[i].classList.remove("active");
    }
    
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

// Modal Functionality
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close modal when clicking outside 
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.classList.remove('active');
    }
}

// Logout confirmation
document.querySelector('.logout-btn').addEventListener('click', function(event) {
    event.preventDefault();
    if(confirm('Are you sure you want to logout?')) {
        alert('Logged out successfully!');
        window.location.href = 'login.html';  
    }
});

// Add Patient form validation
function validateForm(event) {
    var form = event && event.target ? event.target : document.querySelector('#addPatientModal form');
    var phoneInput = form ? form.querySelector('input[name="tel"]') : null;
    var phoneValue = phoneInput ? phoneInput.value.trim() : '';
    var isValid = /^\d{10}$/.test(phoneValue);

    if (!isValid) {
        alert('Phone number must contain exactly 10 digits.');
        if (phoneInput) {
            phoneInput.focus();
        }
        if (event && event.preventDefault) {
            event.preventDefault();
        }
        return false;
    }
    alert("PATIENT ADDED SUCCESSFULLY");    
    return true;
}

function validateDoctorForm(event) {
    var form = event && event.target ? event.target : document.querySelector('#addDoctorModal form');
    var phoneInput = form ? form.querySelector('input[name="doctor_tel"]') : null;
    var phoneValue = phoneInput ? phoneInput.value.trim() : '';
    var isValid = /^\d{10}$/.test(phoneValue);

    if (!isValid) {
        alert('Phone number must contain exactly 10 digits.');
        if (phoneInput) {
            phoneInput.focus();
        }
        if (event && event.preventDefault) {
            event.preventDefault();
        }
        return false;
    }
    alert("DOCTOR ADDED SUCCESSFULLY");
    return true;
}

// Add Appointment form validation
function validateAppointmentForm(event) {
    var form = event && event.target ? event.target : document.querySelector('#addAppointmentModal form');
    if (!form) {
        return false;
    }

    var appointmentId = form.querySelector('input[name="appointment_id"]');
    var patientName = form.querySelector('input[name="patient_name"]');
    var doctorName = form.querySelector('input[name="doctor_name"]');
    var dateInput = form.querySelector('input[name="appointment_date"]');
    var timeSelect = form.querySelector('select[name="appointment_time"]');

    if (!appointmentId.value.trim()) {
        alert('Please enter an appointment ID.');
        appointmentId.focus();
        event.preventDefault();
        return false;
    }
    if (!patientName.value.trim()) {
        alert('Please enter a patient name.');
        patientName.focus();
        event.preventDefault();
        return false;
    }
    if (!doctorName.value.trim()) {
        alert('Please enter a doctor name.');
        doctorName.focus();
        event.preventDefault();
        return false;
    }
    if (!dateInput.value) {
        alert('Please select a date.');
        dateInput.focus();
        event.preventDefault();
        return false;
    }
    if (!timeSelect.value) {
        alert('Please select a time.');
        timeSelect.focus();
        event.preventDefault();
        return false;
    }

    alert('APPOINTMENT ADDED SUCCESSFULLY');
    return true;
}

