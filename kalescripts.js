// Tämä koodi käsittelee moodin vaihtamisen 
function setMode(mode) {
    const calendar = document.getElementById('calendar');
    if (mode === 'biit') {
        calendar.classList.remove('rink');
        calendar.classList.add('biit');
    } else if (mode === 'rink') {
        calendar.classList.remove('biit');
        calendar.classList.add('rink');
    }
}

// Tämä käsittelee eri tilojen näyttämisen
let mode = 'biit';

function setMode(newMode) {
    mode = newMode;
    updateCalendar();
}

let currentDate = new Date();

function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    updateCalendar();
}

function updateCalendar() {
    const monthNames = ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"];
    document.querySelector('h2').textContent = monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();

    // Fetch and display events or available times based on the mode
    if (mode === 'biit') {
        // Display events
        // Lisää koodi tapahtumien näyttämiseksi
    } else if (mode === 'rink') {
        // Display available times
        // Lisää koodi vapaiden aikojen näyttämiseksi
    }
}

// Tämä näyttää timeslotit kun päivää klikataan
document.addEventListener('DOMContentLoaded', function() {
    const timeslots = document.querySelector('.timeslots');
    
    window.showTimeslots = function(day) {
        timeslots.innerHTML = ''; // Tyhjennä aikaisemmat timeslotit
        timeslots.style.display = 'grid'; // Näytä timeslotit
        for (let i = 8; i < 24; i++) {
            const slot = document.createElement('div');
            slot.classList.add('slot');
            slot.textContent = `${i}:00 - ${i + 1}:00`;
            timeslots.appendChild(slot);
        }
    };
});