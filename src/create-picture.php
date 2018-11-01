<?php
    header('Content-type: image/png');
    $testname = $_POST['testname'];
    $username = $_POST['username'];
    $date = $_POST['date'];
    $correctAnswers = $_POST['correctAnswers'];
    $totalAnswers = $_POST['totalAnswers'];
    $answers = $correctAnswers . ' из ' . $totalAnswers;
    $img = imagecreatefromjpeg('img/picture.jpg');
    $white = imagecolorallocate($img, 035, 035, 035);
    $imageWidth = getimagesize('img/picture.jpg');
    $imageWidth = $imageWidth[0];
    $textPoints = imagettfbbox(300, 0, 'fonts/OpenSans.ttf', $username);
    $textWidth = $textPoints[2] - $textPoints[0];
    $x = ($imageWidth - $textWidth) / 2;
    imagettftext($img, 300, 0, $x, 1000, $white, 'fonts/OpenSans.ttf', $username . '!');
    imagettftext($img, 200, 0, 1600, 1625, $white, 'fonts/OpenSans.ttf', $testname);
    imagettftext($img, 180, 0, $x, 1976, $white, 'fonts/OpenSans.ttf', $answers);
    imagettftext($img, 150, 0, 1600, 2325, $white, 'fonts/OpenSans.ttf', $date);
    imagepng($img);
    imagedestroy($img);
?>
