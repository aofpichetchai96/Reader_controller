<?php
require_once './fnc.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    
    if (check_id_exists($id)) {
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
                    text: 'Username นี้ถูกลบไปแล้ว',
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


    //ลบผู้ใช้
    $result = delete_user($id);

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
                            text: 'ลบผู้ใช้สำเร็จเรียบร้อยแล้ว',
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
                    text: 'เกิดข้อผิดพลาดในการลบข้อมูลผู้ใช้',
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
                    text: 'เกิดข้อผิดพลาดในการลบข้อมูลผู้ใช้',
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