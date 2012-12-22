<?php
 # config.db.php  - GMS db configuration file
 
 # Jeff testing WAMP localhost 
 $db_host = "localhost"; $db_user = "root"; $db_pass = ""; $db_name = "gms2";  
 
 # Tables
 $table_fellow = 'fellow';
 $table_input = 'input';
 $table_subjarea = 'subjarea';
 $table_announcements = 'rostrum';
  
 # connection string for PDO objects to mysql db
 $pdo_connect = "mysql:host=$db_host;dbname=$db_name";
 
 # debugging
 # echo '<br><b>Found config.db.php</b> ('. $pdo_connect.') <br>';
 ?>