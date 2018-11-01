<?php
    require_once 'src/core.php';
	
    if (!isAuthorized() && !isQuest()) {
        location('admin.php');
    }
	
    $allTests = glob('tests/*.json');
    $testNumber = $_GET['number'];
    $requiredTest = $allTests[$testNumber];
	
    if (!isset($requiredTest) || !isset($testNumber)) {
        header($_SERVER['SERVER_PROTOCOL'] . '404 Not Found');
        exit;
    }
	
    $test = json_decode(file_get_contents($requiredTest), true);

    if (isset($_POST['check-test'])) {
        $testname = basename($requiredTest);
        if (isAuthorized()) {
            $username = $_SESSION['user']['name'];
        }
        if (isQuest()) {
            $username = $_SESSION['quest']['username'];
        }
            $date = date("d-m-Y H:i");
            $correctAnswers = answersCounter($test)['correct'];
            $totalAnswers = answersCounter($test)['total'];
            $variables = [
            'testname' => $testname,
            'username' => $username,
            'date' => $date,
            'correctAnswers' => $correctAnswers,
            'totalAnswers' => $totalAnswers
            ];
    }
	
    if (isset($_POST['generate-picture'])) {
        include_once 'src/create-picture.php';
    }
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>ТЕСТ</title>
      <style type="text/css">	
	    h1, h2 {
	      text-align: center;
		  color: navy;
	    }
		a {
          color: navy;
          text-decoration: none;
        }
		.result {
	      margin: 40px auto 20px;
		  width: 40%;
        }
	    .correct {
          background-color: palegreen;
          padding: 20px;
        }
        .incorrect {
          background-color: pink;
          padding: 20px;
        }
        #hidden-radio {
          position: absolute;
          left: 10%;
          top: 30%;
          opacity: 0;
        }	
    </style>    
  </head>
  <body>
    <a href="<?php echo isset($_POST['check-test']) ? $_SERVER['HTTP_REFERER'] : 'list.php' ?>"><b>&laquo; НАЗАД</b></a><br>
    <div class="result">
    <?php if (isset($_GET['number']) && !isset($_POST['check-test'])): 
	?>
        <form method="POST">
        <h1><?php echo basename($requiredTest); ?></h1>
            <?php foreach ($test as $key => $item): 
			?>
            <fieldset>
                <input type="radio" name="answer<?php echo $key ?>" id="hidden-radio" required>
                <legend><b><?php echo $item['question'] ?></b></legend>
                <label><input type="radio" name="answer<?php echo $key ?>" value="0"><?php echo $item['answers'][0] ?>
                </label><br>
                <label><input type="radio" name="answer<?php echo $key ?>" value="1"><?php echo $item['answers'][1] ?>
                </label><br>
                <label><input type="radio" name="answer<?php echo $key ?>" value="2"><?php echo $item['answers'][2] ?>
                </label><br>
                <label><input type="radio" name="answer<?php echo $key ?>" value="3"><?php echo $item['answers'][3] ?>
                </label>				
            </fieldset>
			<br><br>
                <?php endforeach; 
				?>
                <input type="submit" name="check-test" value="Результат">
        </form>
    <?php endif; 
	?>

    <?php if (isset($_POST['check-test'])): 
	?>
    	<h1>Ваши ответы:</h1>
        <?php checkTest($test) 
		?>
        <br><h4>Всего правильных ответов: <?php echo "$correctAnswers из $totalAnswers" ?></h4>
        <h2>Вы можете сгенерировать сертификат, <i><?php echo $username ?></i>  &darr; </h2>
            <form method="POST">
                <input type="submit" name="generate-picture" value="Сгенерировать">
                <?php foreach ($variables as $key => $variable): 
				?>
                <input type="hidden" value="<?php echo $variable ?>" name="<?php echo $key ?>">
                <?php endforeach; 
				?>
            </form>

        <?php endif; 
		?>
	  </div>	
  </body>
</html>
