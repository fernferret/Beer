<?php 
	include "includes/config.php";
	include "includes/db.php";
	include "util/adminhelp.php";
	include $_TEMPLATE."header.php";
?>
	<div class="container">
		<div class="column span-24">			
			<div class="shadow" style="width:100%">
				<div class="page" id="browse">
                <h2>Top 10 Beers.</h2>
					<table>
                    <th>Beer Name</th>
                    <th>Average Rating</th>
                    <th>People who rated this beer</th>
                    <th>Served By</th>
					<?php
					$querystring = "SELECT * FROM view_beer_browser";
					//echo $querystring;
					$query = mssql_query($querystring);
					$result = mssql_fetch_assoc($query);
					$id = $result["id"];
					$name = $result["name"];
					$ratingweight = $result["numofratings"];
					$rating = $result["rating"];
					$servedby = $result["servedby"];
					echo "<tr class=\"topten\"><td><a href=\"beer.php?id=$id\">$name</a></td><td>$rating</td><td>$ratingweight</td><td>$servedby</td></tr>";
					while ($result = mssql_fetch_assoc($query))
					{
						$id = $result["id"];
						$name = $result["name"];
						$ratingweight = $result["numofratings"];
						$rating = $result["rating"];
						$servedby = $result["servedby"];
						echo "<tr class=\"topten\"><td><a href=\"beer.php?id=$id\">$name</a></td><td>$rating</td><td>$ratingweight</td><td>$servedby</td></tr>";

					}
					
					?>
                    </table>
				</div>
			</div>
		</div>
		<div class="clearfooter"></div>
	</div>

<?php
	include $_TEMPLATE."footer.php"; 
?>