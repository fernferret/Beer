<?php
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    require_once $_UTIL."user.php";
    require_once $_UTIL."functions.php";
    
    $username = $_GET['u'];
    
    if(!$username) {
	    echo '<meta http-equiv="refresh" content="0;error.php">';
    }
    
    $res = mssql_query("SELECT * FROM beer_lovers WHERE username = '".$username."'");
	$row = mssql_fetch_assoc($res);
	
	$name = $row["name"];
	$address = $row["address"];
	$email = $row["email"];
	$picture = $row["picture"];
	$date_joined = $row["date_joined"];
	if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		echo '<meta http-equiv="refresh" content="0;login.php">';
	}
?>
<div class="container">
    <div class="column span-24">
       	<div class="beers beer-detail column span-17 append-1">
			<div class="shadow">
				<div class="beer" id="profile">
					<img src="<?php echo $picture ?>" alt="<?php echo $username; ?>" style="float: left; width:170px; padding: 10px; height: 200px; margin:0 10px 0 0"/>
					<ul class="user_attributes">
						<li><?php echo $name; ?></li>
						<li><?php echo $address; ?></li>
						<li><?php echo $email; ?></li>
						<li>
						<?php 
							$res = mssql_query("SELECT city FROM regions WHERE region_id = '".$row["region_id"]."'");
							$row = mssql_fetch_assoc($res);
							echo $row["city"];				
						?>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="beers column span-6 last">
			<div class="shadow">
				<div class="blurb">
					<h3>About <strong><a href="profile.php?u=<?php echo $username ?>"><?php echo $username; ?></a></strong></h3>
					<h1>Active Beers:</h1>
					 <?php
                        echo'<ul class="active_beers">';
						$res = mssql_query("SELECT * FROM beers WHERE last_username = '".$username."'");
						if(mssql_num_rows($res) != 0) {
							for($i=0;$i<mssql_num_rows($res);$i++) {
								$row = mssql_fetch_assoc($res);
								echo "<li><a href='beer.php?id=".$row["beer_id"]."'>".$row["name"]."</a></li>\n";
							}
						} else {
							echo "<li>No active beers!</li>\n";
						}
						echo'</ul>';
					?>
					<br />
					<h1>Joined on: <strong><?php echo $date_joined; ?></strong></h1>
					<br />
					<form name="send_email" method="post" action="mailto:<?php echo $email; ?>">
				        <p><input type="submit" id="Send_Email" name="Send_Email" value="Email <?php echo $username; ?>" class="button"></p>			        
			        </form>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="clearfooter"></div>
<?php
	include $_TEMPLATE."footer.php"; 
?>