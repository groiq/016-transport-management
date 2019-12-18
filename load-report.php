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

    <div id="bg-img-wrapper">
        <div id="content-wrapper">

            <div id="controller">

                <?php

                // connect to database 
                $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');
                // $pdo = new PDO('mysql:host=tms-database.mariadb.database.azure.com:3306;dbname=transport_management', 'tmsadmin@tms-database', 'nRfO4v7t6AOl5OORuXJm');

                // read data
                $reportQuerySql = 'select * from load_reports where load_id = ' 
                                    . $_GET['loadId'] . ';';
                $reportQuery = $pdo->query($reportQuerySql);
                $reports = $reportQuery->fetchAll(\PDO::FETCH_ASSOC);
                $report = $reports[0];

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
 
                    <h2>Transport Bericht</h2>

                    <hr />

                    <div class="row">
                        <div class="col">
                            Dauer erwartet: 
                        </div>
                        <div class="col">
                            <?php echo $report['duration_prior_estimate']; ?> h
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Dauer tats&auml;chlich: 
                        </div>
                        <div class="col">
                            <?php echo $report['duration_actual']; ?> h
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Kosten erwartet: 
                        </div>
                        <div class="col">
                            <?php echo $report['total_cost_prior_estimate']; ?> &euro;
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Kosten tats&auml;chlich: 
                        </div>
                        <div class="col">
                            <?php echo $report['total_cost_actual']; ?> &euro;
                        </div>
                    </div>


                    <hr />

                    <div class="row">
                        <div class="col">
                            Von: 
                        </div>
                        <div class="col">
                            <?php echo $report['start_name']; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Nach: 
                        </div>
                        <div class="col">
                            <?php echo $report['target_name']; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Strecke: 
                        </div>
                        <div class="col">
                            <?php echo $report['total_distance']; ?> km
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Abfahrt: 
                        </div>
                        <div class="col">
                            <?php echo substr($report['start_time'], 11); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Ankunft erwartet: 
                        </div>
                        <div class="col">
                            <?php echo substr($report['target_time_prior_estimate'], 11); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            Ankunft tats&auml;chlich: 
                        </div>
                        <div class="col">
                            <?php echo substr($report['target_time_actual'], 11); ?>
                        </div>
                    </div>

                    <hr />

                   <div class="m-1 current">
                       <a href="./index.php" class="btn btn-primary container-fluid">Zur&uuml;ck zur Startseite</a>
                    </div>

                    <!-- <div id="debug"> -->
                    <div id="debug" style="display: none">
                        <h2>Debug output</h2>


                        <?php
                            echo("<pre>");
                            echo $report['duration_prior_estimate'];
                            print_r($report['duration_prior_estimate']);

                            print_r($report);
                            echo("\n\n");

     
                            echo("</pre>");
                        ?>

                    </div>
        
 

                </div>

            </div>

        </div>

    </div>


</body>

</html>