<?php include 'header.php'; ?>
<?php
date_default_timezone_set('Europe/Helsinki');
$currentMonth = date('F Y');
$daysInMonth = date('t');
$weekdays = ['Ma', 'Ti', 'Ke', 'To', 'Pe', 'La', 'Su'];

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
        // Hae nykyinen päivämäärä
        $date = date('Y-m-d');
        $paivamaara = date('d-m-Y');
        
        // Generoi kalenterin sisältö
        $first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
        $days_in_month = date('t', $first_day_of_month);
        $day_of_week = date('w', $first_day_of_month);
        $day_of_week = ($day_of_week + 6) % 7; // Muuta viikon ensimmäinen päivä maanantaiksi
        $weekdays = ['Ma', 'Ti', 'Ke', 'To', 'Pe', 'La', 'Su'];   

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
        
        // Funktio kiinteiden pyhäpäivien laskemiseksi
        function getFixedHolidays($year) {
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
            return $holidays;
        }

        // Lisää liikkuvat pyhäpäivät (esim. pääsiäinen)
        $year = date('Y');
        $easter = easter_date($year);
        $holidays[date('m-d', $easter)] = 'Pääsiäispäivä';
        $holidays[date('m-d', strtotime('+1 day', $easter))] = 'Toinen pääsiäispäivä';
        $holidays[date('m-d', strtotime('+39 days', $easter))] = 'Helatorstai';
        $holidays[date('m-d', strtotime('+49 days', $easter))] = 'Helluntaipäivä';

        // Juhannuspäivä on kesäkuun 20. päivän jälkeinen lauantai
        $juhannuspaiva = new DateTime("June 20 $year");
        $juhannuspaiva->modify('next Saturday');
        $holidays[$juhannuspaiva->format('m-d')] = 'Juhannuspäivä';

        //Funktio liikkuvien pyhäpäivien laskemiseksi
        function getMovableHolidays($year) {
            $holidays = [];
        
            // Pääsiäinen
            $easter = easter_date($year);
            $holidays[date('m-d', $easter)] = 'Pääsiäispäivä';
            $holidays[date('m-d', strtotime('+1 day', $easter))] = 'Toinen pääsiäispäivä';
            $holidays[date('m-d', strtotime('+39 days', $easter))] = 'Helatorstai';
            $holidays[date('m-d', strtotime('+49 days', $easter))] = 'Helluntaipäivä';
        
            // Juhannuspäivä on kesäkuun 20. päivän jälkeinen lauantai
            $juhannuspaiva = new DateTime("June 20 $year");
            $juhannuspaiva->modify('next Saturday');
            $holidays[$juhannuspaiva->format('m-d')] = 'Juhannuspäivä';
                
            return $holidays;
        }
        
        // Yhdistä kiinteät ja liikkuvat pyhäpäivät
        function getAllHolidays($year) {
        return array_merge(getFixedHolidays($year), getMovableHolidays($year));
        }

        // Hae pyhäpäivät tälle vuodelle
        $year = date('Y');
        $holidays = getAllHolidays($year);
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
        // Hae POST-data
        $tapahtumat = $_POST['tapahtumat'] ??''; // Tapahtumat
        $vapaat_ajat = $_POST['vapaat_ajat'] ?? ''; // Vapaat ajat

            // Näytä tapahtumat
            $sql = "SELECT tapahtumat FROM tapahtumat WHERE paivamaara='$date'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='tapahtumat'>" . $row['tapahtumat'] . "</div>";
                }
            }

            // Näytä vapaat ajat
            $sql = "SELECT vapaat_ajat FROM vapaat_ajat WHERE paivamaara='$date'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='vapaat_ajat'>" . $row['vapaat_ajat'] . "</div>";
                }
            }

            // Näytä pyhät ja juhlat
            //if (array_key_exists($paivamaara, $holidays)) {
              //  echo "<div class='holiday'>" . $holidays[$paivamaara] . "</div>";
            //}

            // Näytä pyhät ja juhlat v.2UUSI
            if (array_key_exists(date('m-d', strtotime($date)), $holidays)) {
            echo "<div class='holiday'>" . $holidays[date('m-d', strtotime($date))] . "</div>";
            }

            // Pyhäpäivien muunnin funktio
            function muunnaPvm ($month, $day) {
            return sprintf('%02d-%02d', $month, $day);
            }
            
            function pyha ($month, $day) {
            $holidays = $GLOBALS['holidays'];
            $indeksi = muunnaPvm($month, $day);
            return isset($holidays[$indeksi]);
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
    <h1>Tapahtumakalenteri</h1>
    <div class="info">
    <p>Selaa kalenteria nähdäksesi M/S Orbiit tapahtumat tai varaa aika Redsven's Ink Tatuointistudioon!</p><br>
    <p>Jonotusaika studiolle on arviolta 2-3 kuukautta.</p><br>    
    <p>Ajanvaraus vaatii sisäänkirjautumisen tai rekisteröitymisen omalla sähköpostiosoitteella.</p>
    </div>
    <h2><?php echo $currentMonth; ?></h2>
    <div class="buttons">
        <button onclick="setMode('biit')">Tapahtumat</button>
        <button onclick="setMode('rink')">Ajanvaraus</button>
    </div>  
    <div class="navigation">
        <button onclick="changeMonth(-1)">Edellinen</button>
        <button onclick="changeMonth(1)">Seuraava</button>
    </div> 
    
    <div class="header">
    <div class="calendar">
            <?php foreach ($weekdays as $weekday): ?>
                <div class="weekday"><?php echo $weekday; ?></div>
            <?php endforeach; ?>
                <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                    <?php
                    $class = 'day';
                    if (is_array($vapaat_ajat) && in_array($paivamaara, $vapaat_ajat)) {
                        $class .= ' vapaat_ajat';
                    } elseif (is_array($tapahtumat) && in_array($paivamaara, $tapahtumat)) {
                        $class .= ' tapahtumat';
                    }
                    if (pyha($month, $day)) {
                        $class .= ' pyha';
                    }
                    ?>
                    <button class='<?php echo $class; ?>' onclick='showTimeslots(<?php echo $day; ?>)'>
                        <?php echo $day; ?>
                    </button>
                <?php endfor; ?>
        </div>
    </div>

    <div class="timeslots" style="display: none;"></div>

<?php include 'footer.php'; ?>
</body>
</html>