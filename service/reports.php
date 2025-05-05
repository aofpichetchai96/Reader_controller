<?php
  session_start();
  require_once './function/report.php';
  if (!isset($_SESSION['user'])) {
      header("Location: login.php");
      exit;
  }

  if (isset($_POST['atdate'])) {
    $atdate = $_POST['atdate'];
  } elseif (isset($_GET['atdate'])) {
      $atdate = $_GET['atdate'];
  } else {
      $atdate = date('Y-m-d');
  }

  // จำนวนรายการต่อหน้า
  $items_per_page = 10;
  
  // ดึงข้อมูลสรุป
  $data_summary = get_data($atdate);
  
  // จัดการ pagination
  $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  if ($current_page < 1) $current_page = 1;
  
  // เรียกข้อมูลตามช่วงที่ต้องการ
  $data_transaction = data_transaction($atdate, $items_per_page, ($current_page - 1) * $items_per_page);
  
  // ดึงจำนวนข้อมูลทั้งหมด
  $total_items = get_total_transactions($atdate);
  
  $total_pages = ceil($total_items[0]['total'] / $items_per_page);
  // print_r($total_pages);
  // ปรับค่าหน้าปัจจุบัน หากเกินจำนวนหน้าทั้งหมด
  if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
    // Redirect to the correct page
    header("Location: reports.php?page=$current_page&atdate=$atdate");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายงานการเข้าออก</title>
  <link href="./css/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="./assets/logo-001.png" sizes="32x32">
  <!-- <link href="styles.css" rel="stylesheet"> -->
 
</head>

<body class="bg-gray-100 text-gray-800">
  <nav class="bg-black shadow p-4 mb-6">
    <div class="container mx-auto flex justify-between items-center">
      <a href="index.php" class="flex items-center space-x-2 text-white">
        <img src="./assets/logo.png" alt="Logo" class="h-8 w-auto">
        <span class="font-semibold text-xl">รายงาน</span>
      </a>
      <ul class="flex space-x-6 items-center">
        <!-- <li><a href="index.php" class="text-white hover:underline">หน้าแรก</a></li> -->
        <li><a href="members.php" class="text-white hover:underline">จัดการสมาชิก</a></li>
        <li><a href="reports.php" class="text-white hover:underline">รายงาน</a></li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) { ?>
          <li><a href="admin.php" class="text-white hover:underline">จัดการ User</a></li>
        <?php } ?>
        <!-- <li><a href="contacts.php" class="text-white hover:underline">ผู้ติดต่อ</a></li> -->
        <a href="./login/logout.php"  class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Log out</a>
      </ul>
    </div>
  </nav>

  <main class="container mx-auto">

        <!-- สถิติ -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">      
          <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold">สถิติการ Scan Access</h2>            
            <form action="" method="POST" class="flex items-center space-x-2">
              <label class="text-xl font-bold">วันที่</label>
              <input class="border rounded px-2 py-1 cursor-pointer text-xl font-bold" type="date" name="atdate" id="atdate" value="<?php echo $atdate; ?>">
              <input type="hidden" name="page" value="1">
              <input class="bg-green-600 text-white cursor-pointer px-4 py-2 rounded hover:bg-green-700" type="submit" value="Search">             
            </form>
            <form action="" method="POST" class="flex items-center space-x-2">
              <input type="hidden" name="atdate" value="<?php echo $atdate; ?>">
              <input class="bg-green-600 text-white cursor-pointer px-4 py-2 rounded hover:bg-green-700" type="submit" value="Refresh">    
              <!-- <a class="bg-green-600 text-white cursor-pointer px-4 py-2 rounded hover:bg-green-700" href="reports.php">Refresh</a> -->
            </form>
          </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
              <div class="bg-green-600 text-white p-4 rounded-lg text-center">
                <p class="text-2xl font-bold">จำนวนการ Scan Access<br>สำเร็จของ Staft</p>
                <p class="text-4xl"><?php echo $data_summary[0]['cnt_staft_true'];?></p>
              </div>
              <div class="bg-red-600 text-white p-4 rounded-lg text-center">
                <p class="text-2xl font-bold">จำนวนการ Scan Access<br>ไม่สำเร็จของ Staft</p>
                <p class="text-4xl"><?php echo $data_summary[0]['cnt_staft_false'];?></p>
              </div>

              <div class="bg-blue-600 text-white p-4 rounded-lg text-center">
                <p class="text-2xl font-bold">จำนวนการ Scan Access<br>สำเร็จของ Student</p>
                <p class="text-4xl"><?php echo $data_summary[0]['cnt_student_true'];?></p>
              </div>
              <div class="bg-yellow-600 text-white p-4 rounded-lg text-center">
                <p class="text-2xl font-bold">จำนวนการ Scan Access<br>ไม่สำเร็จของ Student</p>
                <p class="text-4xl"><?php echo $data_summary[0]['cnt_student_false'];?></p>
              </div>
            </div>
          </div>
          
    <!-- รายงานคนเข้าออก -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Transactions Scan Access <?php echo $atdate; ?></h2>
        <div class="text-gray-600">
          แสดง <?php echo min(($current_page - 1) * $items_per_page + 1, $total_items[0]['total']); ?> - 
          <?php echo min($current_page * $items_per_page, $total_items[0]['total']); ?> 
          จากทั้งหมด <?php echo $total_items[0]['total']; ?> รายการ
        </div>
      </div>

      <table class="table-auto w-full text-left border border-gray-300" style=" table-layout: fixed; white-space: pre;">
        <thead class="bg-gray-200">
            <tr>
              <th class="px-4 py-2 border w-1/12">ลำดับ</th>
              <th class="px-4 py-2 border w-2/12">Datetime</th>
              <th class="px-4 py-2 border w-4/12">Token</th>
              <th class="px-4 py-2 border w-1/12">Type</th>
              <th class="px-4 py-2 border w-1/12">Status</th>
              <th class="px-4 py-2 border w-3/12">Message</th>
            </tr>
          </thead>
        </thead>
        <tbody>
          <?php if(count($data_transaction) > 0 ) { ?>
            <?php foreach ($data_transaction as $key => $value) { 
              $row_number = ($current_page - 1) * $items_per_page + $key + 1;
            ?>
              <tr>
                <td class="px-4 py-2 border text-center"><?php echo $row_number; ?></td>
                <td class="px-4 py-2 border text-center"><?php echo $value['createtime']; ?></td>
                <td class="px-4 py-2 border" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo $value['cardid']; ?></td>
                <td class="px-4 py-2 border text-center"><?php echo $value['type']; ?></td>
                <td class="px-4 py-2 border status-cell text-center"><?php echo $value['rs_success']; ?></td>
                <td class="px-4 py-2 border" style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;"><?php echo $value['rs_message']; ?></td>
              </tr>
            <?php } ?>           
          <?php } else { ?>
            <tr>
              <td colspan="6" class="px-4 py-2 border text-red-600 text-center">ไม่มีข้อมูล Transactions Scan</td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <?php if($total_pages > 1): ?>
      <div class="mt-6 flex justify-center">
        <div class="flex space-x-1">
          <!-- Previous page -->
          <?php if($current_page > 1): ?>
          <a href="?page=<?php echo $current_page - 1; ?>&atdate=<?php echo $atdate; ?>" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            &laquo; ก่อนหน้า
          </a>
          <?php else: ?>
          <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed">
            &laquo; ก่อนหน้า
          </span>
          <?php endif; ?>
          
          <!-- Page numbers -->
          <?php
          $start_page = max(1, $current_page - 2);
          $end_page = min($total_pages, $current_page + 2);
          
          if ($start_page > 1) {
            echo '<a href="?page=1&atdate='.$atdate.'" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">1</a>';
            if ($start_page > 2) {
              echo '<span class="px-2 py-2">...</span>';
            }
          }
          
          for($i = $start_page; $i <= $end_page; $i++): 
          ?>
            <?php if($i == $current_page): ?>
            <span class="px-4 py-2 bg-blue-500 text-white rounded">
              <?php echo $i; ?>
            </span>
            <?php else: ?>
            <a href="?page=<?php echo $i; ?>&atdate=<?php echo $atdate; ?>" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
              <?php echo $i; ?>
            </a>
            <?php endif; ?>
          <?php endfor; 
          
          if ($end_page < $total_pages) {
            if ($end_page < $total_pages - 1) {
              echo '<span class="px-2 py-2">...</span>';
            }
            echo '<a href="?page='.$total_pages.'&atdate='.$atdate.'" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">'.$total_pages.'</a>';
          }
          ?>
          
          <!-- Next page -->
          <?php if($current_page < $total_pages): ?>
          <a href="?page=<?php echo $current_page + 1; ?>&atdate=<?php echo $atdate; ?>" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            ถัดไป &raquo;
          </a>
          <?php else: ?>
          <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed">
            ถัดไป &raquo;
          </span>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </main>

  <script>
  // ฟังก์ชันในการอัปเดตการแสดงสถานะและประเภทของการ Scan
  function updateStatus() {
    // อัปเดตเซลล์สถานะ
    const statusCells = document.querySelectorAll('.status-cell');
    statusCells.forEach(cell => {
      const status = cell.innerText;
      if (status != 'true') {
        cell.innerText = 'ไม่สำเร็จ';
        cell.classList.add('text-red-600', 'font-medium');
      } else {
        cell.innerText = 'สำเร็จ';
        cell.classList.add('text-green-600', 'font-medium');
      }
    });

    // อัปเดตเซลล์ประเภท (Type)
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
      const typeCell = row.querySelector('td:nth-child(4)'); // ตำแหน่งคอลัมน์ Type
      if (typeCell) {
        const type = typeCell.innerText;
        if (type === 'staft') {
          typeCell.classList.add('text-yellow-600', 'font-medium');
        } else if (type === 'student') {
          typeCell.classList.add('text-blue-600', 'font-medium');
        }
      }
    });
  }

  // เรียกใช้ฟังก์ชันเมื่อโหลดหน้า
  document.addEventListener('DOMContentLoaded', updateStatus);
</script>
</body>

</html>