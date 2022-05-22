<?php
session_start();
if(isset($_SESSION["email"])) {
  $email = $_SESSION["email"];
  $userEmailName = substr($email,0,strpos($email,"@"));
  $userName = preg_replace('/[0-9\@\.\;\" "]+/', ' ', 
  $userEmailName);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<header>
  <h1 class="user_name">
    Hi <?=$userName?></h1>
</header>
<div>
<a class="btn_logout" href="./modules/close_session.php">Logout</a>
</div>
</body>
</html>