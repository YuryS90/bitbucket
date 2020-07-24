<?php

include "../bitbucket/vendor/autoload.php";

use App\XML;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    $save = new XML();

    $save
        ->readFile()
        ->addData(
            $_POST['login'],
            $_POST['pass'],
            $_POST['email'],
            $_POST['name']
        )
        ->saveFile();

    ?>

    <h1>Регистрация</h1>
    <form action="?" method="POST">
        <h3>Логин</h3>
        <input type="text" name="login">
        <h3>Пароль</h3>
        <input type="password" name="pass">
        <h3>Потвердите пароль</h3>
        <input type="password" name="conpass">
        <h3>Еmail</h3>
        <input type="email" name="email">
        <h3>Имя</h3>
        <input type="text" name="name">
        <input type="submit" value="ok">
    </form>

    <br>

    <h1>Авторизация</h1>
    <form action="">
        <input type="text" name="login">
        <input type="password" name="pass">
        <input type="submit" value="ok">
    </form>
</body>

</html>