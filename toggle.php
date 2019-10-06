<?php
include_once("db.php");

$userID = $_POST["user"];
$taskID = $_POST["task"];

if ( $userID === NULL || $taskID === NULL) die("Some error occured!");
if ( $userID !== $_SESSION["logged_in"]) die("Please log in first.");

$completed_tasks = $dbconn->query("SELECT Completed_task_IDs FROM `$users_table_name` WHERE ID=$userID")->fetch_all()[0][0];
$num_of_solved = intval($dbconn->query("SELECT Num_of_solved FROM `$users_table_name` WHERE ID=$userID")->fetch_all()[0][0]);

if (strpos($completed_tasks, ":" . $taskID . ":") === false)
{
	$num_of_solved++;
	$dbconn->query("UPDATE `$users_table_name` SET `Completed_task_IDs` = '$completed_tasks$taskID:', `Num_of_solved` = '$num_of_solved' WHERE `users`.`ID` = $userID");
}
else
{
	$num_of_solved--;
	$completed_tasks = str_replace(":" . $taskID . ":", ":", $completed_tasks);
	$dbconn->query("UPDATE `$users_table_name` SET `Completed_task_IDs` = '$completed_tasks', `Num_of_solved` = '$num_of_solved' WHERE `users`.`ID` = $userID");
}
?>