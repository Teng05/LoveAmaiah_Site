<?php
session_start();
ob_start(); // Start output buffering

// --- Temporarily enable full error reporting for debugging ---
// REMOVE THESE LINES IN PRODUCTION TO PREVENT SENSITIVE INFORMATION LEAKS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// --- End temporary debugging setup ---

$loggedInUserType = null;
$loggedInID = null;

if (isset($_SESSION['OwnerID'])) {
    $loggedInUserType = 'owner';
    $loggedInID = $_SESSION['OwnerID'];
    error_log("DEBUG: tranlist.php - Logged in as Owner with ID: " . $loggedInID);
} elseif (isset($_SESSION['EmployeeID'])) {
    $loggedInUserType = 'employee';
    $loggedInID = $_SESSION['EmployeeID'];
    error_log("DEBUG: tranlist.php - Logged in as Employee with ID: " . $loggedInID);
} else {
    error_log("ERROR: tranlist.php - No OwnerID or EmployeeID in session. Redirecting to login.");
    header('Location: login.php'); // login.php is in the same 'all' folder
    ob_end_clean();
    exit();
}

require_once('../classes/database.php'); // Path from 'all/' to 'classes/'
$con = new database();

$allOrders = $con->getOrdersForOwnerOrEmployee($loggedInID, $loggedInUserType);
error_log("DEBUG: tranlist.php - getOrdersForOwnerOrEmployee returned " . count($allOrders) . " orders.");
error_log("DEBUG: tranlist.php - Raw fetched orders array: " . print_r($allOrders, true));


$customerAccountOrders = [];
$walkinStaffOrders = [];

foreach ($allOrders as $transaction) { 
    if ($transaction['UserTypeID'] == 3 && !empty($transaction['CustomerUsername'])) {
        $customerAccountOrders[] = $transaction;
    } else {
        $walkinStaffOrders[] = $transaction;
    }
}

error_log("DEBUG: tranlist.php - Categorized customerAccountOrders count: " . count($customerAccountOrders));
error_log("DEBUG: tranlist.php - Categorized walkinStaffOrders count: " . count($walkinStaffOrders));

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Transaction Records</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { 
        font-family: 'Inter', sans-serif; 
        background: url('../images/LAbg.png') no-repeat center center/cover; 
    }
    /* Styles for the sidebar and main content layout */
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
        z-index: 10; /* Ensure sidebar is above main content */
    }
    .sidebar a, .sidebar button {
        color: #4B2E0E;
        font-size: 26px;
        text-decoration: none;
        transition: color 0.3s ease;
        /* Make button look like link */
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
    }
    .sidebar a:hover, .sidebar button:hover {
        color: #C4A07A;
    }

    /* Adjust main content positioning */
    .main-content {
        flex-grow: 1;
        padding: 1.5rem; /* Equivalent to p-6 */
        position: relative; /* For background image */
        display: flex;
        flex-direction: column;
        align-items: center; /* Center content within main-content */
        justify-content: center; /* Center content within main-content */
    }

    /* Background image for main-content */
    .main-content .bg-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.2;
        z-index: -10;
    }
    
    .overflow-y-auto::-webkit-scrollbar {
        width: 8px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: rgba(200, 200, 200, 0.3);
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background-color: #C4A07A;
        border-radius: 10px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background-color: #a17850;
    }
  </style>
</head>
<body class="min-h-screen flex">
  
  <?php if ($loggedInUserType == 'owner'): ?>
    <!-- Owner Sidebar -->
    <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <img src="../images/logo.png" alt="Logo" class="w-10 h-10 rounded-full mb-4" />
  <button title="Dashboard" onclick="window.location='../Owner/dashboard.php'"><i class="fas fa-chart-line text-xl"></i></button>
  <button title="Home" onclick="window.location='../Owner/mainpage.php'"><i class="fas fa-home text-xl"></i></button>
  <button title="Orders" onclick="window.location='../Owner/page.php'"><i class="fas fa-shopping-cart text-xl"></i></button>
  <button title="Order List" onclick="window.location='../all/tranlist.php'"><i class="fas fa-list text-xl"></i></button>
  <button title="Inventory" onclick="window.location='../Owner/product.php'"><i class="fas fa-box text-xl"></i></button>
  <button title="Users" onclick="window.location='../Owner/user.php'"><i class="fas fa-users text-xl"></i></button>
  <button title="Settings" onclick="window.location='../all/setting.php'"><i class="fas fa-cog text-xl"></i></button>
  <button id="logout-btn" title="Logout"><i class="fas fa-sign-out-alt text-xl"></i></button>
</aside>
  <?php elseif ($loggedInUserType == 'employee'): ?>
    <!-- Employee Sidebar -->
    <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
   <button aria-label="Home" class="text-[#4B2E0E] text-xl" title="Home" type="button" onclick="window.location='../Employee/employesmain.php'"><i class="fas fa-home"></i></button>
   <button aria-label="Cart" class="text-[#4B2E0E] text-xl" title="Cart" type="button" onclick="window.location='../Employee/employeepage.php'"><i class="fas fa-shopping-cart"></i></button>
   <button aria-label="Order List" class="text-[#4B2E0E] text-xl" title="Transaction Records" type="button" onclick="window.location='../all/tranlist.php'"><i class="fas fa-list"></i></button>
   <button aria-label="Box" class="text-[#4B2E0E] text-xl" title="Box" type="button" onclick="window.location='../Employee/productemployee.php'"><i class="fas fa-box"></i></button>
   <button aria-label="Settings" class="text-[#4B2E0E] text-xl" title="Settings" type="button" onclick="window.location='../all/setting.php'"><i class="fas fa-cog"></i></button>
   <button id="logout-btn" aria-label="Logout" name="logout" class="text-[#4B2E0E] text-xl" title="Logout" type="button"><i class="fas fa-sign-out-alt"></i></button>
  </aside>
  <?php endif; ?>

  <!-- Main content container -->
  <div class="main-content">
    <img src="../images/Labg.png" alt="Background image of coffee beans" class="bg-image" />
    
    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
    
        <!-- Customer Account Orders Box (Left Column on md screens and up) -->
        <div>
          <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
            <i class="fas fa-user-check"></i> Customer Account Orders
          </h1>
          <?php if (empty($customerAccountOrders)): ?>
            <p class="text-gray-700">No registered customer orders found.</p>
          <?php else: ?>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                <?php foreach ($customerAccountOrders as $transaction): ?>
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
                    <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($transaction['OrderID']) ?></p>
                    <p class="text-xs text-gray-600 mb-2">
                        Customer: <span class="font-medium"><?= htmlspecialchars($transaction['CustomerUsername']) ?></span><br>
                        Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($transaction['OrderDate']))) ?>
                    </p>
                    <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
                        <?php if (!empty($transaction['OrderItems'])): ?>
                            <li><?= nl2br(htmlspecialchars($transaction['OrderItems'])) ?></li>
                        <?php else: ?>
                            <li>No specific items recorded.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="flex justify-between items-center mt-2">
                        <span class="font-bold text-lg text-[#4B2E0E]">₱<?= htmlspecialchars(number_format($transaction['TotalAmount'], 2)) ?></span>
                        <span class="text-sm text-gray-600">Ref: <?= htmlspecialchars($transaction['ReferenceNo'] ?? 'N/A') ?></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Payment: <?= htmlspecialchars($transaction['PaymentMethod'] ?? 'N/A') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <div>
          <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
            <i class="fas fa-walking"></i> Walk-in/Staff-Assisted Orders
          </h1>
          <?php if (empty($walkinStaffOrders)): ?>
            <p class="text-gray-700">No walk-in or staff-assisted orders found.</p>
          <?php else: ?>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                <?php foreach ($walkinStaffOrders as $transaction): ?>
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
                    <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($transaction['OrderID']) ?></p>
                    <p class="text-xs text-gray-600 mb-2">
                        Placed by: 
                        <?php
                            if ($transaction['UserTypeID'] == 1) {
                                $ownerName = trim((string)($transaction['OwnerFirstName'] ?? '') . ' ' . (string)($transaction['OwnerLastName'] ?? ''));
                                echo htmlspecialchars($ownerName ?: 'Owner') . ' (Owner)'; 
                            } elseif ($transaction['UserTypeID'] == 2) { 
                                $employeeName = trim((string)($transaction['EmployeeFirstName'] ?? '') . ' ' . (string)($transaction['EmployeeLastName'] ?? ''));
                                echo htmlspecialchars($employeeName ?: 'Employee') . ' (Employee)'; 
                            } elseif ($transaction['UserTypeID'] == 3) {
                                echo 'Walk-in (Customer)'; 
                            } else {
                                echo 'Guest/Unknown'; 
                            }
                        ?><br>
                        Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($transaction['OrderDate']))) ?>
                    </p>
                    <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
                        <?php if (!empty($transaction['OrderItems'])): ?>
                            <li><?= nl2br(htmlspecialchars($transaction['OrderItems'])) ?></li>
                        <?php else: ?>
                            <li>No specific items recorded.</li>
                        <?php endif; ?>
                    </ul>
                    <div class="flex justify-between items-center mt-2">
                        <span class="font-bold text-lg text-[#4B2E0E]">₱<?= htmlspecialchars(number_format($transaction['TotalAmount'], 2)) ?></span>
                        <span class="text-sm text-gray-600">Ref: <?= htmlspecialchars($transaction['ReferenceNo'] ?? 'N/A') ?></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Payment: <?= htmlspecialchars($transaction['PaymentMethod'] ?? 'N/A') ?></p>
                </div>
                <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

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
    </script>
</body>
</html>