<!DOCTYPE html>
<?php
$dbserver = "DBINFO";
$dbuser = "DBINFO";
$dbpass = "DBINFO";
$dbname = "DBINFO";
$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);


$Players = [];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT T.TeamID, T.Name AS TeamName, T.LogoURL, U.Name AS Player, U.RiotID , Tournaments.Name AS TName, Tournaments.BannerURL,Tournaments.Game, Tournaments.Color, Tournaments.TournamentID, Matches.StartTime FROM Teams AS T 
          LEFT JOIN TeamMembers AS M ON M.TeamID = T.TeamID LEFT JOIN Users AS U ON U.UserID = M.UserID 
          LEFT JOIN Tournaments ON Tournaments.TournamentID = T.TournamentID 
          LEFT JOIN Matches ON Matches.MatchID = T.NextMatchID
          WHERE T.TeamID = " . $_GET['id'] . "
          ORDER BY T.Name";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Tournament_Naam = $row['TName'];
        $TeamName = $row['TeamName'];
        $Game = $row['Game'];
        $TeamLogo = $row['LogoURL'];
        $BannerURL = $row['BannerURL'];
        if ($row['StartTime']) {
            $NextMatch = $row['StartTime'];
        } else {
            $NextMatch = "TBD";
        }
        $TournamentColor = $row['Color'];
        $TournamentID = $row['TournamentID'];
        array_push($Players, $row['Player']);
    }
} else {
    header("Location: ../404.html");
}

$sql = "SELECT * FROM `Users`";
$result = $conn->query($sql);
$AllPlayers = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Maco - Double Duelists</title>
    <link rel="icon" href="../assets/img/logo.svg" sizes="any">
    <link rel="stylesheet" href="../assets/style/team.css">
    <link rel="stylesheet" href="../assets/style/navbar.css">
    <link rel="stylesheet" href="../assets/style/hero.css">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://mormelmania.be/team/" />
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
            <a href="../">
                <img class="LOGO" src="../assets/img/logo.svg" alt="">
            </a>
        </div>
        <div class="NAV_BAR_MENU">
            <nav>
                <li><a href="../tournament/<?php echo $TournamentID; ?>">Information</a></li>
                <li><a href="../matches/<?php echo $TournamentID; ?>">Matches</a></li>
                <li><a href="../teams/<?php echo $TournamentID; ?>">Teams</a></li>
                <li><a href="https://www.twitch.tv/distinctesportevents" target="_blank">Stream</a></li>
            </nav>
        </div>
    </div>
    <?php echo "<div class='HERO' style='background-image: url(" . $BannerURL . "); background-color: " . $TournamentColor . "'>
        <img src='" . $TeamLogo . "' >
        <h1>" . $TeamName . "</h1>
        <p>Next match: " . $NextMatch . "</p>"; ?>
    </div>

    <div class="Page">
        <div class="LEFT">
            <div class="Players">
                <h2>Team members: </h2>
                <?php
                $PIndex = 0;
                $TeamMembers = [];
                if ($Players == [""]) {
                    echo "<p class='Speler'>TBD</p><br>";
                } else {
                    foreach ($Players as $P) {
                        array_push($TeamMembers, $p);
                        echo "<p class='Speler'> - " . $P . "</p><br>";
                    }
                }
                ?>
            </div>
        </div>
        <div class="RIGHT">
        </div>
    </div>
</body>

</html>

<script src="../assets/scripts/navbar.js"></script>