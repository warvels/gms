<?php

# ==============================================================================================
# functionsGMS.php
# ==============================================================================================
# fundamental functions 
#  error logging
# ==============================================================================================



# ==============================================================================================
# GMS error handler
function gmsError( $errorCode, $errorText, $av1, $av2 )
{
	# this is debugging only
	$displayFlag = 0;
	if ( $displayFlag ) {
		#echo '<br> GMSerror <br> ';
		printf( ' <b>GMS-Error: '. $errorCode. '  '. $errorText. '</b>');
	}
	# Log errors
	$log_file = 'logs/gmserror.log';
	$dlm = ';';
	# create an entry with date.time, errorcode, error text. to get NEWLINE must put \r\n in double quotes.
	$logdata = gmdate('Y-m-d H:i:s'). 'UTC '. $dlm. $errorCode. $dlm. $errorText. $dlm. "\r\n";
	error_log($logdata, 3, $log_file);
	return 1;
}








# ==============================================================================================
# dbopenGMS - Open $pdo_connect definedin included db config. return channnel or exception
function dbopenGMS( $db_link, $db_task )
{
	# if already open, just return the passed db_link
	if (isset($db_link) AND ($db_link != NULL) ) {
		#printf('  db link is already set and is Not NULL <br>' );
		return $db_link;
	}
	include("config.db.active.php");
	
##printf(' pre try pdo open ');			
	try {
		$pdo_dblink = new PDO( "mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass );
		if (!$pdo_dblink) {
			throw new DBEx("Cannot connect to the database"); 
		} 
	} catch (PDOException $e) {
		gmsError( 'ECDBOPEN', $e->getMessage(), '', '', '' );
		##printf(' catch 1  ');
		return null;
	} catch (DBEx $e) {
		gmsError( 'ECDBOPENDBEX', $e->getMessage(), '', '', '' );
		return null;
	}

	# setup Db attributes and return the 
	$pdo_dblink->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$pdo_dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db_link = $pdo_dblink;
	return $pdo_dblink;
	
}

# =============================================================================================
# dbqueryGMS - Validates the query passed. return results from function. rowcnt return as param
# does not get any rows. 
function dbqueryGMS( $db_link, $query_str, $row_count, $av1)
{
	#$result = $stmt->setFetchMode(PDO::FETCH_NUM);  # will return results in array with integer index (not col name)
	try {
		$result = $db_link->query( $query_str );
		$row_count = $result->rowCount();
	} catch (PDOException $e) {
print_r($db_link->errorInfo());
		gmsError( 'ECDBQUERY', $e->getMessage(), '', '', '' );
		$row_count = 0;
		return null;
	}		
	return $result;
}

# =============================================================================================
# dbqueryfetchallGMS - Validates the query passed and returns all results from query
function dbqueryfetchallGMS( $db_link, $query_str, $av2, $av1)
{
	try {
		$stmt = $db_link->query( $query_str );
	} catch (PDOException $e) {
	print_r($db_link->errorInfo());
		gmsError( 'ECDBQUERY', $e->getMessage(), '', '', '' );
		$row_count = 0;
		return null;
	}	
	
	try {
    	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$row_count = $stmt->rowCount();    
		return $result;
	} catch (PDOException $e) {
	print_r($db_link->errorInfo());
		gmsError( 'ECDBFETCHALL', $e->getMessage(), '', '', '' );
		$row_count = 0;
		return null;
	}	
}

# ==============================================================================================
# dbupdateGMS - Execute the query passed and insert into the table
function dbupdateGMS( $db_link, $insert_query, $insertId, $av1)
{
	# exec for INSERT, UPDATE, DELETE statements. 
	#   
	try {
		$result = $db_link->exec( $insert_query );
		#$insertId = $db_link->lastInsertId();
	} catch (PDOException $e) {
		gmsError( 'ECDBUPDATE', $e->getMessage(), '', '', '' );
		return null;
	}
	return $result;
	#return $insertId;

}

?>