<?php
require_once "Classes.php";
session_start();
 if(!isset($_SESSION["sess_user_id"])){
   header("Location: login-form.php");
 } 
 // var_dump($_COOKIE);
$db=new Database($pdo);
$user=new User($db);
$task=new Task($db);
$tasks=$task->get_all();
if (isset($_POST['hiddenTasks'])) {
  $hiddenTasks=$_POST['hiddenTasks'];
}elseif(isset($_SESSION['hiddenTasks'])){
  $hiddenTasks=$_SESSION['hiddenTasks'];
}else{
  $hiddenTasks='off';
}
// var_dump($tasks);
// var_dump($_POST);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Tasks</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>

  <body>

    <header>
      <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 col-md-7 py-4">
              <h4 class="text-white">О проекте</h4>
              <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
            </div>
            <div class="col-sm-4 offset-md-1 py-4">
              <h4 class="text-white"><?php echo $user->get_email(); ?></h4>
              <ul class="list-unstyled">
                <li><a href="logout.php" class="text-white">Выйти</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container d-flex justify-content-between">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
            <strong>Tasks</strong>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </div>
    </header>

    <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Проект Task-manager</h1>
          <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don't simply skip over it entirely.</p>
          <p>
            <a href="create-form.php" class="btn btn-primary my-2">Добавить запись</a>
          </p>
            <form action="list.php" method="POST">
              <input type="hidden" name="hiddenTasks" value="<?php if($hiddenTasks=='off'){ echo "on";$_SESSION['hiddenTasks']='off';}if($hiddenTasks=='on'){echo "off";$_SESSION['hiddenTasks']='on';}?>">
              <button type="submit">
              <?php
              if($hiddenTasks=='off'){ echo "Включить отображение скрытых задач"; }
              if($hiddenTasks=='on'){ echo "Выключить отображение скрытых задач"; }
              ?>
              </button>
            </form>
        </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row">
          <!--  -->
          <?php foreach ($tasks as $one_task) {
            if($hiddenTasks=='off' AND $one_task['task_isHidden']=='yes'){continue;}
            $name=$one_task['task_name'];
            $img=$one_task['task_img'];
            $task_id=$one_task['task_id'];
            ?>
             <div class="col-md-4">
              <div class="card mb-4 shadow-sm">
                <img class="card-img-top" src="<?php echo $img ?>">
                <div class="card-body">
                  <p class="card-text"><?php echo $name ?></p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group">
                      <a href="show.php?task_id=<?php echo $task_id; ?>" class="btn btn-sm btn-outline-secondary">Подробнее</a>
                      <a href="edit-form.php?task_id=<?php echo $task_id; ?>" class="btn btn-sm btn-outline-secondary">Изменить</a>
                      <a href="delete.php?task_id=<?php echo $task_id; ?>" class="btn btn-sm btn-outline-secondary" onclick="confirm('Are you sure?')">Удалить</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <!--  -->
        <?php } ?>
          </div>
        </div>
      </div>

    </main>

    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Наверх</a>
        </p>
        <p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
        <p>New to Bootstrap? <a href="../../">Visit the homepage</a> or read our <a href="../../getting-started/">getting started guide</a>.</p>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
<?php

?>
