<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<?php header("Content-Type: text/xml") ?>
<root>
<?php 
if (isset($_POST['buname'])) {	
	$username = str_replace("'","_",$_POST['buname']);
	$password = str_replace("'","_",$_POST['bpass']);
	$confirm_password = str_replace("'","_",$_POST['bpassc']);
	$name = str_replace("'","_",$_POST['bname']);
	$email = str_replace("'","_",$_POST['bemail']);
	$region = str_replace("'","_",$_POST['bregion']);
	
	if(strcmp($password,$confirm_password) == 0) {					
		$user = new User();
		$user->add_beer_lover($name, $email, $address, $username, $password, $region);
	} else {
		$error = "Those passwords don't match!";
		//alert("Passwords do not match!", FALSE);
	}
	
} 
?>
	<go to="waResults" /> 
	<part> 
		<destination mode="replace" zone="waResults" create="true" />
		
		<data><![CDATA[
			<div class="iBlock">
			<h3>Search Results</h3> 
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