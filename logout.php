<?php
    
    ini_set('session.use_strict_mode', 1);
    session_start();
    // free variables then destory session
    session_unset();
    session_destroy();
    
    header('Location: index.php');

?>

<!-- Write Code for a Logout Information or keep the above and redirect--> 