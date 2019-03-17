<?php require_once "config.php"; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Errors</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
      
    </style>
  </head>

  <body>
    <div class="container text-center mt-5">
      <?php 
      if (isset($errMsg)) {
        echo $errMsg."<br>";
      }
      else{
      ?>
      <p>Произошла ошибка.</p>
    <?php } ?>
      <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Назад</a>
    </div>
  </body>
</html>