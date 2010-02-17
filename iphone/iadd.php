<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<?php header("Content-Type: text/xml") ?>
<root>
<?php
			if(!isset($_SESSION['username']))
			{
				echo "<go to=\"waLogin\" />";
			}
			else
			{
				$phone = new iPhone();
				$results = $phone->search($_GET['search']);
							echo "<part> 
		<destination mode=\"replace\" zone=\"waResults\" create=\"true\" />
		";?>
		<data><![CDATA[
			<a href="#" rel="action" onclick="return WA.Form('headForm')" class="iButton iBClassic">Search</a> 
			<div class="iMenu">
			<h3>Search Results</h3> 
						<ul class="iArrow"> 
				<?php foreach( $results as $r)
				{
					if($r['name'] != "")
					{
						echo "<li><a href=\"ifind.php?id=".$r['beer_id']."\" rev=\"async\">".$r['name']."</a></li>";
					}
				}
				?>
				</ul>
			</div> 
		]]></data> 
	</part> 
 	<?php } ?>
</root> 