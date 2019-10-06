<?php
include_once("db.php");

$userID = $_GET["user"];
$pwd = $_GET["pwd"];

if ( $userID === NULL || $pwd === NULL) die("Some error occured!");
$password = $dbconn->query("SELECT Password FROM `$users_table_name` WHERE ID=$userID")->fetch_all()[0][0];

if ($password !== $pwd) die("Wrong password!");

$_SESSION["logged_in"] = $userID;
?>