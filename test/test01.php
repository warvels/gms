<html>

<!--    testing Menu includes with PHP -->

<head>
<!--   Load and CSS link file -->
<?php 


$filename = "csslink.php"; 
if (is_readable($filename)) {
  echo " including $filename <br>";
  include($filename); 
  }  
?>
</head>

<body>
<?php 
$filename = "menu.php"; 
if (is_readable($filename)) {
  echo " including $filename <br>";
  include($filename); 
  }  
?>
<?php 
  # DATE  checking and formating
  $br = '<br>';  
  echo "Timezone = ". ini_get('date.timezone') ;
  $currdate = date("Y-m-d H:i:s");
  echo "<br> date time testing <hr>";
  echo 'current date '. $currdate. $br. "<hr>";
  echo "sys date plain -date(Y-m-d H:i:s)  ". date("Y-m-d H:i:s"). $br  ;
  echo "gmdate(Y-m-d H:i:s)  ". gmdate('Y-m-d H:i:s').  $br. $br;

  
  echo $br. "now setting timezone to NY";  
  ini_set('date.timezone', 'America/New_York');
  echo $br;
  echo "sys date plain -date(Y-m-d H:i:s)  ". date("Y-m-d H:i:s"). $br  ;
  echo "gmdate(Y-m-d H:i:s)  ". gmdate('Y-m-d H:i:s').  $br. $br;

 
	if( ini_get('date.timezone') )
	{
	   echo "setting timezone to GMS - with date_default_timezone_set". $br;
	   date_default_timezone_set('GMT');
	} else {
	   echo "Timezone was ARLEADY set ". $br;
	   echo ini_get('date.timezone'). $br;
	}

  echo "sys date plain -date(Y-m-d H:i:s)  ". date("Y-m-d H:i:s"). $br  ;
  echo "gmdate(Y-m-d H:i:s)  ". gmdate('Y-m-d H:i:s').  $br  ;

?>

<!--   Load and display Slidebox file  --->
<?php #$filename = "slidebox.php"; if (is_readable($filename)) { include($filename); }  ?>

</body>
</html>