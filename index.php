<!DOCTYPE html>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style/navbar.css">
    <link rel="icon" href="../assets/img/logo.svg" sizes="any">
    <link rel="stylesheet" href="../assets/style/tournaments.css">

    <meta content="Tournaments" property="og:title" />
    <meta content="The new maco custom website for hosting tournaments!" property="og:description" />
    <meta content="https://mormelmania.be" property="og:url" />
    <meta content="https://mormelmania.be/assets/img/logo.svg" property="og:image" />
    <meta property="og:site_name" content="Mormel Mania">
    <meta content="#FF7000" data-react-helmet="true" name="theme-color" />
    <title>Mormel Mania</title>
</head>

<body>
    <div class="NAV_BAR">
        <div class="NAV_BAR_LOGO">
            <a href="./">
                <img class="LOGO" src="../assets/img/logo.svg" alt="">
            </a>
        </div>
        <div class="NAV_BAR_MENU">
            <nav>
                <li><a href="./">Tournaments</a></li>
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
                    echo "<a href='./tournament/" . $row['TournamentID'] . "'> <div class='Project' style='background-image: url(" . $row['BannerURL'] . "); background-color: " . $row['Color'] . "'>
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