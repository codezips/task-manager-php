<?php
    // ob_start();
    # Creates a session or resumes one, based on a 'session identifier' via COOKIES, GET or POST
    # Required it seems
    # Docs: http://php.net/manual/en/function.session-start.php

    include("config.php");
    session_start();

    if(isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == 1){
        header('Location: home.php');
    }

    // Do not allow to use too old session ID
    if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
        session_destroy();
        session_start();
    }
    // error_reporting(E_ALL);
    // init_set("display_errors", 1);
    $msg = NULL;

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if ( isset($_POST['login']) ) {
            require "login.php";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Task Manager</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!--<link href="css/style.css" rel="stylesheet">-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container form-signin">
    <h1 class="welcome">Login to your Task Manager</h1>
    <h2 class="welcome-sub">Enter username and password</h2>
        <?php 

        ?>
    </div>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-signin" method="post">
            <!--<div class="alert alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Warning!</strong> Better check yourself, you're not looking too good.
                <?php echo $_SERVER['messege']; ?>
            </div>-->
            <input type="text" class="form-control" name="username" placeholder="Enter your username" required autofocus>
            <br>
            <input type="password" class="form-control" name="password" placeholder="Enter your password">
            <br>
            <button class="btn btn-lg btn-primary btn-block" name="login" type="submit">Login</button>
        </form>
        Click here to clean <a href = "logout.php" tite = "Logout">Session</a>
    </div>
                <!--<pre>
<?php //qqqvar_dump($_SESSION); ?>
            </pre>-->
</body>
</html>
