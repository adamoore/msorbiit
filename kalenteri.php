<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalenteri</title>
    <style>
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        .day {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
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
    </style>
</head>
<body>
    <h1>Tapahtumakalenteri</h1>
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
    <?php endif; ?>
    <div class="calendar">
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
<?php include 'footer.php'; ?>
</body>
</html>