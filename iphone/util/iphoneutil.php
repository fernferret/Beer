<?php
//require_once "db.php";
include "../includes/config.php";
include "../includes/dbvars.php";
include "../util/functions.php";

// Connect to database
$db = mssql_connect($dbhost, $dbuser, $dbpass)
  or die("Couldn't connect to SQL Server on $dbhost"); 

// Select database
$selected = mssql_select_db($dbname, $db)
  or die("Couldn't open database $dbname"); 
	 
class iPhone
{
	function add_beer_lover($name, $email, $address, $username, $password, $region_id) {
		global $db;
		$proc = "usp_add_beer_lover";
		$stmt = mssql_init($proc, $db);
		
		$res = mssql_query("SELECT * FROM regions WHERE City = '".$region_id."'");
		$row = mssql_fetch_assoc($res);
		$region_id = $row["region_id"];
		
		date_default_timezone_set('UTC');
		$date_joined = date("m/d/y");
		
		if(!isValidEmail($email)) {
			alert("You must enter a valid email address!", FALSE);
			return 0;
		}
				
		/* now bind the parameters to it */
		mssql_bind($stmt, "@name", $name, SQLVARCHAR);
		mssql_bind($stmt, "@email", $email, SQLVARCHAR);
		mssql_bind($stmt, "@address", $address, SQLVARCHAR);    
		mssql_bind($stmt, "@username", $username, SQLVARCHAR);    
		mssql_bind($stmt, "@password", $password, SQLVARCHAR);    
		mssql_bind($stmt, "@region_id", $region_id, SQLINT2);    	
		mssql_bind($stmt, "@date_joined", $date_joined, SQLVARCHAR);    	

		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
	
		/* now execute the procedure */
		$result = mssql_execute($stmt);
	
		if($return == 0) { 
			$_SESSION['username'] = $username;
			$_SESSION['logged_in'] = 1;
			alert("Successfully registered as " . $_SESSION['username'], TRUE);
			echo '<meta http-equiv="refresh" content="0;index.php">'; //refresh the page to see if membership worked.
		} else if($return == 1)
			alert("You must enter a valid username, password, and email!", FALSE);
		else if($return == 2)
			alert("That username is already taken!", FALSE);
		else if($return == 3)
			alert("That email address is already taken!", FALSE);
	}
	
	function modify_beer_lover($name, $email, $address, $username, $password, $region_id, $picture) {
		/* prepare the statement */
		global $db;
		$proc = "usp_modify_beer_lover";
		$stmt = mssql_init($proc, $db);
		
		/* now bind the parameters to it */
		mssql_bind($stmt, "@newname", $name, SQLVARCHAR);
		mssql_bind($stmt, "@newemail", $email, SQLVARCHAR);
		mssql_bind($stmt, "@newaddress", $address, SQLVARCHAR);    
		mssql_bind($stmt, "@username", $username, SQLVARCHAR);    
		mssql_bind($stmt, "@newpassword", $password, SQLVARCHAR);    
		mssql_bind($stmt, "@newregion_id", $region_id, SQLINT2);    	
		mssql_bind($stmt, "@newpicture", $picture, SQLVARCHAR);    	
		
		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
	
		/* now execute the procedure */
		$result = mssql_execute($stmt);
		
		if($return == 0) 
			alert("Your profile has been updated!", TRUE);
		else if($return == 1)
			alert("You must enter a valid username!", FALSE);
		else if($return == 2)
			alert("Region ID is invalid!", FALSE);
	}
	
	public function login($username, $password) { 
		global $db;
		$proc = "usp_auth_beer_lover";
		$stmt = mssql_init($proc, $db);

		/* now bind the parameters to it */
		mssql_bind($stmt, "@username", $username, SQLVARCHAR);    
		mssql_bind($stmt, "@password", $password, SQLVARCHAR);      	
		
		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
	
		/* now execute the procedure */
		$result = mssql_execute($stmt);
	
		if($return == 0) {
			$_SESSION['username'] = $username;
			$_SESSION['logged_in'] = 1;
			
			//alert("Successfully logged in as " . $_SESSION['username'], TRUE);
			return $_SESSION['username'];
			//redirect("/"); //refresh the page to see if membership worked.
		} else if($return == 1 || $return == 2) {
			//alert("You need to enter a valid username/password!", FALSE);
			return "-1";
		}
	}
	
	public function topten() {
		$column = "name";
		$query = "select * from view_beer_browser";
		$result = mssql_query($query);
		//echo "<h1>Results:</h1>\n";
		if($result) {
			$r[0] = mssql_fetch_assoc($result);
			$i = 1;
			while($r[$i] = mssql_fetch_assoc($result))
			{
				$i++;
			}
			return 	$r;//'<br><span style="font-size: 36px; margin-left: 15px"><strong><a href="beer.php?id='.$r["beer_id"].'">'.$r["name"].'</a></strong></span>';
		} else {
			//alert("No results found! Try again.", FALSE);
			return '-1';
		}
	}
	
	public function logout() {
		if (!empty($_SESSION['logged_in']) && !empty($_SESSION['username']))	{
			$_SESSION = array(); 
			session_destroy();
			return 1;
		} else  {
			return 0; 
		}
	}	
	
	public function search($search) {
		$column = "name";
		$query = "select * from beers where $column like \"%$search%\" order by $column";
		$result = mssql_query($query);
		//echo "<h1>Results:</h1>\n";
		if($result) {
			$r[0] = mssql_fetch_assoc($result);
			$i = 1;
			while($r[$i] = mssql_fetch_assoc($result))
			{
				$i++;
			}
			return 	$r;//'<br><span style="font-size: 36px; margin-left: 15px"><strong><a href="beer.php?id='.$r["beer_id"].'">'.$r["name"].'</a></strong></span>';
		} else {
			//alert("No results found! Try again.", FALSE);
			return '-1';
		}
	}
	public function showBeerById($id) {
		$column = "beer_id";
		$query = "select * from beers where $column like \"%$id%\" order by $column";
		$result = mssql_query($query);
		//echo "<h1>Results:</h1>\n";
		if($result) {
			$r['Beer'] = mssql_fetch_assoc($result);
			
			$query = "select * from [has_property] where [beer_id] like \"%$id%\"";
			$result = mssql_query($query);
			$i = 0;
			while($r['Attr'][$i] = mssql_fetch_assoc($result))
			{
				$i++;
			}
			
			if(isset($_SESSION['username']))
			{
				$uname = $_SESSION['username'];
				$query = "select [lovedbeers] from [beer_lovers] where [username] = $uname";
				$result = mssql_query($query);
				$r['Love'] = mssql_fetch_assoc($result);
			}
			
			return $r;
			
			
		} else {
			//alert("No results found! Try again.", FALSE);
			return '-1';
		}
	}
}