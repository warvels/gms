<!-- 
 quick select of INPUT - 
-->

<html>
<head>
<link href="css/styleforms.css" rel="stylesheet" type="text/css">
<link href="css/stylegms.css" rel="stylesheet" type="text/css">

<style type="text/css"> 
/* extra styles that we dont have in styleform.css */
body 
{
	background-color: #989F99;  
	
}
</style>
</head>
<body>


<p class="formInfo">

<?php

# required db configuration info
include("config.db.php");
 
function htmlthequery( $queryStatement, $tcols ) {
	# for all rows in the queryStatement - create a table. 
	# cols to display are defined as the $key of passed array $tcols

	$debuggms = 0; 

	# Setup local vars : html table building values
	$dlm = '';
	$tabledef = "<table border='2'>";
	$theaddef = '<thead>';
	$prec = '<td>';  $postc = '</td>';
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
	$debuggms = 0; 
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
		$row = "<tr>". $line. "</tr>";
		$html_table .= $row;
		//echo 'row: '. $row;
	}
	# finish html Table
	$html_table .= "</table>";	
		
	return $html_table;
	# end of function
}
 

 # start main query of INPUT table.
 $br = '<br>';

 $db_table = $table_input;
 $query = 
	'select f.nick, f.fname, f.lname, f.email as fellow_email, i.created_by, i.created_dt, i.email, sa.area, 
	i.suggestion, i.details, i.* from '.
	$table_input. ' i '.
	'join '. $table_subjarea. ' sa on (i.idsubject = sa.idsubjarea) '.
	'join '. $table_fellow. ' f on (i.created_by = f.idfellow)'.
	' where idinput > 3'
	.' order by idinput'
	.';'
	;
 
 # header about database and other stuff for Debugging
 $select_header_page = 'db_header_info.php';
 if (file_exists($select_header_page)) { include $select_header_page ; }
 
    
 # --------------------------------------------------------------------------------------
 # setup database link
 
 # create a PDO database link for the sql db (TODO test for no link)
 $pdo_dblink = new PDO( $pdo_connect, $db_user, $db_pass );

 if ($pdo_dblink) 
 {
	#echo 'Searching all rows in Table '. $db_table . $br;

	# execute SQL query 
	$sql_cmd = $query;    /* passed value for query */
	$queryStatement = $pdo_dblink->query( $sql_cmd );
	if (!$queryStatement) {
	   echo "\nPDO::errorInfo():\n";
	   print_r($pdo_dblink->errorInfo());
	} else {
		# --------------------------------------------------------------------------------------
		# display info about this query
		echo '<h4>'. 'Select of INPUT table with Join to subject area and linked Fellow'. '</h4>'; 
		echo '<p class="formInfo"> ';

	    $inputSelectver = '1.1';
		echo 'Version = '. $inputSelectver. '<a href=./> Return to Previous</a>' . $br;

		echo '<b>'. $query . $br. '</b>'. $br;	
		echo 'Current UTC date : '. gmdate('Y-m-d H:i:s'). $br. '----------------'.$br ;
	
		# Setup array for the Columns to export
		#  Desired column-name from table (index of array) and the display value for table-header (value)
		$tcols = array(
		'idinput' => 'idinput',
		'created_by' => 'created by id',
		'nick' => 'nick',
		'fellow_email' => 'email fellow',
		'created_dt' => 'created_dt',
		'area' => 'area',
		'email' => 'email from submit',
		'suggestion' => 'suggestion',
		'details' => 'details'		
		);
		
		# fetch the rows from the query of table INPUT and join with the SUBJAREA and FELLOW
		$html = htmlthequery( $queryStatement, $tcols );
		echo $html;
	}	
	
	// query statement executed
	

 
 # --------------------------------------------------------------------------------------
 # query just the INPUT table.

	# execute SQL query 
	$query = 'SELECT * FROM ' . $db_table . ' where idinput > 0';
	$sql_cmd = $query;    /* passed value for query */
	$queryStatement = $pdo_dblink->query( $sql_cmd );
	if (!$queryStatement) {
	   echo "\nPDO::errorInfo():\n";
	   print_r($pdo->errorInfo());
	} else {
 
		 echo '<h4>'. 'Select of INPUT table raw'. '</h4>'; 
		 echo '<p class="formInfo"> ';
		 echo $query .$br;

		 echo '   (Current UTC date : '. gmdate('Y-m-d H:i:s'). ')'. $br. '----------------'.$br ;
 
		$tcols = array(
		'suggestion' => 'suggestion',
		'email' => 'email',
		'details' => 'details',
		'idsubject' => 'idsubject',
		'created_by' => 'created by' );
		
		try {
			$html = htmlthequery( $queryStatement, $tcols );
			echo $html;
		} catch(Exception $e) {
			echo 'Exception Message: ' .$e->getMessage();
			# gmsError( 'ECDBHTMLBUILD, $e->getMessage(), '', '', '' );
		}
		

	}	
	
	echo '<hr>';
	
	
	
 } else {
	$pdo_dblink = null;
	// perform ErrorHandler('formerror.html', $errordata)
	echo 'Could not Open database connection ';
 }
	
	
 
# --------------------------------------------------------------------------------------
# close database
if($pdo_dblink) $pdo_dblink = null;
 
?>



</p>
</body>
</html>