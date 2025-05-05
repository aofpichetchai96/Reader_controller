<?php
date_default_timezone_set('Asia/Bangkok');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

function get_data($atdate) {
    global $mysqli;

    $sql = "SELECT ? AS atdate,
                    IFNULL(a_staft_false.cnt_staft_false,0) AS cnt_staft_false,
                    IFNULL(a_staft_true.cnt_staft_true,0) AS cnt_staft_true,
                    IFNULL(b_student_false.cnt_student_false,0) AS cnt_student_false,
                    IFNULL(b_student_true.cnt_student_true,0) AS cnt_student_true
            FROM (
                SELECT ? AS atdate, COUNT(id) AS cnt_staft_false
                FROM scan_logs
                WHERE DATE(createtime) = ? AND type = 'staft' AND rs_success = 'false'
            ) AS a_staft_false
            JOIN (
                SELECT ? AS atdate, COUNT(id) AS cnt_staft_true
                FROM scan_logs
                WHERE DATE(createtime) = ? AND type = 'staft' AND rs_success = 'true'
            ) AS a_staft_true ON a_staft_true.atdate = a_staft_false.atdate
            JOIN (
                SELECT ? AS atdate, COUNT(id) AS cnt_student_false
                FROM scan_logs
                WHERE DATE(createtime) = ? AND type = 'student' AND rs_success = 'false'
            ) AS b_student_false ON b_student_false.atdate = a_staft_false.atdate
            JOIN (
                SELECT ? AS atdate, COUNT(id) AS cnt_student_true
                FROM scan_logs
                WHERE DATE(createtime) = ? AND type = 'student' AND rs_success = 'true'
            ) AS b_student_true ON b_student_true.atdate = a_staft_false.atdate";

    $stmt = $mysqli->prepare($sql);

    $stmt->bind_param("sssssssss", $atdate, $atdate, $atdate, $atdate, $atdate, $atdate, $atdate, $atdate, $atdate);

    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
    return $data;
}

function get_total_transactions($atdate) {
    global $mysqli;
    
    $sql = "SELECT COUNT(*) as total FROM scan_logs WHERE DATE(createtime) = ?";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $atdate);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
    return $data;
}
function data_transaction($atdate, $limit = null, $offset = null) { 
    global $mysqli; 
     
    $sql = "SELECT * FROM scan_logs WHERE DATE(createtime) = ? ORDER BY createtime DESC"; 
     
    // เพิ่มการแบ่งหน้า 
    if ($limit !== null) { 
        $sql .= " LIMIT ?"; 
        if ($offset !== null) { 
            $sql .= " OFFSET ?"; 
        } 
    } 
     
    $stmt = $mysqli->prepare($sql); 
     
    // กำหนด parameters ตามจำนวนที่ใช้ 
    if ($limit !== null) { 
        if ($offset !== null) { 
            // กรณีมีทั้ง limit และ offset 
            $stmt->bind_param("sii", $atdate, $limit, $offset); 
        } else { 
            // กรณีมีแค่ limit 
            $stmt->bind_param("si", $atdate, $limit); 
        } 
    } else { 
        // กรณีไม่มีทั้ง limit และ offset 
        $stmt->bind_param("s", $atdate); 
    } 
     
    $stmt->execute(); 
    $result = $stmt->get_result(); 
    $data = []; 
    while ($row = $result->fetch_assoc()) { 
        $data[] = $row; 
    } 
    $stmt->close(); 
    return $data;
}

?>