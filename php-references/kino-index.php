<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kino</title>
  </head>
  <body>

  <?php
	  
	  $pdo = new PDO('mysql:host=localhost:3306;dbname=groiq_kino_mirror','groiq_kino','37atyV5$');
	  //print_r($pdo);
	  //var_dump($pdo);
	  
	  //$filmListSQL = "select * from vorfuehrung";
	  $filmListSQL = "SELECT vorf_id,datum,zeit,titel,sid,beruehmtheit as saal FROM `vorfuehrung` join film using (filmcode) join saal using (sid)";
	  $filmList = $pdo->query($filmListSQL);
	  
	  ?>

	  	  <h1>Ticket reservieren</h1>

<table border="1">
	<tr>
		<th>Datum</th>
		<th>Zeit</th>
		<th>Film</th>
		<th>Saal</th>
		<th>Tickets</th>
	</tr>
	
	<?php
		foreach ($filmList as $filmTuple) {
			echo("<tr>\n");
			echo("<td>".$filmTuple["datum"]."</td>\n");
			echo("<td>".$filmTuple["zeit"]."</td>\n");
			echo("<td>".$filmTuple["titel"]."</td>\n");
			echo("<td>".$filmTuple["saal"]."</td>\n");
			echo("<td><form action=\"reservieren.php\" method=\"post\">\n");
			echo("\t<input type=\"hidden\" id=\"vorf_id\" name=\"vorf_id\" value=\"".$filmTuple["vorf_id"]."\">\n");
			echo("\t<input type=\"hidden\" id=\"datum\" name=\"datum\" value=\"".$filmTuple["datum"]."\">\n");
			echo("\t<input type=\"hidden\" id=\"zeit\" name=\"zeit\" value=\"".$filmTuple["zeit"]."\">\n");
			echo("\t<input type=\"hidden\" id=\"titel\" name=\"titel\" value=\"".$filmTuple["titel"]."\">\n");
			echo("\t<input type=\"hidden\" id=\"sid\" name=\"sid\" value=\"".$filmTuple["sid"]."\">\n");
			echo("\t<input type=\"hidden\" id=\"saal\" name=\"saal\" value=\"".$filmTuple["saal"]."\">\n");
			echo("\t<input type=\"submit\" value=\"Jetzt reservieren!\">\n");
			echo("</form></td>\n\n");
			//echo("<td><a href=\"./reservieren.php?vorfuehrung=".$filmTuple["vorf_id"]."\">Jetzt reservieren!</a></td>\n");
			echo("</tr>\n");
		}
	?>
	
	  </table>
<!--
- clone the db to my account
- design a proper sql query
-->
	  
<pre>
<?php
print_r($filmTuple);
?>
</pre>

<!--
array(10) {
  ["filmcode"]=>
  string(1) "3"
  [0]=>
  string(1) "3"
  ["zeit"]=>
  string(5) "20:00"
  [1]=>
  string(5) "20:00"
  ["datum"]=>
  string(10) "2019-10-11"
  [2]=>
  string(10) "2019-10-11"
  ["vorf_id"]=>
  string(1) "3"
  [3]=>
  string(1) "3"
  ["sid"]=>
  string(1) "1"
  [4]=>
  string(1) "1"
}
-->



  </body>
</html>
