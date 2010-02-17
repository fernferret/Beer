<?php
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    require_once $_UTIL."user.php";
    
	if (empty($_SESSION['logged_in']) || empty($_SESSION['username'])) {
		redirect("login");
	}
	
    $username = str_replace("/", "", $_GET['u']);
    
    $logged_in_username = $_SESSION['username'];
    
    $res = mssql_query("SELECT * FROM beer_lovers WHERE username = '".$username."'");
	$row = mssql_fetch_assoc($res);
	
    if(!$username || mssql_num_rows($res) == 0) {
		redirect("error");
    }
	
	$name = $row["name"];
	$address = $row["address"];
	$email = $row["email"];
	$picture = $row["picture"];
	$date_joined = $row["date_joined"];
	$region_id = $row["region_id"];
	
	$user = new User();
?>
<div class="container">
    <div class="column span-24">
       	<div class="profiles column span-17 append-1" >
			<div class="shadow full">
				<div class="profile" id="profile">
					<img src="<?php echo $picture ?>" alt="<?php echo $username; ?>" style="float: left; width:170px; padding: 10px; height: 200px; margin:0 10px 0 0"/>
					<ul class="user_attributes">
						<li><?php echo $name; ?></li>
						<li><?php echo $address; ?></li>
						<li><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></li>
						<li><?php echo $user->region_name($region_id); ?></li>
					</ul>	
					<ul class="top_recommended">
						<li style="text-align: center; background-color: #444; font-size: 28px;">Beers You Might Like</li>
						<?php
						$res = mssql_query("SELECT * FROM ufn_likes_beer_recommend('".$username."')");
						while ($r = mssql_fetch_assoc($res)) {
							echo '<a href="../beers/'.$r["beer_id"].'"><li>'.$r["beer_name"].'</li></a>';
						}
						?>
					</ul>
					<ul class="favorites">
						<li style="text-align: center; background-color: #444; font-size: 28px;">Favorite Beers</li>
						<?php
						$res = mssql_query("SELECT * FROM loves_beer WHERE username = '".$username."'");
						while ($r = mssql_fetch_assoc($res)) {
							echo '<li class="fav_'.$r["beer_id"].'"><a href="../beers/'.$r["beer_id"].'">'.$user->beer_name($r["beer_id"]).'</a>';
							if($logged_in_username == $username)
								echo '<span class="remove_favorite" id="'.$r["beer_id"].'">Remove</span>';
							echo '</li>';
						}
						?>
					</ul>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div class="profiles column span-6 last">
			<div class="shadow">
				<div class="blurb">
					<h3>About <strong><a href="<?php echo $username ?>"><?php echo $username; ?></a></strong></h3>
					<h1>Recently Recommended:</h1>
					<?php
                        echo'<ul class="recommended_beers">';
						$to_res = mssql_query("SELECT TOP 5 * FROM recommends WHERE to_user = '".$username."'");
						if(mssql_num_rows($to_res) != 0) {
							for($i=0;$i<mssql_num_rows($to_res);$i++) {
								$row = mssql_fetch_assoc($to_res);
								echo "<li><a href='../beers/".$row["beer_id"]."'>".$user->beer_name($row["beer_id"])."</a> from <a href='".$row["from_user"]."'>".$row["from_user"]."</a></li>\n";
							}
						} 
						$from_res = mssql_query("SELECT TOP 5 * FROM recommends WHERE from_user = '".$username."'");
						if(mssql_num_rows($from_res) != 0) {
							for($i=0;$i<mssql_num_rows($from_res);$i++) {
								$row = mssql_fetch_assoc($from_res);
								echo "<li><a href='../beers/".$row["beer_id"]."'>".$user->beer_name($row["beer_id"])."</a> to <a href='".$row["to_user"]."'>".$row["to_user"]."</a></li>\n";
							}
						}
						if(mssql_num_rows($from_res) == 0 && mssql_num_rows($to_res) == 0) {
							echo "No active recommendations!";
						}
						echo'</ul>';
					?>
					<br />
					<h1>Submitted Beers:</h1>
					 <?php
                        echo'<ul class="submitted_beers">';
						$last_res = mssql_query("SELECT TOP 5 * FROM beers WHERE submitted_by = '".$username."'");
						if(mssql_num_rows($last_res) != 0) {
							for($i=0;$i<mssql_num_rows($last_res);$i++) {
								$row = mssql_fetch_assoc($last_res);
								echo "<li><a href='../beers/".$row["beer_id"]."'>".$row["name"]."</a></li>\n";
							}
						}
						$com_res = mssql_query("SELECT TOP 5 * FROM comments_on WHERE username = '".$username."'");
						if(mssql_num_rows($com_res) != 0) {
							for($i=0;$i<mssql_num_rows($com_res);$i++) {
								$row = mssql_fetch_assoc($com_res);
								echo "<li><a href='../beers/".$row["beer_id"]."'>".$user->beer_name($row["beer_id"])."</a></li>\n";
							}
						}
						if(mssql_num_rows($last_res) == 0 && mssql_num_rows($com_res) == 0) {
							echo "No beers submitted, <a href='../submit'>yet</a>!";
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