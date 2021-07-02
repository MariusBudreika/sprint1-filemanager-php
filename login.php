<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h2>Enter Username and Password</h2>
</body>
<?php

session_start();
// logout logic
if (isset($_GET['action']) and $_GET['action'] == 'logout') {
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['logged_in']);
    print('Logged out!');
}
// login logic
$msg = '';
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    if ($_POST['username'] == 'Marius' && $_POST['password'] == '1234') {
        $_SESSION['logged_in'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = $_POST['username'];
        header('Location: ' . 'index.php');
    } else {
        $msg = 'Wrong username or password';
    }
}
?>

<div>
    <form action="./login.php" method="post">
        <h4><?php echo $msg; ?></h4>
        <input type="text" name="username" placeholder="username=Marius" required autofocus></br>
        <input type="password" name="password" placeholder="password=1234" required>
        <button class="btn" type="submit" name="login">Login</button>
    </form>
</div>

</html>