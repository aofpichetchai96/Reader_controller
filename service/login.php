<?php
  date_default_timezone_set('Asia/Bangkok');
  session_start();
  if (isset($_SESSION['user'])) {
      header("Location: index.php");
      exit;
  }
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="./css/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="./assets/logo-001.png" sizes="32x32">
  <link href="./css/styles.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center h-screen">

  <!-- Login Form -->
  <div class="login-container p-8 rounded-lg shadow-lg w-full max-w-sm">
    <img src="./assets/logo.png" alt="Logo" class="h-12 w-auto mx-auto mb-4">
    <h2 class="text-2xl font-bold text-center mb-6">เข้าสู่ระบบ</h2>

    <!-- Login Form Fields -->
    <form action="./login/check_login.php" method="POST">
      <div class="mb-4">
        <label for="username" class="block text-white">ชื่อผู้ใช้</label>
        <input style="color: black;" type="text" id="username" name="username" placeholder="กรอกชื่อผู้ใช้" class="w-full p-2 border border-gray-300 rounded mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
      </div>

      <div class="mb-6">
        <label for="password" class="block text-white">รหัสผ่าน</label>
        <input style="color: black;" type="password" id="password" name="password" placeholder="กรอกรหัสผ่าน" class="w-full p-2 border border-gray-300 rounded mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
      </div>

      <div class="mb-4">
        <button   href="login.php"  class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200">เข้าสู่ระบบ</button>
      </div>
    </form>
  </div>

</body>

</html>