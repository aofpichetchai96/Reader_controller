<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>จัดการสมาชิก</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-800">
  <nav class="bg-black shadow p-4 mb-6">
    <div class="container mx-auto flex justify-between items-center">
      <a href="index.php" class="flex items-center space-x-2 text-white">
        <img src="./assets/logo.png" alt="Logo" class="h-8 w-auto">
        <span class="font-semibold text-xl">ระบบจัดการสมาชิก</span>
      </a>
      <ul class="flex space-x-6 items-center">
        <li><a href="index.php" class="text-white hover:underline">หน้าแรก</a></li>
        <li><a href="members.php" class="text-white hover:underline">จัดการสมาชิก</a></li>
        <li><a href="reports.php" class="text-white hover:underline">รายงาน</a></li>
        <!-- <li><a href="about.php" class="text-white hover:underline">เกี่ยวกับ</a></li> -->
        <!-- <li><a href="contacts.php" class="text-white hover:underline">ผู้ติดต่อ</a></li> -->
        <a href="login.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log out</a>
      </ul>
      
    </div>
  </nav>

  <main class="container mx-auto">
    <!-- หน้าตัวอย่าง: โปรดเลือกเมนูด้านบน -->
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
      <h1 class="text-2xl font-bold mb-2">ยินดีต้อนรับสู่ระบบจัดการสมาชิก</h1>
      <p class="text-gray-600">กรุณาเลือกเมนูจากด้านบนเพื่อเริ่มต้นใช้งาน</p>
    </div>
  </main>
</body>

</html>