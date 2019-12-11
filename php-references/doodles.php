 <!-- https://stackoverflow.com/questions/41449364/reuse-pdo-result-set-multiple-times -->

<?php include_once "app/init.php";
      $dataQuery = $db->prepare("
      SELECT column FROM dataType");

  $dataQuery->execute([]);
  $dataTypes = $dataQuery->rowCount() ? $dataQuery : [];
?>

<div>
   <select>
      <?php foreach($dataTypes as $dataType): ?>
          <option>
               <?php echo $dataType['dataType']; ?>
          </option>
      <?php endforeach; ?>
    </select>
 </div>

 <!-- My init.php to show using PDO: -->

<?php session_start();
  $_SESSION['user']=1;
  $db = new PDO ('mysql:dbname=myDB;host=localhost', 'root', 'root');
  if(!isset($_SESSION['user'])) {
     die('You are not signed in');
 };

 
// You can only loop through the PDO statement once, so you should put the result in a temporary array.

// Instead of:

$dataTypes = $dataQuery->rowCount() ? $dataQuery : [];
// Do

$dataTypes = $dataQuery->fetchAll(\PDO::FETCH_ASSOC);