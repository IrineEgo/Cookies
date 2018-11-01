<?php
    function checkTest($testFile)
    {
        // Проверка ответов на ВСЕ вопросы
        foreach ($testFile as $key => $item) {
            if (!isset($_POST['answer' . $key])) {
                echo 'Должны быть решены все задания!';
                die;
            }
        }
        // Проверка теста
        foreach ($testFile as $key => $item) {
            if ($item['correct_answer'] === $_POST['answer' . $key]) {
               $infoStyle = 'correct';
            } 
		    else {
                $infoStyle = 'incorrect';
            }
            echo '<div class=' . $infoStyle . '>' .
            'Вопрос: ' . $item['question'] . '<br>' .
            'Ваш ответ: ' . $item['answers'][$_POST['answer' . $key]] . '<br>' .
            'Правильный ответ: ' . $item['answers'][$item['correct_answer']] . '<br>' .
            '</div>' .
            '<hr>';
        }
    }
    
    function answersCounter($testFile)
    {
        $i = 0;
        $questions = 0;
        foreach ($testFile as $key => $item) {
            $questions++;
            if ($item['correct_answer'] === $_POST['answer' . $key]) {
                $i++;
            }
        }
        return ['correct' => $i, 'total' => $questions];
    }
    //Загрузка файлов и их проверка
    function checkUploadedFile($file)
    {
        $uploadfile = 'tests/' . basename($file['name']);
        $allFiles = !empty(glob('tests/*.json')) ? glob('tests/*.json') : $allFiles = [];
        if (pathinfo($file['name'], PATHINFO_EXTENSION) !== 'json') {
            return [
                'classname' => 'error',
                'message' => 'Можно загружать файлы только с расширением json!'
            ];
        } 
            else if (in_array($uploadfile, $allFiles, true)) {
                return [
                    'classname' => 'error',
                    'message' => 'Файл с таким именем уже существует!'
                ];
            } 
	        else if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
                return [
                    'classname' => 'success',
                    'message' => 'Файл корректен и успешно загружен на сервер'
                ];
            } 
	        else {
                return [
                    'classname' => 'error',
                    'message' => 'Произошла ошибка'
                ];
            }
    }
    //Все тесты
    function dispayAllTests($allTests)
    {
        $i = 0;
        while ($i < count($allTests)) {
            if (in_array($allTests[$i], $allTests, true)) {
                echo '<div class="file-block">';
                echo '<h3>' . str_replace('tests/', '', $allTests[$i]) . '<h3>';
                echo '<a href="test.php?number=' . array_search($allTests[$i], $allTests) . '">Пройти тест &Rrightarrow;</a><br><br>';
                if (isAuthorized()) {
                    echo '<form method="POST">';
                    echo "<input type=\"hidden\" name=\"path\" value=\"$allTests[$i]\">";
                    echo '<input type="submit" name="del" value="Удалить тест" class="del">';
                    echo '</form>';
                }
                echo '</div>';
                $i++;
            }
        }
    }
    function login($login, $password)
    {
        $users = getUsers();
        foreach ($users as $user) {
            if ($user['login'] == $login && $user['password'] == $password) {
                unset($user['password']);
                $_SESSION['user'] = $user;
                return true;
            }
        }
        return false;
    }
    function getUsers()
    {
        $path = __DIR__ . '/users.json';
        $fileData = file_get_contents($path);
        $data = json_decode($fileData, true);
        if (!$data) {
            return [];
        }
        return $data;
    }
    function getLoggedUserData()
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }
        return $_SESSION['user'];
    }
    function isAuthorized()
    {
        return (getLoggedUserData() !== null);
    }
    function getQuestUserData()
    {
        if (!isset($_SESSION['quest'])) {
            return null;
        }
        return $_SESSION['quest'];
    }
    function isQuest()
    {
        return getQuestUserData() !== null;
    }
    function location($path)
    {
        header("Location: $path");
        die;
    }
    function isPOST()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    function logout()
    {
        session_destroy();
        location('../index.php');
    }
    function delTest($files)
    {
        $path = $_POST['path'];
        if (file_exists($path)) {
            unlink($path);
        }
        unset($files[array_search($path, $files, true)]);
        return glob('tests/*.json');
    }
    function generateCode()
    {
        $chars = 'abdefhknrstyz23456789';
        $length = rand(4, 6);
        $numChars = strlen($chars);
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $str;
    }
    function imgCode($code)
    {
        header('Content-type: image/png');
        $font = '../fonts/OpenSans.ttf';
        $img = imagecreatetruecolor(200, 50);
        $bg = imagecolorallocate($img, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
        imagefill($img, 0, 0, $bg);
        $x = mt_rand(0, 35);
        for($i = 0; $i < strlen($code); $i++) {
            $x+=mt_rand(15, 25);
            $fontSize = mt_rand(20, 30);
            $color = imagecolorallocate($img, mt_rand(100, 200), mt_rand(100, 200), mt_rand(100, 200));
            $letter = substr($code, $i, 1);
            imagettftext($img, $fontSize, mt_rand(2, 10), $x, mt_rand(30, 40), $color, $font, $letter);
        }
        imagepng($img);
        imagedestroy($img);
    }
    function checkCaptcha($userCaptcha, $cookieCaptcha) {
        $userCaptcha = strtolower(trim($userCaptcha));
        $userCaptcha = md5($userCaptcha);
        return $userCaptcha === $cookieCaptcha;
    }
    function banUser() {
        setcookie('ban', 'banned', time() + 3600, '/', $_SERVER['HTTP_HOST']); //"Баним" пользователя на час
        session_destroy();
    }
?>
