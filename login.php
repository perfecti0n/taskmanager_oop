<?php
require_once "Classes.php";
$db=new Database($pdo);
$user=new User($db);
$user->authorization();
?>