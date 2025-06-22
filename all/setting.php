
<?php
session_start();

require_once('../classes/database.php');
$con = new database();

$loggedInUserType = ''; 
$userID = null;
$pageTitle = "Settings"; 

if (isset($_SESSION['OwnerID'])) {
    $loggedInUserType = 'owner';
    $userID = $_SESSION['OwnerID'];
    $pageTitle = "Owner Settings";
} elseif (isset($_SESSION['EmployeeID'])) {
    $loggedInUserType = 'employee';
    $userID = $_SESSION['EmployeeID'];
    $pageTitle = "Employee Settings";
} elseif (isset($_SESSION['CustomerID'])) {
    $loggedInUserType = 'customer';
    $userID = $_SESSION['CustomerID'];
    $pageTitle = "Customer Settings";
} else {
    header('Location: login.php'); 
    exit();
}

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($con->updateUserData($userID, $loggedInUserType, $_POST)) { 
        $saved = true;
    }
}

$userData = $con->getUserData($userID, $loggedInUserType);

if (empty($userData)) {
    echo "Error: User could not be found. Please try logging in again.";

    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('../images/LAbg.png') no-repeat center center fixed;
            background-size: cover;
            background-color: rgba(255, 255, 255, 0.7);
        }
        .sidebar {
            width: 90px;
            background-color: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;
            gap: 35px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 10;
        }
        .sidebar a, .sidebar button {
            color: #4B2E0E;
            font-size: 26px;
            text-decoration: none;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
        .sidebar a:hover, .sidebar button:hover {
            color: #C4A07A;
        }
        .main-content-area {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem; 
        }
        .overflow-y-auto::-webkit-scrollbar { width: 8px; }
        .overflow-y-auto::-webkit-scrollbar-track { background: rgba(200, 200, 200, 0.3); border-radius: 10px; }
        .overflow-y-auto::-webkit-scrollbar-thumb { background-color: #C4A07A; border-radius: 10px; border: 2px solid rgba(255, 255, 255, 0.5); }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover { background-color: #a17850; }
    </style>
</head>
<body class="min-h-screen flex">
  
  <?php if ($loggedInUserType == 'owner'): ?>
    <!-- Owner Sidebar -->
    <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
    <img src="../images/logo.png" alt="Logo" class="w-10 h-10 rounded-full mb-4" />
    <?php $current = basename($_SERVER['PHP_SELF']); ?>   
    <button title="Dashboard" onclick="window.location.href='../Owner/dashboard.php'">
        <i class="fas fa-chart-line text-xl <?= $current == 'dashboard.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Home" onclick="window.location.href='../Owner/mainpage.php'">
        <i class="fas fa-home text-xl <?= $current == 'mainpage.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Orders" onclick="window.location.href='../Owner/page.php'">
        <i class="fas fa-shopping-cart text-xl <?= $current == 'page.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Order List" onclick="window.location.href='../all/tranlist.php'">
        <i class="fas fa-list text-xl <?= $current == 'tranlist.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Inventory" onclick="window.location.href='../Owner/product.php'">
        <i class="fas fa-box text-xl <?= $current == 'product.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Users" onclick="window.location.href='../Owner/user.php'">
        <i class="fas fa-users text-xl <?= $current == 'user.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Settings" onclick="window.location.href='../all/setting.php'">
        <i class="fas fa-cog text-xl <?= $current == 'setting.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button id="logout-btn" title="Logout">
        <i class="fas fa-sign-out-alt text-xl text-[#4B2E0E]"></i>
    </button>
</aside>
  <?php elseif ($loggedInUserType == 'employee'): ?>
    <!-- Employee Sidebar -->
    <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
    <img src="../images/logo.png" alt="Logo" class="w-10 h-10 rounded-full mb-4" />
    <?php $current = basename($_SERVER['PHP_SELF']); ?>   

    <button title="Home" onclick="window.location.href='../Employee/employesmain.php'">
        <i class="fas fa-home text-xl <?= $current == 'employesmain.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Cart" onclick="window.location.href='../Employee/employeepage.php'">
        <i class="fas fa-shopping-cart text-xl <?= $current == 'employeepage.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Transaction Records" onclick="window.location.href='../all/tranlist.php'">
        <i class="fas fa-list text-xl <?= $current == 'tranlist.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Box" onclick="window.location.href='../Employee/productemployee.php'">
        <i class="fas fa-box text-xl <?= $current == 'productemployee.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Settings" onclick="window.location.href='../all/setting.php'">
        <i class="fas fa-cog text-xl <?= $current == 'setting.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button id="logout-btn" title="Logout">
        <i class="fas fa-sign-out-alt text-xl text-[#4B2E0E]"></i>
    </button>
</aside>
  <?php elseif ($loggedInUserType == 'customer'): ?>
    <!-- Customer Sidebar -->
     <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
     <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <img src="../images/logo.png" alt="Logo" style="width: 56px; height: 56px; border-radius: 9999px; margin-bottom: 25px;" />
  <button aria-label="Home" class="text-xl" title="Home" type="button" onclick="window.location='../Customer/advertisement.php'">
    <i class="fas fa-home <?= $currentPage === 'advertisement.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button aria-label="Cart" class="text-xl" title="Cart" type="button" onclick="window.location='../Customer/customerpage.php'">
    <i class="fas fa-shopping-cart <?= $currentPage === 'customerpage.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button aria-label="Order List" class="text-xl" title="Order List" type="button" onclick="window.location='../Customer/transactionrecords.php'">
    <i class="fas fa-list <?= $currentPage === 'transactionrecords.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button aria-label="Settings" class="text-xl" title="Settings" type="button" onclick="window.location='../all/setting.php'">
    <i class="fas fa-cog <?= $currentPage === 'setting.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button id="logout-btn" aria-label="Logout" name="logout" class="text-xl" title="Logout" type="button">
    <i class="fas fa-sign-out-alt text-[#4B2E0E]"></i>
  </button>
</aside>
  <?php endif; ?>

  <!-- Main content area for the settings form -->
  <div class="main-content-area">
    <div class="bg-white/90 rounded-2xl shadow-xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-[#4B2E0E] mb-6 text-center">Account Settings</h2>     
        <?php if ($saved): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Success', 'Profile updated successfully!', 'success');
            });
        </script>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">New Password <span class="text-xs text-gray-400">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" placeholder="Enter new password">
            </div>
            <button type="submit" class="w-full bg-[#c19a6b] hover:bg-[#a17850] text-white font-semibold py-2 rounded-lg transition">Save Changes</button>
        </form>
    </div>
  </div>

    <script>
        document.getElementById("logout-btn").addEventListener("click", () => {
            Swal.fire({
                title: 'Are you sure you want to log out?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4B2E0E',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "logout.php"; 
                }
            });
        });

        <?php if ($loggedInUserType == 'customer'): ?>
    document.getElementById("logout-btn").addEventListener("click", () => {
     Swal.fire({
       title: 'Are you sure you want to log out?',
       icon: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#4B2E0E',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Yes, log out',
       cancelButtonText: 'Cancel'
     }).then((result) => {
       if (result.isConfirmed) {
         window.location.href = "../all/logoutcos.php";
       }
     });
   });
  <?php endif; ?>
    </script>
</body>
</html>