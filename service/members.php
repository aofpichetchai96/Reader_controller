<?php
  require_once './function/fnc_member.php';

  session_start();
  if (!isset($_SESSION['user'])) {
      header("Location: login.php");
      exit;
  } 

  $member = get_member();  

  if (isset($_POST['toggle_active']) && isset($_POST['user_id'])) {
    $active_member = active_member($_POST['user_id']);  
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
  }
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>จัดการสมาชิก</title>
  <link href="./css/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="./assets/logo-001.png" sizes="32x32">
  <script src="./js/sweetalert2.all.min.js"></script>
</head>

<body class="bg-gray-100 text-gray-800">
  <nav class="bg-black shadow p-4 mb-6">
    <div class="container mx-auto flex justify-between items-center">
      <a href="index.php" class="flex items-center space-x-2 text-white">
        <img src="./assets/logo.png" alt="Logo" class="h-8 w-auto">
        <span class="font-semibold text-xl">จัดการสมาชิก</span>
      </a>
      <ul class="flex space-x-6 items-center">
        <li><a href="index.php" class="text-white hover:underline">หน้าแรก</a></li>
        <li><a href="members.php" class="text-white hover:underline">จัดการสมาชิก</a></li>
        <li><a href="reports.php" class="text-white hover:underline">รายงาน</a></li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) { ?>
          <li><a href="admin.php" class="text-white hover:underline">จัดการ User</a></li>
        <?php } ?>
        <a href="./login/logout.php" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log out</a>
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
            <th class="px-4 py-2 border">วันหมดอายุการ์ด</th>
            <th class="px-4 py-2 border">เลขบัตร Mifare</th>
            <th class="px-4 py-2 border">สถานะ</th>
            <th class="px-4 py-2 border">การจัดการ</th>
          </tr>
        </thead>

        <tbody id="memberTable">
        <?php if(count($member) > 0 ) { 
          foreach ($member as $user => $value) { ?>
            <tr>
              <td class="px-4 py-2 border"><?php echo $value['firstname'] . ' ' . $value['lastname']; ?></td>
              <td class="px-4 py-2 border"><?php echo $value['phone']; ?></td>
              <td class="px-4 py-2 border"><?php echo $value['email']; ?></td>
              <td class="px-4 py-2 border"><?php echo $value['position']; ?></td>
              <td class="px-4 py-2 border"><?php echo $value['enddate']; ?></td>
              <td class="px-4 py-2 border"><?php echo $value['cardnumber']; ?></td>
              <td class="px-4 py-2 border text-center">
                  <form method="post" class="inline">
                    <input type="hidden" name="user_id" value="<?php echo $value['id']; ?>">
                    <input type="hidden" name="toggle_active" value="1">
                    <?php if($value['active'] == 1){ ?>
                      <button type="submit" class="w-40 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">อณุญาติ</button>
                    <?php } else { ?>
                      <button type="submit" class="w-40 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">ไม่อณุญาติ</button>
                    <?php } ?>
                  </form>
              </td>                
              <td class="px-4 py-2 border">
                <button class="text-blue-600 hover:underline" onclick="openEditModal(<?php echo $value['id']; ?>, '<?php echo $value['firstname']; ?>', '<?php echo $value['lastname']; ?>', '<?php echo $value['phone']; ?>', '<?php echo $value['email']; ?>', '<?php echo $value['position']; ?>', '<?php echo $value['enddate']; ?>', '<?php echo $value['cardnumber']; ?>')">แก้ไข</button>
                <button class="text-red-600 hover:underline ml-2" onclick="confirmDelete(<?php echo $value['id']; ?>)">ลบ</button>
              </td>
            </tr>
          <?php }} else{?>
            <tr>
              <td colspan="9" class="border text-center py-4">ไม่พบข้อมูล Member</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Add/Edit Modal -->
  <div id="memberModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
      <h3 class="text-xl font-bold mb-4" id="modalTitle">เพิ่มสมาชิก</h3>
      
      <!-- Form to Add/Edit Member -->
      <form action="./function/save_member.php" method="POST">
          <input type="hidden" id="member_id" name="member_id" value="">
          <input type="text" id="firstname" name="firstname" placeholder="ชื่อ" class="mb-2 p-2 border w-full" required>
          <input type="text" id="lastname" name="lastname" placeholder="นามสกุล" class="mb-2 p-2 border w-full" required>
          <input type="text" id="phone" name="phone" placeholder="เบอร์โทร" class="mb-2 p-2 border w-full" required>
          <input type="email" id="email" name="email" placeholder="อีเมล" class="mb-2 p-2 border w-full" required>
          <input type="text" id="position" name="position" placeholder="ตำแหน่ง" class="mb-2 p-2 border w-full" required>
          <input type="datetime-local" id="enddate" name="enddate" placeholder="วันหมดอายุและเวลา" class="mb-2 p-2 border w-full" required>
          <input type="text" id="cardnumber" name="cardnumber" placeholder="เลขบัตร Mifare" class="mb-4 p-2 border w-full" required>
          
          <div class="text-right">
            <button type="button" class="bg-gray-300 px-4 py-2 rounded mr-2" onclick="closeModal()">ยกเลิก</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">บันทึก</button>
          </div>
      </form>
    </div>
  </div>

  <script>
    function openAddModal() {
      document.getElementById('modalTitle').innerText = 'เพิ่มสมาชิก';
      // Reset form fields
      document.getElementById('member_id').value = '';
      document.getElementById('firstname').value = '';
      document.getElementById('lastname').value = '';
      document.getElementById('phone').value = '';
      document.getElementById('email').value = '';
      document.getElementById('position').value = '';
      document.getElementById('enddate').value = '';
      document.getElementById('cardnumber').value = '';
      document.getElementById('memberModal').classList.remove('hidden');
    }

    function openEditModal(id, firstname, lastname, phone, email, position, enddate, cardnumber) {
      document.getElementById('modalTitle').innerText = 'แก้ไขข้อมูลสมาชิก';
      
      // Fill form with existing data
      document.getElementById('member_id').value = id;
      document.getElementById('firstname').value = firstname;
      document.getElementById('lastname').value = lastname;
      document.getElementById('phone').value = phone;
      document.getElementById('email').value = email;
      document.getElementById('position').value = position;
      
      // Format enddate for datetime-local input
      const formattedDate = formatDateTimeForInput(enddate);
      document.getElementById('enddate').value = formattedDate;
      
      document.getElementById('cardnumber').value = cardnumber;
      document.getElementById('memberModal').classList.remove('hidden');
    }

    function formatDateTimeForInput(dateTimeStr) {
      // Format the date and time to match the datetime-local input format
      // Example: "2023-05-23 14:30:00" -> "2023-05-23T14:30"
      try {
        const dateObj = new Date(dateTimeStr);
        // Format date as YYYY-MM-DDThh:mm
        if (isNaN(dateObj.getTime())) {
          // If conversion fails, try to parse manually
          const parts = dateTimeStr.split(' ');
          if (parts.length === 2) {
            return parts[0] + 'T' + parts[1].substring(0, 5);
          }
          return dateTimeStr; // Return as is if parsing fails
        }
        
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
      } catch (e) {
        console.error("Error formatting date:", e);
        return dateTimeStr; // Return original if formatting fails
      }
    }

    function closeModal() {
      document.getElementById('memberModal').classList.add('hidden');
    }

    function filterMembers() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const rows = document.querySelectorAll('#memberTable tr');
      rows.forEach(row => {
        const phone = row.children[1].innerText;
        row.style.display = phone.includes(input) ? '' : 'none';
      });
    }

    function confirmDelete(id) {
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณต้องการลบสมาชิกนี้หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบ!',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = './function/delete_member.php?id=' + id;
        }
      });
    }
  </script>
</body>

</html>