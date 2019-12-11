<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
	<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" /> 

	<!-- Inline CSS based on choices in "Settings" tab -->
	<style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>

    <title>Transport Management System</title>
</head>

<body>

    <?php

    // connect to database 
    $pdo = new PDO('mysql:host=localhost:3306;dbname=transport_management', 'transport', 'transport_mgmt');

    // write to database if there's something in the form
    if (!empty($_POST["dbInsert"])) {
        $statement = $pdo->prepare("INSERT INTO loads (start_location_id,target_location_id,truck_id) VALUES (?,?,?);");
        $statement->execute(array($_POST['startLocation'],$_POST['targetLocation'],$_POST['truck']));
    }

    // read data
    $locationQuerySql = "select location_id, name from locations;";
    $locationQuery = $pdo->query($locationQuerySql);
    $locations = $locationQuery->fetchAll(\PDO::FETCH_ASSOC);

    $truckQuerySql = "select truck_id, license_plate from trucks;";
    $truckQuery = $pdo->query($truckQuerySql);
    $trucks = $truckQuery->fetchAll(\PDO::FETCH_ASSOC);
 
    function offerOptions($optionList,$optionValue,$optionName) {
        foreach ($optionList as $option) {
            echo("<option value='".$option[$optionValue]."'>".$option[$optionName]."</option>\n");
        }
    }

    ?>



  
    <h1>Transport Management System</h1>

    
    <form action="./index.php" method="post">
        <input type="hidden" id="dbInsert" name="dbInsert" value="insertLoad">
        <div class="form-group">
            <label for="startLocation">Start location</label>
            <select class="form-control" id="startLocation" name="startLocation">
                <?php
                    offerOptions($locations,"location_id","name");
                ?>
            </select>
            <label for="targetLocation">Target location</label>
            <select class="form-control" id="targetLocation" name="targetLocation">
                <?php
                    offerOptions($locations,"location_id","name");
                    ?>
            </select>
			<label class="control-label" for="date">Date</label>
			<input class="form-control" id="date" name="date" placeholder="MM/DD/YYY" type="text"/>

            <label for="truck">Truck</label>
            <select class="form-control" id="truck" name="truck">
                <?php
                    offerOptions($trucks,"truck_id","license_plate");
                    ?>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" value="Fuhre erstellen" />
        </div>
    </form>


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




    <!-- -------------------------------------------------- -->
<h1>Debug data</h1>

<ul>
        <?php
        foreach ($locations as $location) {
            // print_r($locationTuple);
            echo ("<li>".$location["name"]."</li>");
        }
        ?>
        
    </ul>

 <pre>
        <?php

        if (!empty($_POST["dbInsert"])) {
            print_r($_POST);
        } else {
            echo("no data");
        }

        echo("\n\n");

    print_r($locations);

    ?>
    </pre>
    <!-- -------------------------------------------------- -->

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


</body>

</html>