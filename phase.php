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
$sql = "SELECT P.Name AS PhaseName, P.Type AS PhaseType, P.TeamsAmount, P.Finished, Tour.Name AS TourName, Tour.BannerURL, Tour.Color, Tour.Finished AS TourFinished 
          , M.StartTime, M.MatchOrder, Tour.TournamentID
          FROM `Phases` AS P 
          LEFT JOIN Tournaments AS Tour ON Tour.TournamentID = P.TournamentID
          LEFT JOIN Matches AS M ON M.PhaseID = P.PhaseID
          WHERE P.PhaseID =  " . $_GET['id'] . "
          ORDER BY MatchOrder DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Tournament_Naam = $row['PhaseName'];
        $BannerURL = $row['BannerURL'];
        $TournamentTimes = $row['StartTime'];
        $TournamentColor = $row['Color'];
        $Finished = $row['Finished'];
        $TourneyID = $row['TournamentID'];
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
    <link rel="stylesheet" href="../assets/style/phase.css">
    <link rel="stylesheet" href="../assets/style/navbar.css">
    <link rel="stylesheet" href="../assets/style/hero.css">
    <link rel="stylesheet" href="../assets/style/matchinformation.css">
    <meta property="og:type" content="website" />
    <meta property="og:url" content="http://mormelmania.be/matches/" />
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
                <li><a href="../tournament/<?php echo $TourneyID; ?>">Information</a></li>
                <li><a href="../matches/<?php echo $TourneyID; ?>">Matches</a></li>
                <li><a href="../teams/<?php echo $TourneyID; ?>">Teams</a></li>
                <li><a href="https://www.twitch.tv/distinctesportevents" target="_blank">Stream</a></li>
            </nav>
        </div>
    </div>
    <?php echo "<div class='HERO' style='background-image: url(" . $BannerURL . "); background-color: " . $TournamentColor . "'>
        <img src='../assets/img/logo.svg' >
        <h1>" . $Tournament_Naam . "</h1>";
    if ($Finished == 1) {
        echo "<p>Tournament Finished</p>";
    } else {
        echo "<p>Starting: " . $TournamentTimes . "</p>";
    }
    ?>
    </div>



    <div class="Page">
        <div class="LEFT">
            <?php
            $sql = "SELECT * FROM `Phases` WHERE PhaseID = " . $_GET['id'];
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if ($row['Type'] == 1) {
                        echo "<div class=Poule>
                        <table>
                        <tr>
                          <th>Team</th>
                          <th>Games played</th>
                          <th>Rounds won</th>
                          <th>Rounds lost</th>
                          <th>Rounds diffrence</th>
                          <th>Points</th>
                        </tr>                        
                        ";
                        $PouleSQL = "SELECT Team.Name, PI.Points, PI.GamesPlayed, PI.RoundsWon, PI.RoundsLost FROM `PouleInformation` AS PI 
                        LEFT JOIN Teams AS Team ON Team.TeamID = PI.TeamID
                        WHERE PhaseID =  " . $row['PhaseID'] . "
                        ORDER BY PI.Points DESC";
                        $PouleResult = $conn->query($PouleSQL);
                        if ($PouleResult->num_rows > 0) {
                            while ($PouleInfo = $PouleResult->fetch_assoc()) {
                                $Diffrence = $PouleInfo['RoundsWon'] - $PouleInfo['RoundsLost'];
                                echo "<tr>
                                    <td>" . $PouleInfo['Name'] . "</td>
                                    <td>" . $PouleInfo['GamesPlayed'] . "</td>
                                    <td>" . $PouleInfo['RoundsWon'] . "</td>
                                    <td>" . $PouleInfo['RoundsLost'] . "</td>
                                    <td>" . $Diffrence . "</td>
                                    <td>" . $PouleInfo['Points'] . "</td>
                                </tr>
                                ";
                            }
                            echo "</table>";
                        } else {
                            echo "<p class='TeamNaam'>No Poule information yet!</p>";
                        }
                        echo "</div>
                    </div>"; ?>
                        <div class="RIGHT">
                            <div class="UPCOMING_MATCHES">

                                <?php
                                $sql = "SELECT M.MatchID,M.Stream, M.StartTime, T1.Name AS Team1, T2.Name AS Team2, Map.Name AS Map, P.Name AS Phase  FROM `Matches` AS M
                        LEFT JOIN Phases AS P ON M.PhaseID = P.PhaseID
                        LEFT JOIN Teams AS T1 ON M.Team1ID = T1.TeamID
                        LEFT JOIN Teams AS T2 ON M.Team2ID = T2.TeamID
                        LEFT JOIN Maps AS Map ON M.MapID = Map.MapID
                        WHERE M.PhaseID = " . $_GET['id'] . " AND M.Finished = 0 ORDER BY P.PhaseOrder ASC, M.MatchOrder ASC, P.PhaseID ASC LIMIT 5 ";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    echo "<h2>Upcoming matches:</h2>";
                                    while ($row = $result->fetch_assoc()) {
                                        $url = 'window.open("../match/' . $row['MatchID'] . '", "self")';
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
                        WHERE M.PhaseID = " . $_GET['id'] . " AND M.Finished = 1 ORDER BY P.PhaseOrder DESC, M.MatchOrder DESC, P.PhaseID DESC LIMIT 5";
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
                    } else if ($row['Type'] == 2) {
                        echo "<div class=Bracket>";
                        $BracketSQL = "SELECT M.MatchID, M.Stream, M.StartTime, T1.Name AS Team1, T2.Name AS Team2, M.Team1Score, M.Team2Score, M.StartTime, Map.Name AS Map, M.MatchOrder FROM `Matches` AS M 
                        LEFT JOIN Teams AS T1 ON T1.TeamID = M.Team1ID
                        LEFT JOIN Teams AS T2 ON T2.TeamID = M.Team2ID
                        LEFT JOIN Maps AS Map ON Map.MapID = M.MapID
                        WHERE M.PhaseID = " . $_GET['id'] . " AND M.LoserBracket = 0
                        ORDER BY MatchOrder ASC";
                        $Round = 0;
                        $BracketResult = $conn->query($BracketSQL);
                        if ($BracketResult->num_rows > 0) {
                            while ($BracketInfo = $BracketResult->fetch_assoc()) {
                                if ($Round != $BracketInfo['MatchOrder']) {
                                    if ($Round != 0) {
                                        echo "</div>";
                                    }
                                    $Round = $BracketInfo['MatchOrder'];
                                    echo "<div class='Round'>";
                                }
                                $url = 'window.open("../match/' . $BracketInfo['MatchID'] . '", "_self")';
                                echo '<div class="UPCOMING_MATCH"' . "onClick='" . $url . "'>" . '
                                <h3> Round ' . $BracketInfo['MatchOrder'] . '</h3>';
                                if ($BracketInfo['Stream'] == 1) {
                                    echo '<img class="STREAM" src="../assets/img/stream.png">';
                                }
                                if ($BracketInfo['Team1Score'] > $BracketInfo['Team2Score']) {
                                    echo '<div class="FINISHED_MATCH_SCORE">
                                            <h4 class="TEAMNAME">' . $BracketInfo['Team1'] . '</h4>
                                            <h4 id="WON" class="SCORE">' . $BracketInfo['Team1Score'] . '</h4>
                                        </div>
                                        <div class="FINISHED_MATCH_SCORE">
                                            <h4 class="TEAMNAME">' . $BracketInfo['Team2'] . '</h4>
                                            <h4 id="LOST" class="SCORE">' . $BracketInfo['Team2Score'] . '</h4>
                                        </div>';

                                    echo '<div class="UPCOMING_MATCH_INFORMATION">
                                        <p>Map: ' . $BracketInfo['Map'] . '</p>';
                                    echo '<p>Game finished</p>';
                                } else if ($BracketInfo['Team2Score'] > $BracketInfo['Team1Score']) {
                                    echo '<div class="FINISHED_MATCH_SCORE">
                                            <h4 class="TEAMNAME">' . $BracketInfo['Team2'] . '</h4>
                                            <h4 id="WON" class="SCORE">' . $BracketInfo['Team2Score'] . '</h4>
                                        </div>
                                        <div class="FINISHED_MATCH_SCORE">
                                            <h4 class="TEAMNAME">' . $BracketInfo['Team1'] . '</h4>
                                            <h4 id="LOST" class="SCORE">' . $BracketInfo['Team1Score'] . '</h4>
                                        </div>';

                                    echo '<div class="UPCOMING_MATCH_INFORMATION">
                                        <p>Map: ' . $BracketInfo['Map'] . '</p>';
                                    echo '<p>Game finished</p>';
                                } else {
                                    echo '<div class="FINISHED_MATCH_SCORE">
                                    <h4 class="TEAMNAME">' . $BracketInfo['Team2'] . '</h4>
                                    <h4 id="TBD" class="SCORE">-</h4>
                                </div>
                                <div class="FINISHED_MATCH_SCORE">
                                    <h4 class="TEAMNAME">' . $BracketInfo['Team1'] . '</h4>
                                    <h4 id="TBD" class="SCORE">-</h4>
                                </div>';
                                    echo '<div class="UPCOMING_MATCH_INFORMATION">
                                <p>Map: ' . $BracketInfo['Map'] . '</p>';
                                    echo '<p>Estimated starting time: ' . $BracketInfo['StartTime'] . '</p>';
                                }
                                echo '</div> </div>';
                            }
                        }
                        echo "</div>        </div>
                        </div>"; ?>
                                    <div class="RIGHT">
                                        <div class="UPCOMING_MATCHES">
                                            <?php
                                            $sql = "SELECT M.MatchID,M.Stream, M.StartTime, M.Team1Score, M.Team2Score, T1.Name AS Team1, T2.Name AS Team2, Map.Name AS Map, P.Name AS Phase FROM `Matches` AS M 
                            LEFT JOIN Phases AS P ON M.PhaseID = P.PhaseID 
                            LEFT JOIN Teams AS T1 ON M.Team1ID = T1.TeamID 
                            LEFT JOIN Teams AS T2 ON M.Team2ID = T2.TeamID 
                            LEFT JOIN Maps AS Map ON M.MapID = Map.MapID 
                            WHERE (M.Team1ID = " . $_GET['id'] . " OR M.PhaseID = " . $_GET['id'] . ") AND M.Finished = 0 AND M.LoserBracket = 1 ORDER BY P.PhaseOrder DESC, M.MatchOrder DESC, P.PhaseID DESC LIMIT 5";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                echo "<h2>Other bracket matches:</h2>";
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
                                            $sql = "SELECT M.MatchID,M.Stream, M.StartTime, M.Team1Score, M.Team2Score, T1.Name AS Team1, T2.Name AS Team2, Map.Name AS Map, P.Name AS Phase FROM `Matches` AS M 
                            LEFT JOIN Phases AS P ON M.PhaseID = P.PhaseID 
                            LEFT JOIN Teams AS T1 ON M.Team1ID = T1.TeamID 
                            LEFT JOIN Teams AS T2 ON M.Team2ID = T2.TeamID 
                            LEFT JOIN Maps AS Map ON M.MapID = Map.MapID 
                            WHERE (M.Team1ID = " . $_GET['id'] . " OR M.PhaseID = " . $_GET['id'] . ") AND M.Finished = 1 AND M.LoserBracket = 1 ORDER BY P.PhaseOrder DESC, M.MatchOrder DESC, P.PhaseID DESC LIMIT 5";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                echo "<h2>Finished other bracket matches:</h2>";
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
                                            echo "</div>";
                    }
                }
            }
            ?>
                        </div>
</body>

</html>
<script src="../assets/scripts/navbar.js"></script>