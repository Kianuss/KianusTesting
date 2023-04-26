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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Naam = $_POST["naam"];
    $Logo = $_POST["logo"];
    $Captain = 1;

    $SQL = "INSERT INTO `Teams` (`TeamID`, `Name`, `LogoURL`, `CaptainID`, `TournamentID`, `NextMatchID`) VALUES (NULL, '" . $Naam . "', '" . $Logo . "', " . $Captain . ", " . $_GET['id'] . ", NULL);";
    $conn->query($SQL);
    
    $SQL = "SELECT TeamID FROM `Teams` WHERE Name = '" . $Naam . "'  ORDER BY TeamID DESC ";
    $result = $conn->query($SQL);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id = $row["TeamID"];
            header("Location: ./team.php?id=" . $id);
        }
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/navbar.css">
    <link rel="stylesheet" href="../assets/style/controls/create.css">
    <link rel="icon" href="../assets/img/logo.svg" sizes="any">
    <title>Tournament Controller</title>
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
            <a href="./">
                <img class="LOGO" src="../assets/img/logo.svg" alt="">
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
    <div class="Creation">
        <form action="create-team.php?id=<?php echo $_GET['id'] ?>" method="post">
            <h1>Create Team</h1>
            <label for="naam">Team Naam:</label><br>
            <input type="text" name="naam" id="naam"><br>
            <label for="logo">Logo URL:</label><br>
            <input type="logo" name="logo" id="logo"><br>
            <input type="submit" class="submit">
        </form>
    </div>

    <div>

</body>

</html>