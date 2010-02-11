<?
function add_to_beer($name, $aroma, $filtered, $db) {
	/* prepare the statement */
	$stmt=mssql_init("usp_add_beer", $db);
	
	/* now bind the parameters to it */
	mssql_bind($stmt, "@newname", $name, SQLVARCHAR, FALSE);
	mssql_bind($stmt, "@newaroma", $aroma, SQLVARCHAR, FALSE);
	mssql_bind($stmt, "@newfiltered", $filtered, SQLVARCHAR, FALSE);    
	
	/* now execute the procedure */
	$result = mssql_execute($stmt);
}
?>