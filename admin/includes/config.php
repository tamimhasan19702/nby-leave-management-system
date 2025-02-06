<?php
/**
 * Database configuration file.
 *
 * This file contains the database connection settings.
 *
 * @package ELMS
 * @author Md. Babul <bablu.pm@gmail.com>
 * @since 1.0.0
 */
// DB credentials.
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','nbyspbnz_elmsdb');

// Establish database connection.
try
{
    $dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
    exit("Error: " . $e->getMessage());
}