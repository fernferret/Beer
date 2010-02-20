<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<?php header("Content-Type: text/xml") ?>
<root>
<?php 
if(isset($_SESSION['username']))
{
	echo "<go to=\"waHome\" />";
}
else
{
	$username = $_GET['buname'];
	$pass = $_GET['bpass'];
			$phone = new iPhone();
			$results = $phone->login($username,$pass);
			//$name = $results['name'];
			//$id = $results['beer_id'];
		?> 
	<go to="waResults" /> 
	<part> 
		<destination mode="replace" zone="waResults" create="true" />
		
		<data><![CDATA[
			<div class="iBlock">
			<h3>Login Results</h3> 
				<?php
				if($results != "-1")
				{ ?>
				<script>
					location.hash = "#_Home";
					location.reload(true);
				</script>
				<?php
				}
				else
				{
					echo "<p>Sorry... I couldn't log you in...</p>";
				}?>
				
			</div> 
		]]></data> 
	</part> 
	<?php } ?> 
</root> 