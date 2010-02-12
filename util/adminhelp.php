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
	 
class Adminhelp
{
	function add_beer_to_vendor($beerid, $vendid) {
		global $db;
		$proc = "usp_add_beer_to_vendor";
		$stmt = mssql_init($proc, $db);
		
		//$res = mssql_query("SELECT * FROM regions WHERE City = '".$region_id."'");
		//$row = mssql_fetch_assoc($res);
		//$region_id = $row["region_id"];
				
		/* now bind the parameters to it */
		mssql_bind($stmt, "@beer_id", $beerid, SQLINT2);
		mssql_bind($stmt, "@vend_id", $vendid, SQLINT2);  	

		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
	
		/* now execute the procedure */
		$result = mssql_execute($stmt);
	
		if($return == 0) { 
			alert("Beer Successfully added!", TRUE);
			//echo '<meta http-equiv="refresh" content="0;admin.php">'; //refresh the page to see if membership worked.
		} else if($return == 1)
			alert("You must enter a valid Beer ID", FALSE);
		else if($return == 2)
			alert("You must enter a valid Vendor ID", FALSE);
		else if($return == 3)
			alert("That beer has already been added to that vendor!", FALSE);
	}
	
		function remove_beer_from_vendor($beerid, $vendid) {
		global $db;
		$proc = "usp_remove_beer_from_vendor";
		$stmt = mssql_init($proc, $db);
		
		//$res = mssql_query("SELECT * FROM regions WHERE City = '".$region_id."'");
		//$row = mssql_fetch_assoc($res);
		//$region_id = $row["region_id"];
				
		/* now bind the parameters to it */
		mssql_bind($stmt, "@beer_id", $beerid, SQLINT2);
		mssql_bind($stmt, "@vend_id", $vendid, SQLINT2);  	

		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
	
		/* now execute the procedure */
		$result = mssql_execute($stmt);
	
		if($return == 0) { 
			alert("Beer Successfully deleted!", TRUE);
			//echo '<meta http-equiv="refresh" content="0;admin.php">'; //refresh the page to see if membership worked.
		} else if($return == 1)
			alert("You must enter a valid Beer ID", FALSE);
		else if($return == 2)
			alert("You must enter a valid Vendor ID", FALSE);
		else if($return == 3)
			alert("That beer is not currently sold by the specified vendor!", FALSE);
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