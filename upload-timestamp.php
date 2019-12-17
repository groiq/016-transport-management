

<?php

// connect to database 
$pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');
// $pdo = new PDO('mysql:host=tms-database.mariadb.database.azure.com:3306;dbname=transport_management', 'tmsadmin@tms-database', 'nRfO4v7t6AOl5OORuXJm');

$sqlTimestamp = date('y-m-d H:i:s', time());
$statement = $pdo->prepare("call add_timestamp(?,?,?);");
$statement->execute(array($_GET['loadId'],$_GET['legId'],$sqlTimestamp));

// $queryString = "select ";

// $testQueryString = "select target_location_id from load_legs where load_id = " . $_GET['loadId'] 
    // . "  and number_in_sequence = " . $_GET['legId'] . ";";
$testQueryString = "select * from locations;";
$testQuery = $pdo->query($testQueryString);
$result =  $testQuery->fetchAll(\PDO::FETCH_ASSOC);
$resultEncoded = json_encode($result);
// echo $sqlTimestamp;
echo $resultEncoded;
exit;

?>
