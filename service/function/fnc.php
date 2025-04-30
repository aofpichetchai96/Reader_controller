<?php
date_default_timezone_set('Asia/Bangkok');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';


function get_user_admin() {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `user` WHERE role_id = ? order by id asc");    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $role_id = 2;
    $stmt->bind_param("i", $role_id); 
    $stmt->execute();

    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        unset($row['password']);
        unset($row['updatetime']);
        unset($row['createtime']);
        $users[] = $row;        
    }
    $stmt->close();
    return $users;  
}
function active_user($id) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `user` WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $is_active = 1;
    if ($user['active'] == 1) {
        $is_active = 0;
    }

    $stmt = $mysqli->prepare("UPDATE `user` SET active = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ii", $is_active, $id);
    $stmt->execute();
    $stmt->close();
    
    return true;  
}

function check_username_exists($username) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `user` WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true; // ชื่อผู้ใช้มีอยู่แล้ว
    } else {
        return false; // ชื่อผู้ใช้ไม่ซ้ำ
    }
}

function check_id_exists($id) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `user` WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return false; //ยังไม่ถูกลบ
    } else {
        return true; //ถูกลบไปแล้ว
    }
}

function create_user($username, $password, $prefix, $firstname, $lastname, $phone) {
    global $mysqli;

    $role_id = 2;
    $is_active = 0;

    $stmt = $mysqli->prepare("INSERT INTO `user` 
        (username, password, role_id, prefix, firstname, lastname, phone, active) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ssissssi", $username, $password, $role_id, $prefix, $firstname, $lastname, $phone, $is_active);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}


function delete_user($id){
    global $mysqli;
    $stmt = $mysqli->prepare("DELETE FROM `user` WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

?>