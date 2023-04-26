<!DOCTYPE html>
<?php
$dbserver = "DBINFO";
$dbuser = "DBINFO";
$dbpass = "DBINFO";
$dbname = "DBINFO";
$conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);


$Players = [];
$RiotIDS = [];
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT M.StartTime,M.Finished, M.Stream, Tourn.Name AS TName, Tourn.BannerURL, Tourn.Game, Tourn.Color, Tourn.TournamentID, T1.Name AS Team1, T2.Name AS Team2, M.Team1Score, M.Team2Score, M.Team1ID, M.Team2ID FROM Matches AS M
          LEFT JOIN Tournaments AS Tourn ON M.TournamentID = Tourn.TournamentID
          LEFT JOIN Teams AS T1 ON T1.TeamID = M.Team1ID
          LEFT JOIN Teams AS T2 ON T2.TeamID = M.Team2ID
          WHERE M.MatchID = " . $_GET['id'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Tournament_Naam = $row['Team1'] . " VS " . $row['Team2'];
        $Game = $row['Game'];
        $BannerURL = $row['BannerURL'];
        if ($row['Finished'] == 0) {
            $NextMatch = "Starting: " . $row['StartTime'];
        } else {
            $NextMatch = "Result: " . $row['Team1Score'] . " - " . $row['Team2Score'];
        }
        $TournamentColor = $row['Color'];
        $TournamentID = $row['TournamentID'];
        $Team1 = $row['Team1'];
        $Team2 = $row['Team2'];
        $Team1ID = $row['Team1ID'];
        $Team2ID = $row['Team2ID'];
        $Stream = $row['Stream'];
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
    <link rel="stylesheet" href="../assets/style/match.css">
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
        <img src='../assets/img/logo.svg' >
        <h1>" . $Tournament_Naam . "</h1>
        <p>" . $NextMatch . "</p>"; ?>
    </div>
    <?php if ($Stream == 1) {
        echo "<h1>This match is being streamed!</h1>";
    } ?>
    <div class="Page">

        <div class="Team1">
            <h2>
                <?php echo $Team1; ?> :
            </h2>
            <?php
            $sql = "SELECT Users.Name, Users.RiotID FROM `TeamMembers` AS TM
                LEFT JOIN Users ON TM.UserID = Users.UserID
                WHERE TM.TeamID = " . $Team1ID;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p class='Speler'>" . $row['Name'] . "</p> <br>";
                    if ($Game == "Valorant") {
                        if ($row['RiotID']) {
                            echo "<p class='GameID'>RiotID: " . $row['RiotID'] . "</p> <br>";
                        }
                    }
                }

            } else {
                echo "<p>TBD</p>";
            }
            ?>
        </div>
        <div class="Team2">
            <h2>
                <?php echo $Team2; ?> :
            </h2>
            <?php
            $sql = "SELECT Users.Name, Users.RiotID FROM `TeamMembers` AS TM
                LEFT JOIN Users ON TM.UserID = Users.UserID
                WHERE TM.TeamID = " . $Team2ID;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<p class='Speler'>" . $row['Name'] . "</p> <br>";
                    if ($Game == "Valorant") {
                        if ($row['RiotID']) {
                            echo "<p class='GameID'>RiotID: " . $row['RiotID'] . "</p> <br>";
                        }
                    }
                }

            } else {
                echo "<p>TBD</p>";
            }
            ?>
        </div>
    </div>
</body>

</html>

<script src="../assets/scripts/navbar.js"></script>