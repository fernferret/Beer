<?php
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    require_once $_UTIL."user.php";
    require_once $_UTIL."functions.php";
    
    $username = $_GET['u'];
    
    $res = mssql_query("SELECT * FROM beer_lovers WHERE username = '".$username."'");
	$row = mssql_fetch_assoc($res);
	
	if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		echo '<meta http-equiv="refresh" content="0;login.php">';
	}
?>
<div class="container">
    <div class="column span-24">
       	<div class="beers beer-detail column span-15 append-1">
			<div class="shadow">
				<div class="beer profile">
					<img src="<?php echo $row["picture"]; ?>" alt="<?php echo $username; ?>" style="float: left; width:170px; padding: 10px; height: 200px; margin:0 10px 0 0"/>
					<ul class="user_attributes">
						<li><?php echo $row["name"]; ?></li>
						<li><?php echo $row["address"]; ?></li>
						<li><?php echo $row["email"]; ?></li>
						<li>
						<?php 
							$res = mssql_query("SELECT city FROM regions WHERE region_id = '".$row["region_id"]."'");
							$row = mssql_fetch_assoc($res);
							echo $row["city"];				
						?>
						</li>
					</ul>
					<h2>Joined on: <strong>$date</strong></h2>
				</div>
			</div>
		</div>
		<div class="beers column span-8 last">
			<div class="shadow">
				<div class="blurb">
					<h3>About <strong><a href="profile.php?u=<?php echo $username ?>"><?php echo $username; ?></a></strong></h3>
					<h2>Active Beers:</h2>
					 <?php
                        echo'<ul class="active_beers">';
						$res = mssql_query("SELECT * FROM beers WHERE last_username = '".$username."'");
						for($i=0;$i<mssql_num_rows($res);$i++) {
							$row = mssql_fetch_assoc($res);
							echo "<li>".$row["name"]."</li>";
						}
						echo'</ul>';
					?>
			        <p><input type="submit" id="Email_User" name="Email_User" value="Contact <?php echo $username; ?>" class="button"></p>			        
				</div>
			</div>
		</div>
    </div>
</div>
<div class="clearfooter"></div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
