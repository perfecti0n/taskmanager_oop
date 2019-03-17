<?php
require_once "Classes.php";
session_start();
if(!isset($_SESSION["sess_user_id"])){
  header("Location: login-form.php");
} 
$db=new Database($pdo);
$task=new Task($db);
$task->edit();
?>