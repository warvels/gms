<?php
# ==============================================================================================
# \gms\functionsGMS.php
# ==============================================================================================
# some good Error logging
#  http://php.net/manual/en/function.error-log.php
# ==============================================================================================




# ==============================================================================================
function funcjeff($x,$y)
{
	$total=$x+$y;
	return $total;
}
/* 


require_once    and other include info
	http://php.net/manual/en/function.require-once.php
<?php 
define('__ROOT__', dirname(dirname(__FILE__))); 
require_once(__ROOT__.'/config.php); 
	


Searching for values in the database with passed parameters
#$stmt = $db->prepare("SELECT * FROM table WHERE id=? AND name=?");
#$stmt->execute(array($id, $name));
#$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

PDO TUTORIAL for mysql - use these techniques for error handling and select with Binding
	http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers

# exception handler  (for db errors and all errors?)
  http://www.php.net/manual/en/function.set-exception-handler.php
# try catch{} with blocks and error handling
  http://stackoverflow.com/questions/5683452/try-catch-vs-if-else-in-pdo-and-some-other-things
  
# example code for pdo open
$dbConnection = new PDO('mysql:dbname=dbtest;host=127.0.0.1;charset=utf8', 'user', 'pass');
$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

In the above example the error mode isn't strictly necessary, but it is advised to add it. This way the script will not stop with a Fatal Error when something goes wrong. And gives the developer the chance to catch any error(s) (which are throwed as PDOExceptions.
What is mandatory however is the setAttribute() line, which tells PDO to disable emulated prepared statements and use real prepared statements. This makes sure the statement and the values aren't parsed by PHP before sending it the the MySQL server (giving a possible attacker no chance to inject malicious SQL).
Although you can set the charset in the options of the constructor it's important to note that 'older' versions of PHP (< 5.3.6) silently ignored the charset parameter in the DSN.

    try
    {    
        $db = DB::getInstance();
        // --prepare and execute query here, fetch the result--
        return $my_list_of_recent_activities;
    }
    catch (PDOException $e)
    {
        return "some fail-messages";
    }

	
	
# good ARRAY key parsing and other utils (include .csv export  and a table create from array keys
	http://php.net/manual/en/function.array-keys.php	
	
	
###  generic try and catch	
<?php
//create function with an exception
function checkNum($number)
  {
  if($number>1)
    {
    throw new Exception("Value must be 1 or below");
    }
  return true;
  }

//trigger exception in a "try" block
try
  {
  checkNum(2);
  //If the exception is thrown, this text will not be shown
  echo 'If you see this, the number is 1 or below';
  }

//catch exception
catch(Exception $e)
  {
  echo 'Message: ' .$e->getMessage();
  }
?>	
	
	
*/


# ==============================================================================================
# Ver 1 - core database function to Open and Close GMS database
function dbfunc_gms( $curr_db_link, $db_task, $db_conn, $db_name )
{
	include("config.db.php");

	switch ($db_task) {
    case "open":
        #echo " db open command ";
	
		# already have a handle, don't reopen, just return it
		if (isset($curr_db_link) AND ($curr_db_link != NULL) ) {
			printf('  db link is already set and is Not NULL <br>' );
#if ($curr_db_link == NULL) { printf('<br> dblink.is.null <br>'); } else { printf('<br> dblink.is.NOT.null <br>'); }
#echo '<br> dbfunc - vardump of db link <br> ';
#var_dump( $curr_db_link );	
			return $curr_db_link;
		}
		# attempt PDO open to db  
		# db conn info is defined in include file above. must handle when the include file is missing
		if (!isset( $pdo_connect)) {
			return null;
		}
		$pdo_dblink = new PDO( "mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass );

		if ( $pdo_dblink ) {
			return $pdo_dblink;
		} else {
			gmsError( 'db.error.open', 'PDO open '. $db_conn, 1, 'av1', 'av2' );
			$db_link = '';   # may want = null 
			return $db_link;
		}
		
        break;
		
    case "close":
		#  close
        echo "close db";
		$curr_db_link = null;
        break;
	}
}

# ==============================================================================================
# SHOW_KEYS - get all keys from an array. and return in <table> form
function show_keys( $ar )
{
	$ret_table = 1;
   $tabel_list = "<table width='100%' border='1' bordercolor='#6699CC' cellspacing='0' 
   cellpadding='4'><tr valign='top'>";
   $arr_list = '';
    foreach ($ar as $k => $v ) {
		$crow = "<td align='center' bgcolor='#EEEEEE'> <table border='1' cellpadding='3'>
		<tr><td bgcolor='#FFFFFF'><font face='verdana' size='1'>
              " . $k . "
           </font></td></tr></table>";
		$tabel_list .= $crow;
		# recursive call for multi-dimensional arrays.
         if (is_array($ar[$k])) {
              $sub_arr = show_keys ($ar[$k]);
			  # for multi dimension array, each set of arr names is delim with ;
			  if ($sub_arr) {  $arr_list .= ','; }
         }
        $tabel_list .= "</td>";
		$arr_list .= ',';
      }
    $tabel_list .= "</tr></table>";
	#echo $tabel_list;
	if ($ret_table) {
		return $tabel_list;
	} else {
		return $arr_list;
	}
} 

# ==============================================================================================
# QUICKDBLIST - create a list of all rows from passeed $query_result that will perform PDO fetch
function quickdblist( $curr_db_link, $query_result, $av1, $av2 )
{
$i = 0;
$cdlm = ';'; $ldlm = '<br>';
$qlist = '';
#$qlist .= $ldlm;
#$query_result = $pdo_dblink->query( $sql_cmd );
while ( $row = $query_result->fetch(PDO::FETCH_ASSOC) ) {
		$i = $i + 1;
		if ($i == 1) { 
			$qlist .= show_keys( $row ); 
			$qlist .= "<table border='2'>";
		}
		if ( strlen($qlist) > 0 ) { $qlist .= $ldlm; }
		# add current row by implode an array[]
		#print_r(array_keys($row));
		#echo '<br><hr>';
		#var_dump( $row ) ; printf('<br>');
	$cdlm = '</td><td>'; 
	$qlist .= '<tr><td>';
		$qlist .= implode ( $cdlm, $row) ;
	$qlist .= '</tr>';
	}
if (isset($qlist)) {
	$qlist .= '</table>';
}
return $qlist;
}		


# ==============================================================================================
# DBTEST - for Try and Catch exceptions.
function dbtest( $curr_db_link, $db_task )
{
	include("config.db.php");

	
try {
    #$db = new Database();
	$pdo_dblink = new PDO( "mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass );

        if (!$pdo_dblink) { throw new DBEx("Cannot connect to the database"); }
	
		$pdo_dblink->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$pdo_dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


		
	$query_str = 'SELECT i.created_dt, i.* FROM input i where idsubject < 216';
	$query_str = '
select f.nick, f.fname, f.lname, f.email, i.created_by, i.created_dt, 
i.email, sa.area, i.suggestion, i.details,
i.* from input i
join subjarea sa on (i.idsubject = sa.idsubjarea)
join fellow f on (i.created_by = f.idfellow)
order by i.created_dt';

	#echo '<br> query '.$query_str. ' <br>';

		$result = $pdo_dblink->query( $query_str );
		$row_count = $result->rowCount();
		if (!$result) { throw new QUERYEx("Query failed"); }
	
		if ($result) {
			printf('..Query result found '.$row_count.' results.' ); 
			$newhtml = quickdblist( $pdo_dblink, $result, '', ''  );
			echo $newhtml;
		}	else {
			printf('..no result..');
		}
} catch (PDOException $e) {
    echo '<br> GMSerror PDOException : <br> '. $e->getMessage();
} catch (DBEx $e) {
    echo '<br> GMSerror DBEx : <br> '. '$e->customFunction()';
} catch (QUERYEx $e) {
    echo '<br> GMSerror QueryEx : <br> '. '$e->customFunctionForQuery()';
}

}   # end function

# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# ==============================================================================================
# ==============================================================================================

# ==============================================================================================
# GMS error handler
function gmsError( $errorCode, $errorText, $displayFlag, $av1, $av2 )
{
	# display errors? if flag set.
	#echo '<br> GMSerror <br> ';
	$displayFlag = 1;
	if ( $displayFlag ) {
		printf( ' <b>GMS-Error: '. $errorCode. '  '. $errorText. '</b>');
		#printf( ' kk %d', funcjeff(2,3) );
	}
	# Log errors
	return 1;
}

# ==============================================================================================
# Build html <table> out of all rows from the passed database $queryStatment (PDO)
function htmlthequery( $queryStatement, $tcols ) {
	# for all rows in the queryStatement - create a table. 
	# columns of db table to display are defined as the $key of passed array $tcols

	$debuggms = 0; 

	# Setup local vars : html table building values
	$dlm = '';
	$tabledef = "<table width='100%' border='1' bordercolor='#6699CC' cellspacing='0' cellpadding='4'><tr valign='top'>";
	#$tabledef = "<table class='gridtable'>";

	$theaddef = "<thead><font face='verdana' size='1'>";
	$prec = "<td align='center' bgcolor='#EEEEEE'><font face='verdana' size='1'>";  $postc = '</td>';
	$preh = '<th>';  $posth = '</th>';

	#debugging
	if ($debuggms) { echo ';in htmlthequery'; }

	# start Table and add header
	$html_table = $tabledef;
	$theader = $theaddef;
	# create header of Table based on the Value of array $tcols
	foreach ( $tcols as $key => $value) {
		$theader .= $preh. $value. $posth;
	}
	$theader .= '</thead>'; 
	$html_table .= $theader;
	
	# fetch the query data from $queryStatement and build the output of this. Can be html <tr> table rows
	$i = 0;	
	while ( $row = $queryStatement->fetch(PDO::FETCH_ASSOC) ) {
		$i = $i + 1;
		if ($debuggms)  { print_r( $row );   echo '<br><hr>';	}
		# for each colname defined in $tcols array, add the Cell to the table
		$line='';
		foreach ( $tcols as $key => $value ) {
			# table cell <td> $value </td>
			$line .= $prec. $row[$key]. $dlm. $postc;
		}
		#var_dump($row);			 #  will show contents of array $row  can use print_r too.
		# create the Row ofo the html table
		$row = "<tr>". $line. "</tr>";
		$html_table .= $row;
		//echo 'row: '. $row;
	}
	# finish html Table
	$html_table .= "</table>";	
		
	return $html_table;
	# end of function
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
	include("config.db.php");
	
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
#print_r($db_link->errorInfo());
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
	#print_r($db_link->errorInfo());
		gmsError( 'ECDBQUERY', $e->getMessage(), '', '', '' );
		$row_count = 0;
		return null;
	}	
	
	try {
    	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$row_count = $stmt->rowCount();    
		return $result;
	} catch (PDOException $e) {
	#print_r($db_link->errorInfo());
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