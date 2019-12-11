<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kino</title>
	  <style>
		  .free {}
		  .taken {color: grey;}
	  </style>
  </head>
  <body>
	  
	  <?php
	  
	  
	  	//  $pdo = new PDO('mysql:host=localhost:3306;dbname=Kinoverwaltungssystem','intensivkurs','Codersbay2019!');
	  $pdo = new PDO('mysql:host=localhost:3306;dbname=groiq_kino_mirror','groiq_kino','37atyV5$');
	  //print_r($pdo);
	  //var_dump($pdo);
	  
	  $freeSeatsSql = "SELECT platzId, reihe, sitzNr, case when platzId in (SELECT platzId from Reservierung WHERE vorf_id = ".$_POST["vorf_id"].") then 0 else 1 end as free"
		  //.", (SELECT max(reihe) from platz WHERE sid = ".$_POST["sid"].") as reihen,(SELECT max(sitzNr) from platz WHERE sid=".$_POST["sid"].") as plaetze"
		  ." FROM platz WHERE sid = ".$_POST["sid"]." ORDER BY reihe, sitzNr;";
	  $freeSeats = $pdo->query($freeSeatsSql);
	  
	  $seats = array();
	  $rows = -1;
	  $rowLength = -1;
	  
	  foreach($freeSeats as $singleSeat) {
		  $rows = max($rows,$singleSeat["reihe"]);
		  $rowLength = max($rowLength,$singleSeat["sitzNr"]);
		  $row = $singleSeat["reihe"];
		  $seatInRow = $singleSeat["sitzNr"];
		  $free = $singleSeat["free"] > 0 ? true : false;
		  $seats[$row][$seatInRow] = array("seatID"=>$singleSeat["platzId"],"free"=>$free);
	  }
	  
	  $customerSQL = "select * from kunde";
	  $customers = $pdo->query($customerSQL);

	  ?>

	  <h1>Ihre Reservierung für <?php echo($_POST["titel"]." am ".$_POST["datum"]." um ".$_POST["zeit"]);?></h1>
	  
	  <form action="reserviert.php" method="post">
		  
		  <p>1. Einloggen über unsere SQL-Injection-sichere Maske:</p>
		  
		  
		  <select id="customer" name="customer">
			  <?php
			  	foreach($customers as $customer) {
					echo("<option value=\"".$customer["knummer"]."\">".$customer["kname"]."</option>\n");	
				}
			  ?>
		  </select>
	  
		  <p>2. Plätze aussuchen:</p>
		  
		  <?php
			echo("\t<input type=\"hidden\" id=\"vorf_id\" name=\"vorf_id\" value=\"".$_POST["vorf_id"]."\">\n");
		  echo("<table>\n\n");
		  foreach($seats as $rowNum => $row) {
		  		
		  		echo("<tr>\n");
			  echo("<td><b>Reihe " . $rowNum . "</b>|</td>");
			  foreach($row as $seatNum => $seat) {
				  $free = $seat["free"];
				  echo("<td class=\"". ($free ? "free" : "taken") ."\">|");
				  if($free) {
					  echo("<label><input type=\"checkbox\" name=\"seat[]\" value=\"".$seat["seatID"]."\">");
				  }
				  echo($seatNum);
				  if($free) {
					  echo("</label>");
				  }
				  echo("</td>\n");
			  }
			  echo("</tr>\n");
		  
		  }
		  echo("</table>\n\n");
		  ?>
		  
		  
	  
		  <p>3. Reservierung absenden</p>
		  <input type="Submit" value="reservieren" />
	  </form>
		  
	  <pre>
	  <?php
		//print_r($_GET);
		print_r($_POST);
		print_r($customer);
		print_r($seats);
		print_r($singleSeat);
		//print_r($freeSeats);
		//var_dump($freeSeats);

		foreach($freeSeats as $singleSeat) {
			print_r($singleSeat);
		}

		?>
	  </pre>


  </body>
</html>
