<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kino</title>
  </head>
  <body>
	  
	  <?php
	  	  	 // $pdo = new PDO('mysql:host=localhost:3306;dbname=Kinoverwaltungssystem','intensivkurs','Codersbay2019!');
	  $pdo = new PDO('mysql:host=localhost:3306;dbname=groiq_kino_mirror','groiq_kino','37atyV5$');
	  
	  $reservationsSQL = 'select * from Reservierung where vorf_id = ' . $_POST['vorf_id'];
  	  $reservationsBefore = $pdo->query($reservationsSQL);

	  
	  $statement = $pdo->prepare("INSERT INTO Reservierung (knummer,vorf_id,platzId) values (?,?,?);");
	  foreach($_POST['seat'] as $singleSeat) {
		  //echo("values: " . $_POST['customer'] . ", " . $_POST['vorf_id'] . ", $singleSeat <br />");
		  $statement->execute(array($_POST['customer'],$_POST['vorf_id'],$singleSeat));
	  }

  	  $reservationsAfter = $pdo->query($reservationsSQL);
	  
	  function showReservations($reservations) {
		
		  echo("<table border=\"1\">");
		  echo("<tr>\n");
		  echo("\t<th>Res.-Nr.</th>\n");
		  echo("\t<th>Platz</th>\n");
		  echo("\t<th>Kunde</th>\n");
		  //echo("<th>Dumb dump</th>");
		  foreach($reservations as $singleReservation) {
			  echo("<tr>\n");
			  echo("\t<td>");
			  echo($singleReservation['reservierungsnummer']);
			  echo("</td>\n");
			  echo("\t<td>");
			  echo($singleReservation['platzId']);
			  echo("</td>\n");
			  echo("\t<td>");
			  echo($singleReservation['knummer']);
			  echo("</td>\n");
			  //echo("<td>");
			//print_r($singleReservation); 
			  //echo("</td>");
			  echo("</tr>\n");
		  }
		  echo("</table>");
		  //print_r($reservations);
			  //Array ( [reservierungsnummer] => 1 [0] => 1 [platzId] => 1 [1] => 1 [vorf_id] => 1 [2] => 1 [knummer] => 1 [3] => 1 [pnummer] => 1 [4] => 1 )
	  }

	  ?>
	  
	  <h3>Ihre Reservierung wurde entgegengenommen.</h3>
	  
	  <a href="./index.php">Zur&uuml;ck zur Startseite</a>
	  
	  <?php
	  
	  echo("<h3>Reservierungen davor:</h3>\n\n");
	  showReservations($reservationsBefore);
	  
	  echo("<h3>Reservierungen danach:</h3>\n\n");
	  showReservations($reservationsAfter);
	  
	  ?>
	  
	  
	  <pre>
	  <?php
		print_r($_POST);
		//var_dump($_POST);
		print_r($singleReservation);
		?>
	  </pre>


  </body>
</html>
