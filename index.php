<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Transport Management System</title>
</head>

<body>

    <?php

    // connect to database 
    $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');

    // write to database if there's something in the form
    if (!empty($_POST["dbInsert"])) {
        $statement = $pdo->prepare("INSERT INTO loads (start_location_id,target_location_id,truck_id) VALUES (?,?,?);");
        $statement->execute(array($_POST['startLocation'],$_POST['targetLocation'],$_POST['truck']));
    }

    // read data
    $locationQuerySql = "select location_id, name from locations;";
    $locationQuery = $pdo->query($locationQuerySql);
    $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);

    $truckQuerySql = "select truck_id, license_plate from trucks;";
    $truckQuery = $pdo->query($truckQuerySql);
    $trucks = $truckQuery->fetchAll(\PDO::FETCH_ASSOC);
 
    function offerOptions($optionList,$optionValue,$optionName) {
        foreach ($optionList as $option) {
            echo("<option value='".$option[$optionValue]."'>".$option[$optionName]."</option>\n");
        }
    }

    ?>



  
    <h1>Transport Management System</h1>

    
    <form action="./index.php" method="post">
        <input type="hidden" id="dbInsert" name="dbInsert" value="insertLoad">
        <div class="form-group">
            <label for="startLocation">Start location</label>
            <select class="form-control" id="startLocation" name="startLocation">
                <?php
                    offerOptions($locations,"location_id","name");
                ?>
            </select>
            <label for="targetLocation">Target location</label>
            <select class="form-control" id="targetLocation" name="targetLocation">
                <?php
                    offerOptions($locations,"location_id","name");
                    ?>
            </select>
            <label for="truck">Truck</label>
            <select class="form-control" id="truck" name="truck">
                <?php
                    offerOptions($trucks,"truck_id","license_plate");
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
            echo ("<li>".$location["name"]."</li>");
        }
        ?>
        
    </ul>

 <pre>
        <?php

        if (!empty($_POST["dbInsert"])) {
            print_r($_POST);
        } else {
            echo("no data");
        }

        echo("\n\n");

    print_r($locations);

    ?>
    </pre>
    <!-- -------------------------------------------------- -->



</body>

</html>