<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>จัดการสมาชิก</title>
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
    <div class="bg-white p-6 rounded-lg shadow-md">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">รายการสมาชิก</h2>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="openAddModal()">+ เพิ่มสมาชิก</button>
      </div>

      <input type="text" id="searchInput" placeholder="ค้นหาด้วยเบอร์โทร..." class="mb-4 p-2 border border-gray-300 rounded w-full" onkeyup="filterMembers()">

      <table class="table-auto w-full text-left border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">ชื่อ</th>
            <th class="px-4 py-2 border">เบอร์โทร</th>
            <th class="px-4 py-2 border">อีเมล</th>
            <th class="px-4 py-2 border">ตำแหน่ง</th>
            <th class="px-4 py-2 border">วันหมดอายุ</th>
            <th class="px-4 py-2 border">เลขบัตร Mifare</th>
            <th class="px-4 py-2 border">สถานะ</th> <!-- เพิ่มคอลัมน์สถานะ -->
            <th class="px-4 py-2 border">การจัดการ</th>
          </tr>
        </thead>
        <tbody id="memberTable">
          <tr>
            <td class="px-4 py-2 border">สมชาย ใจดี</td>
            <td class="px-4 py-2 border">0801234567</td>
            <td class="px-4 py-2 border">somchai@example.com</td>
            <td class="px-4 py-2 border">ผู้จัดการ</td>
            <td class="px-4 py-2 border">2025-12-31</td>
            <td class="px-4 py-2 border">MF12345678</td>
            <td class="px-4 py-2 border">ใช้งาน</td> <!-- แสดงสถานะ -->
            <td class="px-4 py-2 border">
              <button class="text-blue-600 hover:underline" onclick="openEditModal()">แก้ไข</button>
              <button class="text-red-600 hover:underline ml-2" onclick="confirmDelete()">ลบ</button>
            </td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">สายฝน เย็นใจ</td>
            <td class="px-4 py-2 border">0899876543</td>
            <td class="px-4 py-2 border">saifon@example.com</td>
            <td class="px-4 py-2 border">เจ้าหน้าที่</td>
            <td class="px-4 py-2 border">2024-10-15</td>
            <td class="px-4 py-2 border">MF87654321</td>
            <td class="px-4 py-2 border">ไม่ใช้งาน</td> <!-- แสดงสถานะ -->
            <td class="px-4 py-2 border">
              <button class="text-blue-600 hover:underline" onclick="openEditModal()">แก้ไข</button>
              <button class="text-red-600 hover:underline ml-2" onclick="confirmDelete()">ลบ</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Add/Edit Modal -->
  <div id="memberModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
      <h3 class="text-xl font-bold mb-4" id="modalTitle">เพิ่มสมาชิก</h3>
      <input type="text" placeholder="ชื่อ" class="mb-2 p-2 border w-full">
      <input type="text" placeholder="เบอร์โทร" class="mb-2 p-2 border w-full">
      <input type="email" placeholder="อีเมล" class="mb-2 p-2 border w-full">
      <input type="text" placeholder="ตำแหน่ง" class="mb-2 p-2 border w-full">
      <input type="date" placeholder="วันหมดอายุ" class="mb-2 p-2 border w-full">
      <input type="text" placeholder="เลขบัตร Mifare" class="mb-4 p-2 border w-full">
      <div class="text-right">
        <button class="bg-gray-300 px-4 py-2 rounded mr-2" onclick="closeModal()">ยกเลิก</button>
        <button class="bg-blue-600 text-white px-4 py-2 rounded">บันทึก</button>
      </div>
    </div>
  </div>

  <!-- Confirm Delete -->
  <div id="confirmDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm text-center">
      <p class="mb-4">คุณแน่ใจหรือไม่ว่าต้องการลบสมาชิกนี้?</p>
      <div class="flex justify-center space-x-4">
        <button class="bg-gray-300 px-4 py-2 rounded" onclick="closeDeleteModal()">ยกเลิก</button>
        <button class="bg-red-600 text-white px-4 py-2 rounded">ยืนยัน</button>
      </div>
    </div>
  </div>

  <script>
    function openAddModal() {
      document.getElementById('modalTitle').innerText = 'เพิ่มสมาชิก';
      document.getElementById('memberModal').classList.remove('hidden');
    }
    function openEditModal() {
      document.getElementById('modalTitle').innerText = 'แก้ไขสมาชิก';
      document.getElementById('memberModal').classList.remove('hidden');
    }
    function closeModal() {
      document.getElementById('memberModal').classList.add('hidden');
    }
    function confirmDelete() {
      document.getElementById('confirmDeleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
      document.getElementById('confirmDeleteModal').classList.add('hidden');
    }
    function filterMembers() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const rows = document.querySelectorAll('#memberTable tr');
      rows.forEach(row => {
        const phone = row.children[1].innerText;
        row.style.display = phone.includes(input) ? '' : 'none';
      });
    }
  </script>
</body>

</html>
