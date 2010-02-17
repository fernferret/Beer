<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<?php header("Content-Type: text/xml") ?>
<root>
<?php 
	$phone = new iPhone();
	$results = $phone->logout();
?> 
	<go to="waLoggingOut" /> 
	<part> 
		<destination mode="replace" zone="waLoggingOut" create="true" />
		
		<data><![CDATA[
		<script>
				location.reload(true);
			</script>
	]]></data> 
	</part> 
</root> 