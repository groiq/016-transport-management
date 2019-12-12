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

?>

<!-- Date and Time picker + form builder -->
<!-- https://formden.com/blog/date-picker -->
<!-- https://eonasdan.github.io/bootstrap-datetimepicker/ -->

<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" /> 

<!-- Inline CSS based on choices in "Settings" tab -->
<style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>

<!-- HTML Form (wrapped in a .bootstrap-iso div) -->
<div class="bootstrap-iso">
 <div class="container-fluid">
  <div class="row">
   <div class="col-md-6 col-sm-6 col-xs-12">
    <div class="formden_header">
     <h2>
      Create new Load
     </h2>
     <p>
     </p>
    </div>
    <form class="form-horizontal" method="post">
     <div class="form-group ">
      <label class="control-label col-sm-2 requiredField" for="startLocation">
       Start location
       <span class="asteriskField">
        *
       </span>
      </label>
      <div class="col-sm-10">
       <select class="select form-control" id="startLocation" name="startLocation">
        <option value="New York">
         New York
        </option>
        <option value="Moscow">
         Moscow
        </option>
       </select>
      </div>
     </div>
     <div class="form-group ">
      <label class="control-label col-sm-2 requiredField" for="targetLocation">
       Target location
       <span class="asteriskField">
        *
       </span>
      </label>
      <div class="col-sm-10">
       <select class="select form-control" id="targetLocation" name="targetLocation">
        <option value="New York">
         New York
        </option>
        <option value="Third Choice">
         Third Choice
        </option>
       </select>
      </div>
     </div>
     <div class="form-group ">
      <label class="control-label col-sm-2 requiredField" for="truck">
       Truck
       <span class="asteriskField">
        *
       </span>
      </label>
      <div class="col-sm-10">
       <select class="select form-control" id="truck" name="truck">
        <option value="LL-something">
         LL-something
        </option>
        <option value="W-somethingElse">
         W-somethingElse
        </option>
       </select>
      </div>
     </div>
     <div class="form-group ">
      <label class="control-label col-sm-2 requiredField" for="date">
       Date
       <span class="asteriskField">
        *
       </span>
      </label>
      <div class="col-sm-10">
       <input class="form-control" id="date" name="date" placeholder="MM/DD/YYYY" type="text"/>
      </div>
     </div>
     <div class="form-group">
      <div class="col-sm-10 col-sm-offset-2">
       <button class="btn btn-primary " name="submit" type="submit">
        Fuhre erstellen
       </button>
      </div>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>


<!-- Extra JavaScript/CSS added manually in "Settings" tab -->
<!-- Include jQuery -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

<script>
    $(document).ready(function(){
        var date_input=$('input[name="date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'mm/dd/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
    })
</script>

<!-- FORM BUILDER FOR BOOTSTRAP 4 -->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<form>
  <div class="form-group row">
    <label for="text" class="col-4 col-form-label">Datum</label> 
    <div class="col-8">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-address-card"></i>
          </div>
        </div> 
        <input id="text" name="text" type="text" class="form-control">
      </div>
    </div>
  </div>
  <div class="form-group row">
    <label for="select" class="col-4 col-form-label">Fahrzeug</label> 
    <div class="col-8">
      <select id="select" name="select" class="custom-select">
        <option value="rabbit">Rabbit</option>
        <option value="duck">Duck</option>
        <option value="fish">Fish</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="select1" class="col-4 col-form-label">Nach</label> 
    <div class="col-8">
      <select id="select1" name="select1" class="custom-select">
        <option value="rabbit">Rabbit</option>
        <option value="duck">Duck</option>
        <option value="fish">Fish</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label for="select2" class="col-4 col-form-label">Von</label> 
    <div class="col-8">
      <select id="select2" name="select2" class="custom-select">
        <option value="rabbit">Rabbit</option>
        <option value="duck">Duck</option>
        <option value="fish">Fish</option>
      </select>
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>