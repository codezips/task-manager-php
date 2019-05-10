<?php
    /* Displays user information and some useful messages */
    // Write your session id here for reference:
    // PHPSESSID=cp1c43ifqrm42gfhut2lk0gs06
    ini_set('session.use_strict_mode', 1);
    include("config.php");
    session_start();

    // Do not allow to use too old session ID
    if (!empty($_SESSION['deleted_time']) && $_SESSION['deleted_time'] < time() - 180) {
        session_destroy();
        session_start();
    }
    
    // Check login credentials
    // if fail, redirect to login.php
    if ( $_SESSION['logged-in'] != 1 ) {
        $_SESSION['messege'] = "You must log in before viewing your profile page!";
        header("location: index.php");    
    }
    else {
        // Easier reading and referencing
        $first_name = $_SESSION['name'];
        $last_name = $_SESSION['surname'];
        $email = $_SESSION['email'];
        // FOR EMAIL VERFICATION STATUS
        $active = $_SESSION['active'];
        $user_id = $_SESSION['user-id'];
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST['add-task'])){
            require 'add_task.php';
        }
        else if (isset($_POST['remove_task'])){

        }
        else if (isset($_POST['add_category'])){

        }
        else if (isset($_POST['remove_category'])){

        }
    }
    else if($_SERVER["REQUEST_METHOD"] == "GET"){
        //Something
    }
    ?>
    <!-- Home.php -->
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Home | Tasks Overview</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!--<link href="css/style.css" rel="stylesheet">-->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js"></script>
        <style>
        </style>
    </head>
    <body>
        <div class="container">
        <h1 class="home-title">Welcome, <b><?php print $_SESSION['username'];?></b>!</h1>
        <h1 class="banner small">Status, current number tasks, overdue tasks summary here!</h1>
            <div class="main-section">
                <!--//tasks.php-->
                <!--TODO-->
                <!--<pre>-->
                <h2>Task Overview</h2>
                <?php
                    # Code to retrieve all tasks from the database

                    $task_stmt = $mysqli->prepare(
                        "SELECT * FROM task WHERE user_id=?");
                    
                    $task_stmt->bind_param('s', $user_id);
                    $task_stmt->execute(); 
                    $result = $task_stmt->get_result();

                    if ($result->num_rows == 0){
                        printf("<p class=\"text-center\">%s</p>", "No tasks listed. All Clear");
                    }
                    else{
                        $container = array();
                        while($rows = $result->fetch_array(MYSQLI_ASSOC)){
                            array_push($container, $rows);
                        }
                        // var_dump($container);
                    }

                    $result->free();
                    $task_stmt->close();
                ?>
                <!--</pre>-->
                <div class="container overview-wrapper">
                    <table class="table table-bordered table-condensed table-hover">
                        <thead>
                        <?php
                            // Can access $container? Yes
                            // var_dump($container);
                            if (isset($container)){

                                print("<tr>");
                                ?>
                                <!--<th></th>-->
                                <?php
                                foreach ($container[0] as $key => $v) {
                                    print("<th>$key</th>");
                                }
                                print("</tr>");
                        ?>
                        </thead>
                        <tbody>
                        <?php
function printRow($v)
{
    ?>
    <td>
        <?php echo $v; ?>
        <button style="display: inline-block" class="btn btn-sm btn-primary" type="submit" name="upvote">+</button>
        <button style="display: inline-block" class="btn btn-sm btn-danger" type="submit" name="downvote">-</button>
    </td>
    <?php
}
                                // Can access a $container?
                                // var_dump($container);
                                foreach ($container as $value) {
                                    print("<tr>\n");
                                    foreach ($value as $key => $v) {
                                        if (!$v && $v !== 0){
                                            print("<td>--</td>\n");
                                        }
                                        else{
                                            if($key === 'created_on' || $key === 'completed_on'){
                                                print("<td>". date_format(date_create($v), DATE_RFC1123)."</td>\n");
                                            }
                                            else if($key === 'votes'){
                                                printRow($v);
                                            }
                                            else{
                                                print("<td>$v</td>\n");
                                            }
                                        }
                                    }
                                    // printRow();
                                    print("</tr>");
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="container">
                    <div class="table-wrapper">
                        <?php
                            # code to count all tasks by category
                            "SELECT count(x) AS 'Tasks_Count'
                            FROM task 
                            WHERE user_id=?
                            GROUP BY category_id";
                        ?>
                    </div>
                </div>
                <!--//add_task.php-->
                <div class="add-form col-sm-6">
                    <h3 class="task-form-title">Add Task</h3>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" class="" method="post">
                        <div class="form-group">
                            <label for="TaskAddInputTitle">Task name</label>
                            <input type="text" class="form-control" name="title" placeholder="Title" required autofocus>
                        </div>
                        <div class="form-group">
                            <label for="TaskAddInputDescription">Task description</label>
                            <input type="text" class="form-control" name="description" placeholder="Describe the task" required>
                        </div>
                        <!--<br/>
                        <br/>-->
                        <div class="select-category-control form-group">
                            <label for="TaskAddInputCategory">Category</label>
                            <!--<div class="checkbox">
                                <label for="categoryCheckboxOption">
                                    <input type="checkbox" id="categoryCheckboxOption" value="" checked>
                                Include a Category for Task?</label>
                            </div>-->

                            <!--div.select-category-option.form-control>select#category-select[name="category-select"].form-control>option[value="opt$"]{Cat$}*5-->
                            <select name="category" class="form-control">
                                <?php
                                    # Code to retrieve possible categories from the database
                                    // if($user_id == '' || ! isset($user_id)){
                                    //     //errror [code]: user_id is not set
                                    //     die("Error [104]: User id is not set");
                                    // }
                                    $cl_stmt = $mysqli->prepare(
                                        "SELECT id, name, description
                                        FROM category
                                        WHERE user_id=?");
                                    
                                    if (!$cl_stmt->bind_param('s', $_SESSION['user-id'])){
                                        $_SESSION['no-categories'] = true;
                                    }

                                    # Error handling different, suggest disabling functionality and reporting error to the user via alerts. But with no categories is also acceptable
                                    if (!$cl_stmt->execute()){
                                        $_SESSION['no-categories'] = true;
                                    }
                                    if (!$result = $cl_stmt->get_result()){
                                        $_SESSION['no-categories'] = true;
                                    }
                                    // $rows = $cl_stmt->fetch_array(MYSQLI_ASSOC);
                                    if ($result->num_rows == 0){
                                        $_SESSION['no-categories'] = true;
                                    }
                                    else{
                                        $c_list = array();
                                        while ($rows = $result->fetch_array(MYSQLI_ASSOC)) {
                                            $c_list[$row['id']] = $row['name'];
                                            printf("<option value=\"%s\">%s</option>", $row['id'], $row['name']);
                                        }
                                        $_SESSION['category-list'] = $c_list;
                                        if (isset($_SESSION['no-categories'])){
                                            unset($_SESSION['no-categories']);
                                        }
                                    }
                                    printf("<option value=\"Unassigned\">None</option>\n");
                                    // if (isset($_SESSION['no-categories'])){
                                    // }

                                ?>
                            </select>                            
                        </div>
                        <!--<br>-->
                        <!--<label for="priority-val" class="control-label">Priority</label>
                        <input type="" class="form-control" name="" placeholder="Enter your password">-->
                        <div class="form-group">
                            <label for="priority-val" class="control-label">Priority</label>
                            <select name="status" id="" class="form-control">
                                <option value="LOW">Low</option>
                                <option value="NORMAL" selected>Normal</option>
                                <option value="HIGH">High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status-val" class="control-label">Status</label>
                            <select name="priority" id="" class="form-control">
                                <option value="READY">Ready</option>
                                <option value="ACTIVE" selected>Active</option>
                                <option value="ON HOLD">On Hold</option>
                                <!--<option value="COMPLETE">Complete</option>-->
                            </select>
                        </div>
                        <button class="btn btn-primary" name="add-task" type="submit">Add Task</button>
                    </form>
                </div>
                <div class="add-form col-sm-6">
                    <h3 class="form-title">Add Category</h3>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" class="" method="post">
                        <div class="form-group">
                            <label for="CategoryAddName">Category Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Text Here" required autofocus>
                        </div>
                        <div class="form-group"><label for="CategoryAddDescription">Description</label>
                        <input type="text" class="form-control" name="value-field" placeholder="Text Here" required>
                        </div>
                        <div class="form-group"><label for="CategoryAddTagColour">Tag Colour</label><input type="color" class="form-control" name="tag-color" placeholder="Tag Colour" required>
                        </div>
                        <button class="btn btn-primary" name="add-category" type="submit">Add Category</button>
                    </form>                    
                </div>
                <div class="clearfix"></div>
                <!--//remove_task.php-->
                <div class="remove-form col-sm-6">
                    <h3 class="form-title">Remove Actions</h3>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" class="" method="post">
                        <div class="form-group">
                            <label for="CategorySelectName">Category</label>
                            <select name="category" id="select-category">
                                <option value="1">Cat1</option>
                                <option value="2">Cat2</option>
                                <option value="3">Cat3</option>
                                <option value="4">Cat4</option>
                                <option value="5">Cat5</option>
                            </select>
                            <button class="btn btn-primary" name="add-category" type="submit">Remove Category</button>
                        </div>
                    </form>                    
                </div>
                <div class="clearfix"></div>
            </div>
            <div>
                //tasks.php - pass arguement by category
                //add_category.php
                //remove_category.php
            </div>
            <pre>
<?php var_dump($_SESSION); ?>
            </pre>
            <!--a[href="logout.php"]>button.btn.btn-lg.btn-cancel.btn-block[name=logout]{logout}-->
            <a href="logout.php">
                <button class="btn btn-lg btn-primary" name="logout">logout</button>
            </a>
        </div>


    </body>
    </html>