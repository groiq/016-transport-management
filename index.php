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

    <?php

    // connect to database 
    $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');

    // write to database if there's something in the form
    if (!empty($_POST["dbInsert"])) {
        $statement = $pdo->prepare("INSERT INTO loads (start_location_id,target_location_id,truck_id,start_time_estimate) VALUES (?,?,?,?);");
        $timestamp = strtotime($_POST["date"] . " " . $_POST["time"]);
        $sqlTimestamp = date('y-m-d H:i:s',$timestamp);
        $statement->execute(array($_POST['startLocation'], $_POST['targetLocation'], $_POST['truck'], $sqlTimestamp));

    }

    // read data
    $locationQuerySql = "select location_id, name from locations;";
    $locationQuery = $pdo->query($locationQuerySql);
    $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);

    $truckQuerySql = "select truck_id, license_plate from trucks;";
    $truckQuery = $pdo->query($truckQuerySql);
    $trucks = $truckQuery->fetchAll(\PDO::FETCH_ASSOC);

    ?>

    <?php

    // function for dropdown lists
    function offerOptions($optionList, $optionValue, $optionName)
    {
        foreach ($optionList as $option) {
            echo ("<option value='" . $option[$optionValue] . "'>" . $option[$optionName] . "</option>\n");
        }
    }

    ?>


    <h1>Transport Management System</h1>

    <h2>Neue Fuhre</h2>

    <form action="./index.php" method="post">
        <input type="hidden" id="dbInsert" name="dbInsert" value="insertLoad">

        <div class="form-group">

            <!-- <label class="control-label" for="startTime">Abfahrt</label>
            <input class="form-control" id="startTime" name="startTime" type="datetime-local"> -->

            <label class="control-label" for="date">Datum</label>
            <input class="form-control" id="date" name="date" placeholder="MM/DD/YYY" type="date" />

            <label for="time">Uhrzeit</label>
            <input class="form-control" type="time" id="time" name="time">

            <label for="truck">Fahrzeug</label>
            <select class="form-control" id="truck" name="truck">
                <?php
                offerOptions($trucks, "truck_id", "license_plate");
                ?>
            </select>

        </div>
        <div class="form-group">


            <label for="startLocation">Von</label>
            <select class="form-control" id="startLocation" name="startLocation">
                <?php
                offerOptions($locations, "location_id", "name");
                ?>
            </select>
            <label for="targetLocation">Nach</label>
            <select class="form-control" id="targetLocation" name="targetLocation">
                <?php
                offerOptions($locations, "location_id", "name");
                ?>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Fuhre erstellen" />
        </div>
    </form>




    <!-- -------------------------------------------------- -->
    <h1>Debug data</h1>

    <ul>
        <?php
        foreach ($locations as $location) {
            // print_r($locationTuple);
            echo ("<li>" . $location["name"] . "</li>");
        }
        ?>

    </ul>

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
            $sqlTimestamp = date('y-m-d H:i:s',$timestamp);
            echo($sqlTimestamp);
            // $t = strtotime('20130409163705');
            // echo date('d/m/y H:i:s', $t);
            // Format: YYYY-MM-DD hh:mm:ss.
        } else {
            echo ("no data");
        }

        echo ("\n\n");

        print_r($locations);

        var_dump($locations);

        ?>
    </pre>
    <!-- -------------------------------------------------- -->

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


</body>

</html>