<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายงานการเข้าออก</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- <link href="styles.css" rel="stylesheet"> -->
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

        <!-- สถิติ -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">สถิติ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div class="bg-blue-600 text-white p-4 rounded-lg text-center">
                <p class="text-2xl font-bold">จำนวนคนเข้า</p>
                <p class="text-4xl">5</p>
              </div>
              <div class="bg-green-600 text-white p-4 rounded-lg text-center">
                <p class="text-2xl font-bold">จำนวนคนออก</p>
                <p class="text-4xl">4</p>
              </div>
              <div class="bg-yellow-600 text-white p-4 rounded-lg text-center">
                <p class="text-2xl font-bold">คนเข้าเฉลี่ย/วัน</p>
                <p class="text-4xl">4</p>
              </div>
            </div>
          </div>
    <!-- รายงานคนเข้าออก -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
      <h2 class="text-xl font-bold mb-4">รายงานคนเข้าออก</h2>

      <table class="table-auto w-full text-left border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">วันที่</th>
            <th class="px-4 py-2 border">ชื่อ</th>
            <th class="px-4 py-2 border">เวลาเข้า</th>
            <th class="px-4 py-2 border">เวลาออก</th>
            <th class="px-4 py-2 border">สถานะ</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="px-4 py-2 border">2025-04-24</td>
            <td class="px-4 py-2 border">สมชาย ใจดี</td>
            <td class="px-4 py-2 border">08:00</td>
            <td class="px-4 py-2 border">17:00</td>
            <td class="px-4 py-2 border text-green-600">ออกแล้ว</td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">2025-04-24</td>
            <td class="px-4 py-2 border">สายฝน เย็นใจ</td>
            <td class="px-4 py-2 border">09:00</td>
            <td class="px-4 py-2 border">-</td>
            <td class="px-4 py-2 border text-yellow-600">กำลังทำงาน</td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">2025-04-23</td>
            <td class="px-4 py-2 border">สมหญิง หรรษา</td>
            <td class="px-4 py-2 border">08:30</td>
            <td class="px-4 py-2 border">17:30</td>
            <td class="px-4 py-2 border text-green-600">ออกแล้ว</td>
          </tr>
        </tbody>
      </table>
    </div>


  </main>

  <script>
    // ฟังก์ชันในการคำนวณสถานะของผู้ที่ยังไม่ออก
    function updateStatus() {
      const rows = document.querySelectorAll('tbody tr');
      rows.forEach(row => {
        const exitTime = row.children[3].innerText;
        if (exitTime === '-') {
          row.children[4].innerText = 'กำลังทำงาน';
          row.children[4].classList.add('text-yellow-600');
        } else {
          row.children[4].innerText = 'ออกแล้ว';
          row.children[4].classList.add('text-green-600');
        }
      });
    }

    // เรียกใช้ฟังก์ชันเมื่อโหลดหน้า
    updateStatus();
  </script>
</body>

</html>
