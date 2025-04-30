<?php
date_default_timezone_set('Asia/Bangkok');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

function get_member() {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `member` WHERE status = ? order by id asc");    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $status = 1;
    $stmt->bind_param("i", $status); 
    $stmt->execute();

    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        unset($row['startdate']);
        unset($row['status']);
        $users[] = $row;        
    }
    $stmt->close();
    return $users;  
}


function active_member($id) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `member` WHERE id = ?");
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

    $stmt = $mysqli->prepare("UPDATE `member` SET active = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ii", $is_active, $id);
    $stmt->execute();
    $stmt->close();
    
    return true;  
}


function check_id_member_exists($id) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `member` WHERE id = ? and  status = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $status = 1;
    $stmt->bind_param("ii", $id,$status);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return false; //ยังไม่ถูกลบ
    } else {
        return true; //ถูกลบไปแล้ว
    }
}


function delete_member($id){
    global $mysqli;
    $stmt = $mysqli->prepare("UPDATE `member` SET status = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $status = 0;
    $stmt->bind_param("ii",$status,$id);
    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

function check_member_exists($cardnumber) {
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `member` WHERE cardnumber = ? and status = 1");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("s", $cardnumber);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true; // cardnumber มีอยู่แล้ว
    } else {
        return false; // cardnumber ไม่ซ้ำ
    }
}

function create_member($firstname, $lastname, $phone, $email, $position, $enddate, $cardnumber, $createby) {
    global $mysqli;

    $stmt = $mysqli->prepare("INSERT INTO `member` 
        (firstname, lastname, phone, email, position, enddate, cardnumber,createby) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ssssssss", $firstname, $lastname, $phone, $email, $position, $enddate, $cardnumber, $createby);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function check_mf_card($cardnumber,$id){
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM `member` WHERE cardnumber = ? and id = ? and status = 1");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("si", $cardnumber,$id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return true; // cardnumber มีอยู่แล้ว
    } else {
        $rs = check_member_exists($cardnumber);
        if($rs) return false;
        return true; // cardnumber ไม่ซ้ำ
    }
    
}

function update_member($firstname, $lastname, $phone, $email, $position, $enddate, $cardnumber, $createby,$id) {
    $rs = check_mf_card($cardnumber,$id);
    if($rs == false) return false;

    global $mysqli;
    $stmt = $mysqli->prepare("UPDATE `member` SET firstname = ?, lastname = ?, phone = ?, email = ?, position = ?, enddate = ?, cardnumber = ?, createby = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ssssssssi", $firstname, $lastname, $phone, $email, $position, $enddate, $cardnumber, $createby, $id);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}




?>