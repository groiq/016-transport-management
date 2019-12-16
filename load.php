<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="apple-touch-startup-image" href="./imgs/launch.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="icon" href="./imgs/favicon.png" type="image/png">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="./js/loadScripts.js">
     

    </script>


    <title>TMS</title>
</head>

<body>

    <div id="bg-img-wrapper">
    <div id="content-wrapper">

    <div id="controller">


        <?php

        // echo("<pre>");
        // print_r($_POST);
        // echo("</pre>");

        // function for dropdown lists should be unnecessary on this page
        // function offerOptions($optionList, $optionValue, $optionName)
        // {
        //     foreach ($optionList as $option) {
        //         echo ("<option value='" . $option[$optionValue] . "'>" . $option[$optionName] . "</option>\n");
        //     }
        // }

        // connect to database 

        // $con=mysqli_init(); mysqli_ssl_set($con, NULL, NULL, {ca-cert filename}, NULL, NULL); mysqli_real_connect($con, "tms-database.mariadb.database.azure.com", "tmsadmin@tms-database", {your_password}, {your_database}, 3306);
        // $options = array(
        // PDO::MYSQL_ATTR_SSL_CA => '/var/www/html/BaltimoreCyberTrustRoot.crt.pem'
        // );
        // $pdo = new PDO('mysql:host=tms-database.mariadb.database.azure.com;port=3306;dbname=transport_management', 'tmsadmin@tms-database', 'nRfO4v7t6AOl5OORuXJm', $options);
        /*
        $options = array(
        PDO::MYSQL_ATTR_SSL_CA => '/var/www/html/BaltimoreCyberTrustRoot.crt.pem'
        );
        $db = new PDO('mysql:host=mydemoserver.mysql.database.azure.com;port=3306;dbname=databasename', 'username@mydemoserver', 'yourpassword', $options);
        */
        $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');
        // $pdo = new PDO('mysql:host=tms-database.mariadb.database.azure.com:3306;dbname=transport_management', 'tmsadmin@tms-database', 'nRfO4v7t6AOl5OORuXJm');

        // write to database if there's something in the form
        if (!empty($_POST["dbInsert"])) {

            // insert a row into loads
            // stored procedure doesn't work with pdo->lastInsertId,
            // gotta fall back to direct insert
            $statement = $pdo->prepare("INSERT INTO loads (start_location_id,target_location_id,truck_id) VALUES (?,?,?);");
            // $statement = $pdo->prepare("call add_load(?,?,?);");

            $statement->execute(array($_POST['startLocation'], $_POST['targetLocation'], $_POST['truck']));
            $statement = null;

            // fetch id of new row
            $newLoadId = $pdo->lastInsertId();

            $addLegStatement = $pdo->prepare("call add_leg(?,?,?);");

            // If there are entries for legs, insert start location to first leg,
            // then go through legs, then insert last leg to target location.
            // Otherwise just set a leg from start to target.
            if (isset($_POST['legs'])) {
                // set convenience vars
                $legs = $_POST['legs'];
                $lastLegIndex = count($legs) - 1;
                // insert legs; start counting at 0
                // later: leave the sequence count to the database!
                $addLegStatement->execute(array($newLoadId, $_POST['startLocation'], $legs[0]));
                for ($i = 0; $i < $lastLegIndex; $i++) {
                    // $statement = $pdo->prepare("INSERT INTO load_legs (load_id, start_location_id, target_location_id, number_in_sequence) VALUES (?,?,?,?);");
                    $addLegStatement->execute(array($newLoadId, $legs[$i], $legs[$i + 1]));
                }
                $addLegStatement->execute(array($newLoadId, $legs[$lastLegIndex], $_POST['targetLocation']));
            } else {
                $addLegStatement->execute(array($newLoadId, $_POST['startLocation'], $_POST['targetLocation']));
            }
            $addLegStatement = null;
        }

        // read data
        $queryLegsStatement = $pdo->prepare('select * from load_leg_data where load_id = ?');
        $queryLegsStatement->execute(array($newLoadId));
        $loadLegData = $queryLegsStatement->fetchAll(\PDO::FETCH_ASSOC);
        // $locationQuerySql = "select location_id, name from locations;";
        // $locationQuery = $pdo->query($locationQuerySql);
        // $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);

        /*
        SELECT 
            number_in_sequence,
            start_location_id,
            start_location.name AS start_location_name,
            target_location_id,
            target_location.name AS target_location_name
        FROM
            locations start_location
                JOIN
            load_legs ON start_location.location_id = load_legs.start_location_id
                JOIN
            locations target_location ON load_legs.target_location_id = target_location.location_id
            where load_id = 2;
        */

        ?>

    </div>

    <div class="fixed-top container container-fluid bg-primary text-white">
            <h1>
                <span class="align-middle">
                    TMS
                </span>
            </h1>
    </div>

    <div class="container d-flex flex-column" id="main">
 
       <div class="align-self-center maxwidth p-1" id="form">

            <h2>Aktueller Transport</h2>

            <div class="currentLeg" id="0">
                <div>
                    Abfahrt: <?php echo($loadLegData[0]['start_location_name']) ?>
                </div>
                <div class="past">
                    13:45
                </div>
                <div class="current row">
                    <div class="col currentTime">
                    </div>
                    <div class="col trackDuration">
                        00:00:00
                    </div>
                </div>
                <div class="m-1 current">
                    <button type="button" class="btn btn-primary container-fluid" id="btn-0">
                        Starten
                    </button>
                </div>
                <hr />
            </div>

            <?php
                foreach ($loadLegData as $leg) {
                    $sequenceNumber = $leg['number_in_sequence'];
                    echo('<div class="futureLeg" id="'.$sequenceNumber . '">');
                        echo('<div>');
                            if ($sequenceNumber != count($loadLegData)) {
                                echo($sequenceNumber . '. ');
                            } else {
                                echo('Letzte ');
                            }
                            echo('Etappe: ' . $leg['target_location_name']);
                        echo('</div>');
                        echo('<div class="past">');
                            echo('(timestamp goes here)');
                        echo('</div>');
                        echo('<div class="current row">');
                            echo('<div class="col currentTime">');
                            echo('</div>');
                            echo('<div class="col trackDuration">');
                                echo('00:00:00');
                            echo('</div>');
                        echo('</div>');
                        echo('<div class="m-1 current">');
                            echo('<button type="button" class="btn btn-primary container-fluid" id="btn-1">');
                                if ($sequenceNumber == count($loadLegData)) {
                                    echo('Ankunft');
                                } else {
                                    echo('Etappe');
                                }
                            echo('</button>');
                        echo('</div>');
                        echo('<hr />');
                    echo('</div>');
                }
            ?>
<!-- 
            <div class="futureLeg" id="1">
                <div>
                    1. Etappe: Linz
                </div>
                <div class="past">
                    13:45 (fixiert)
                </div>
                <div class="current row">
                <div class="col currentTime">
                    </div>
                    <div class="col trackDuration">
                        00:00:00
                    </div>
                </div>
                <div class="m-1 current">
                    <button type="button" class="btn btn-primary container-fluid" id="btn-1">
                        Etappe
                    </button>
                </div>
            </div> -->




            <div id="debug">
                <h2>Debug output</h2>

                <?php
                    echo("<pre>");

                    print_r($_POST);
                    echo("\n\n");

                    print_r($loadLegData);

                    echo("</pre>");
                ?>

            </div>

 
        
 
        </div>

    </div>
 
    </div>

    </div>

    <!-- </div> -->

    <!-- </div> -->

    <!-- Optional JavaScript -->
 
 
    <script type="text/javascript">

        // var dt = new Date();
        // document.getElementById("datetime").innerHTML = dt.toLocaleTimeString();

        var currentTime = setInterval(currentDate, 1000);

        function currentDate() {
            var now = new Date();
            document.getElementById("datetime").innerHTML = now.toLocaleTimeString();
        }
 
        currentDate();
    
    // Set the date we're counting down to
var countDownDate = new Date("Jan 5, 2021 15:37:25").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("demo").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("demo").innerHTML = "EXPIRED";
  }
}, 1000);
    
    </script>

</body>

</html>