<?php
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    require_once $_UTIL."beer.php";
    require_once $_UTIL."functions.php";
    
    $beer_id = $_GET['id'];
      
    $res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
	$row = mssql_fetch_assoc($res);
	
    if(!$beer_id || mssql_num_rows($res) == 0) {
	    echo '<meta http-equiv="refresh" content="0;error.php">';
    }
  
	$beer = $row['name'];
	$aroma = $row['aroma'];
	$filtered = $row['filtered'];
	$last_username = $row['last_username'];
 
 	$ra = mssql_query("SELECT AVG(value) as avgrate FROM rates WHERE beer_id = '".$beer_id."'");
	$ro = mssql_fetch_assoc($ra);
	
	$rating = $ro["avgrate"];
	if($rating == '') $rating = 0;
	
	$pres = mssql_query("SELECT * FROM has_property WHERE beer_id = '".$beer_id."'");
	for($i=0;$i<mssql_num_rows($pres);$i++) {
		$prow = mssql_fetch_assoc($pres);
		$description[$i] = $prow["description"];
		$property_name[$i] = $prow["property_name"];	
	}
?>

<div class="container">
    <div class="column span-24">
       	<div class="beers column span-17 append-1">
			<div class="shadow">
				<div class="beer" style="width: 610px">
					<div>
					<ul class="user_attributes">
						Beer Name: <li style="text-align: center; background-color: #444; font-size: 36px;"><?php echo $beer; ?></li>
						Aroma: <li><?php echo $aroma; ?></li>
						Filtered: <li><?php echo $filtered; ?></li>
						<?php
						$resp = mssql_query("SELECT * FROM has_property WHERE beer_id = '".$beer_id."'");
	            		for($i=0;$i<mssql_num_rows($resp);$i++) {
	            			echo $property_name[$i].": <li>".$description[$i]."</li>\n";
	            		}
						?>
					</ul>
					</div>					
					<h2><a href="beer.php?id=<?php echo $beer_id ?>"><?php echo $beer; ?></a></h2>
					<span class="modified">Last modified by: <a href="profile.php?u=<?php echo $last_username; ?>"><?php echo $last_username; ?></a></span>					
				</div>
			</div>
		</div>

		<div class="beers column span-6 last">
			<div class="shadow">
				<div class="blurb">
					<div id="rating">
						<strong><?php echo $rating; ?></strong> / 5
						<form name="rating_submit" method="post" action="beer.php?id=<?php echo $beer_id; ?>">
				        	<p><input style="width: 100px; float: left;" type="text" size="10" id="Form_Rating" class="numeric" name="Form_Rating" value="" class="inputbox"><input style="width: 50px;" type="submit" id="rating_submit" name="rating_submit" value="Vote"></p>			        
			        	</form>
					</div>
					<?php
					if (isset($_POST['rating_submit'])) {
						$beer = new Beer();
						$username = $_SESSION["username"];
						$rating = $_POST["Form_Rating"];
						$beer->add_rating($username, $rating, $beer_id);
					}
					?>
					<form name="edit_submit" method="post" action="edit_beer.php?id=<?php echo $beer_id; ?>">
				        <p><input type="submit" id="edit_submit" name="edit_submit" value="Edit Beer" class="button"></p>			        
			        </form>
				</div>
			</div>
		</div>
		
		<div class="beers column span-17">
			<div class="shadow">
				<div class="page">
					<ul id="comments">
					<?php						
						$cres = mssql_query("SELECT * from get_comments_for_beer WHERE beer_id = '".$beer_id."'");
						$crow = mssql_fetch_assoc($cres);
						$uname = $crow["username"];
						$time = $crow["time"];
						$text = $crow["text"];
						
						if (isset($_POST['comment_submit'])) {
							$beer = new Beer();
							$username = $_SESSION["username"];
							$description = $_POST["Form_Comment"];
							$beer->add_comment($username, $beer_id, $description);
						}
						
						if(mssql_num_rows($cres) == 0) {
							echo "<div>No comments on this entry!</div>";
						}
						else
						{
							echo "<ul>";
						echo "<li class='q'><a href='profile.php?u=".$uname."'>".$uname."</a> says, <span class='quote'>\"".$text."\"</span> at <i>".$time."</i></li>";
						}
						
						while($crow = mssql_fetch_assoc($cres)) {
							$uname = $crow["username"];
						$time = $crow["time"];
						$text = $crow["text"];
							echo "<li class='q'><a href='profile.php?u=".$uname."'>".$uname."</a> says, <span class='quote'>\"".$text."\"</span> at <i>".$time."</i></li>";
						}
						if(mssql_num_rows($cres) != 0) {
							echo "</ul>";
						}
						
					?>	
					<form name="comment_submit" method="post" action="beer.php?id=<?php echo $beer_id; ?>">
						<ul>
							<li><label for="Form_Comment">Add a comment:</label> <input type="text" id="Form_Comment" name="Form_Comment" value="" class="inputbox"></li>
					        <br/>
					        <li><input type="submit" id="comment_submit" name="comment_submit" value="Comment" class="button"></li>	
				        </ul>
			        </form>
					</ul>
					<div class="clearfooter"></div>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="clearfooter"></div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
