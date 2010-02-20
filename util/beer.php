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
	
error_reporting(0);
	 
class Beer	
{
	function add_beer($name, $aroma, $filtered, $weight, $bitterness, $hoppiness, $color, $clarity, $head, $type, $alcohol, $username) {																										
		$beer = new Beer();

		global $db;
		$proc = "usp_add_beer";
		$stmt = mssql_init($proc, $db);
		
		if(empty($name)) {
			alert("Please enter a beer name!", FALSE);
			return 0;
		}
		
		/* now bind the parameters to it */
		mssql_bind($stmt, "@newname", $name, SQLVARCHAR);
		mssql_bind($stmt, "@newaroma", $aroma, SQLVARCHAR);
		mssql_bind($stmt, "@newfiltered", $filtered, SQLVARCHAR);    
		mssql_bind($stmt, "@submitted_by", $username, SQLVARCHAR);    

		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
		
		/* now execute the procedure */
		$result = mssql_execute($stmt);
				
		$beer_id = $beer->beer_id($name);

		$beer->add_property_to_beer($beer_id, $beer->property_id("Weight"), $weight);
		$beer->add_property_to_beer($beer_id, $beer->property_id("Bitterness"), $bitterness);
		$beer->add_property_to_beer($beer_id, $beer->property_id("Hoppiness"), $hoppiness);
		$beer->add_property_to_beer($beer_id, $beer->property_id("Color"), $color);
		$beer->add_property_to_beer($beer_id, $beer->property_id("Clarity"), $clarity);
		$beer->add_property_to_beer($beer_id, $beer->property_id("Head"), $head);
		$beer->add_property_to_beer($beer_id, $beer->property_id("Type"), $type);
		$beer->add_property_to_beer($beer_id, $beer->property_id("AlcoholContent"), $alcohol);
		
		if($return==2) {
			alert("Please enter a beer name!", FALSE);
		}else if($return==0) {
			alert("Success! <strong>".$name."</strong> added. <a href='beers/".$beer_id."'>Click here</a> to view your submission.", TRUE);
		}
	}
	
	function add_property_to_beer($beer_id, $property_id, $description) {
		global $db;

		$proc_prop = "usp_add_property_to_beer";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);
		mssql_bind($stmt_prop, "@property_id", $property_id, SQLINT2);
		mssql_bind($stmt_prop, "@description", $description, SQLVARCHAR);    

		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);
		
		if($return==5) {
			alert("Beer already exists!", FALSE);
		}
		
	}
	
	function modify_beer($beer_id, $name, $aroma, $filtered, $weight, $bitterness, $hoppiness, $color, $clarity, $head, $type, $alcohol, $username) {																										
		$beer = new Beer();

		global $db;
		$proc = "usp_modify_beer";
		$stmt = mssql_init($proc, $db);
		
		/* now bind the parameters to it */
		mssql_bind($stmt, "@modname", $name, SQLVARCHAR);
		mssql_bind($stmt, "@modaroma", $aroma, SQLVARCHAR);
		mssql_bind($stmt, "@beer_id", $beer_id, SQLINT2);    
		mssql_bind($stmt, "@modfiltered", $filtered, SQLVARCHAR);    

		mssql_bind($stmt, "RETVAL", $return, SQLINT2);
		
		/* now execute the procedure */
		$result = mssql_execute($stmt);

		$beer->modify_property_of_beer($beer_id, $beer->property_id("Weight"), $weight);
		$beer->modify_property_of_beer($beer_id, $beer->property_id("Bitterness"), $bitterness);
		$beer->modify_property_of_beer($beer_id, $beer->property_id("Hoppiness"), $hoppiness);
		$beer->modify_property_of_beer($beer_id, $beer->property_id("Color"), $color);
		$beer->modify_property_of_beer($beer_id, $beer->property_id("Clarity"), $clarity);
		$beer->modify_property_of_beer($beer_id, $beer->property_id("Head"), $head);
		$beer->modify_property_of_beer($beer_id, $beer->property_id("Type"), $type);
		$beer->modify_property_of_beer($beer_id, $beer->property_id("AlcoholContent"), $alcohol);
		
		if($return==1) {
			alert("Please enter a beer name!", FALSE);
		}else if($return==0) {
			alert("Success! <strong>".$name."</strong> modified. <a href='../beers/".$beer_id."'>Click here</a> to view your submission.", TRUE);
			redirect('edit/'.$beer_id);
		}
	}
	
	function modify_property_of_beer($beer_id, $property_id, $description) {
		global $db;

		//alert($beer_id . " - " . $property_id . " - " . $description, FALSE);

		$proc_prop = "usp_modify_property_of_beer";
		$stmt_prop = mssql_init($proc_prop, $db);
				
		/* now bind the parameters to it */
		mssql_bind($stmt_prop, "@beer_id", $beer_id, SQLINT2);
		mssql_bind($stmt_prop, "@property_id", $property_id, SQLINT2);
		mssql_bind($stmt_prop, "@newdescription", $description, SQLVARCHAR);    

		mssql_bind($stmt_prop, "RETVAL", $return, SQLINT2);

		/* now execute the procedure */
		$result_prop = mssql_execute($stmt_prop);
		
		if($return==1) {
			alert("Enter a valid beer please.", FALSE);
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
	
	public function property_values($beer_id, $property_id) {
		$res = mssql_query("SELECT * FROM property_values WHERE property_id = '".$property_id."'");
		$beer = new Beer();
		$property_name = $beer->property_name($property_id);
		echo '<li><label for="Form_'.$property_name.'">'.$property_name.'<strong>*</strong></label>';
		echo '<select name="Form_'.$property_name.'">';
		while ($row = mssql_fetch_assoc($res)) {
			$resn = mssql_query("SELECT * FROM has_property WHERE beer_id ='".$beer_id."' AND property_id = '".$property_id."'");
			$rown = mssql_fetch_assoc($resn);
			$desc = $rown["description"];

			if($desc == $row["description"]) {
				echo '<option selected="selected">'.$row["description"].'</option>\n';
			} else {
				echo '<option>'.$row["description"].'</option>\n';
			}
		}
		echo "</select></li>\n";
		echo "<span style='color: #6d6d6d;'>".$beer->property_desc($property_id)."</span></li>\n\n";
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
	
	public function property_id($property_name) {
		$res = mssql_query("SELECT * FROM property WHERE name = '".$property_name."'");
		$row = mssql_fetch_assoc($res);
		return $row["property_id"];				
	}
	
	public function beer_id($beer_name) {
		$res = mssql_query("SELECT * FROM beers WHERE name = '".$beer_name."'");
		$row = mssql_fetch_assoc($res);
		return $row["beer_id"];				
	}
	
	public function beer_name($beer_id) {
		$res = mssql_query("SELECT * FROM beers WHERE beer_id = '".$beer_id."'");
		$row = mssql_fetch_assoc($res);
		return $row["name"];				
	}
	
	public function property_desc($property_id) {
		$res = mssql_query("SELECT description FROM property WHERE property_id = '".$property_id."'");
		$row = mssql_fetch_assoc($res);
		return $row["description"];				
	}
}