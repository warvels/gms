<?php
 # config.db.active.php  - GMS db configuration file
 
# gms web Server - uses Localhost
# $db_host = "localhost";$db_user = "insider";$db_pass = "I*UJKO(8ujko9i";$db_name = "gms2";  
 

# Jeff testing WAMP localhost 
 $db_host = "localhost";$db_user = "jeff";$db_pass = "marmot";$db_name = "gms2";  
 
 # Tables
 $table_fellow = 'fellow';
 $table_input = 'input';
 $table_subjarea = 'subjarea';
  
 # connection string for PDO objects to mysql db
 $pdo_connect = "mysql:host=$db_host;dbname=$db_name";
 
 # debugging
 # echo '<br><b>Found config.db.active.php</b> ('. $pdo_connect.') <br>';
 ?>