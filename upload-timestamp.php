

<?php

// connect to local database (for development)
// $config = parse_ini_file('../tms_config.ini');
// $dbName = $config['dbname'];
// $dbUserName = $config['username'];
// $dbPwd = $config['pwd'];
// $pdo = new PDO('mysql:host=localhost:3306;dbname=' . $dbName, $dbUserName, $dbPwd);

// connect to azure database
$dbHostname = getenv('MYSQLCONNSTR_hostname');
$dbName = getenv('MYSQLCONNSTR_db-name');
$dbUserName = getenv('MYSQLCONNSTR_username');
$dbPwd = getenv('MYSQLCONNSTR_pwd');
$pdo = new PDO('mysql:host=' . $dbHostname . '.mariadb.database.azure.com:3306;dbname=' . $dbName, $dbUserName, $dbPwd);

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
