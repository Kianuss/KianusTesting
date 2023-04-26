<!DOCTYPE html>

<?php
ob_start();
session_start();
if ($_SESSION['valid'] == false) {
    header("Location: ./login.php");
}
if ($_SESSION['username'] != 'YhIOiQyCfGz3GAG') {
    session_destroy();
    header("Location: ./login.php");
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
    $Name = $_POST["naam"];
    $Description = $_POST["Description"];
    $Rules = $_POST["Rules"];
    $Banner = $_POST["foto"];
    $Time = $_POST["stijd"];
    $Color = $_POST["color"];
    $Game = $_POST["game"];
    if ($_POST['finished']) {
        $Finished = 1;
    } else {
        $Finished = 0;
    }
    ;
    if ($_POST['archived']) {
        $Finished = 2;
    }
    ;
    $SQL = "UPDATE `Tournaments` SET `Name` = '".$Name."', `Description` = '".$Description."', `Rules` = '".$Rules."', `BannerURL` = '".$Banner."', `Color` = '".$Color."', `Game` = '".$Color."', `StartTime` = '".$Time."', `Finished` = '".$Finished."' WHERE `Tournaments`.`TournamentID` = " . $_GET['id'];
    $conn->query($SQL);
}


$sql = "SELECT * FROM Tournaments WHERE TournamentID = " . $_GET['id'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Tournament_Naam = $row['Name'];
        $Tournament_Description = $row['Description'];
        $Tournament_Rules = $row['Rules'];
        $BannerURL = $row['BannerURL'];
        $TournamentTimes = $row['StartTime'];
        $TournamentColor = $row['Color'];
        $Tournament_Game = $row['Game'];
        $Tournament_Finished = $row['Finished'];
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
            <a href="./tournament.php?id=<?php echo $_GET['id'] ?>">
                <img class="LOGO" src="../assets/img/logo.svg" alt="">
            </a>
        </div>
        <div class="NAV_BAR_MENU">
            <nav>
                <li><a href="./edit.php?id=<?php echo $_GET['id'] ?>">Information</a></li>
                <li><a href="./structure.php/id=<?php echo $_GET['id'] ?>">Structure</a></li>
                <li><a href="./teams.php?id=<?php echo $_GET['id'] ?>">Teams</a></li>
            </nav>
        </div>
    </div>
    <div class="Creation">
        <form action="edit-tournament.php?id=<?php echo $_GET['id'] ?>" method="post">
            <h1>Edit Tournament</h1>
            <label for="naam">Tournament Naam:</label><br>
            <input type="text" name="naam" id="naam" value="<?php echo $Tournament_Naam ?>"><br>
            <label for="Description">Beschrijving:</label><br>
            <textarea name="Description" id="Description"><?php echo $Tournament_Description ?></textarea><br>
            <label for="Rules">Regels:</label><br>
            <textarea name="Rules" id="Rules"><?php echo $Tournament_Rules ?></textarea><br>
            <label for="foto">Foto URL:</label><br>
            <input type="foto" name="foto" id="foto" value="<?php echo $BannerURL ?>"><br>
            <label for="stijd">Start tijd:</label><br>
            <input type="stijd" name="stijd" id="stijd" value="<?php echo $TournamentTimes ?>"><br>
            <label for="color">Kleur:</label><br>
            <input type="text" name="color" id="color" value="<?php echo $TournamentColor ?>">
            <label for="color">Game:</label><br>
            <input type="text" name="game" id="game" value="Valorant" value="<?php echo $Tournament_Game ?>">
            <label for="finished">Finished:</label>
            <input type="checkbox" id="finished" name="finished" value="1" <?php if ($Tournament_Finished == 1 || $Tournament_Finished == 2) {
                echo " checked ";
            } ?>><br>
            <label for="archived">Archive:</label>
            <input type="checkbox" id="archived" name="archived" value="1" <?php if ($Tournament_Finished == 2) {
                echo " checked ";
            } ?>><br>
            <input type="submit" class="submit">
        </form>
    </div>

    <div>

</body>

</html>