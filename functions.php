<?php

function connect_db($server, $user, $pass, $database){
    $conn = new mysqli($server, $user, $pass, $database);
    if ($conn->connect_error){
        die('Connection Failed '.$conn->connect_error);
    }
    return $conn;
}

function create_task($user, $title, $contents, $date, $status): int
{
    $conn = connect_db('localhost','webapp_insert', 'TE1rrJ0M4tKD!x4I','credentialsbt');
    $sql = 'INSERT INTO tasks(User_ID, Title, Contents, Due_Date, Completed, deleted, priority) VALUES (?,?,?,?,0,0,?)';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('issss',$user, $title, $contents, $date,$status);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        return 1;
    } else{
        return 0;
    }
}

function edit_task($contents, $title, $date, $status, $user, $task): int
{
    $conn = connect_db('localhost','webapp_update','*j8hBQt3@i-m7ynQ', 'credentialsbt');
    $sql = 'UPDATE tasks SET Contents = ?,Title = ?, Due_Date = ?, User_ID = ?, priority = ? WHERE Task_ID=?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssisi', $contents, $title, $date, $user, $status, $task);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        return 1;
    } else{
        return 0;
    }
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
    $sql = 'SELECT tasks.User_ID, Task_ID, Title, Contents, Due_Date, Completed, priority FROM tasks INNER JOIN methodone ON methodone.user_id = tasks.User_ID WHERE tasks.deleted = 0 AND methodone.user_id = ? ORDER BY tasks.priority DESC;';
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
    $sql = 'SELECT tasks.User_ID, Task_ID, Title, Contents, Due_Date, Completed, priority FROM tasks INNER JOIN methodone ON methodone.user_id = tasks.User_ID WHERE tasks.deleted = 0 AND methodone.user_id != ? ORDER BY tasks.priority DESC;';
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
    $sql = 'SELECT permissions FROM methodone WHERE methodone.user_id = ?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_id);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        $results = $stmt->get_result();
        $row = $results->fetch_assoc();
        $data = $row['permissions'];
    }
    if ($data == 'Admin'){
        return 1;
    }
    return 0;
}

function isGuest($user_id){
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT permissions FROM methodone WHERE methodone.user_id = ?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_id);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        $results = $stmt->get_result();
        $row = $results->fetch_assoc();
        $data = $row['permissions'];
    }
    if ($data == 'Guest'){
        return 1;
    }
    return 0;
}

function firstLogon($userId): int{
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT firstLogon FROM methodone WHERE methodone.user_id = ?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $userId);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        $results = $stmt->get_result();
        $row = $results->fetch_assoc();
        $data = $row['firstLogon'];
    }
    return $data;
}

function checkPassword($userId,$password): int{
    $conn = connect_db('localhost','webapp_select','P_k(x[1!gDObxh7-', 'credentialsbt');
    $sql = 'SELECT password from credentialsbt.methodone WHERE user_id = ?';
    try{
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $userId);
    }catch(Exception $e){
        echo 'Your error is '.$e;
    }

    if($stmt->execute()){
        $result = $stmt->get_result();
        $array = $result->fetch_assoc();
        $storedPassword = $array['password'];
        if (password_verify($password, $storedPassword)){
            return 1;
        } else{
            return 0;
        }
    }
}

function submitPassword($userId, $password): int
{
    $conn = connect_db('localhost','webapp_update','*j8hBQt3@i-m7ynQ', 'credentialsbt');
    $sql = 'UPDATE methodone SET password = ? WHERE user_id = ?';
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    try{
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si',$hashed_password, $userId);
    }catch (Exception $e){
        echo 'Your error is '.$e;
    }
    if ($stmt->execute()){
        return 1;
    }
    return 0;
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
    $sql = 'UPDATE methodone SET permissions = ? WHERE user_id = ?';
    if (!$stmt = $conn->prepare($sql)) {
        die('Preparation Error: ' . $conn->error);
    }
    if (!$stmt->bind_param('si', $newPermissionLevel, $userId)) {
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

function logEvent($event){
    $conn = connect_db('localhost','webapp_insert','TE1rrJ0M4tKD!x4I','credentialsbt');
    $currentUser = (int) $_SESSION['user_id'];
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $sql = 'INSERT INTO logs(User_ID, IP_Address, Description) VALUES (?,?,?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss',$currentUser,$ipAddress,$event);
    if ($stmt->execute()){
        return 1;
    }
    return 0;
}