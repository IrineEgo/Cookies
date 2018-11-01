<?php
    require_once 'src/core.php';
	
    if (!isAuthorized() && !isQuest()) {
        location('index.php');
    }
	
    if (isset($_POST['upload'])) {
        header('refresh:3; url=list.php');
        $file = $_FILES['testfile'];
        $uploadResult = checkUploadedFile($file);
    }
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>ADMIN панель</title>	
    <style type="text/css">
	  h1 {
	      text-align: center;
	  }
      a {
        color: navy;
        text-decoration: none;
      }
	  fieldset {
	    margin: 40px auto 20px;
		width: 40%;
		background-color: ivory;
	  }
    </style>  	
  </head>
<body>
  <!-- Информация о файле/загрузке/ошибках -->
    <?php 
        if (isset($_POST['upload'])): 
	?>
        <b><a href="<?php $_SERVER['HTTP_REFERER'] ?>">&laquo; НАЗАД</a></b><br><br>
		<p class="<?php echo $uploadResult['classname'] ?>"><?php echo $uploadResult['message']; ?></p><br>
		<h1>Вы будете перенаправлены на страницу с тестами...</h1>
        <?php endif; 
		?>

	<?php if (!isset($_POST['upload'])): 
	?>
        <b><a href="src/logout.php">&laquo; ВОЙТИ ПОД ДРУГИМ ПОЛЬЗОВАТЕЛЕМ</a></b><br><br>
    <!-- Варианты вывода формы -->
	<?php if (isAuthorized()): 
	?>
        <h1>Здравствуйте, <i><?php echo $_SESSION['user']['name'] ?></i>!</h1>
        <form id="load-json" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend><h3>Загрузите файл с тестом (в текстовом формате JSON)</h3></legend>
                <input type="file" name="testfile" id="uploadfile" required>
                <input type="submit" value="Загрузить" id="submit-upload" name="upload"><br><br>
            </fieldset>
        </form>
        <?php endif; 
	    ?>
	
    <?php if (isQuest()): 
	?>
        <h1>Здравствуйте, <i><?php echo $_SESSION['quest']['username'] ?></i>!</h1>
        <?php endif; 
	    ?>
            <div>
                <fieldset>
                    <a href="list.php">ПОСМОТРЕТЬ ТЕСТЫ &raquo;</a>
                </fieldset>
            </div>
    <?php endif; 
    ?>	

  </body>
</html>
