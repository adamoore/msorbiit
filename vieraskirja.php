<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kalenteri";

// Luo yhteys
$conn = new mysqli($servername, $username, $password, $dbname);

// Tarkista yhteys
if ($conn->connect_error) {
    die("Yhteys epäonnistui: " . $conn->connect_error);
}

// Hymiöiden lyhenteet ja niiden vastaavat Unicode-hymiöt
$emojis = [
    ':)' => '😊',
    ':(' => '😢',
    ':D' => '😃',
    ':P' => '😛',
    ';)' => '😉',
    ':o' => '😮',
    ':|' => '😐',
    'XD' => '😆',
    ':/' => '😕',
    '<3' => '❤️'
];

// Käsittele lomakkeen lähetys
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nimi = $conn->real_escape_string($_POST['nimi']);
    $viesti = $conn->real_escape_string($_POST['viesti']);

    // Korvaa hymiöiden lyhenteet Unicode-hymiöillä
    $viesti = str_replace(array_keys($emojis), array_values($emojis), $viesti);

    $sql = "INSERT INTO vieraskirja (nimi, viesti) VALUES ('$nimi', '$viesti')";

    if ($conn->query($sql) === TRUE) {
        echo "Viesti lisätty onnistuneesti!";
    } else {
        echo "Virhe: " . $sql . "<br>" . $conn->error;
    }
}

// Hae kaikki viestit
$sql = "SELECT nimi, viesti, aika FROM vieraskirja ORDER BY aika DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vieraskirja</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="site.css">
    <?php include 'header.php'; ?>
</head>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Vieraskirja</h1>
        <form action="vieraskirja.php" method="post">
            <div class="form-group">
                <label for="nimi">Nimi:</label>
                <input type="text" id="nimi" name="nimi" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="viesti">Viesti:</label>
                <textarea id="viesti" name="viesti" rows="4" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>Lisää hymiö:</label><br>
                <button type="button" class="btn btn-light" onclick="addEmoji(':)')">😊</button>
                <button type="button" class="btn btn-light" onclick="addEmoji(':(')">😢</button>
                <button type="button" class="btn btn-light" onclick="addEmoji(':D')">😃</button>
                <button type="button" class="btn btn-light" onclick="addEmoji(':P')">😛</button>
                <button type="button" class="btn btn-light" onclick="addEmoji(';)')">😉</button>
                <button type="button" class="btn btn-light" onclick="addEmoji(':o')">😮</button>
                <button type="button" class="btn btn-light" onclick="addEmoji(':|')">😐</button>
                <button type="button" class="btn btn-light" onclick="addEmoji('XD')">😆</button>
                <button type="button" class="btn btn-light" onclick="addEmoji(':/')">😕</button>
                <button type="button" class="btn btn-light" onclick="addEmoji('<3')">❤️</button>
            </div>
            <button type="submit" class="btn btn-primary">Lähetä</button>
        </form>
        <h2 class="mt-5">Viestit</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="viesti">
                    <div class="nimi"><?php echo htmlspecialchars($row['nimi']); ?></div>
                    <div class="aika"><?php echo $row['aika']; ?></div>
                    <div class="teksti"><?php echo nl2br(htmlspecialchars($row['viesti'])); ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Ei viestejä.</p>
        <?php endif; ?>
    </div>
    <script>
        function addEmoji(emoji) {
            var viesti = document.getElementById('viesti');
            viesti.value += emoji;
        }
    </script>
</body>
</html>