<?php
require_once './fnc.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower($_POST['username']);
    $password = md5($_POST['password'].KEY_HASH);
    $prefix = $_POST['prefix'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    
    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำไหม
    if (check_username_exists($username)) {
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
                    text: 'Username นี้มีอยู่แล้ว',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../admin.php';
                    }
                });
            </script>
        </body>
        </html>
        ";
        exit;
    }

    // เพิ่มผู้ใช้ใหม่
    $result = create_user($username, $password, $prefix, $firstname, $lastname, $phone);

    if ($result) {
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
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'เพิ่มผู้ใช้สำเร็จเรียบร้อยแล้ว',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../admin.php';
                        }
                    });
                </script>
            </body>
            </html>
            ";
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
                    text: 'เกิดข้อผิดพลาดในการเพิ่มผู้ใช้',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../admin.php';
                    }
                });
            </script>
        </body>
        </html>
        ";
        exit;
    }
}else{
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
                    text: 'เกิดข้อผิดพลาดในการเพิ่มผู้ใช้',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../admin.php';
                    }
                });
            </script>
        </body>
        </html>
        ";
        exit;
}
?>
