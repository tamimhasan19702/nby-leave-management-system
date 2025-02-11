<?php 
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','nbyspbnz_elmsdb');


// define('DB_HOST','https://premium34.web-hosting.com');
// define('DB_USER','nbyspbnz_hrm');
// define('DB_PASS','w;&aI2!Mqs2&');
// define('DB_NAME','nbyspbnz_elmsdb');

// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>