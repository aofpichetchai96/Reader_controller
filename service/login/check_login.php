<?php
require_once '../config/config.php';
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $username = strtolower($username);
    $stmt = $mysqli->prepare("SELECT * FROM user WHERE username = ? and active = 1");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();      
    
    //hash password
    $password_hash = md5($password.KEY_HASH); 

    if ($user &&count($user) > 0 && $password_hash === $user['password']) {
        $userNewdata = [];
        $userNewdata['id'] = $user['id'];
        $userNewdata['username'] = $user['username'];
        $userNewdata['role_id'] = $user['role_id'];
        $userNewdata['prefix'] = $user['prefix'];
        $userNewdata['firstname'] = $user['firstname'];
        $userNewdata['lastname'] = $user['lastname'];
        $userNewdata['phone'] = $user['phone'];

        $_SESSION['user'] = $user['username'];
        $_SESSION['user_role'] = $user['role_id'];
        $_SESSION['user_data'] = $userNewdata;

        $stmt = $mysqli->prepare("UPDATE user SET lastlogin = ? WHERE id = ? AND active = 1");
        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        $now = date('Y-m-d H:i:s');
        $user_id = (int)$user['id'];
        $stmt->bind_param("si", $now, $user_id);
        $stmt->execute();

        $stmt->close();
        $mysqli->close();

        header("Location: ../index.php");
        exit;
    } else {
        echo "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <script src='../js/sweetalert2.all.min.js '></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../login.php';
                        }
                    });
                </script>
            </body>
            </html>
            ";
            exit;
    }   
    $stmt->close();
    $mysqli->close(); 
} else {
    header('Location: ../login.php');
    exit;
}
?>