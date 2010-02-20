<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
	
header("Content-Type: text/xml") ?>
<root>
<?php 
			$phone = new iPhone();
			$results = $phone->lovebeer($_SESSION['username'], $_GET['beerid']);
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
						<script>
							location.hash = \"#_Home\";
							location.reload(true);
						</script>
			</div> 
		]]></data> 
	</part>  
</root> 