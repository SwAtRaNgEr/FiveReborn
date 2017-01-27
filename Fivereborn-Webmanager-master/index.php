<?php
// Settings
$website_title="SidewaysInc Web Management";
$website_header="SidewaysInc Web Management";

// Edit this to your own server details (last one doesnt need a , at the end).
$serverinfo = array
	(
//  array("ID","NAME","IP","PORT","RCONPASSWORD"). First server id is always 0!
	array("0","My servername 1","127.0.0.1","31100","myrconpassword"),
	array("1","My servername 2","127.0.0.1","31300","myrconpassword")
	);

// Messages for kick & ban
$kickmessage = "You got kicked by an admin. You should know why. If not, ask an admin at Discord.";
$banmessage = "You got banned by an admin. You should know why. If not, ask an admin at Discord.";

// DO YOUR LOGIN PROTECTION HERE



// DONT EDIT AFTER HERE
require("./rcon/q3query.class.php");
if (isset($_GET['action'])) {
    $action = $_GET['action'];
	$user_id = $_GET['uid'];
	$server_id = $_GET['sid'];
}
		
	// Check for action in url, imports the variables and do command.	
	if($action == "kick") {
			   
		foreach ($serverinfo as $server) {
			if($server['0'] == $server_id){
				
				$con = new q3query($server['2'], $server['3'], $success);
				if (!$success) {
					die ("Fehler bei der Verbindungherstellung");
				}
				$con->setRconpassword($server['4']);
				$con->rcon("clientkick $user_id $kickmessage");
				echo "You successfully should have kicked the user with ID $user_id. Redirect after 3 seconds.";
				header( "refresh:3;url=index.php" );
				die();
			}		
		}
	} else if($action == "ban") {
			if($server['0'] == $server_id){
				
				$con = new q3query($server['2'], $server['3'], $success);
				if (!$success) {
					die ("Fehler bei der Verbindungherstellung");
				}
				$con->setRconpassword($server['4']);
				echo $con->rcon("tempbanclient $user_id $banmessage");
				echo "You successfully should have banned the user with ID $user_id. Redirect after 3 seconds.";
				header( "refresh:3;url=index.php" );
				die();
			}
	}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Status mamanger for fivereborn server">
    <meta name="author" content="Slluxx">
    <title><?php echo $website_title; ?></title>
    <link rel="stylesheet" href="https://bootswatch.com/flatly/bootstrap.min.css">
	<style>
	.footer {
	  position: absolute;
	  bottom: 0;
	  width: 100%;
	  /* Set the fixed height of the footer here */
	  height: 60px;
	  line-height: 60px; /* Vertically center the text there */
	  background-color: #f5f5f5;
	}
	</style>
  </head>
  <body>
    <div class="container">		
		<div class="row">
			<div class="col-md-12">
				<center>
				<h1><?php echo $website_header; ?></h1>
				</center>
			</div>
		</div>
		<div class="row">
<?php		

	foreach ($serverinfo as $server) {
		echo "<div class='row'>";	
		echo "<div class='col-md-12'>";
		$con = new q3query($server['2'], $server['3'], $success);
		if (!$success) {
			die ("Fehler bei der Verbindungherstellung");
		}
		$con->setRconpassword($server['4']);
	
		$server_players_array=explode("\n",$con->rcon("status"));
		$xpop = array_pop($server_players_array);
		$server_players_total = count($server_players_array);
	
		echo "<b>".$server['1']."</b>";
		echo "<table class='table table-condensed table-bordered'>
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>SteamID</th>
						<th>IP</th>
						<th>Ping</th>
						<th>KICK</th>
						<th>BAN</th>
					</tr>
			  </thead>
			  <tbody>";

	// Splitting the multiple lines of status command into arrays and split them into arrays seperated by " "
	// the mess is because playernames can have spaces. So we remove every entry before and after name
	// and put the rest together as name string.
	foreach ($server_players_array as $server_player) {
		$playerinfo=explode(" ",$server_player);
		$player_id = array_shift($playerinfo);
		$player_ipsteam = array_shift($playerinfo);
		$player_ipsteam2 = explode(":", $player_ipsteam);
		if($player_ipsteam2[0] == "steam"){
			$player_ipsteam3 = $player_ipsteam2[1];
		}else{
			$player_ipsteam3 = "-";
		}
		$player_ping = array_pop($playerinfo);
		$player_ip = array_pop($playerinfo);
		$player_name = implode(" ", $playerinfo);
		echo "<tr>
				<td>$player_id</td>
				<td>$player_name</td>
				<td>$player_ipsteam3</td>
				<td>$player_ip</td>
				<td>$player_ping</td>
				<td><a href='index.php?action=kick&uid=$player_id&sid=$server[0]' class='btn btn-warning btn-xs'> KICK</a></td>
				<td><a href='index.php?action=ban&uid=$player_id&sid=$server[0]' class='btn btn-danger btn-xs'> BAN</a></td></tr>
			  </tr>";				  
		}
		echo "</tbody>
		</table>";
	
	
	echo"</div>";
	echo"</div>";
}
?>
		</div>


    </div><!-- /.container -->
	<footer class="footer">
      <div class="container">
        <span class="text-muted">Made with <3 by <a href="https://github.com/Slluxx">Slluxx</a>. All my work is free but if you consider to help, you can <a href="https://www.paypal.me/slluxx/">donate here.</a></span>
      </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  </body>
</html>
