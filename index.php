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
    $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');
    // print_r($pdo);
    $locationQuerySql = "select location_id, name from locations;";
    $locationList = $pdo->query($locationQuerySql);
    ?>

  
    <ul>
        <?php
        foreach ($locationList as $locationTuple) {
            // print_r($locationTuple);
            echo ("<li>".$locationTuple["name"]."</li>");
        }
        ?>
        <?php
        foreach ($locationList as $locationTuple) {
            // print_r($locationTuple);
            echo ("<li>".$locationTuple["name"]."</li>");
        }
        ?>
    </ul>

    <pre>
        <?php
    print_r($locationList);
    ?>
    </pre>

    <h1>Transport Management System</h1>

    
    <form>
        <div class="form-group">
            <label for="startLocation">Start location</label>
            <select class="form-control" id="startLocation">
                <option>New York</option>
                <option>Moscow</option>

            </select>
        </div>
    </form>


</body>

</html>