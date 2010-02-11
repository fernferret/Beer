<?php 
// Include global vars.
include "dbvars.php";
include "config.php";

// Connect to database
$db = mssql_connect($dbhost, $dbuser, $dbpass)
  or die("Couldn't connect to SQL Server on $dbhost"); 

// Select database
$selected = mssql_select_db($dbname, $db)
  or die("Couldn't open database $dbname"); 


// Start the session, to keep user logged in.
session_start();  
