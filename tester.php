<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Test Code for TMS</title>
</head>

<body>

    <h1>Test Code for TMS - Attempt 17</h1>

    <?php

        // $dummyString = getenv('MYSQLCONNSTR_dummy-string');
        // $testVar = getenv('APPINSIGHTS_PROFILERFEATURE_VERSION');
        // $dummyAppSetting = getenv('dummy-application-setting');

        // $pdo = new PDO('mysql:host=tms-database.mariadb.database.azure.com:3306;dbname=transport_management', 'tmsadmin@tms-database', 'nRfO4v7t6AOl5OORuXJm');

        $sqlUsername = getenv('db_username');
        $dbName = getenv('db_name');
        $dbPwd = getenv('db_pwd');
        $dbHostname = getenv('db_hostname');


        $pdo = new PDO('mysql:host='.$dbHostname.'.mariadb.database.azure.com:3306;dbname='.$dbName, $sqlUsername, $dbPwd);

        // echo('<pre>');
        // echo ('$sqlUsername: ');
        // var_dump($sqlUsername);
        // echo ('$dbName: ' . $dbName);
        // echo ('$dbHostname ' . $dbHostname);
        // echo ('$dbPwd: ' . $dbPwd);
        // echo ('\nmysql:host=' . $dbHostname . '.mariadb.database.azure.com:3306;dbname=' . $dbName);
        // echo('</pre>');

        
        $locationQuerySql = "select location_id, name from locations;";
        $locationQuery = $pdo->query($locationQuerySql);
        $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);


    ?>


    <pre>

        <?php

            // echo('$dummyString: ');
            // var_dump($dummyString);
            // echo('$testVar: ');
            // var_dump($testVar);
            // echo('$dummyAppSetting: ');
            // var_dump($dummyAppSetting);
            // echo('$pdo: ');
            // var_dump($pdo);

            print_r($locations);

        ?>

    </pre>


</body>

</html>