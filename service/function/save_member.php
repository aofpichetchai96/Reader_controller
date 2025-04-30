<?php
require_once './fnc_member.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $enddate = date('Y-m-d H:i:s', strtotime($_POST['enddate']));
    $cardnumber = $_POST['cardnumber'];
    $createby = $_SESSION['user'] ? $_SESSION['user'] : null;

    if($member_id == ''){  
        //เพิ่ม Member
        if (check_member_exists($cardnumber)) {
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
                        text: 'เลขบัตร Mifare นี้มีอยู่แล้ว',
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

        // เพิ่ม Member ใหม่   
        $result = create_member($firstname, $lastname, $phone, $email, $position, $enddate, $cardnumber, $createby);

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
                            text: 'เพิ่ม Member สำเร็จเรียบร้อยแล้ว',
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
                        text: 'เกิดข้อผิดพลาดในการเพิ่ม Member',
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
        //แก้ไข Member
        if (check_id_member_exists($member_id)) {
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

        $result = update_member($firstname, $lastname, $phone, $email, $position, $enddate, $cardnumber, $createby, $member_id);

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
                            text: 'แก้ไข Member สำเร็จเรียบร้อยแล้ว',
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
                        text: 'เกิดข้อผิดพลาดในการแก้ไข Member',
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
                    text: 'เกิดข้อผิดพลาดในการจัดการ Member',
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
