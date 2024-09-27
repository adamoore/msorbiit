<?php include 'header.php'; ?>
<?php
date_default_timezone_set('Europe/Helsinki');
$currentMonth = date('F Y');
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalenteri</title>
    <style>
        .calendar {
            display: inline-grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            background-color: #133667;
            color: #DD992C;
            margin: 0 auto;
        }
        .day {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            background-color: #D3F6DB;
            color: #133667; 
        }
        .tapahtumat, .holiday, .vapaat_ajat {
            background-color: #f0f0f0;
            margin-top: 5px;
            padding: 5px;
        }
        .holiday {
            background-color: #FF817E;
        }
        .vapaat_ajat {
            background-color: #90EE90;
        }
        
        .buttons {
            margin-bottom: 20px;
        }
        .buttons button {
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #17C3B2;
            font-family: 'Sofadi One', system-ui;
        }
        .navigation {
            margin-bottom: 20px;
        }
        .navigation button {
            margin-right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            background-color: #133667;
            color: #FFFC99;
            font-family: 'Sofadi One', system-ui;
        }
    </style>
</head>
<body>
    <h1>Tapahtumakalenteri</h1>
    <h2><?php echo $currentMonth; ?></h2>
    <div class="buttons">
        <button onclick="setMode('ms_orbiit')">M/S Orbiit tapahtumat</button>
        <button onclick="setMode('redsven_ink')">Redsven's Ink Ajanvaraus</button>
    </div>
    <div class="navigation">
        <button onclick="changeMonth(-1)">Edellinen</button>
        <button onclick="changeMonth(1)">Seuraava</button>
    </div>
    <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == true): ?>
    <form action="kalenteri.php" method="post">
        <label for="date">Päivämäärä:</label>
        <input type="date" id="date" name="date" required>
        <label for="tapahtumat">Tapahtumat:</label>
        <input type="text" id="tapahtumat" name="tapahtumat">
        <label for="vapaat_ajat">Vapaita aikoja:</label>
        <input type="text" id="vapaat_ajat" name="vapaat_ajat">
        <button type="submit">Lisää</button>
    </form>
    <?php else: ?>
    <p><a href="admin_login.php">Admin kirjautuminen</a></p>
    <?php endif; ?>
    <div class="calendar" id="kalenteri">
        <?php
        // Yhdistä tietokantaan (esimerkki MySQL)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "kalenteri";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Yhteys epäonnistui: " . $conn->connect_error);
        }
 // Aseta oletuskuukausi ja -vuosi
 $month = date('m');
 $year = date('Y');

 if (isset($_GET['month']) && isset($_GET['year'])) {
     $month = $_GET['month'];
     $year = $_GET['year'];
 }

 // Generoi kalenterin sisältö, muutetaan suomeks kentät myöhemmin
 $first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
 $days_in_month = date('t', $first_day_of_month);
 $day_of_week = date('w', $first_day_of_month);
 $day_of_week = ($day_of_week + 6) % 7; // Muuta viikon ensimmäinen päivä maanantaiksi

 echo "<div class='calendar'>";
 for ($i = 0; $i < $day_of_week; $i++) {
     echo "<div class='day'></div>";
 }
 for ($day = 1; $day <= $days_in_month; $day++) {
     echo "<div class='day'>$day</div>";
 }
 echo "</div>";

 $conn->close();
 ?>
<?php
// Käsittele lomakkeen lähetys
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $date = $_POST['date'];
            $tapahtumat = $_POST['tapahtumat'];
            $vapaat_ajat = $_POST['vapaat_ajat'];

            if (!empty($tapahtumat)) {
                $sql = "INSERT INTO tapahtumat (date, tapahtumat) VALUES ('$date', '$tapahtumat')";
                if ($conn->query($sql) === TRUE) {
                    echo "Tapahtuma lisätty onnistuneesti";
                } else {
                    echo "Virhe: " . $sql . "<br>" . $conn->error;
                }
            }

            if (!empty($vapaat_ajat)) {
                $sql = "INSERT INTO vapaat_ajat (date, vapaat_ajat) VALUES ('$date', '$vapaat_ajat')";
                if ($conn->query($sql) === TRUE) {
                    echo "Vapaa aika lisätty onnistuneesti";
                } else {
                    echo "Virhe: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        // Suomalaiset pyhät ja juhlat
        $holidays = [
            '01-01' => 'Uudenvuodenpäivä',
            '06-01' => 'Loppiainen',
            '04-30' => 'Vappuaatto',
            '05-01' => 'Vappu',
            '06-24' => 'Juhannusaatto',
            '06-25' => 'Juhannuspäivä',
            '12-06' => 'Itsenäisyyspäivä',
            '12-24' => 'Jouluaatto',
            '12-25' => 'Joulupäivä',
            '12-26' => 'Tapaninpäivä'
        ];

        // Näytä kalenteri
        $month = date('m');
        $year = date('Y');
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $days_in_month; $day++) {
            $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            $day_key = date('m-d', strtotime($date));
            echo "<div class='day'>$day";

            // Näytä tapahtumat
            $sql = "SELECT tapahtumat FROM tapahtumat WHERE date='$date'";
            $result = $conn->query($sql);
            //If-lausetta päivitetty $result &&-toiminnallisuudella, saatiin virheilmoitus pois
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='tapahtumat'>" . $row['tapahtumat'] . "</div>";
                }
            }

            // Näytä vapaat ajat
            $sql = "SELECT vapaat_ajat FROM vapaat_ajat WHERE date='$date'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='vapaat_ajat'>" . $row['vapaat_ajat'] . "</div>";
                }
            }

            // Näytä pyhät ja juhlat
            if (array_key_exists($day_key, $holidays)) {
                echo "<div class='holiday'>" . $holidays[$day_key] . "</div>";
            }

            echo "</div>";
        }

        $conn->close();
        ?>
</div>
    <script>
        //Tämä koodi käsittelee moodin vaihtamisen
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
        // Display events
        } else if (mode === 'redsven_ink') {
        // Display available times
        }
        }

        // Tämä on parametri?
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
    </script>
<?php include 'footer.php'; ?>
</body>
</html>