<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test Code for TMS</title>
</head>

<body>

    <?php

        $dummyString = getenv('MYSQLCONNSTR_dummy-string');
        $testVar = getenv('APPINSIGHTS_PROFILERFEATURE_VERSION');
        $dummyAppSetting = getenv('dummy-application-setting');

        $pdo = new PDO('mysql:host=tms-database.mariadb.database.azure.com:3306;dbname=transport_management', 'tmsadmin@tms-database', 'nRfO4v7t6AOl5OORuXJm');


        
        $locationQuerySql = "select location_id, name from locations;";
        $locationQuery = $pdo->query($locationQuerySql);
        $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);


    ?>

    <h1>Test Code for TMS</h1>

    <div>
        <?php
        echo ('attempt 07: ');
        echo ($dummyString);

        ?>
    </div>

    <pre>

        <?php

            var_dump($dummyString);
            var_dump($testVar);
            var_dump($dummyAppSetting);
            var_dump($pdo);

            print_r($locations);

        ?>

    </pre>


</body>

</html>