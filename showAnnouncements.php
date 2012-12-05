<!-- 
 View all Announcements / rostrum Submitted Items 
   showAnnouncements.php
  
-->

<html>
<head>
</head>

<body>

<?php

#-----------------------------------------------------------------------------------------
# php to open the database, select the input file with join and then display in html table.

$br = '<br>';
$debuggms = 0;

# required db configuration info
require_once("config.db.active.php");
 
$gmsfunctions_page  = 'functionsGMS.php';

if (!file_exists( $gmsfunctions_page )) {
	printf("Severe Error including GMS php Functions - <A HREF='javascript:history.back()'>Return</A> ");
	# cannot log using gmserror() is it's part of above functions.
} else {

    #----------------------------- Include the functions php file
	if ($debuggms)  { printf( 'functions php found : '. $gmsfunctions_page );   echo '<br><hr>'; }
	require_once($gmsfunctions_page);

	# include PHP header about database and other stuff for Debugging
	$select_header_page = 'db_header_info.php';
	#if (file_exists($select_header_page)) { include $select_header_page ; }


    #----------------------------- db open and query using try, catch
	$db_link = null;
	$db_link  = dbopenGMS( $db_link, 'open' );
	if ($db_link) {
		# db connection successful, 
		# setup query of INPUT table.
		$db_table = $table_announcements;
		$queryStatement = 'select * from '. $db_table. ' a '. ' where idrostrum > 0'. ' order by a.created_on desc ;';

		# Setup array for the Columns to export for DB table selected.
		#  Desired column-name from table (index of array) and the display value for table-header (value)
		$tcols = array(
		'created_on' => 'Date',
		'created_by' => 'User ID',
		'our_text' => 'Announcement'
		);
		
		# test then execute the query.
		$rowcnt = '';
		$qresult = dbqueryGMS( $db_link, $queryStatement, $rowcnt, '' );
		#var_dump( $qresult );	var_dump( $rowcnt );
		
		if ($qresult) {
			if ($rowcnt == 1)  {
				echo '<br> No Annoucements<br>';
			} else {
				# --------------------------------------------------------------------------------------
				#echo '<p class="formInfo"> ';
				#echo " <A HREF='javascript:history.back()'>Return to Global Mind Share</A> ";
				#echo ' -- Results Generated on : '. gmdate('Y-m-d H:i:s'). ' UTC '. $br  ;
							
				# fetch the rows from the query of db table. this has been turned into html table
#				$html = htmlthequery( $qresult, $tcols );
#				echo $html;

				# fetch the query data from $queryStatement and build the output of this. Can be html <tr> table rows
				$html = '<ul style = "padding-left:8%;">';
				$i = 0;	
				while ( $row = $qresult->fetch(PDO::FETCH_ASSOC) ) {
					$i = $i + 1;
					if ($debuggms)  { print_r( $row );   echo '<br><hr>';	}
					# add the date and text of announcement
					$line =  '<b>'. date("Y-m-d", strtotime($row['created_on']) ). '</b></font>';
					$line .= '<font color="white" size="2"> - '. $row['our_text'];
					$line .= '</font>';
					$row = '<li><font color="#0B75AF">'. $line. "</li>";
					$html .= $row;
					//echo 'row: '. $row;
				}
				# finish html UL
				$html .= "</ul>";
				echo $html ;
				
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

<!-- #<A HREF='javascript:history.back()'>Return to Global Mind Share</A> -->

</body>
</html>