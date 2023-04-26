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
        array_push($RiotIDS, $row['RiotID']);
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
                if ($Players == [""]) {
                    echo "<p class='Speler'>TBD</p> <br>";
                } else {

                    foreach ($Players as $P) {
                        echo "<p class='Speler'>" . $P . "</p> <br>";
                        if ($Game == "Valorant") {
                            if ($RiotIDS[$PIndex]) {
                                echo "<p class='GameID'>RiotID: " . $RiotIDS[$PIndex] . "</p> <br>";
                            }
                            $PIndex .= 1;
                        }

                    }

                }
                ?>
            </div>
        </div>
        <div class="RIGHT">
            <div class="UPCOMING_MATCHES">

                <?php
                $sql = "SELECT M.MatchID,M.Stream, M.StartTime, T1.Name AS Team1, T2.Name AS Team2, Map.Name AS Map, P.Name AS Phase  FROM `Matches` AS M
            LEFT JOIN Phases AS P ON M.PhaseID = P.PhaseID
            LEFT JOIN Teams AS T1 ON M.Team1ID = T1.TeamID
            LEFT JOIN Teams AS T2 ON M.Team2ID = T2.TeamID
            LEFT JOIN Maps AS Map ON M.MapID = Map.MapID
            WHERE (M.Team1ID = " . $_GET['id'] . " OR  M.Team2ID = " . $_GET['id'] . ") AND M.Finished = 0 ORDER BY P.PhaseOrder ASC, M.MatchOrder ASC, P.PhaseID ASC LIMIT 5 ";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<h2>Upcoming matches:</h2>";
                    while ($row = $result->fetch_assoc()) {
                        $url = 'window.open("../match/' . $row['MatchID'] . '", "_self")';
                        echo '<div class="UPCOMING_MATCH"' . "onClick='" . $url . "'>" . '
                            <h3>' . $row['Phase'] . '</h3>';
                        if ($row['Stream'] == 1) {
                            echo '<img class="STREAM" src="../assets/img/stream.png">';
                        }
                        echo '<div class="UPCOMING_MATCH_PARTICIPANTS">
                                <h4>' . $row['Team1'] . '</h4>
                                <h4 class="vs"> VS </h4>
                                <h4>' . $row['Team2'] . '</h4>
                            </div>
                            <div class="UPCOMING_MATCH_INFORMATION">
                            <p>Map: ' . $row['Map'] . '</p>';
                        if ($row['StartTime']) {
                            echo '<p>Estimated starting time: ' . $row['StartTime'] . '</p>';
                        } else {
                            echo '<p>Estimated starting time: TBD</p>';
                        }
                        echo '</div>
                    </div>';
                    }
                }
                ?>

                <?php
                $sql = "SELECT M.MatchID,M.Stream, M.StartTime, M.Team1Score, M.Team2Score, T1.Name AS Team1, T2.Name AS Team2, Map.Name AS Map, P.Name AS Phase FROM `Matches` AS M 
            LEFT JOIN Phases AS P ON M.PhaseID = P.PhaseID 
            LEFT JOIN Teams AS T1 ON M.Team1ID = T1.TeamID 
            LEFT JOIN Teams AS T2 ON M.Team2ID = T2.TeamID 
            LEFT JOIN Maps AS Map ON M.MapID = Map.MapID 
            WHERE (M.Team1ID = " . $_GET['id'] . " OR M.Team2ID = " . $_GET['id'] . ") AND M.Finished = 1 ORDER BY P.PhaseOrder DESC, M.MatchOrder DESC, P.PhaseID DESC LIMIT 5";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<h2>Finished Matches:</h2>";
                    while ($row = $result->fetch_assoc()) {
                        $url = 'window.open("../match/' . $row['MatchID'] . '", "_self")';
                        echo '<div class="UPCOMING_MATCH"' . "onClick='" . $url . "'>" . '
                            <h3>' . $row['Phase'] . '</h3>';
                        if ($row['Stream'] == 1) {
                            echo '<img class="STREAM" src="../assets/img/stream.png">';
                        }
                        if ($row['Team1Score'] > $row['Team2Score']) {
                            echo '<div class="FINISHED_MATCH_SCORE">
                                <h4 class="TEAMNAME">' . $row['Team1'] . '</h4>
                                <h4 id="WON" class="SCORE">' . $row['Team1Score'] . '</h4>
                            </div>
                            <div class="FINISHED_MATCH_SCORE">
                                <h4 class="TEAMNAME">' . $row['Team2'] . '</h4>
                                <h4 id="LOST" class="SCORE">' . $row['Team2Score'] . '</h4>
                            </div>';
                        } else {
                            echo '<div class="FINISHED_MATCH_SCORE">
                                <h4 class="TEAMNAME">' . $row['Team2'] . '</h4>
                                <h4 id="WON" class="SCORE">' . $row['Team2Score'] . '</h4>
                            </div>
                            <div class="FINISHED_MATCH_SCORE">
                                <h4 class="TEAMNAME">' . $row['Team1'] . '</h4>
                                <h4 id="LOST" class="SCORE">' . $row['Team1Score'] . '</h4>
                            </div>';
                        }

                        echo '<div class="UPCOMING_MATCH_INFORMATION">
                            <p>Map: ' . $row['Map'] . '</p>';
                        echo '<p>Game finished</p>';
                        echo '</div>
                    </div>';
                    }
                }
                ?>

            </div>
</body>

</html>

<script src="../assets/scripts/navbar.js"></script>