<?php include 'header.php'; ?>
<?php
date_default_timezone_set('Europe/Helsinki');
$currentMonth = date('F Y');
$daysInMonth = date('t');
$holidays = [5, 15]; // Esimerkki pyhäpäivistä
$vapaat_ajat = [10, 20]; // Esimerkki vapaista ajoista
$tapahtumat = [12, 25]; // Esimerkki tapahtumista
$weekdays = ['Ma', 'Ti', 'Ke', 'To', 'Pe', 'La', 'Su'];
$hours = range(8, 23);
?>
<?php
// Aseta oletuskuukausi ja -vuosi
$month = date('m');
$year = date('Y');

if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = (int)$_GET['month'];
    $year = (int)$_GET['year'];

    // Validate month and year
    if ($month < 1 || $month > 12) {
        $month = date('m');
    }
    if ($year < 1970 || $year > 2038) {
        $year = date('Y');
    }
}
// Generoi kalenterin sisältö
        $first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
        $days_in_month = date('t', $first_day_of_month);
        $day_of_week = date('w', $first_day_of_month);
        $day_of_week = ($day_of_week + 6) % 7; // Muuta viikon ensimmäinen päivä maanantaiksi
        $weekdays = ['ma', 'ti', 'ke', 'to', 'pe', 'la', 'su'];
        $hours = range(8, 24); // Tunnit 08:00 - 00:00
        
        echo "<div class='calendar'>";
        for ($i = 0; $i < $day_of_week; $i++) {
        echo "<div class='day'></div>";
        }
        for ($day = 1; $day <= $days_in_month; $day++) {
        echo "<div class='day'>$day</div>";
        }
        echo "</div>";

        // Suomalaiset pyhät ja juhlat (kiinteät)
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

        // Lisää liikkuvat pyhät (esim. pääsiäinen ja juhannus)
        function getEasterDate($year) {
        $base = new DateTime("$year-03-21");
        $days = easter_days($year);
        return $base->add(new DateInterval("P{$days}D"));
        }

        $easter = getEasterDate($year)->format('m-d');
        $holidays[$easter] = 'Pääsiäispäivä';

        // Juhannuspäivä on kesäkuun 20. päivän jälkeinen lauantai
        $juhannuspaiva = new DateTime("June 20 $year");
        $juhannuspaiva->modify('next Saturday');
        $holidays[$juhannuspaiva->format('m-d')] = 'Juhannuspäivä';

?>
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
        
        // Käsittele lomakkeen lähetys
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $date = $_POST['date'];
            $tapahtumat = $_POST['tapahtumat'];
            $vapaat_ajat = $_POST['vapaat_ajat'];

            if (!empty($tapahtumat)) {
                $sql = "INSERT INTO tapahtumat (date, tapahtumat) VALUES ('$date', '$tapahtumat')";
                echo $sql;
                exit;
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
            // Näytä tapahtumat
            $sql = "SELECT tapahtumat FROM tapahtumat WHERE date='$date'";
            $result = $conn->query($sql);
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
        
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalenteri</title>
    <link rel="stylesheet" href="kaletyyli.css">
    <script src="kalescripts.js" defer></script>
</head>
<body>
    </html>
    <h1>Tapahtumakalenteri</h1>
    <h2><?php echo $currentMonth; ?></h2>
    <p>Selaa kalenteria nähdäksesi M/S Orbiit tapahtumat tai varaa aika Redsven's Ink Tatuointistudioon!</p><br>
    <p>Jonotusaika studiolle on arviolta 2-3 kuukautta.</p><br>    
    <p>Ajanvaraus vaatii sisäänkirjautumisen tai rekisteröitymisen omalla sähköpostiosoitteella.</p>
    <div class="buttons">
        <button onclick="setMode('biit')">Tapahtumat</button>
        <button onclick="setMode('rink')">Ajanvaraus</button>
    </div>  
    <div class="navigation">
        <button onclick="changeMonth(-1)">Edellinen</button>
        <button onclick="changeMonth(1)">Seuraava</button>
    </div> 
    <p><a href="admin_login.php">Admin kirjautuminen</a></p>
    <div class='calendar'>
        <div class='header'>
            <div class='empty'></div> <!-- Tyhjä solu tuntien ja päivien väliin -->
            <?php foreach ($weekdays as $weekday): ?>
                <div class='weekday'><?php echo $weekday; ?></div>
            <?php endforeach; ?>
        </div>

        <?php foreach ($hours as $hour): ?>
             <div class='hour-row'>
                <div class='hour'><?php echo str_pad($hour, 2, '0', STR_PAD_LEFT); ?>:00</div> 
                <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                    <?php
                    $class = 'day';
                    if (in_array($day, $holidays)) {
                        $class .= ' holiday';
                    } elseif (in_array($day, $vapaat_ajat)) {
                        $class .= 'vapaat_ajat';
                    } elseif (in_array($day, $tapahtumat)) {
                        $class .= 'tapahtumat';
                    }
                    ?>
                    <button class='<?php echo $class; ?>' onclick='showTimeslots(<?php echo $day; ?>)'>
                        <?php echo $day; ?>
                    </button>
                <?php endfor; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="timeslots" style="display: none;"></div>

<?php include 'footer.php'; ?>
</body>
</html>