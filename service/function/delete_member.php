<?php
require_once './fnc_member.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    
    if (check_id_member_exists($id)) {
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
                    text: 'Member นี้ถูกลบไปแล้ว',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../members.php';
                    }
                });
            </script>
        </body>
        </html>
        ";
        exit;
    }


    // เพิ่มผู้ใช้ใหม่
    $result = delete_member($id);

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
                            text: 'ลบ Member สำเร็จเรียบร้อยแล้ว',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../members.php';
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
                    text: 'เกิดข้อผิดพลาดในการลบข้อมูล Member',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../members.php';
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
                    text: 'เกิดข้อผิดพลาดในการลบข้อ Member',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../members.php';
                    }
                });
            </script>
        </body>
        </html>
        ";
        exit;
}

?>