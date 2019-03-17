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

    <title>Show</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="form-wrapper text-center">
      <img src="<?php echo $img; ?>" alt="" width="400">
      <h2><?php echo $name; ?></h2>
      <p>
        <?php echo htmlentities($text); ?>
      </p>
      <a href="list.php">Список задач</a>
    </div>
  </body>
</html>
