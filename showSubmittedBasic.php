<!-- 
 View Submitted Items - showSubmittedBasic.php
  Early version that just shows the INPUT table sorted by subarea
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


<h3>
<center>Listing of all Submitted Problems. </center>
</h3>


<p class="formInfo">


<?php

#-----------------------------------------------------------------------------------------
# php to open the database, select the input file with join and then display in html table.

$br = '<br>';
$debuggms = 0;

# required db configuration info
require_once("config.db.php");
 
$gmsfunctions_page  = 'functionsGMS.php';

if (!file_exists( $gmsfunctions_page )) {
	printf("Severe Error including GMS php Functions - <A HREF='javascript:history.back()'>Return</A> ");
	# cannot log using gmserror() is it's part of above functions.
} else {

    #----------------------------- Include the functions php file
	if ($debuggms)  { printf( 'functions php found : '. $gmsfunctions_page );   echo '<br><hr>'; }
	require_once($gmsfunctions_page);

	echo '<hr>';
	# include PHP header about database and other stuff for Debugging
	$select_header_page = 'db_header_info.php';
	#if (file_exists($select_header_page)) { include $select_header_page ; }


    #----------------------------- db open and query using try, catch
	$db_link = null;
	$db_link  = dbopenGMS( $db_link, 'open' );
	if ($db_link) {
		# db connection successful, 
		# setup query of INPUT table.
		$db_table = $table_input;
		$query = 
		'select f.nick, f.fname, f.lname, f.email as fellow_email, i.created_by, i.created_dt, i.email, sa.area, 
		i.suggestion, i.details, i.liked, i.disliked, i.* from '.
		$table_input. ' i '.
		'join '. $table_subjarea. ' sa on (i.idsubject = sa.idsubjarea) '.
		'join '. $table_fellow. ' f on (i.created_by = f.idfellow)'.
		' where idinput > 0'
		.' order by sa.area, i.created_dt '
		.';'
		;

		# Setup array for the Columns to export for DB table selected.
		#  Desired column-name from table (index of array) and the display value for table-header (value)
		$tcols = array(
#		'nick' => 'nick',
#		'fellow_email' => 'email fellow',
		'area' => 'Subject Srea',
		'created_dt' => 'Date Entered',
		'email' => 'email',
		'suggestion' => 'Summary',
		'details' => 'Problem Statement',
		'liked' => 'liked',		
		'disliked' => 'disliked'		
		);
		
		# test then execute the query.
		$rowcnt = '';
		$qresult = dbqueryGMS( $db_link, $query, $rowcnt, '' );
		#var_dump( $qresult );	var_dump( $rowcnt );
		
		if ($qresult) {
			if ($rowcnt == 1)  {
				echo '<br> No items have been submtted.<br>';
			} else {
				# --------------------------------------------------------------------------------------
				# display info about this query
				echo '<p class="formInfo"> ';
				#echo '<b>';	

				# ----------
				# show the query string
				#echo $query. $br;
				
				#   cannot return param from a function.   
				# echo $br. " rowcnt = ". $rowcnt. $br;
				#echo 'Current date : '. date("Y-m-d H:i:s"). $br  ;
				echo " <A HREF='javascript:history.back()'>Return to Global Mind Share</A> ";
				echo ' -- Results Generated on : '. gmdate('Y-m-d H:i:s'). ' UTC '. $br  ;
				#echo '';	
							
				# fetch the rows from the query of db table. this has been turned into html table
				$html = htmlthequery( $qresult, $tcols );
				echo $html;
			}
		}  # if query result	 

		# --------------------------------------------------------------------------------------
		# close database
		if (isset($db_link)) { $db_link = null; }
		
	} else {
		# no db open - 
		$db_link = null;
		// perform ErrorHandler('formerror.html', $errordata)
		echo 'Could not Open database connection ';
	}

} # if include of functionsGMS.php
	
 

?>
</p>
<A HREF='javascript:history.back()'>Return to Global Mind Share</A> 
</body>
</html>