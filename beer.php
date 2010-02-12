<?php
    include "includes/config.php";
    include "includes/db.php";
    include $_TEMPLATE."header.php";
    require_once $_UTIL."user.php";
    require_once $_UTIL."functions.php";
    
    $beer_id = $_GET['id'];
    
    if(!$beer_id) {
	    echo '<meta http-equiv="refresh" content="0;error.php">';
    }
    
    $res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
	$row = mssql_fetch_assoc($res);

	$beer = $row['name'];
	$aroma = $row['aroma'];
	$filtered = $row['filtered'];
	$last_username = $row['last_username'];
 
 	$res = mssql_query("SELECT AVG(value) FROM rates WHERE beer_id = '".$beer_id."'");
	$row = mssql_fetch_assoc($res);
	$rating = $row["column1"];

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
					<span class="modified">Last modified by: <a href="view_profile.php?u=<?php echo $last_username; ?>"><?php echo $last_username; ?></a></span>					
				</div>
			</div>
		</div>
		<div class="beers column span-6 last">
			<div class="shadow">
				<div class="blurb">
					<div id="rating">
						<?php echo $rating; ?> / 5
					</div>
					<form name="edit_submit" method="post" action="edit_beer.php?id=<?php echo $beer_id; ?>">
				        <p><input type="submit" id="edit_submit" name="edit_submit" value="Edit Beer" class="button"></p>			        
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
