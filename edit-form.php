<?php
require_once "Classes.php";
session_start();
if(!isset($_SESSION["sess_user_id"])){
  header("Location: login-form.php");
} 
$db=new Database($pdo);
$task=new Task($db);
$currentTask=$task->get_one();
if ($currentTask['user_id']!=$_SESSION['sess_user_id']) {
  showError("Эта запись не принадлежит вам <br> <a href=\"list.php\">Вернуться в список задач</a> <!-- ");
}
$name=$currentTask['task_name'];
$img=$currentTask['task_img'];
$text=$currentTask['task_description'];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Edit Task</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="form-wrapper text-center">
      <form class="form-signin" action="edit.php" method="POST" enctype="multipart/form-data" >
        <img class="mb-4" src="assets/img/bootstrap-solid.svg" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Изменить запись</h1>
        <label for="inputEmail" class="sr-only">Название</label>
        <input type="text" id="inputEmail" class="form-control" name="name"placeholder="" required value="<?php echo $name; ?>">
        <label for="inputEmail" class="sr-only">Описание</label>
        <textarea name="text" class="form-control" cols="30" rows="10" placeholder=""><?php echo $text; ?></textarea>
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="customSwitch2" name="isEdittingPhoto">
          <label class="custom-control-label" for="customSwitch2">Изменить картинку</label>
        </div>
        <input type="file" name="img">
        Текущая картинка:
        <img src="<?php echo $img; ?>" alt="" width="300" class="mb-3">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="customSwitch1" name="ishidden" >
          <label class="custom-control-label" for="customSwitch1">Включите чтобы сдeлать запись скрытой</label>
        </div>
        <input type="hidden" name="id" value="<?php echo $_GET['task_id']; ?>">
        <input type="hidden" name="img" value="<?php echo $img; ?>">
        <button class="btn btn-lg btn-success btn-block" type="submit">Редактировать</button>
        <a href="list.php">Вернуться в список задач</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2018-2019</p>
      </form>
    </div>
  </body>
</html>
