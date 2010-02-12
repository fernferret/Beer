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
		mssql_bind($stmt, "@newpicture", $picture, SQLINT2);    	
		
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
			echo '<meta http-equiv="refresh" content="0;index.php">'; //refresh the page to see if membership worked.
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
}