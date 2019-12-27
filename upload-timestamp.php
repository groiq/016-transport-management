

<?php

// connect to local database (for development)
// $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');

// connect to azure database
$sqlUsername = getenv('db_username');
$dbName = getenv('db_name');
$dbPwd = getenv('db_pwd');
$dbHostname = getenv('db_hostname');
$pdo = new PDO('mysql:host=' . $dbHostname . '.mariadb.database.azure.com:3306;dbname=' . $dbName, $sqlUsername, $dbPwd);

$sqlTimestamp = date('y-m-d H:i:s', time());
$statement = $pdo->prepare("call add_timestamp(?,?,?);");
$statement->execute(array($_GET['loadId'],$_GET['legId'],$sqlTimestamp));

// $queryString = "select ";

// $testQueryString = "select target_location_id from load_legs where load_id = " . $_GET['loadId'] 
    // . "  and number_in_sequence = " . $_GET['legId'] . ";";
$testQueryString = 'select number_in_sequence, target_time from load_legs where load_id = '.$_GET['loadId'].';';
$testQuery = $pdo->query($testQueryString);
$result =  $testQuery->fetchAll(\PDO::FETCH_ASSOC);
$resultEncoded = json_encode($result);
// echo $sqlTimestamp;
echo $resultEncoded;
exit;

?>
