<?php
include_once("db.php");

$name = $_POST["name"];
$surname = $_POST["surname"];
$pwd = $_POST["pwd"];
$ip = $_SERVER['REMOTE_ADDR'];

$result = $dbconn->query("SELECT TIMESTAMPDIFF(DAY, Added_ts, CURRENT_TIMESTAMP) FROM `$users_table_name` WHERE Added_from=\"$ip\" ORDER BY Added_ts DESC");
if ($result !== false)
{
	$diff = $result->fetch_all();
	if (count($diff) > 0 && $diff[0][0] === "0")
	{
		die ("You have added a new user recently. Please try again later.");
	}
}

if ( $name === NULL || $surname === NULL || $pwd === NULL) die("Some error occured!");
$result = $dbconn->query("INSERT INTO `$users_table_name`(`Name`, `Surname`, `Password`, `Added_from`) VALUES (\"$name\", \"$surname\", \"$pwd\", \"$ip\")");
if ($result === false) die ("Some error occured!");

$_SESSION["logged_in"] = $dbconn->insert_id . "";
echo "Now you can toggle the status of a problem by clicking on the corresponding cell.";
?>