<?php
    require_once 'src/core.php';
	
    if (!isAuthorized() && !isQuest()) {
        location('admin.php');
    }
	
    $allFiles = glob('tests/*.json');
    if (!empty($_POST['path'])) {
        $allFiles = delTest($allFiles);
        header('refresh: 0');
    }
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Список тестов</title> 
	  <style type="text/css">
	    h1 {
	      text-align: center;
	    }	  
        a {
          color: navy;
          text-decoration: none;
        }
		.file-block {
	      margin: 40px auto 20px;
		  width: 40%;
		  border-bottom: 1px solid navy;
        }
      </style>  
  </head>
  <body>
    <b><a href="admin.php">&laquo; НАЗАД</a></b><br><br>

    <h1>Список тестов:</h1>
    <?php if (!empty($allFiles)): 
	?>
        <?php dispayAllTests($allFiles); 
		?>
            <?php else: 
			?>
                <?php echo 'Нет ни одного теста!'; 
				?>
        <?php endif; 
	    ?>
  </body>
</html>
