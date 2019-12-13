<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />

    



    <title>Transport Management System</title>
</head>

<body>


    <div id="controller">


        <?php

        // function for dropdown lists
        function offerOptions($optionList, $optionValue, $optionName)
        {
            foreach ($optionList as $option) {
                echo ("<option value='" . $option[$optionValue] . "'>" . $option[$optionName] . "</option>\n");
            }
        }


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

            // set convenience vars
            $legs = $_POST['legs'];
            $lastLegIndex = count($legs) - 1;

            // test output: first and last leg
            // echo($legs[0] . ' -> ' . $legs[$lastLegIndex] . "\n");

            // insert a row into loads
            $statement = $pdo->prepare("INSERT INTO loads (start_location_id,target_location_id,truck_id,start_time_estimate) VALUES (?,?,?,?);");
            $timestamp = strtotime($_POST["date"] . " " . $_POST["time"]);
            $sqlTimestamp = date('y-m-d H:i:s', $timestamp);
            $statement->execute(array($legs[0], $legs[$lastLegIndex], $_POST['truck'], $sqlTimestamp));
            $statement = null;

            // fetch id of new row
            $newLoadId = $pdo->lastInsertId();
            // $query = $pdo->query("SELECT LAST_INSERT_ID();");
            // $queryResult = $query->fetchAll(\PDO::FETCH_ASSOC);
            // $newLoadId = $queryResult[0]['LAST_INSERT_ID()'];
            // echo($newLoadId);

            // insert legs; start counting at 0
            for ($i = 0; $i < $lastLegIndex; $i++) {
                // echo("inserting: " . $legs[$i] . " -> " . $legs[$i+1] . " as leg #" . $i . "\n");
                $statement = $pdo->prepare("INSERT INTO load_legs (load_id, start_location_id, target_location_id, number_in_sequence) VALUES (?,?,?,?);");
                $statement->execute(array($newLoadId, $legs[$i], $legs[$i + 1], $i + 1));
                $statement = null;
            }
        }


        // read data
        $locationQuerySql = "select location_id, name from locations;";
        $locationQuery = $pdo->query($locationQuerySql);
        $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);

        $truckQuerySql = "select truck_id, license_plate from trucks;";
        $truckQuery = $pdo->query($truckQuerySql);
        $trucks = $truckQuery->fetchAll(\PDO::FETCH_ASSOC);

        ?>

    </div>

    <div class="container">

        <h1>Transport Management System</h1>

        <div class="card">
            <!-- <div> -->

            <div class="card-header">
                <h2 class="card-title">Neuer Transport</h2>
            </div>

            <!-- <p class="card-body"> -->
            <div class="card-body">

                <form action="./index.php" method="post">
                    <input type="hidden" id="dbInsert" name="dbInsert" value="insertLoad">

                    <div class="form-group">

                        <div class="row">

                            <div class="col">
                                <label class="control-label" for="date">Datum</label>
                                <input class="form-control" id="date" name="date" placeholder="MM/DD/YYY" type="date" />
                            </div>
                            <div class="col">
                                <label for="time">Uhrzeit</label>
                                <input class="form-control" type="time" id="time" name="time">

                            </div>

                        </div>

                        <label for="truck">Fahrzeug</label>
                        <select class="form-control" id="truck" name="truck">
                            <?php
                            offerOptions($trucks, "truck_id", "license_plate");
                            ?>
                        </select>

                    </div>
                    <div class="form-group">

                        <div class="row">
                            <div class="col-3">
                                <label for="startLocation">Start</label>

                            </div>
                            <div class="col">
                                <select class="form-control" id="startLocation" name="legs[]">
                                    <?php
                                    offerOptions($locations, "location_id", "name");
                                    ?>
                                </select>

                            </div>
                        </div>

                        <div id="dynamicInput"></div>
                        <div class="form-button">
                            <input type="button" class="btn btn-light" value="Etappe hinzuf&uuml;gen" onclick="addInput('dynamicInput');" />
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <label for="targetLocation">Ziel</label>
                            </div>
                            <div class="col">
                                <select class="form-control" id="targetLocation" name="legs[]">
                                    <?php
                                    offerOptions($locations, "location_id", "name");
                                    ?>
                                </select>
                            </div>

                            <!-- <input type="button" value="Save" /> -->
                        </div>

                        <div class="form-group form-button">
                            <button type="submit" class="btn btn-primary">Transport erstellen</button>
                            <!-- <input type="submit" value="Transport erstellen" /> -->
                        </div>
                </form>

            </div>
            <!-- </p> -->

        </div>

    </div>



    <div id="debug">

        <h1>Debug data</h1>

        <pre>
        <?php

        if (!empty($_POST["dbInsert"])) {
            print_r($_POST);
            echo ("\n\n");
            var_dump($_POST);
            echo ("\n\n");
            echo ($_POST["date"] . " " . $_POST["time"]);
            echo ("\n\n");
            $timestamp = strtotime($_POST["date"] . " " . $_POST["time"]);
            // echo($timestamp);
            $sqlTimestamp = date('y-m-d H:i:s', $timestamp);
            echo ($sqlTimestamp);
            // $t = strtotime('20130409163705');
            // echo date('d/m/y H:i:s', $t);
            // Format: YYYY-MM-DD hh:mm:ss.
        } else {
            echo ("no data");
        }

        echo ("\n\n");

        // print_r($locations);

        // var_dump($locations);

        ?>
        </pre>


    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script> -->

    <!-- <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker();
            });
        </script> -->

    <!-- var locations = <?php echo json_encode($locations); ?>; -->

    <script type="text/javascript">
        var choices = ["one", "two"];
        var elemCounter = 0;

        function addInput(divName) {
            var locations = <?php echo json_encode($locations); ?>;
            // alert(locations);
            elemCounter += 1;
            var newDiv = document.createElement('div');
            var selectHTML = '';
            selectHTML += '<div class="row">\n';
            selectHTML += '<div class="col-3">\n';
            selectHTML += '<label for="leg">Etappe</label>\n';
            selectHTML += '</div>\n';
            selectHTML += '<div class="col">\n';
            selectHTML += '<select class="form-control" id="leg" name="legs[]">\n';
            // selectHTML = "<select class='form-control' id='leg_" + elemCounter + "' name='leg_" + elemCounter + "'>";
            // <select class="form-control" id="startLocation" name="startLocation">
            for (i = 0; i < locations.length; i = i + 1) {
                selectHTML += "<option value='" + locations[i]['location_id'] + "'>" + locations[i]['name'] + "</option>\n";
            }
            selectHTML += '</select>\n';
            selectHTML += '</div>\n';
            selectHTML += '</div>\n';
            newDiv.innerHTML = selectHTML;
            document.getElementById(divName).appendChild(newDiv);
        }
    </script>
    <!-- 

                            </div>
                        </div> -->

</body>

</html>