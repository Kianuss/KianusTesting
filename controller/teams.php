<!DOCTYPE html>
<?php

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
        $BannerURL = $row['BannerURL'];
        $TournamentTimes = $row['StartTime'];
        $TournamentColor = $row['Color'];
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
    <link rel="stylesheet" href="../assets/style/navbar.css">
    <link rel="stylesheet" href="../assets/style/hero.css">
    <link rel="stylesheet" href="../assets/style/controls/teams.css">

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
            <a href="../">
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
    <?php echo "<div class='HERO' style='background-image: url(" . $BannerURL . "); background-color: " . $TournamentColor . "'>
        <img src='../assets/img/logo.svg' >
        <h1>" . $Tournament_Naam . "</h1>
        <p>Starts: " . $TournamentTimes . "</p>"; ?>
    </div>
    <div class="Page">
        <div class="TopPart">
            <h2>Teams participating:</h2>
            <a class="CREATE" href="./create-team.php?id=<?php echo $_GET['id'] ?>">Create team</a>
        </div>
        <div class="Teams">

            <?php
            $CurrentTeam = "";
            $Start = True;
            $sql = "SELECT T.TeamID, T.Name AS TeamName, T.LogoURL, U.Name AS Player FROM Teams AS T
        LEFT JOIN TeamMembers AS M ON M.TeamID = T.TeamID 
        LEFT JOIN Users AS U ON U.UserID = M.UserID 
        WHERE T.TournamentID = " . $_GET['id'] . "
        ORDER BY T.Name";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['TeamName'] != "TBD") {
                        if ($CurrentTeam == $row['TeamName']) {
                            echo "<p class='Speler'>" . $row['Player'] . "</p><br>";
                        } else {
                            if ($Start == True) {
                                $Start = False;
                            } else {
                                echo "</a></div>";
                            }
                            $CurrentTeam = $row['TeamName'];
                            $url = 'window.open("./team.php?id=' . $row['TeamID'] . '", "_self")';
                            echo "<div class='Team' onClick='" . $url . "'>
                        <h2 class='TeamNaam'>" . $row['TeamName'] . "</h2>";
                            if ($row['LogoURL']) {
                                echo "<img class='TeamLogo' src='" . $row['LogoURL'] . "'>";
                            }
                            if ($row['Player']) {
                                echo "<p class='Speler'>" . $row['Player'] . "</p><br>";
                            } else {
                                echo "<p class='Speler'>TBD</p><br>";
                            }
                        }
                    }
                }
                echo "</a></div>";
            } else {
                #header("Location: ../404.html");
            
            }

            ?>
        </div>

    </div>


</body>

</html>

<script src="../assets/scripts/navbar.js"></script>