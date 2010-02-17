<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<?php header("Content-Type: text/xml") ?>
<root>
	<part>
		<data><![CDATA[
			<?php 
			if(isset($_SESSION['username']))
			{
				echo $_SESSION['username'];
			}
			?>
			</div> 
		]]></data> 
	</part>  
</root> 