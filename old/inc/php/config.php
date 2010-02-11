<?
	// Define database credentials
	$dbhost = 'titan.cs.rose-hulman.edu';
	$dbuser = 'brousapg';
	$dbpass = 'brousapg';
	$dbname = 'Beer';

	session_start();

	// Connect to database
	$dbhandle = mssql_connect($dbhost, $dbuser, $dbpass)
	  or die("Couldn't connect to SQL Server on $dbhost"); 
	
	// Select database
	$selected = mssql_select_db($dbname, $dbhandle)
	  or die("Couldn't open database $myDB"); 
?>