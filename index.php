<?php 
	include "includes/config.php";
	include "includes/db.php";
	include $_TEMPLATE."header.php";
?>
<script type="text/javascript">
var aDataSet = [
	//Beer Name, Type, Average Rating, # of Ratings, Submitted By
	
	<?php
	$res = mssql_query("SELECT * FROM view_beer_browser");
	while ($row = mssql_fetch_assoc($res)) {
		print("['<a href=\"beers/".$row["id"]."\">".$row["name"]."</a>','".$row["description"]."','".$row["rating"]."','".$row["numofratings"]."','<a href=\"profiles/".$row["submitted_by"]."\">".$row["submitted_by"]."'],\n");
	}
	?>

];

$(document).ready(function() {
	$('#dynamic').html( '<table cellpadding="0" cellspacing="0" border="0" class="display" id="top_ten"></table>' );
	oTable = $('#top_ten').dataTable( {
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": false,
		"bSort": true,
		"bInfo": false,
		"bAutoWidth": false,
		"aaData": aDataSet,
		"aoColumns": [
			{ "sTitle": "Beer Name", "sType": "html"},
			{ "sTitle": "Type" },
			{ "sTitle": "Average Rating" },
			{ "sTitle": "# of Ratings" },
			{ "sTitle": "Submitted By" }
		],		
		"aaSorting": [[3, 'asc']]
	} );
	('#top_ten_filter').hide();
} );
</script>
	<div class="container">
		<div class="column span-24">			
			<div class="shadow full">
				<div class="page" id="browse">
                <h2>Top 10 Beers</h2>
					<div id="dynamic"></div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<?php
	include $_TEMPLATE."footer.php"; 
?>