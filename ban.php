<?php
    require_once 'src/core.php';
    if (!isset($_COOKIE['ban'])) {
        location('index.php');
    }
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Доступ временно запрещен</title>
	<style>
	  h1 {
	  color: red;
	  text-align: center;
	  font-weight: bold;
	  border: 1px solid red;
	  margin-top: 20%;
	  }
	</style>
  </head>
  <body>
    <h1>Подождите часок, мы Вас временно заблокировали, а затем попробуйте снова!</h1>
  </body>
</html>
