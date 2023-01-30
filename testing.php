Connection Testing<br/>
<?php  
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$dbconn = pg_connect("host=35.187.148.36 user=readonly password=Hcurvereadonly dbname=events sslmode=disable");
echo "dbconn is".$dbconn."<br/>";
//connect to a database named "postgres" on the host "host" with a username and password  
if (!$dbconn){  
  echo "<center><h1>Doesn't work =(</h1></center>";  
} else {
  echo "<center><h1>Good connection</h1></center>";     
  pg_close($dbconn);
}

?>  






