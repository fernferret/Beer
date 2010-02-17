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
	 
class Beer	
{
	function add_beer($name, $aroma, $filtered, $weight, $hoppiness, $finish, $color,
														$clarity, $type, $head, $alcohol, $username) {
																												
		$beer = new Beer();

		global $db;
		$proc = "usp_add_beer";
		$stmt = mssql_init($proc, $db);
		
		/* now bind the parameters to it */
		mssql_bind($stmt, "@newname", $name, SQLVARCHAR);
		mssql_bind($stmt, "@newaroma", $aroma, SQLVARCHAR);
		mssql_bind($stmt, "@newfiltered", $filtered, SQLVARCHAR);    
		mssql_bind($stmt, "@submitted_by", $username, SQLVARCHAR);    

		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
		
		/* now execute the procedure */
		$result = mssql_execute($stmt);
				
		$res = mssql_query("SELECT * FROM beers WHERE name = '".$name."'");
		$row = mssql_fetch_assoc($res);
		$beer_id = $row["beer_id"];

		$beer->add_property_to_beer($beer_id, "Weight", $weight);
		$beer->add_property_to_beer($beer_id, "Hoppiness", $hoppiness);
		$beer->add_property_to_beer($beer_id, "Finish", $finish);
		$beer->add_property_to_beer($beer_id, "Color", $color);
		$beer->add_property_to_beer($beer_id, "Clairty", $clarity);
		$beer->add_property_to_beer($beer_id, "Type", $type);
		$beer->add_property_to_beer($beer_id, "Head", $head);
		$beer->add_property_to_beer($beer_id, "AlcoholContent", $alcohol);
		
		if($return==0) {
			alert("Success! <strong>".$name."</strong> added. <a href='beers/".$beer_id."'>Click here</a> to view your submission.", TRUE);
		}
	}
	
	function add_property_to_beer($beer_id, $property_name, $description) {
		global $db;

		$proc_prop = "usp_add_property_to_beer";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);
		mssql_bind($stmt_prop, "@property_name", $property_name, SQLVARCHAR);
		mssql_bind($stmt_prop, "@description", $description, SQLVARCHAR);    

		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);

		$res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
		$row = mssql_fetch_assoc($res);
		$name = $row["name"];
		
		if($return==5) {
			alert("Beer already exists!", FALSE);
		}
		
	}
	
	function add_rating($username, $rating, $beer_id) {
		global $db;

		$proc_prop = "usp_add_rating";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@username", $username, SQLVARCHAR);
		mssql_bind($stmt_prop, "@ratingvalue", $rating, SQLINT2);
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);    

		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);
		
		if($return==4) {
			alert("Already rated this beer!", FALSE);
		} else if($return==3) {
			alert("Please enter a rating.", FALSE);
		} else if($return==0) {
			redirect('beers/'.$beer_id);
		}
	}
	
	function add_comment($username, $beer_id, $description) {
		global $db;

		$proc_prop = "usp_add_comment";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@username", $username, SQLVARCHAR);
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);    
		mssql_bind($stmt_prop, "@description", $description, SQLTEXT);    
		
		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);
		
		if($return==0) {
			redirect('beers/'.$beer_id);
		}
		
	}
	
	function add_recommendation($username, $to_user, $beer_id) {
		global $db;

		$proc_prop = "usp_add_recommendation";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@from_user", $username, SQLVARCHAR);
		mssql_bind($stmt_prop, "@to_user", $to_user, SQLVARCHAR);    
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);    
		
		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);
		
		if($return==3) {
			alert("Please choose a valid user!", FALSE);
		} else if($return==0) {
			alert("Beer recommendation sent!", TRUE);
		}
		
	}
	
	public function get_rating($beer_id) {
		$res = mssql_query("SELECT AVG(value) as avgrate FROM rates WHERE beer_id = '".$beer_id."'");
		$row = mssql_fetch_assoc($res);
		$rating = $row["avgrate"];
		if($rating == '') $rating = 0;
		return $rating;				
	}
	
	
	public function property_name($property_id) {
		$res = mssql_query("SELECT name FROM property WHERE property_id = '".$property_id."'");
		$row = mssql_fetch_assoc($res);
		return $row["name"];				
	}
	
	public function property_desc($property_id) {
		$res = mssql_query("SELECT description FROM property WHERE property_id = '".$property_id."'");
		$row = mssql_fetch_assoc($res);
		return $row["description"];				
	}
}