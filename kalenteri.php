<?php 
include 'debuggeri.php';
include 'header.php'; ?>
<?php
date_default_timezone_set('Europe/Helsinki');
$currentMonth = date('F Y');
$daysInMonth = date('t');
$weekdays = ['Ma', 'Ti', 'Ke', 'To', 'Pe', 'La', 'Su'];
$tapahtumat = [];
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
        $paivamaara = date('d.m.Y');
        
        // Generoi kalenterin sisältö
        $first_day_of_month = mktime(0, 0, 0, $month, 1, $year); //tarkista tämän yhteensopivuus kalenterin kanssa
        $days_in_month = date('t', $first_day_of_month);
        $day_of_week = date('w', $first_day_of_month);
        $day_of_week = ($day_of_week + 6) % 7; // Muuta viikon ensimmäinen päivä maanantaiksi
        $weekdays = ['Ma', 'Ti', 'Ke', 'To', 'Pe', 'La', 'Su'];   //Tarvitseeko weekdaysit olla tuplana?

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
                "01-01-$year" => "Uudenvuodenpäivä",
                "06-01-$year" => "Loppiainen",
                "30-04-$year" => "Vappuaatto",
                "01-05-$year" => "Vappu",
                "24-06-$year" => "Juhannusaatto",
                "25-06-$year" => "Juhannuspäivä",
                "06-12-$year" => "Itsenäisyyspäivä",
                "24-12-$year" => "Jouluaatto",
                "25-12-$year" => "Joulupäivä",
                "26-12-$year" => "Tapaninpäivä",
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
            $holidays[date('d-m-Y', $easter)] = 'Pääsiäispäivä';
            $holidays[date('d-m-Y', strtotime('+1 day', $easter))] = 'Toinen pääsiäispäivä';
            $holidays[date('d-m-Y', strtotime('+39 days', $easter))] = 'Helatorstai';
            $holidays[date('d-m-Y', strtotime('+49 days', $easter))] = 'Helluntaipäivä';
        
            // Juhannuspäivä on kesäkuun 20. päivän jälkeinen lauantai
            $juhannuspaiva = new DateTime("June 20 $year");
            $juhannuspaiva->modify('next Saturday');
            $holidays[$juhannuspaiva->format('d-m-Y')] = 'Juhannuspäivä';
                
            return $holidays;
        }
        
        // Yhdistä kiinteät ja liikkuvat pyhäpäivät
        function getAllHolidays($year) {
        return array_merge(getFixedHolidays($year), getMovableHolidays($year));
        }

        // Hae pyhäpäivät tälle vuodelle -- Pitäisikö muuttujan olla paivamaara eikä year?
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
            
            // Näytä tapahtumat
            $sql = "SELECT paivamaara, tapahtumat FROM tapahtumat";
            debuggeri($sql);
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $tapahtumat[$row['paivamaara']] = $row['tapahtumat'];
                    echo "<div class='tapahtumat'>" . $row['tapahtumat'] . "</div>";
                }
            }
            debuggeri($tapahtumat);

            $vapaat_ajat=[];
            // Näytä vapaat ajat
            $sql = "SELECT paivamaara, vapaat_ajat FROM vapaat_ajat";
            debuggeri($sql);
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $vapaat_ajat[$row['paivamaara']] = $row['vapaat_ajat'];
                    echo "<div class='vapaat_ajat'>" . $row['vapaat_ajat'] . "</div>";
                }
            }
            debuggeri($vapaat_ajat);

            // Näytä pyhät ja juhlat v.2UUSI
            if (array_key_exists(date('m-d', strtotime($date)), $holidays)) {
            echo "<div class='holiday'>" . $holidays[date('m-d', strtotime($date))] . "</div>";
            }

            // Pyhäpäivien muunnin funktio (Jukka)
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
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    debuggeri($date);
                    if (is_array($vapaat_ajat) && array_key_exists($date, $vapaat_ajat)) {
                        debuggeri("date=$date");
                        $class .= ' vapaat_ajat';
                    } elseif (is_array($tapahtumat) && array_key_exists($date, $tapahtumat)) {
                        debuggeri("date=$date");
                        $class .= ' tapahtumat';
                    }
                    if (pyha($month, $day)) {
                        $class .= ' pyha';
                    }
                    ?>
                    <button class='<?php echo $class; ?>' onclick='showTimeslots(<?php echo $day; ?>)'>
                        <?php echo $day."<br>".($vapaat_ajat[$date] ?? ""); $day."<br>".($tapahtumat[$date] ?? "");?>
                    </button>
                <?php endfor; ?>
        </div>
    </div>

    <div class="timeslots" style="display: none;"></div>

<?php include 'footer.php'; ?>
</body>
</html>