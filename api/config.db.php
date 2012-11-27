<?php
 # config.db.php  - GMS db configuration file
 
 # Jeff testing WAMP localhost 
 $dbhost = "localhost"; $dbuser = "root"; $dbpass = ""; $dbname = "gms2";  
 
 # Tables
 $table_fellow = 'fellow';
 $table_input = 'input';
 $table_subjarea = 'subjarea';
 $table_announcements = 'rostrum';
  
 # connection string for PDO objects to mysql db
 $pdo_connect = "mysql:host=$dbhost;dbname=$dbname";
 
 # debugging
 # echo '<br><b>Found config.db.active.php</b> ('. $pdo_connect.') <br>';
 ?>