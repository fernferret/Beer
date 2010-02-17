<?php
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    require_once $_UTIL."beer.php";
    require_once $_UTIL."functions.php";

	$f_beer = new Beer();

    $beer_id = $_GET['id'];
      
    $res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
	$row = mssql_fetch_assoc($res);
	
    if(!$beer_id || mssql_num_rows($res) == 0) {
		redirect("error");
    }
  
  	$username = $_SESSION["username"];
	$beer = $row['name'];
	$aroma = $row['aroma'];
	$filtered = $row['filtered'];
	$submitted_by = $row['submitted_by'];
 	$rating = $f_beer->get_rating($beer_id);
?>

<div class="container">
    <div class="column span-24">
       	<div class="beers column span-17 append-1">
			<div class="shadow full">
				<div class="beer">
					<div>
						<div class="beer_name"><a href="<?php echo $beer_id; ?>"><?php echo $beer; ?></a></div>
						<ul class="beer_attributes">
							<?php 
							if(!empty($aroma)) echo "<li>".$aroma."<span class='property_name'>Aroma</span><span class='property_desc'>What the aroma of the beer is</span></li>\n";
							if(!empty($filtered)) echo "<li>".$filtered."<span class='property_name'>Filtered</span><span class='property_desc'>Is the beer filtered?</span></li>\n";
							$res = mssql_query("SELECT * FROM has_property WHERE beer_id = '".$beer_id."'");
		            		for($i=0;$i<mssql_num_rows($res);$i++) {
			            		$row = mssql_fetch_assoc($res);
		            			if(!empty($row["description"])) {
		            				echo "<li>".$row["description"]."<span class='property_name'>".$f_beer->property_name($row["property_id"])."</span><span class='property_desc'>".$f_beer->property_desc($row["property_id"])."</span></li>\n";
		            			}
		            		}
							?>
						</ul>
					</div>					
					<h2 style="float: left;"><a href="<?php echo $beer_id ?>"><?php echo $beer; ?></a></h2>
					<span class="modified">Submitted by: <a href="../profiles/<?php echo $submitted_by; ?>"><?php echo $submitted_by; ?></a></span>		
					<div class="clear"></div>			
				</div>
			</div>
		</div>	
		<div class="beers column span-6 last">
			<div class="shadow">
				<div class="blurb">
					<div id="rating">
						<strong><?php echo $rating; ?></strong> / 5
						<?php
						    $res = mssql_query("SELECT * FROM rates WHERE beer_id = '".$beer_id."' AND username = '".$username."'");
							$row = mssql_fetch_assoc($res);					
							if(!$row) {
						?>
								<form name="rating_submit" method="post" action="../beers/<?php echo $beer_id; ?>">
						        	<p><input type="text" size="10" id="Form_Rating" class="rating_input" name="Form_Rating" value="" class="inputbox"><input style="width: 50px;" type="submit" id="rating_submit" name="rating_submit" value="Vote" class="g-button large"></p>			        
					        	</form>
					    <?php
					    	}
					    ?>
					</div>
					<?php
					if(isset($_POST['rating_submit'])) {
						$rating = $_POST["Form_Rating"];
						$f_beer->add_rating($username, $rating, $beer_id);
					}
					
					if(isset($_POST['recommend_submit'])) {
						$to_user = $_POST["Form_To"];
						$f_beer->add_recommendation($username, $to_user, $beer_id);
					}
					
					$res = mssql_query("SELECT * FROM beers WHERE submitted_by = '".$username."'");
					$row = mssql_fetch_assoc($res);	
					
					if($row) {
					?>
						<form name="edit_submit" method="post" action="../beers/edit/<?php echo $beer_id; ?>">
					        <p><input type="submit" id="edit_submit" name="edit_submit" value="Edit Beer" class="button"></p>			        
				        </form>
			        <?php
			        }
			        ?>
			        <form name="recommend_submit" method="post" action="../beers/edit/<?php echo $beer_id; ?>">
				        <input id="recommend" name="recommend" value="Recommend" class="button" style="width: 198px">
				        <input type="submit" id="recommend_submit" name="recommend_submit" value="Send" class="button">				
				        <div id="to_recommend">
				        	<label for="Form_To">To Who?<strong>*</strong></label>
				        	<input type="password" id="Form_To" name="Form_To" value="" class="inputbox" style="width: 93%;">
				        </div>
			        </form>
				</div>
			</div>
		</div>
		
		<div class="beers column span-17">
			<div class="shadow full">
				<div class="page" id="recommendations">
					<h2>Like this beer? Try these!</h2>
					<ul class="white_list">
						<?php
							$res = mssql_query("SELECT * FROM ufn_auto_recommend(".$beer_id.")");
							while ($r = mssql_fetch_assoc($res)) {
								echo '<a href="'.$r["beer_id"].'"><li>'.$r["beer_name"].'</li></a>';
							}
						?>
				
					</ul>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		
		<div class="beers column span-17">
			<div class="shadow">
				<div class="page">
					<ul id="comments">
					<?php						
						$res = mssql_query("SELECT * FROM get_comments_for_beer WHERE beer_id = '".$beer_id."'");
						$row = mssql_fetch_assoc($res);
						$uname = $row["username"];
						$time = $row["time"];
						$text = $row["text"];
						
						if (isset($_POST['comment_submit'])) {
							$beer = new Beer();
							$username = $_SESSION["username"];
							$description = $_POST["Form_Comment"];
							$beer->add_comment($username, $beer_id, $description);
						}
						
						if(mssql_num_rows($res) == 0) {
							echo "<div>No comments on this entry!</div>";
						} else {
							echo "<ul>";
							echo "<li class='q'><a href='profile.php?u=".$uname."'>".$uname."</a> says, <span class='quote'>\"".$text."\"</span> at <i>".$time."</i></li>";
						}
						
						while($row = mssql_fetch_assoc($res)) {
							$uname = $row["username"];
							$time = $row["time"];
							$text = $row["text"];
							echo "<li class='q'><a href='profile.php?u=".$uname."'>".$uname."</a> says, <span class='quote'>\"".$text."\"</span> at <i>".$time."</i></li>";
						}
					
						if(mssql_num_rows($res) != 0) {
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
