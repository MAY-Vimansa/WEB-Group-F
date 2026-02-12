var today = new Date().toISOString().split('T')[0];
document.getElementById('date').setAttribute('min', today);

function validateBooking() {


var patientName = document.bookingForm.patientName.value.trim();
if (patientName === '') {
    alert("Please enter patient name");
    return false;
}

var date = document.bookingForm.date.value;
if (date === '') {
    alert("Please select an appointment date");
    return false;
}

var selectedDate = new Date(date);
var today = new Date();
today.setHours(0, 0, 0, 0);

if (selectedDate < today) {
    alert("Please select a future date");
    return false;
}

var time = document.querySelector('input[name="time"]:checked');
if (!time) {
    alert("Please select a time slot");
    return false;
}

var reason = document.bookingForm.reason.value.trim();
if (reason === '') {
    alert("Please provide a reason for your visit");
    return false;
}

if (reason.length < 10) {
    alert("Please provide more details about your reason for visit (at least 10 characters)");
    return false;
}

var doctorName = document.getElementById('doctorName').textContent;

alert("Appointment Booked Successfully!\n\nDoctor: " + doctorName + "\nPatient: " + patientName + "\nDate: " + date + "\nTime: " + time.value );

return true;
}

var urlParams = new URLSearchParams(window.location.search);
var doctor = urlParams.get('doctor');
var specialty = urlParams.get('specialty');

if (doctor) {
    document.getElementById('doctorName').textContent = doctor;
}
if (specialty) {
    document.getElementById('doctorSpecialty').textContent = specialty;
}
