<?php
function delete_task($remove_ID){
    $server_name = 'localhost';
    $sql_user = 'webapp_select';
    $password = 'P_k(x[1!gDObxh7-';

    $tasks = collect_tasks($server_name, $sql_user, $password);
    $length = count($tasks);
    for ($x = 0; $x<$length; $x++){
        $task_id = $tasks[0];
        if ($task_id == $remove_ID){
            echo 'Task Will be Deleted';
        }
    }
}

function collect_tasks($server_name, $sql_user, $password){
    $conn = new mysqli($server_name, $sql_user, $password);
    if ($conn-> connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }
    $user_id = $_SESSION['user_id'];
    $sql = 'SELECT Task_ID, Title, Contents, Due_Date, Completed FROM credentialsbt.tasks INNER JOIN credentialsbt.methodone ON credentialsbt.methodone.user_id = tasks.User_ID WHERE credentialsbt.methodone.user_id = ?;';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_id);
    }catch(Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        $results = $stmt->get_result();
        $tasks = $results->fetch_all();
    }else{
        echo 'Error: SQL Statement not executed';
    }
    return $tasks;
}