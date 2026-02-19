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



function showCurrentDateTime() {
    var now = new Date();
    var formatted = now.toLocaleString();

    var info = document.createElement("p");
    info.id = "currentDateTime";
    info.style.marginTop = "15px";
    info.style.fontSize = "14px";
    info.style.color = "#555";
    info.textContent = "Current Date & Time: " + formatted;

    var container = document.querySelector(".booking-body");
    if (container) {
        container.appendChild(info);
    }
}

showCurrentDateTime();


var reasonField = document.getElementById("reason");

if (reasonField) {

    var counter = document.createElement("p");
    counter.id = "charCounter";
    counter.style.fontSize = "13px";
    counter.style.color = "#777";
    counter.textContent = "Characters: 0";

    reasonField.parentNode.appendChild(counter);

    reasonField.addEventListener("input", function () {
        counter.textContent = "Characters: " + reasonField.value.length;
    });
}


var form = document.forms["bookingForm"];

if (form) {

    var clearBtn = document.createElement("button");
    clearBtn.type = "button";
    clearBtn.textContent = "Clear Form";
    clearBtn.style.marginLeft = "10px";
    clearBtn.style.padding = "8px";

    clearBtn.onclick = function () {
        form.reset();
        alert("Form cleared!");
    };

    form.appendChild(clearBtn);
}

setInterval(showCurrentDateTime, 1000);




}
