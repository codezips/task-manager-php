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

function viewTasks(){
?>
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
                    ?>
                    <p class="text-center">No tasks listed. All clear.</p>
                    <?php
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
                            ?><tr><?php
                            foreach ($container[0] as $key => $v) {
                                print("<th>$key</th>");
                            }
                            print("</tr>");
                            ?></tr>
                    </thead>
                    <tbody>
                    <?php
                            foreach ($container as $value) {
                                ?><tr><?php
                                foreach ($value as $key => $v) {
                                    if (!$v && $v !== 0){
                                        ?>
                                        <td>--</td>
                                        <?php
                                    }
                                    else{
                                        if($key === 'created_on' || $key === 'completed_on'){
                                            ?><td><?php
                                            echo "date_format(date_create($v), DATE_RFC1123";
                                            ?></td><?php
                                        }
                                        else if($key === 'votes'){
                                            printRow($v);
                                        }
                                        else{
                                        ?><td><?php
                                            echo "date_format(date_create($v), DATE_RFC1123";
                                        ?></td><?php
                                        }
                                    }
                                }
                                ?></tr><?php
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
}

function viewTasksPerCategory(){
    ?>
    <div class="table-view">
        <table class="table table-bordered table-condensed table-hover">
            <
        </table>
    </div>
    <?php
}
?>