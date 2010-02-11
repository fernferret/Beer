<? 
	// Define database credentials
	$dbhost = 'titan.cs.rose-hulman.edu';
	$dbuser = 'brousapg';
	$dbpass = 'brousapg';
	$dbname = 'Beer';
	
	$table_name = 'beers';

	session_start();

	// Connect to database
	$db = mssql_connect($dbhost, $dbuser, $dbpass)
	  or die("Couldn't connect to SQL Server on $dbhost"); 
	
	// Select database
	$selected = mssql_select_db($dbname, $db)
	  or die("Couldn't open database $myDB"); 
	  
	include("inc/elements/header.php");


	if(isset($_POST['add'])){
		add_beer($_POST['name'], $_POST['aroma'], $_POST['filtered'],  $db);	
	}
	
	if(isset($_POST['update'])){
		modify_beer($_POST['beer_id'], $_POST['name'], $_POST['aroma'], $_POST['filtered'],  $db);	
	}
	
	if(isset($_POST['remove'])){
		remove_beer($_POST['beer_id'], $_POST['name'], $db);	
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
				     	if(strpos($field->name, "beer_id") !== false) {
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
					<input type="text" value="beer_id to modify" name="row_id">
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
	    				$beer_id = $_POST['row_id'];
						$result = mssql_query("SELECT * FROM ".$table_name." WHERE beer_id = '" .$beer_id. "'") or die("Query to show fields from table failed");
						echo "<h1>Change row <strong>".$beer_id."</strong>:</h1>";
						while (($row = mssql_fetch_array($result, MSSQL_BOTH))) 
					    { 
					    	echo '<input value="'.$row['beer_id'].'" type="hidden" name="beer_id" />';
						    echo '<label for="name">name</label>';
					        echo '<input value="'.$row['name'].'" type="text" name="name" />';
						    echo '<label for="aroma">aroma</label>';
					        echo '<input value="'.$row['aroma'].'" type="text" name="aroma" />';
						    echo '<label for="filtered">filtered</label>';
					        echo '<input value="'.$row['filtered'].'" type="text" name="filtered" />';
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
		echo "<tr><td>" . $row['name'] . "</td><td>" . $row['aroma'] . "</td><td>" . $row['beer_id'] . "</td><td>" . $row['filtered'] . "</td></tr>";
	} 

	mssql_free_result($result);
	?>
</div>

<?

function add_beer($name, $aroma, $filtered, $db) {
	/* prepare the statement */
	$proc = "usp_add_beer";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@newname", $name, SQLVARCHAR);
	mssql_bind($stmt, "@newaroma", $aroma, SQLVARCHAR);
	mssql_bind($stmt, "@newfiltered", $filtered, SQLVARCHAR);    
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function modify_beer($beer_id, $name, $aroma, $filtered, $db) {
	/* prepare the statement */
	$proc = "usp_modify_beer";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@modname", $name, SQLVARCHAR);
	mssql_bind($stmt, "@modaroma", $aroma, SQLVARCHAR);
	mssql_bind($stmt, "@beer_id", $beer_id, SQLVARCHAR);
	mssql_bind($stmt, "@modfiltered", $filtered, SQLVARCHAR);    
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function remove_beer($beer_id, $name, $db) {
	/* prepare the statement */
	$proc = "usp_remove_beer";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@name", $name, SQLVARCHAR);
	mssql_bind($stmt, "@beer_id", $beer_id, SQLVARCHAR);
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

?>

<? include 'inc/elements/footer.php'; ?>