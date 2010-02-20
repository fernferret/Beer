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
			$res = mssql_query("SELECT * FROM dbo.ufn_auto_recommend(".$id.")");
			$i = 0;
			while ($r = mssql_fetch_assoc($res)) {
				//echo '<a href="'.$r["beer_id"].'"><li>'.$r["beer_name"].'</li></a>';
				
				$recommend[$i] = "<li><a href=\"ifind.php?id=".$r['beer_id']."\" rev=\"async\">".$r['beer_name']."</a></li>";
				$i++;
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
			echo "<h3>Like this beer? Try These!</h3></div>
			<div class=\"iMenu\">
						<ul class=\"iArrow\">";
				
			foreach($recommend as $newr)
			{
				echo $newr;
			}
			echo "</ul></div>";
			if(isset($_SESSION['username']))
			{
				if($alreadyloves)
				{
					echo "<br /><a style=\"width:100%\" class=\"iPush iBWarn\" rev=\"async\" href=\"ihatebeer.php?beerid=".$id." rev=\"async\">I don't love this beer :(</a>";
				}
				else
				{
					echo "<br /><a style=\"width:100%\" class=\"iPush iBClassic\" rev=\"async\" href=\"ilovebeer.php?beerid=".$id." rev=\"async\">I Love This Beer! <img src=\"img/love2.png\" /></a>";
				}
			}
			?>
			
		]]></data> 
	</part>  
</root> 