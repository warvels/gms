<?php

# ==============================================================================================
# /gms/api/functionsGMS.php
# ==============================================================================================
# Revisions
#  Ver 0.3 	2012/11/27	Initial GET/POST functions
#  Ver 0.4 	2012/12/08	Database functions moved to here, Added DB function "findSubjectArea()" to find a SUBJAREA based on passed string
# ==============================================================================================



/* 
 ----------------------------------------------------------------------
 ERROR AND LOGGING FUNCTIONS
 ----------------------------------------------------------------------
*/

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
	$logdata = gmdate('Y-m-d H:i:s'). ' UTC '. $dlm. $errorCode. $dlm. $errorText. $dlm. "\r\n";
	error_log($logdata, 3, $log_file);
	return 1;
}

function gmsLog( $logData, $newFile, $av1, $av2 )
{
	# 
	$log_file = 'logs/gmslog.log';
	$dlm = ';';
	# create an entry with date.time, errorcode, error text. to get NEWLINE must put \r\n in double quotes.
	$logdata = gmdate('Y-m-d H:i:s'). ' UTC '. $dlm. $logData. "\r\n";
	error_log($logdata, 3, $log_file);
	return 1;
}

# ==============================================================================================
# GMS OS level file writer

function gmsfwrite( $filename, $content )
{
// Let's make sure the file exists and is writable first.
if (is_writable($filename)) {

    // In our example we're opening $filename in append mode.
    // The file pointer is at the bottom of the file hence
    // that's where $somecontent will go when we fwrite() it.
    if (!$handle = fopen($filename, 'a')) {
         #   set error stateecho 
		 $error ="Cannot open file ($filename)";
         return 0;
    }

	// bindary :   fwrite($fh,utf8_encode($content)); 
	// fwrite($fp, pack('L',$content) );
	
	
    // Write $somecontent to our opened file.
    if (fwrite($handle, $content) === FALSE) {
        #   set error state 
		$error = "Cannot write to file ($filename)";
        return 0;
    }

    # Success, wrote $content) to file ($filename)";
    fclose($handle);

} else {
    $error = "The file $filename is not writable";
	return 0;
}

}


/* 
 ----------------------------------------------------------------------
 CORE DATABASE  functions
 ----------------------------------------------------------------------
*/


/**  
 * Database OPEN based on gms config file 
 * @param 
 * @return $dbh     PDO database handle
 * @throws
*/
function getConnection() {
	require("config.db.php");
	$dbh = new PDO($pdo_connect, $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}



/**  
 * Database FIND SUBJAREA table from passed string to match 'AREA' column
 * @param : $subject_search = string to search for 
 * @return : ID of subjarea row found if any
 * @throws
*/
function findSubjectArea($subject_search) { 
	#---- Calling example : 
	#$y='education';
	#$x = 'search for sa = '.$y.' = '.findSubjectArea($y);
	#gmslog( $x, '', '', '');
	#echo $x;

	$lower_subject = trim( strtolower( $subject_search) );  #printf('subjectarea = '.$lower_subject);
	#debug var_dump( $lower_subject );	
	$db_link = getConnection();    
	if ($db_link) {
		# query DB for ID of the selected subject area text
		$query = "select sa.idsubjarea, sa.* from subjarea sa where lower(area) = '". $lower_subject. "'" ;
		$qresult = dbqueryfetchallGMS( $db_link, $query, '', '' );
		#var_dump( $qresult ); #print_r($qresult);
		if ($qresult) {
			# From the resutling rows of the query, extract the First row's [0] IDsubjarea (fieldname from db)
			$subject_id = $qresult[0]['idsubjarea'];			
			if ($subject_id) {
				return $subject_id;
			}
		}
	}
	return null;
}


# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# EARLY FUNCTIONS

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