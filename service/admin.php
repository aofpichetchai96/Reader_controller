<?php
  require_once './function/fnc.php';
  // date_default_timezone_set('Asia/Bangkok');
  session_start();
  if (!isset($_SESSION['user'])) {
      header("Location: login.php");
      exit;
  } 

  $admin_users = get_user_admin();  
  
  if (isset($_POST['toggle_active']) && isset($_POST['user_id'])) {
    $active_user = active_user($_POST['user_id']);  
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
        <span class="font-semibold text-xl">จัดการ User</span>
      </a>
      <ul class="flex space-x-6 items-center">
        <!-- <li><a href="index.php" class="text-white hover:underline">หน้าแรก</a></li> -->
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
        <h2 class="text-xl font-bold">รายการ admin</h2>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="openAddModal()">+ เพิ่ม User</button>
      </div>

      <input type="text" id="searchInput" placeholder="ค้นหาด้วยเบอร์โทร..." class="mb-4 p-2 border border-gray-300 rounded w-full" onkeyup="filterMembers()">

      <table class="table-auto w-full text-left border border-gray-300">
        <thead class="bg-gray-200">
          <tr>
            <th class="px-4 py-2 border">ชื่อ</th>
            <th class="px-4 py-2 border text-center">เบอร์โทร</th>
            <th class="px-4 py-2 border text-center">เข้าใช้งานระบบล่าสุด</th>
            <th class="px-4 py-2 border text-center">สถานะ</th> 
            <th class="px-4 py-2 border text-center">การจัดการ</th>
          </tr>
        </thead>

        <tbody id="memberTable">
          <?php if(count($admin_users) > 0 ) { ?>
            <?php foreach ($admin_users as $user => $value) { ?>
              <tr>
                <td class="px-4 py-2 border">
                  <?php echo $value['prefix'] . $value['firstname'] . ' ' . $value['lastname']; ?>
                </td>
                <td class="px-4 py-2 border text-center"><?php echo $value['phone']; ?></td>
                <td class="px-4 py-2 border text-center"><?php echo $value['lastlogin']; ?></td>
                <td class="px-4 py-2 border text-center">
                  <form method="post" class="inline">
                    <input type="hidden" name="user_id" value="<?php echo $value['id']; ?>">
                    <input type="hidden" name="toggle_active" value="1">
                    <?php if($value['active'] == 1){ ?>
                      <button type="submit" class="w-40 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">เปิดใช้งาน</button>
                    <?php } else { ?>
                      <button type="submit" class="w-40 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">ปิดใช้งาน</button>
                    <?php } ?>
                  </form>
                </td>
                <td class="px-4 py-2 border text-center">
                  <!-- <button class="text-blue-600 hover:underline" 
                          onclick="openEditModal(<?php echo $value['id']; ?>, 
                                               '<?php echo $value['username']; ?>', 
                                               '<?php echo $value['prefix']; ?>', 
                                               '<?php echo $value['firstname']; ?>', 
                                               '<?php echo $value['lastname']; ?>', 
                                               '<?php echo $value['phone']; ?>')">แก้ไข</button> -->
                  <button class="text-red-600 hover:underline ml-2" onclick="confirmDelete(<?php echo $value['id']; ?>)">ลบ</button>
                </td>
              </tr>
            <?php } ?>
          <?php } else { ?>
            <tr>
              <td colspan="5" class="border text-center py-4">ไม่พบข้อมูล</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal เพิ่ม/แก้ไขผู้ใช้ -->
  <div id="memberModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <form method="post" action="./function/save_user.php" class="bg-white p-6 rounded shadow-lg w-full max-w-md">
      <h3 class="text-xl font-bold mb-4" id="modalTitle">เพิ่มสมาชิก</h3>
      
      <input type="hidden" id="user_id" name="user_id" value="">
      <input type="hidden" id="action_type" name="action_type" value="add">
      
      <div class="mb-2">
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" required class="p-2 border w-full rounded">
      </div>
      
      <div class="mb-2">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" class="p-2 border w-full rounded">
        <small id="passwordNote" class="text-gray-500 hidden">เว้นว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน</small>
      </div>
      
      <div class="mb-2">
        <label for="prefix" class="block text-sm font-medium text-gray-700">คำนำหน้า</label>
        <select id="prefix" name="prefix" required class="p-2 border w-full rounded">
          <option value="">-- คำนำหน้า --</option>
          <option value="Mr.">นาย</option>
          <option value="Miss.">นางสาว</option>
          <option value="Mrs.">นาง</option>
        </select>
      </div>
      
      <div class="mb-2">
        <label for="firstname" class="block text-sm font-medium text-gray-700">ชื่อจริง</label>
        <input type="text" id="firstname" name="firstname" placeholder="ชื่อจริง" required class="p-2 border w-full rounded">
      </div>
      
      <div class="mb-2">
        <label for="lastname" class="block text-sm font-medium text-gray-700">นามสกุล</label>
        <input type="text" id="lastname" name="lastname" placeholder="นามสกุล" required class="p-2 border w-full rounded">
      </div>
      
      <div class="mb-4">
        <label for="phone" class="block text-sm font-medium text-gray-700">เบอร์โทร</label>
        <input type="text" id="phone" name="phone" placeholder="เบอร์โทร" required class="p-2 border w-full rounded">
      </div>

      <div class="text-right">
        <button type="button" class="bg-gray-300 px-4 py-2 rounded mr-2" onclick="closeModal()">ยกเลิก</button>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">บันทึก</button>
      </div>
    </form>
  </div>

  <!-- Confirm Delete -->
  <div id="confirmDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-sm text-center">
      <p class="mb-4">คุณแน่ใจหรือไม่ว่าต้องการลบสมาชิกนี้?</p>
      <div class="flex justify-center space-x-4">
        <button class="bg-gray-300 px-4 py-2 rounded" onclick="closeDeleteModal()">ยกเลิก</button>
        <button class="bg-red-600 text-white px-4 py-2 rounded" id="confirmDeleteBtn">ยืนยัน</button>
      </div>
    </div>
  </div>

  <script>
    function openAddModal() {
      // Reset form
      document.getElementById('modalTitle').innerText = 'เพิ่ม User';
      document.getElementById('user_id').value = '';
      document.getElementById('action_type').value = 'add';
      document.getElementById('username').value = '';
      document.getElementById('password').value = '';
      document.getElementById('password').required = true;
      document.getElementById('passwordNote').classList.add('hidden');
      document.getElementById('prefix').value = '';
      document.getElementById('firstname').value = '';
      document.getElementById('lastname').value = '';
      document.getElementById('phone').value = '';
      
      document.getElementById('memberModal').classList.remove('hidden');
    }

    function openEditModal(id, username, prefix, firstname, lastname, phone) {
      document.getElementById('modalTitle').innerText = 'แก้ไขสมาชิก';
      
      // Fill form with user data
      document.getElementById('user_id').value = id;
      document.getElementById('action_type').value = 'edit';
      document.getElementById('username').value = username;
      document.getElementById('password').value = '';
      document.getElementById('password').required = false;
      document.getElementById('passwordNote').classList.remove('hidden');
      document.getElementById('prefix').value = prefix;
      document.getElementById('firstname').value = firstname;
      document.getElementById('lastname').value = lastname;
      document.getElementById('phone').value = phone;
      
      document.getElementById('memberModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('memberModal').classList.add('hidden');
    }

    function confirmDelete(id) {
      Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณต้องการลบผู้ใช้นี้หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ลบ!',
        cancelButtonText: 'ยกเลิก'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = './function/delete_user.php?id=' + id;
        }
      });
    }

    function filterMembers() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const rows = document.querySelectorAll('#memberTable tr');
      rows.forEach(row => {
        const phone = row.children[1]?.innerText;
        row.style.display = phone?.includes(input) ? '' : 'none';
      });
    }
  </script>
</body>
</html>