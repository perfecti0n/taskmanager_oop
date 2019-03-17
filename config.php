<?php
$pdo=new PDO("mysql:host=localhost;dbname=task_manager",'root','');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
function showError($msg){
	$errMsg=$msg;
	include "errors.php";
	exit();
}
function empty_validation(array $data){
	foreach ($data as $value) {
		if(empty($value)){
			showError("Заполните все поля");
		}
	}
}
?>