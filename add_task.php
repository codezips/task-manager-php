<?php

# START: Code to insert new task into the database
// $id = generate_id('U');
$title          = $_POST['title'];
$description    = $_POST['description'];
$category       = $_POST['category'];
// echo $category."<br>";

# 'Created on' date is provided by MySQL; easier this way
# disabled assigning a due_date
// $due_date = $_POST['due_date'];

# category id can be ontained as the value for each category is not the name displayed but assigned it's id
$usr_id         = $_SESSION['user-id'];
$cat_id         = (!isset($_SESSION['no-categories']) ? $category : null );
// echo $cat_id."<br>";

# Status and priority values should be defined at front end
$status         = $_POST['status'];
$priority       = $_POST['priority'];

$nt_stmt = $mysqli->prepare(
    'INSERT INTO task 
    (title, description, category_id, user_id, created_on, priority, status)
    VALUES (?,?,?,?,NOW(),?,?)');

$nt_stmt->bind_param('ssddss', $title, $description, $cat_id, $usr_id, $priority, $status);

$nt_stmt->execute();

if ($nt_stmt->affected_rows != 1 || $nt_stmt->sqlstate !== '00000'){
    // error
    echo "SQL Error: ";
    var_dump($nt_stmt->error_list);
    // close statement
}
else {
    //#TODO: use session instead
    $_SESSION['messege'] = "Successfully added new task";
    // echo "Successfully added new task";
    header('location: home.php');
}

// $results->free();
$nt_stmt->close();

# END

# mysqli_stmt::bind_param() -- http://php.net/manual/en/mysqli-stmt.bind-param.php
