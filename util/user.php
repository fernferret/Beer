<?php
//require_once "db.php";
include "includes/config.php";
include "includes/dbvars.php";
include "functions.php";

// Connect to database
$db = mssql_connect($dbhost, $dbuser, $dbpass)
  or die("Couldn't connect to SQL Server on $dbhost"); 

// Select database
$selected = mssql_select_db($dbname, $db)
  or die("Couldn't open database $dbname"); 
	 
class User
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
			redirect("/"); //refresh the page to see if membership worked.
		} else if($return == 1)
			alert("You must enter a valid username, password, and email!", FALSE);
		else if($return == 2)
			alert("That username is already taken!", FALSE);
		else if($return == 3)
			alert("That email address is already taken!", FALSE);
	}
	
	function modify_beer_lover($name, $email, $address, $username, $region_id, $picture) {
		/* prepare the statement */
		global $db;
		$proc = "usp_modify_beer_lover";
		$stmt = mssql_init($proc, $db);
		
		if(!isValidEmail($email)) {
			alert("You must enter a valid email address!", FALSE);
			return 0;
		}	
		
		/* now bind the parameters to it */
		mssql_bind($stmt, "@newname", $name, SQLVARCHAR);
		mssql_bind($stmt, "@newemail", $email, SQLVARCHAR);
		mssql_bind($stmt, "@newaddress", $address, SQLVARCHAR);    
		mssql_bind($stmt, "@username", $username, SQLVARCHAR);      
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
			
			alert("Successfully logged in as " . $_SESSION['username'], TRUE);
			redirect("/"); //refresh the page to see if membership worked.
		} else if($return == 1 || $return == 2) {
			alert("You need to enter a valid username/password!", FALSE);
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
		echo "<h1>Results:</h1>\n";
		if($result) {
			$r[0] = mssql_fetch_assoc($result);
			$i = 1;
			while($r[$i] = mssql_fetch_assoc($result))
			{
				$i++;
			}
			return $r;
		} else {
			alert("No results found! Try again.", FALSE);
		}
	}
	
		
	function remove_favorite($beer_id, $username) {
		global $db;

		$proc_prop = "usp_remove_likes_beer";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);    
		mssql_bind($stmt_prop, "@username", $username, SQLVARCHAR);
		
		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);
	}
	
	function add_favorite($beer_id, $username) {
		global $db;

		$proc_prop = "usp_add_likes_beer";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);    
		mssql_bind($stmt_prop, "@username", $username, SQLVARCHAR);
		
		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);
	}
	
	public function region_name($region_id) {
		$res = mssql_query("SELECT city FROM regions WHERE region_id = '".$region_id."'");
		$row = mssql_fetch_assoc($res);
		return $row["city"];		
	}
	
	public function region_id($city) {
		$res = mssql_query("SELECT region_id FROM regions WHERE city = '".$city."'");
		$row = mssql_fetch_assoc($res);
		return $row["region_id"];		
	}
	
	public function beer_name($beer_id) {
		$res = mssql_query("SELECT name FROM beers WHERE beer_id = '".$beer_id."'");
		$row = mssql_fetch_assoc($res);
		return $row["name"];				
	}

	
	public function get_recommended_user($username) {
		$res = mssql_query("SELECT * FROM dbo.unf_auto_recommend($id)");
		$row = mssql_fetch_assoc($res);
		return $row;
	}
}