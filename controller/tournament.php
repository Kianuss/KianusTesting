<!DOCTYPE html>
<?php
ob_start();
session_start();
if ($_SESSION['valid'] == false) {
    header("Location: ./login");
}
if ($_SESSION['username'] != 'YhIOiQyCfGz3GAG') {
    session_destroy();
    header("Location: ./login");
}

$dbserver = "DBINFO";
$dbuser = "DBINFO";
$dbpass = "DBINFO";
$dbname = "DBINFO";
$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM `Tournaments` WHERE TournamentID = " . $_GET['id'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Tournament_Naam = $row['Name'];
        $Tournament_Description = nl2br($row['Description']);
        $Tournament_Rules = nl2br($row['Rules']);
        $BannerURL = $row['BannerURL'];
        $TournamentTimes = $row['StartTime'];
        $TournamentColor = $row['Color'];
        $Finished = $row['Finished'];
    }
} else {
    header("Location: ../404.html");
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Maco - Double Duelists</title>
    <link rel="icon" href="../assets/img/logo.svg" sizes="any">
    <link rel="stylesheet" href="../assets/style/tournament.css">
    <link rel="stylesheet" href="../assets/style/navbar.css">
    <link rel="stylesheet" href="../assets/style/controls/tournament.css">
    <link rel="stylesheet" href="../assets/style/hero.css">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://mormelmania.be/tournament" />
    <?php
    echo '<meta property="og:title" content="' . $Tournament_Naam . '" />
    <meta property="og:image" content="' . $BannerURL . '" />
    <meta property="og:description" content="' . $Tournament_Description . '" />';
    ?>

</head>

<body>
    <div class="NAV_BAR">
        <input class="checkbox" type="checkbox" name="" id="navmenu" onchange="menu(this)" />
        <div class="hamburger-lines">
            <span class="line line1"></span>
            <span class="line line2"></span>
            <span class="line line3"></span>
        </div>
        <div class="NAV_BAR_LOGO">
            <a href="https://mormelmania.be/controls">
                <img class="LOGO" src="https://mormelmania.be/assets/img/logo.svg" alt="">
            </a>
        </div>
        <div class="NAV_BAR_MENU">
            <nav>
                <li><a href="./edit-tournament.php?id=<?php echo $_GET['id'] ?>">Information</a></li>
                <li><a href="./structure.php?id=<?php echo $_GET['id'] ?>">Structure</a></li>
                <li><a href="./teams.php?id=<?php echo $_GET['id'] ?>">Teams</a></li>
                <li><a href="./bot.php?id=<?php echo $_GET['id'] ?>">Bot Controls</a></li>
            </nav>
        </div>
    </div>
    <?php echo "<div class='HERO' style='background-image: url(" . $BannerURL . "); background-color: " . $TournamentColor . "'>
            <img src='../assets/img/logo.svg' >
            <h1>" . $Tournament_Naam . "</h1>
            <p>Starts: " . $TournamentTimes . "</p>"; ?>
    </div>
    <div class="Buttons">
        <div class="Button">
            <a href="./edit-tournament.php?id=<?php echo $_GET['id'] ?>">
                <h2>General Information</h2>
            </a>
        </div>
        <div class="Button">
            <a href="./structure.php?id=<?php echo $_GET['id'] ?>">
                <h2>Structure</h2>
            </a>
        </div>
        <div class="Button">
            <a href="./teams.php?id=<?php echo $_GET['id'] ?>">
                <h2>Teams</h2>
            </a>
        </div>
        <div class="Button">
            <a href="./botcontrols.php?id=<?php echo $_GET['id'] ?>">
                <h2>BotControls</h2>
            </a>
        </div>
    </div>
</body>

</html>

<script src="../assets/scripts/navbar.js"></script>