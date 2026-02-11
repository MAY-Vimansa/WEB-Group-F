// Current Simulated Token (Assume 14 people already booked)
let currentToken = 14;
const maxTokens = 20;

// 1. Generate Time Slots: 4:00 PM to 9:00 PM (15-min intervals)
function generateSlots() {
    const slotGrid = document.getElementById('slotGrid');
    let startTime = 16; // 4 PM in 24h format
    let endTime = 21;   // 9 PM
    
    for (let hour = startTime; hour < endTime; hour++) {
        for (let min = 0; min < 60; min += 15) {
            let timeString = `${hour > 12 ? hour - 12 : hour}:${min === 0 ? '00' : min} PM`;
            
            let slot = document.createElement('div');
            slot.className = 'slot';
            slot.innerText = timeString;
            
            slot.onclick = function() {
                // Remove selected class from others
                document.querySelectorAll('.slot').forEach(s => s.classList.remove('selected'));
                // Add to this one
                this.classList.add('selected');
            };
            
            slotGrid.appendChild(slot);
        }
    }
}

// 2. Booking Function
function bookAppointment() {
    const alertBox = document.getElementById('alertBox');
    const selectedSlot = document.querySelector('.slot.selected');

    // Check if slots are available
    if (currentToken >= maxTokens) {
        alertBox.className = "alert-error";
        alertBox.innerText = "All tokens are booked!";
        alert("All tokens are booked!");
        return;
    }

    // Check if time is selected
    if (!selectedSlot) {
        alert("Please select a time slot!");
        return;
    }

    // Success logic
    currentToken++;
    document.getElementById('tokenCount').innerText = currentToken;
    
    alertBox.className = "alert-success";
    alertBox.innerText = "Channeling is successful!";
    alert("Channeling is successful!");
}

// Run slot generation on page load
window.onload = generateSlots;