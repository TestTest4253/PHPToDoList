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

function is_admin($user_id){
    $server_name = 'localhost';
    $sql_user = 'webapp_select';
    $password = 'P_k(x[1!gDObxh7-';

    $conn = new mysqli($server_name, $sql_user, $password);

    if ($conn-> connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }
    $sql = 'SELECT Admin FROM credentialsbt.methodone WHERE credentialsbt.methodone.user_id = ?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_id);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        $results = $stmt->get_result();
        $row = $results->fetch_assoc();
        $data = $row['Admin'];
    }
    return $data;
}

function active_users(){
    $server_name = 'localhost';
    $sql_user = 'webapp_select';
    $password = 'P_k(x[1!gDObxh7-';
    $active_users = [];
    $conn = new mysqli($server_name, $sql_user, $password);

    if ($conn-> connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }
    $sql = 'SELECT username FROM credentialsbt.methodone WHERE credentialsbt.methodone.deleted = 0';
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()){
        $results = $stmt->get_result();
        $rows = $results->fetch_all();

        $length = count($rows);
        for ($x=0;$x<$length;$x++){
            $active_users[] = $rows[$x][0];
        }
        return $active_users;
    }
    return 0;
}

function update_permission($newPermissionLevel, $userId){
    $server_name = 'localhost';
    $sql_user = 'webapp_update';
    $password = '*j8hBQt3@i-m7ynQ';

    $conn = new mysqli($server_name, $sql_user, $password);
    if ($conn-> connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }
    $sql = 'UPDATE credentialsbt.methodone SET Admin = ? WHERE user_id = ?';
    if (!$stmt = $conn->prepare($sql)) {
        die('Preparation Error: ' . $conn->error);
    }
    if (!$stmt->bind_param('ii', $newPermissionLevel, $userId)) {
        die('Binding Error: ' . $stmt->error);
    }
    if (!$stmt->execute()) {
        die('Execution Error: ' . $stmt->error);
    }
    return 0;
}

function usernameToID($username){
    $server_name = 'localhost';
    $sql_user = 'webapp_select';
    $password = 'P_k(x[1!gDObxh7-';

    $conn = new mysqli($server_name, $sql_user, $password);
    if ($conn-> connect_error){
        die('Connection Failed: '.$conn->connect_error);
    }

    $sql = 'SELECT user_id from credentialsbt.methodone where username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $results = $stmt->get_result();
        $rows = $results->fetch_assoc();
        return $rows['user_id'];
    }
    return 0;
}