<?php
    $username   = $_POST['username'];
    $password   = $_POST['password'];

    ob_start();
    //prepare, bind param and execute query
    var_dump($mysqli);
    if (!$mysqli){
        require "config.php";
        print("Info: Required Configuration");
    }
    $log_stmt   = $mysqli->prepare('SELECT * FROM user WHERE username=?');
    var_dump($log_stmt);
    // if ($log )
    $log_stmt->bind_param('s', $username);
    if (!$log_stmt->execute()){
        //error and send back to log in with msg
        $msg = "[EID] : Error occured processing your login request. Please try again later.";
        header("messege: \"$msg\"");
        header("location: ./index.php");
        print ($msg);
        //As much as I know right now, exit will kill the current script - affect on MySQL instance unknown
        exit;
    }
    // if ($my)

    $result     = $log_stmt->get_result();
    // $rows       = $result->fetch_assoc();
    $rows       = $result->fetch_array(MYSQLI_ASSOC);

    // if ($rows){ // != null or 
    if ($result->num_rows == 1){
        // $secret_password = $rows['password_hash'];
        $success = password_verify($password, $rows['password_hash']);

        // Update session with user info
        $_SESSION['user-id'] = $rows['id'];
        $_SESSION['username'] = $rows['username'];
        $_SESSION['email'] = $rows['email'];
        $_SESSION['name'] = $rows['name'];
        $_SESSION['surname'] = $rows['surname'];
        $_SESSION['active'] = $rows['active'];
        
        // This is how we'll know the user is logged in
        $_SESSION['logged-in'] = true;

        //send header to redirect to home page
        header('location: home.php');
        // echo "<pre>";
        // var_dump($rows);
        // echo "</pre>";
        $msg = "Success!";
        print ($msg);
    }
    else {
        $error = "[EID] Your credentials were incorrect. Please try again.";
        // header('Status Code: ' . 403);
        header('messege: ' . $error );
        // Using location will also cause a GET resquest - which means that the header messege can never be accessed by the server
        // header('location: index.php');
        // Using exit will cause the program to stop and it will output the var_dump above - this menas that tgere us no redirect.
        // exit;
    }

    // echo "<pre>";
    // var_dump($rows);
    // echo "</pre>";

    // Clear result and close the statement
    $result->free();
    $log_stmt->close();

    $output = ob_get_contents();
    ob_end_clean();
    echo "Writing to file...";
    file_put_contents(__DIR__ . '\output.html', $output);    

    # Note with PHP methods used, you don't need to store the salt as it is included in the hash
    // $secret_salt = $row['password_salt'];
    # use BCRYPT -  designed to hash passwords, but also means the salt is stored with the hashed password
?>