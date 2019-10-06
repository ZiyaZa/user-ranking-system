<?php
if (session_status() === PHP_SESSION_NONE)
{
	session_start();
}

// Change these variables according to your configuration
$dbserver = "localhost";
$dbusername = "root";
$dbpassword = "passw0rd";
$dbname = "camp";
$users_table_name = "users";
$tasks_table_name = "tasks";

// Connecting to server
$dbconn = new mysqli( $dbserver, $dbusername, $dbpassword);
if ($dbconn->connect_error)
{
	die( "Connection to database server failed: " . $dbconn->connect_error);
}

// Creating db if it does not exist
$dbs = $dbconn->query("SHOW DATABASES LIKE '$dbname'")->fetch_all();
if (count($dbs) === 0)
{
	$dbconn->query("CREATE DATABASE `$dbname` /*!40100 DEFAULT CHARACTER SET latin1 */");
}
$dbconn->close();

// Connecting to database
$dbconn = new mysqli( $dbserver, $dbusername, $dbpassword, $dbname);
if ($dbconn->connect_error)
{
	die( "Connection to database failed: " . $dbconn->connect_error);
}

// Creating tables if they do not exist
$tables = $dbconn->query("SHOW TABLES LIKE '$tasks_table_name'")->fetch_all();
if (count($tables) === 0)
{
	$dbconn->query("CREATE TABLE `$tasks_table_name` (
					 `ID` int(11) NOT NULL AUTO_INCREMENT,
					 `Chapter` int(11) NOT NULL,
					 `Section` int(11) NOT NULL,
					 `Name` text NOT NULL,
					 UNIQUE KEY `ID` (`ID`)
					) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1");
}
$tables = $dbconn->query("SHOW TABLES LIKE '$users_table_name'")->fetch_all();
if (count($tables) === 0)
{
	$dbconn->query("CREATE TABLE `$users_table_name` (
					 `ID` int(11) NOT NULL AUTO_INCREMENT,
					 `Name` text NOT NULL,
					 `Surname` text NOT NULL,
					 `Password` text NOT NULL,
					 `Completed_task_IDs` text NOT NULL DEFAULT ':',
					 `Num_of_solved` int(11) NOT NULL DEFAULT 0,
					 `Added_from` text NOT NULL,
					 `Added_ts` timestamp NOT NULL DEFAULT current_timestamp(),
					 PRIMARY KEY (`ID`),
					 UNIQUE KEY `ID` (`ID`)
					) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1");
}
?>