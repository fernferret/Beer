<? 
	// Define database credentials
	$dbhost = 'titan.cs.rose-hulman.edu';
	$dbuser = 'brousapg';
	$dbpass = 'brousapg';
	$dbname = 'Beer';
	
	$table_name = 'beer_lovers';

	session_start();

	// Connect to database
	$db = mssql_connect($dbhost, $dbuser, $dbpass)
	  or die("Couldn't connect to SQL Server on $dbhost"); 
	
	// Select database
	$selected = mssql_select_db($dbname, $db)
	  or die("Couldn't open database $myDB"); 
	  
	include("inc/elements/header.php");


	if(isset($_POST['add'])){
		add_beer_lover($_POST['name'], $_POST['email'], $_POST['address'], $_POST['username'], $_POST['password'], $_POST['region_id'], $db);	
	}
	
	if(isset($_POST['update'])){
		modify_beer_lover($_POST['name'], $_POST['email'], $_POST['address'], $_POST['username'], $_POST['password'], $_POST['region_id'], $db);	
	}
	
	if(isset($_POST['remove'])){
		remove_beer_lover($_POST['username'], $db);	
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
					    echo '<input style="display: none" type="text" name="'.$field->name.'" /><br>';	
					    echo '<input type="text" name="'.$field->name.'" />';	
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
				<input type="text" value="username to modify" name="row_id">
			</li>
			<li>
				<input type="submit" value="Ok" class="large green button" name="ok" />
			</li>
	</fieldset>
	</form>
</div>

<div id="modified">
	<form name="modify_form" method="post" action="">
	    <fieldset>
	    	<ul>
	    		<li>
	    			<?
    				if(isset($_POST['row_id'])) {
	    				$username = $_POST['row_id'];
						$result = mssql_query("SELECT * FROM ".$table_name." WHERE username = '" .$username. "'") or die("Query to show fields from table failed");
						echo "<h1>Change row <strong>".$username."</strong>:</h1>";
						while (($row = mssql_fetch_array($result, MSSQL_BOTH))) 
					    { 
						    echo '<label for="name">name</label>';
					        echo '<input value="'.$row['name'].'" type="text" name="name" />';
						    echo '<label for="email">email</label>';
					        echo '<input value="'.$row['email'].'" type="text" name="email" />';
						    echo '<label for="address">address</label>';
					        echo '<input value="'.$row['address'].'" type="text" name="address" />';
						    echo '<label for="username">username</label>';
					        echo '<input value="'.$row['username'].'" type="text" name="username" />';
						    echo '<label for="password">password</label>';
					        echo '<input value="'.$row['password'].'" type="text" name="password" />';
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
		echo "<tr><td>" . $row['name'] . "</td><td>" . $row['email'] . "</td><td>" . $row['address'] . "</td><td>" . $row['username'] . "</td><td>" . $row['password'] . "</td><td>" . $row['region_id'] . "</td></tr>";
	} 

	mssql_free_result($result);
	?>
</div>

<?

function add_beer_lover($name, $email, $address, $username, $password, $region_id, $db) {
	/* prepare the statement */
	$proc="usp_add_beer_lover";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@name", $name, SQLVARCHAR);
	mssql_bind($stmt, "@email", $email, SQLVARCHAR);
	mssql_bind($stmt, "@address", $address, SQLVARCHAR);    
	mssql_bind($stmt, "@username", $username, SQLVARCHAR);    
	mssql_bind($stmt, "@password", $password, SQLVARCHAR);    
	mssql_bind($stmt, "@region_id", $region_id, SQLINT2);    	
	/* now execute the procedure */
	$result = mssql_execute($stmt);

	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function modify_beer_lover($name, $email, $address, $username, $password, $region_id, $db) {
	/* prepare the statement */
	$proc = "usp_modify_beer_lover";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@newname", $name, SQLVARCHAR);
	mssql_bind($stmt, "@newemail", $email, SQLVARCHAR);
	mssql_bind($stmt, "@newaddress", $address, SQLVARCHAR);    
	mssql_bind($stmt, "@username", $username, SQLVARCHAR);    
	mssql_bind($stmt, "@newpassword", $password, SQLVARCHAR);    
	mssql_bind($stmt, "@newregion_id", $region_id, SQLINT2);    	
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

function remove_beer_lover($username, $db) {
	/* prepare the statement */
	$proc = "usp_remove_beer_lover";
	$stmt=mssql_init($proc, $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@username", $username, SQLVARCHAR);
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
	
	if(!$result)
		echo "<ul class='alert error'><li><p>Procedure: ".$proc." failed with: ".$result."</p></li></ul>";
	else
		echo "<ul class='alert success'><li><p>Success!</p></li></ul>";
}

include 'inc/elements/footer.php'; ?>