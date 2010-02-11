	<? 
	// Define database credentials
	$dbhost = 'titan.cs.rose-hulman.edu';
	$dbuser = 'brousapg';
	$dbpass = 'brousapg';
	$dbname = 'Beer';
	
	$table_name = 'vendors';

	session_start();

	// Connect to database
	$db = mssql_connect($dbhost, $dbuser, $dbpass)
	  or die("Couldn't connect to SQL Server on $dbhost"); 
	
	// Select database
	$selected = mssql_select_db($dbname, $db)
	  or die("Couldn't open database $myDB"); 
	  
	include("inc/elements/header.php");


	if(isset($_POST['add'])){ 
		add_vendor($_POST['type'], $_POST['name'], $_POST['address'], $_POST['region_id'], $db);	
	}
	
	if(isset($_POST['update'])){
		modify_vendor($_POST['type'], $_POST['name'], $_POST['address'], $_POST['region_id'], $_POST['vend_id'], $db);	
	}
	
	if(isset($_POST['remove'])){
		remove_vendor($_POST['vend_id'], $db);	
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
				     	if(strpos($field->name, "vend_id") !== false) {
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
					<input type="text" value="vend_id to modify" name="row_id">
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
	    				$vend_id = $_POST['row_id'];
						$result = mssql_query("SELECT * FROM ".$table_name." WHERE vend_id = '" .$vend_id. "'") or die("Query to show fields from table failed");
						echo "<h1>Change row <strong>".$vend_id."</strong>:</h1>";
						while (($row = mssql_fetch_array($result, MSSQL_BOTH))) 
					    { 
					    	echo '<input value="'.$row['vend_id'].'" type="hidden" name="vend_id" />';
						    echo '<label for="type">type</label>';
					        echo '<input value="'.$row['type'].'" type="text" name="type" />';
						    echo '<label for="name">name</label>';
					        echo '<input value="'.$row['name'].'" type="text" name="name" />';
						    echo '<label for="address">address</label>';
					        echo '<input value="'.$row['address'].'" type="text" name="address" />';
					        echo '<label for="region_id">region_id</label>';
					        echo '<input value="'.$row['region_id'].'" type="text" name="region_id" />';
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
		echo "<tr><td>" . $row['vend_id'] . "</td><td>" . $row['region_id'] . "</td><td>" . $row['type'] . "</td><td>" . $row['name'] . "</td><td>" . $row['address'] . "</td></tr>";
	} 

	mssql_free_result($result);
	?>
</div>

<?

function add_vendor($type, $name, $address, $region_id, $db) {
	/* prepare the statement */
	$proc = "usp_add_vendor";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@region_id", $region_id, SQLINT2);    
	mssql_bind($stmt, "@type", $type, SQLVARCHAR);
	mssql_bind($stmt, "@name", $name, SQLVARCHAR);
	mssql_bind($stmt, "@address", $address, SQLVARCHAR);    
	
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function modify_vendor($type, $name, $address, $region_id, $vend_id, $db) {
	/* prepare the statement */
	$proc = "usp_modify_vendor";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@vendor_id", $vend_id, SQLINT2);
	mssql_bind($stmt, "@region_id", $region_id, SQLINT2);
	mssql_bind($stmt, "@type", $type, SQLVARCHAR);
	mssql_bind($stmt, "@name", $name, SQLVARCHAR);    
	mssql_bind($stmt, "@address", $address, SQLVARCHAR);    
	
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function remove_vendor($vend_id, $db) {
	/* prepare the statement */
	$proc = "usp_remove_vendor";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@vend_id", $vend_id, SQLVARCHAR);
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

?>

<? include 'inc/elements/footer.php'; ?>