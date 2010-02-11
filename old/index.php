<? 
	include("inc/elements/header.php");
?>
<div id="table_list">
	<form name="table_list_form" method="post" action="">
	
		<fieldset>
		
			<ul>
				<li>
					<label for="tables">Tables:</label>
					<select id="tables" name="tables" onchange="window.location.href= this.form.tables.options[this.form.tables.selectedIndex].value"> 
						<option></option>
						<option value="beers.php">beers</option>
						<option value="beer_lovers.php">beer_lovers</option>
						<option value="regions.php">regions</option>
						<option value="vendors.php">vendors</option>
					</select>
				</li>
			</ul>
			
		</fieldset>
		
	</form>
</div>

<? include 'inc/elements/footer.php'; ?>