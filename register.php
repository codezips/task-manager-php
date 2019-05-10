<?php

    ## NOT COMPLETE ##

    $name       = $_POST['name'];
    $surname    = $_POST['surname'];
    $email      = $_POST['email'];
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $confirm_password = $_POST['password'];

    if ($password !== $confirm_password){
        //error id=101: Client error; passwords do not match. retype
        $error = "Passwords did not match. Please try again.";
        header("messege: '$error'");
        header("locatiion: index.php");
    }
    else {
        //hash password
        $secret_password = password_hash($password, PASSWORD_DEFAULT, array('cost' => 12));

        //create prepared INSERT statment
        $reg_stmt = 'INSERT INTO users (name, surname, email, username, password_hash, active) VALUES (?,?,?,?,?)';
        $reg_stmt->bind_param('sssss', $name, $surname, $email, $username, $password);

        //execute statement
        $reg_stmt->execute();

        printf("%d Rows affected.", $reg_stmt->affected_rows());

        if ($reg_stmt->affected_rows() != 1){
            //error id=102: Server error; unable to add user
            echo $mysqli->error();
            exit;
        }

        echo "Success. You are now registered!";
        header('location: index.php');
    }

    $options = ['cost' => 12];
    password_hash($password, PASSWORD_DEFAULT, $options);
?>

<!--register.php-->
<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register New Account | Task Manager</title>
</head>
<body>
    <div class="container">
        h1.form-title{Sign up to Task Manager}
        small.form-subtitle{Enter all detail below to create a new account}
        <form action="<?php ?>" class="form-default" method="post">
            input.form-control[type="text"][name="_name"][placeholder="First Name"][required autofocus]
            input.form-control[type="text"][name="surname"][placeholder="Surname"]
            input.form-control[type="text"][name="email"][placeholder="user@domain"]
            input.form-control[type="password"][name="password"][placeholder="Enter password"]
            input.form-control[type="password"][name="confirm_password"][placeholder="Confirm password"]
            button.btn.btn-lg.btn-primary.btn-block[type="submit" name="register"]{Register}
            button.btn.btn-lg.btn-cancel.btn-block[type="menu" name="cancel"]{Cancel}
        </form>
    </div>
</body>
</html>-->