<? 
	// Define database credentials
	$dbhost = 'titan.cs.rose-hulman.edu';
	$dbuser = 'brousapg';
	$dbpass = 'brousapg';
	$dbname = 'Beer';
	
	$table_name = 'regions';

	session_start();

	// Connect to database
	$db = mssql_connect($dbhost, $dbuser, $dbpass)
	  or die("Couldn't connect to SQL Server on $dbhost"); 
	
	// Select database
	$selected = mssql_select_db($dbname, $db)
	  or die("Couldn't open database $myDB"); 
	  
	include("inc/elements/header.php");


	if(isset($_POST['add'])){ 
		add_region($_POST['city'], $_POST['state'], $_POST['country'], $db);	
	}
	
	if(isset($_POST['update'])){ 
		modify_region($_POST['city'], $_POST['state'], $_POST['country'], $_POST['region_id'], $db);	
	}
	
	if(isset($_POST['remove'])){ 
		remove_region($_POST['region_id'], $db);	
	}
?>
<div id="submit">
	<form name="submit_form" method="post" action="">
	
	    <fieldset>
	    
	    	<ul>
	    		<li>
					<h1>Add new to <strong><? echo $table_name; ?></strong>:</h1>
	    			<?
					$result = mssql_query("SELECT * FROM ".$table_name) or die("Query to show fields from table failed");
					$fields_num = mssql_num_fields($result);
					for($i=0; $i<$fields_num; $i++)
					{
					    $field = mssql_fetch_field($result);
					    echo '<label for="'.$field->name.'">'.$field->name.'</label>';
				     	if(strpos($field->name, "region_id") !== false) {
						    echo '<input style="display: none" type="text" name="'.$field->name.'" /><br>';	
						} else {
						    echo '<input type="text" name="'.$field->name.'" />';	
						}
					}
					?>
	    		</li>
	       		<li>
	    			<input type="submit" value="Add" class="large green button" name="add" />			
	    		</li>
	    	</ul>
	    	
	    </fieldset>
	    
	</form>			
	<hr />
</div>	

<div id="modify">		
	<form name="row_id" method="post" action="">
	<fieldset>
	    	<ul>
	    		<li>
					<input type="text" value="region_id to modify" name="row_id">
				</li>
				<li>
					<input type="submit" value="Ok" class="large green button" name="ok" />
				</li>
	</form>
</div>

<div id="modified">	
	<form name="modify_form" method="post" action="">
	    <fieldset>
	    	<ul>
	    		<li>
	    			<?
    				if(isset($_POST['row_id'])) {
	    				$region_id = $_POST['row_id'];
						$result = mssql_query("SELECT * FROM ".$table_name." WHERE region_id = '" .$region_id. "'") or die("Query to show fields from table failed");
						echo "<h1>Change row <strong>".$region_id."</strong>:</h1>";
						while (($row = mssql_fetch_array($result, MSSQL_BOTH))) 
					    { 
					    	echo '<label for="region_id">region_id</label>';
					    	echo '<input value="'.$row['region_id'].'" type="text" name="region_id" />';
						    echo '<label for="city">city</label>';
					        echo '<input value="'.$row['city'].'" type="text" name="city" />';
						    echo '<label for="state">state</label>';
					        echo '<input value="'.$row['state'].'" type="text" name="state" />';
						    echo '<label for="country">country</label>';
					        echo '<input value="'.$row['country'].'" type="text" name="country" />';
					    } 
					    echo'<li><input type="submit" value="Update" class="large green button" name="update" /><li>';
					    echo'<li><input type="submit" value="Remove" class="large red button" name="remove" /><li>';
					}

					?>
	    		
	    		</li>
	    	</ul>
	    </fieldset>
	</form>	
	<hr />
</div>

<div id="list">
	<?
	$result = mssql_query("SELECT * FROM ".$table_name) or die("Query to show fields from table failed");
	$fields_num = mssql_num_fields($result);
	echo "<h1>Table: <strong>".$table_name."</strong></h1>";
	echo "<table id=".$table_name."><tr>";
	for($i=0; $i<$fields_num; $i++)
	{
	    $field = mssql_fetch_field($result);
	    echo "<th>{$field->name}</th>";
	}
	echo "</tr>\n";
	
	while ($row = mssql_fetch_array($result)) {
		echo "<tr><td>" . $row['state'] . "</td><td>" . $row['country'] . "</td><td>" . $row['city'] . "</td><td>" . $row['region_id'] . "</td></tr>";
	} 

	mssql_free_result($result);
	?>
</div>

<?

function add_region($city, $state, $country, $db) {
	/* prepare the statement */
	$proc = "usp_add_region";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@newcity", $city, SQLVARCHAR);    
	mssql_bind($stmt, "@newstate", $state, SQLVARCHAR);
	mssql_bind($stmt, "@newcountry", $country, SQLVARCHAR);
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function modify_region($city, $state, $country, $region_id, $db) {
	/* prepare the statement */
	$proc = "usp_modify_region";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@modcity", $city, SQLVARCHAR);
	mssql_bind($stmt, "@modstate", $state, SQLVARCHAR);
	mssql_bind($stmt, "@modcountry", $country, SQLVARCHAR);
	mssql_bind($stmt, "@region_id", $region_id, SQLINT2);    
	
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function remove_region($region_id, $db) {
	/* prepare the statement */
	$proc = "usp_remove_region";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@region_id", $region_id, SQLVARCHAR);
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

?>

<? include 'inc/elements/footer.php'; ?>