<?php 
	include "../includes/config.php";
	include "../includes/db.php";
	include "util/iphoneutil.php";
?>
<?php header("Content-Type: text/xml") ?>
<root>

<?php 
			$phone = new iPhone();
			$results = $phone->showBeerById($_GET['id']);
			$name = $results['Beer']['name'];
			$name_url = str_replace(array(" "),array("_"),$name);
			$id = $results['Beer']['beer_id'];
			$aroma = $results['Beer']['aroma'];
			$filtered = $results['Beer']['filtered'];
			$attrs = $results['Attr'];
			$loves = $results['Love'];
			if(strstr($loves," ".$id.","))
			{
				$alreadyloves = true;
			}
			else
			{
				$alreadyloves = false;
			}
		?>
	<title set="wa<?php echo $name_url; ?>"><?php echo $name; ?></title>
	<go to="wa<?php echo $name_url; ?>" /> 
	
	<part>
		<destination mode="replace" zone="wa<?php echo $name_url; ?>" create="true" />
		<data><![CDATA[
			<a href="#" rel="action" onclick="return WA.Form('headForm')" class="iButton iBClassic">Search</a> 
			<div class="iBlock">
			<?php echo "
			<p><strong>Aroma: </strong>$aroma</p>
			<p><strong>Filtered: </strong>$filtered</p>"; 
			foreach ($attrs as $attr)
			{
				if($attr != "")
				{
				echo "<p><strong>".$attr['property_name'].": </strong>".$attr['description']." </p>";
				}
			}
			if($alreadyloves)
			{
				echo "<br /><a style=\"width:100%\" class=\"iPush iBWarn\" rev=\"async\" href=\"ilovebeer.php?id=".$id."&unlove=true rev=\"async\">I don't love this beer :(</a>";
			}
			else
			{
				echo "<br /><a style=\"width:100%\" class=\"iPush iBClassic\" rev=\"async\" href=\"ilovebeer.php?id=".$id." rev=\"async\">I Love This Beer! &lt;3</a>";
			}
			
			?>
			</div> 
		]]></data> 
	</part>  
</root> 