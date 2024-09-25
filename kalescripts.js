    // Tämä koodi käsittelee moodin vaihtamisen 
        function setMode(mode) {
            const calendar = document.getElementById('calendar');
            if (mode === 'ms_orbiit') {
                calendar.classList.remove('redsven_ink');
                calendar.classList.add('ms_orbiit');
            } else if (mode === 'redsven_ink') {
                calendar.classList.remove('ms_orbiit');
                calendar.classList.add('redsven_ink');
            }
        }
    //Tämä käsittelee eri tilojen näyttämisen. Onko sama kuin yllä?
        let mode = 'ms_orbiit';

        function setMode(newMode) {
        mode = newMode;
        updateCalendar();
        }

        function updateCalendar() {
        const monthNames = ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"];
        document.querySelector('h2').textContent = monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
    
        // Fetch and display events or available times based on the mode
        if (mode === 'ms_orbiit') {
        // Display events --> ei tiedä mitä displayata
        } else if (mode === 'redsven_ink') {
        // Display available times --> ei ole näytettävää
        }
        }

        function changeMonth(offset) {
            const urlParams = new URLSearchParams(window.location.search);
            let month = parseInt(urlParams.get('month')) || new Date().getMonth() + 1;
            let year = parseInt(urlParams.get('year')) || new Date().getFullYear();

            month += offset;
            if (month < 1) {
                month = 12;
                year--;
            } else if (month > 12) {
                month = 1;
                year++;
            }

            urlParams.set('month', month);
            urlParams.set('year', year);
            window.location.search = urlParams.toString();
        }

    let currentDate = new Date();

    function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    updateCalendar();
    }

    function updateCalendar() {
    const monthNames = ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"];
    document.querySelector('h2').textContent = monthNames[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
    // Tämä koodi käsittelee kuukausien vaihtamisen
    }