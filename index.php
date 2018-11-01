<?php
    require_once 'src/core.php';
	
    if (isset($_COOKIE['ban'])) {
        location('ban.php');
    }
	
    if (isAuthorized() || isQuest()) {
        location('admin.php');
    }
	
    if (!isset($_SESSION['incorrect'])) $_SESSION['incorrect'] = 0;
        $errors = [];
    if ($_SESSION['incorrect'] < 6) {
        if (!empty($_POST['userLogin'])) {
            if (login($_POST['login'], $_POST['password'])) {
                location('admin.php');
            } 
		        else {
                    $errors[] = 'Неверный логин или пароль';
                    $_SESSION['incorrect']++;
                }
        }
    } 
        else if ($_SESSION['incorrect'] < 11) {
            if (!empty($_POST['userLoginCaptcha'])) {
                if (login($_POST['login'], $_POST['password']) && checkCaptcha($_POST['captcha'], $_SESSION['captcha'])) {
                    location('admin.php');
                }
                    if (!login($_POST['login'], $_POST['password']) || !checkCaptcha($_POST['captcha'], $_SESSION['captcha'])) {
                        $_SESSION['incorrect']++;
                    if (!login($_POST['login'], $_POST['password'])) {
                        $errors[] = 'Неверный логин или пароль!';
                    }
                    if (!checkCaptcha($_POST['captcha'], $_SESSION['captcha'])) {
                        $errors[] = 'Неверно распознана каптча!';
                    }
                }
            }
        }
		
    if ($_SESSION['incorrect'] === 11) {
        banUser();
        location('ban.php');
    }
    if (isset($_POST['guestLogin'])) {
        $_SESSION['quest']['username'] = str_replace(' ', '', $_POST['username']);
        location('admin.php');
    }
    //echo $_SESSION['incorrect'];
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Вход в панель управления</title>
    <style type="text/css">
      legend {
        font-size: 2rem;
        font-weight: bold;
		margin: 20px 10px;
      }
	  fieldset {
	    margin: 40px auto 20px;
		width: 30%;
		background-color: whitesmoke;
	  }
    </style>    
  </head>
  <body>
    <fieldset>
      <legend>Введите логин и пароль</legend>
        <form method="POST">
          <label>Логин:&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="login" id="login"></label><br><br>
          <label>Пароль:&nbsp;&nbsp;<input type="text" name="password" id="password"></label><br>
              <?php if ($_SESSION['incorrect'] < 6): 
			  ?>
                  <br><br><input type="submit" value="ВОЙТИ" name="userLogin">
                  <?php endif; 
			      ?>
              <?php if ($_SESSION['incorrect'] >= 6): 
			  ?>
			  <br><br>
                  Введите каптчу с картинки:<br><br>
                  <img src="src/captcha.php" alt="Каптча"><br><br>
                  <input type="text" name="captcha" <!--required--> <br><br>
                  <input type="submit" value="ВОЙТИ" name="userLoginCaptcha">
                  <?php endif; 
				  ?>
        </form>
    </fieldset>

    <fieldset>
      <legend>Гостевой вход</legend>
        <form method="POST">
          <label>Введите ваше имя:&nbsp;&nbsp; <input type="text" name="username" id="username" required></label><br><br>
          <input type="submit" name="guestLogin" value="ВОЙТИ">
        </form>
    </fieldset>
  </body>
</html>
