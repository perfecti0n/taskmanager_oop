<?php
session_start();
unset($_COOKIE['cookie_id']);
setcookie('cookie_id', null, -1, '/');
session_unset();
header("Location: login-form.php");
?>