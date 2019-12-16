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

        // connect to database 

        // $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');
        $pdo = new PDO('mysql:host=tms-database.mariadb.database.azure.com:3306;dbname=transport_management', 'tmsadmin@tms-database', 'nRfO4v7t6AOl5OORuXJm');

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

            <!-- <div id="debug"> -->
            <div id="debug" style="display: none">
                <h2>Debug output</h2>

                <?php
                    echo("<pre>");

                    print_r($_POST);
                    echo("\n\n");

                    print_r($loadLegData[0]);

                    echo("</pre>");
                ?>

            </div>
 
        </div>

    </div>
 
    </div>

    </div>

</body>

</html>