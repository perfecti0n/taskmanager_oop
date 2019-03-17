<?php
require_once "Classes.php";
session_start();
 if(!isset($_SESSION["sess_user_id"])){
   header("Location: login-form.php");
 } 
 ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Create Task</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>
  <body>  
    <div class="form-wrapper text-center">
      <form class="form-signin" action="create.php" method="POST" enctype="multipart/form-data">
        <img class="mb-4" src="assets/img/bootstrap-solid.svg" alt="" width="72" height="72" >
        <h1 class="h3 mb-3 font-weight-normal">Добавить запись</h1>
        <label for="inputEmail" class="sr-only">Название</label>
        <input type="text" id="inputEmail" class="form-control" placeholder="Название" required name="name">
        <label for="inputEmail" class="sr-only">Описание</label>
        <textarea name="text" class="form-control" cols="30" rows="10" placeholder="Описание"></textarea>
        <input type="file" name="img">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="customSwitch1" name="ishidden">
          <label class="custom-control-label" for="customSwitch1">Включите чтобы сдeлать запись скрытой</label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Отправить</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2018-2019</p>
    <a href="list.php">Вернуться в список задач</a>
      </form>
    </div>
  </body>
</html>
