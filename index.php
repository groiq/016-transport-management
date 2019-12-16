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

            <div id="debug" style="display: none">
                <!-- Apply class .invisible to hide this div. -->

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