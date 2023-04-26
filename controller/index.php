<!DOCTYPE html>

<?php
   ob_start();
   session_start();
   if($_SESSION['valid'] == false){header("Location: ./login.php");}
    if($_SESSION['username'] != 'YhIOiQyCfGz3GAG') {session_destroy(); header("Location: ./login.php");}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/navbar.css">
    <link rel="icon" href="../assets/img/logo.svg" sizes="any">
    <link rel="stylesheet" href="../assets/style/tournaments.css">
    <title>Tournament Controller</title>
</head>
<body>
<div class="NAV_BAR" >
        <input class="checkbox" type="checkbox" name="" id="navmenu" onchange="menu(this)"/>
        <div class="hamburger-lines">
            <span class="line line1"></span>
            <span class="line line2"></span>
            <span class="line line3"></span>
        </div> 
        <div  class="NAV_BAR_LOGO">
            <a href="./">
                <img class="LOGO" src="../assets/img/logo.svg" alt="">
            </a>
        </div>
        <div class="NAV_BAR_MENU">
            <nav>
                <li><a href="./">Tournaments</a></li>
                <li><a class="CREATE" href="./create-tournament.php">Create new tournament</a></li>
            </nav>
        </div>
    </div>
    <div class='SITE'>
        <h1>Active tournaments:</h1>
        <div class="Projects">
            <?php
$dbserver = "DBINFO";
$dbuser = "DBINFO";
$dbpass = "DBINFO";
$dbname = "DBINFO";
            $conn = new mysqli($dbserver, $dbuser, $dbpass, $dbname);


            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM Tournaments WHERE Finished= 0 ";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<a href='./tournament.php?id=" . $row['TournamentID'] . "'> <div class='Project' style='background-image: url(" . $row['BannerURL'] . "); background-color: " . $row['Color'] . "'>
                        <h2>" . $row['Name'] . "</h2>
                    </div></a>
                    ";
                }
            }

            ?>
        </div>
    </div>
</body>

</html>