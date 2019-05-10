<?php
    # Define() - used to define a costant at runtime
    define('DB_SERVER', 'localhost:3306');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_DATABASE', 'taskmanager');

    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if($mysqli->connect_errno > 0){
        die("Unable to connect to db_server '" . DB_SERVER . "' : [" . $mysqli->$connect_error . "]");
    }
    // mysql_select_db(DB_DATABASE);

    # TODO: log Sucessful connection @ time
    ini_set('session.use_strict_mode', 1 );

?>
