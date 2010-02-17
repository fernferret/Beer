<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
	
<?php header("Content-Type: text/xml") ?>
<root>
<?php 
			$phone = new iPhone();
			$results = $phone->topten();
			//$name = $results['name'];
			//$id = $results['beer_id'];
		?> 
	<title set="waTopTen">Top Ten Beers</title>
	<go to="waTopTen" /> 
	<part> 
		<destination mode="replace" zone="waTopTen" create="true" />
		
		<data><![CDATA[
			<a href="#" rel="action" onclick="return WA.Form('headForm')" class="iButton iBClassic">Search</a> 
			<div class="iMenu">
						<ul class="iArrow"> 
				<?php
				$i = 1;
				 foreach( $results as $r)
				{
					if($r['name'] != "")
					{
						echo "<li><a href=\"ifind.php?id=".$r['id']."\" rev=\"async\">".$i.". ".$r['name']."</a></li>";
						$i++;
					}
				}
				?>
				</ul>
			</div> 
		]]></data> 
	</part>  
</root> 