<?php 
require_once "config.php";
if(isset($_SESSION["sess_user_id"])){
  header("Location: list.php");
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="form-wrapper text-center">
      <form class="form-signin" action="register.php" method="POST">
        <img class="mb-4" src="assets/img/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Регистрация</h1>
        <label for="inputEmail" class="sr-only">Имя</label>
        <input type="text" id="inputEmail" class="form-control" placeholder="Имя" autofocus name="name">
        <label for="inputEmail" class="sr-only">Email</label>
        <input type="email" id="inputEmail" class="form-control" placeholder="Email" required name="email" >
        <label for="inputPassword" class="sr-only">Пароль</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required name="pswd">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Зарегистрироваться</button>
        <a href="login-form.php">Войти</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2018-2019</p>
      </form>
    </div>
  </body>
</html>