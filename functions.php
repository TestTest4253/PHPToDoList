<?php

function connect_db($server, $user, $pass, $database){
    $conn = new mysqli($server, $user, $pass, $database);
    if ($conn->connect_error){
        die('Connection Failed '.$conn->connect_error);
    }
    return $conn;
}

function create_task($user, $title, $contents, $date): int
{
    $conn = connect_db('localhost','webapp_insert', 'TE1rrJ0M4tKD!x4I','credentialsbt');
    $sql = 'INSERT INTO tasks(User_ID, Title, Contents, Due_Date, Completed, deleted, priority) VALUES (?,?,?,?,0,0,0)';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('isss',$user, $title, $contents, $date);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        return 1;
    } else{
        return 0;
    }
}

function edit_task(): int
{
    $conn = connect_db('localhost','webapp_update','*j8hBQt3@i-m7ynQ', 'credentialsbt');
}
function delete_task($remove_ID): int
{
    $conn = connect_db('localhost','webapp_update','*j8hBQt3@i-m7ynQ', 'credentialsbt');
    $sql = 'UPDATE tasks SET deleted = 1 WHERE Task_ID=?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $remove_ID);
    }catch (Exception $e){
        echo 'Your error is '. $e;
    }
    if ($stmt->execute()){
        return 1;
    }
    return 0;
}

function collect_tasks(): array
{
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $user_id = $_SESSION['user_id'];
    $sql = 'SELECT tasks.User_ID, Task_ID, Title, Contents, Due_Date, Completed FROM tasks INNER JOIN methodone ON methodone.user_id = tasks.User_ID WHERE tasks.deleted = 0 AND methodone.user_id = ?;';
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
function all_tasks(): array
{
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $user_id = $_SESSION['user_id'];
    $sql = 'SELECT tasks.User_ID, Task_ID, Title, Contents, Due_Date, Completed FROM tasks INNER JOIN methodone ON methodone.user_id = tasks.User_ID WHERE tasks.deleted = 0 AND methodone.user_id != ?;';
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
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT Admin FROM methodone WHERE methodone.user_id = ?';
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

function active_users(): int|array
{
    $active_users = [];
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT username FROM methodone WHERE methodone.deleted = 0';
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

function inactive_users(): int|array
{
    $active_users = [];
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT username FROM methodone WHERE methodone.deleted = 1';
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

function deleteUser($userId){
    $conn = connect_db('localhost','webapp_update','*j8hBQt3@i-m7ynQ', 'credentialsbt');
    $sql = 'UPDATE methodone SET deleted = 1 WHERE user_id = ?';
    if (!$stmt = $conn->prepare($sql)) {
        die('Preparation Error: ' . $conn->error);
    }
    if (!$stmt->bind_param('i',$userId)) {
        die('Binding Error: ' . $stmt->error);
    }
    if (!$stmt->execute()) {
        die('Execution Error: ' . $stmt->error);
    }
    return 0;
}

function addUser($userId){
    $conn = connect_db('localhost','webapp_update','*j8hBQt3@i-m7ynQ', 'credentialsbt');
    $sql = 'UPDATE methodone SET deleted = 0 WHERE user_id = ?';
    if (!$stmt = $conn->prepare($sql)) {
        die('Preparation Error: ' . $conn->error);
    }
    if (!$stmt->bind_param('i',$userId)) {
        die('Binding Error: ' . $stmt->error);
    }
    if (!$stmt->execute()) {
        die('Execution Error: ' . $stmt->error);
    }
    return 0;
}

function update_permission($newPermissionLevel, $userId){
    $conn = connect_db('localhost','webapp_update','*j8hBQt3@i-m7ynQ', 'credentialsbt');
    $sql = 'UPDATE methodone SET Admin = ? WHERE user_id = ?';
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
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT user_id from methodone where username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $results = $stmt->get_result();
        $rows = $results->fetch_assoc();
        return $rows['user_id'];
    }
    return 0;
}

function IDtoUsername($id){
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT username from methodone where user_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $id);
    if ($stmt->execute()) {
        $results = $stmt->get_result();
        $rows = $results->fetch_assoc();
        return $rows['username'];
    }
    return 0;
}