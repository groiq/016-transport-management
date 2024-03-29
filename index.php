<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="apple-touch-startup-image" href="./imgs/launch.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!-- <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> -->
    <link rel="icon" href="./imgs/favicon.png" type="image/png">

    <title>TMS</title>
</head>

<body>

    <!-- <div id="bg-color-wrapper"> -->
    <div id="bg-img-wrapper">
        <div id="content-wrapper">

            <div id="controller">

                <?php

                // function for dropdown lists
                function offerOptions($optionList, $optionValue, $optionName)
                {
                    foreach ($optionList as $option) {
                        echo ("<option value='" . $option[$optionValue] . "'>" . $option[$optionName] . "</option>\n");
                    }
                }

                // connect to local database (for development)
                // $config = parse_ini_file('../tms_config.ini');
                // $dbName = $config['dbname'];
                // $dbUserName = $config['username'];
                // $dbPwd = $config['pwd'];
                // $pdo = new PDO('mysql:host=localhost:3306;dbname='.$dbName, $dbUserName, $dbPwd);

                // connect to azure database
                $dbHostname = getenv('MYSQLCONNSTR_hostname');
                $dbName = getenv('MYSQLCONNSTR_db-name');
                $dbUserName = getenv('MYSQLCONNSTR_username');
                $dbPwd = getenv('MYSQLCONNSTR_pwd');
                $pdo = new PDO('mysql:host=' . $dbHostname . '.mariadb.database.azure.com:3306;dbname=' . $dbName, $dbUserName, $dbPwd);

                // read data
                $locationQuerySql = "select location_id, name from locations;";
                $locationQuery = $pdo->query($locationQuerySql);
                $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);

                $truckQuerySql = "select truck_id, license_plate from trucks;";
                $truckQuery = $pdo->query($truckQuerySql);
                $trucks = $truckQuery->fetchAll(\PDO::FETCH_ASSOC);

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
 
                    <h2>Neuer Transport</h2>

                    <div class="">

                        <form action="./load.php" method="post">
                            <input type="hidden" id="dbInsert" name="dbInsert" value="insertLoad">

                            <div class="form-group">
                                <label for="truck">Truck:</label>
                                <select class="form-control" id="truck" name="truck">
                                    <?php
                                    offerOptions($trucks, "truck_id", "license_plate");
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="startLocation">Von:</label>
                                <select class="form-control" id="startLocation" name="startLocation">
                                    <?php
                                    offerOptions($locations, "location_id", "name");
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="targetLocation">Nach:</label>
                                <select class="form-control" id="targetLocation" name="targetLocation">
                                    <?php
                                    offerOptions($locations, "location_id", "name");
                                    ?>
                                </select>
                            </div>

                            <div id="dynamicInput">
                            </div>

                            <div class="form-group">
                                <input type="button" class="btn btn-block btn-primary" value="Etappe hinzuf&uuml;gen" onclick="addInput('dynamicInput');" />
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Transport erstellen</button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">
        var elemCounter = 0;

        function addInput(divName) {

            var locations = <?php echo json_encode($locations); ?>;
            elemCounter += 1;

            var newDiv = document.createElement('div');
            var formGroupAttr = document.createAttribute('class');
            formGroupAttr.nodeValue = 'form-group';
            newDiv.setAttributeNode(formGroupAttr);

            var labelDiv = document.createElement('label');
            var labelAttr = document.createAttribute('for');
            labelAttr.nodeValue = 'leg';
            labelDiv.setAttributeNode(labelAttr);
            labelDiv.innerHTML = elemCounter + '. Etappe:';
            newDiv.appendChild(labelDiv);

            var selectDiv = document.createElement('select');
            var selectClassAttr = document.createAttribute('class');
            selectClassAttr.nodeValue = 'form-control';
            selectDiv.setAttributeNode(selectClassAttr);
            var selectIdAttr = document.createAttribute('id');
            selectIdAttr.nodeValue = 'leg';
            selectDiv.setAttributeNode(selectIdAttr);
            var selectNameAttr = document.createAttribute('name');
            selectNameAttr.nodeValue = 'legs[]';
            selectDiv.setAttributeNode(selectNameAttr);
            newDiv.appendChild(selectDiv);

            for (i = 0; i < locations.length; i = i + 1) {
                var newOption = document.createElement('option');
                var optionValueAttr = document.createAttribute('value');
                optionValueAttr.nodeValue = locations[i]['location_id'];
                newOption.setAttributeNode(optionValueAttr);
                newOption.innerHTML = locations[i]['name'];
                selectDiv.appendChild(newOption);
            }

            document.getElementById(divName).appendChild(newDiv);
        }
    </script>

</body>

</html>