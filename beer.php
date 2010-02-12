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
?>
<div class="container">
    <div class="column span-24">
       	<div class="beers column span-15 append-1">
			<div class="shadow">
				<div class="beer">
					<div>
						<ul class="property_list">
							<li>Property</li>
						</ul>
					</div>					
					<h2><a href="beer.php?id=<?php echo $beer_id ?>"><?php echo $beer; ?></a></h2>
					<span class="modified">Last modified by: <a href="view_profile.php?u=<?php echo $last_username; ?>"><?php echo $last_username; ?></a></span>
				</div>
			</div>
		</div>
		<div class="beers column span-8 last">
			<div class="shadow">
				<div class="blurb">
					<p>Rating Here<br>Editing Here</p>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="clearfooter"></div>
<?php
	include $_TEMPLATE."footer.php"; 
?>
