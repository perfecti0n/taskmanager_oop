<?php
require_once "Classes.php";
session_start();
$db=new Database($pdo);
$task=new Task($db);
$task->create();
?>