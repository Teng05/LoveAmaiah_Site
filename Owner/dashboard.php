<?php
session_start();

require_once('../classes/database.php');
$con = new database();

$loggedInOwnerID = null;
$ownerFirstName = 'Owner';

// Check if an Owner is logged in
if (isset($_SESSION['OwnerID'])) {
    $loggedInOwnerID = $_SESSION['OwnerID'];
    $ownerFirstName = $_SESSION['OwnerFN']; 
} else {
    // If not logged in as Owner, redirect to login
    header('Location: ../all/login.php');
    exit();
}

// Get dashboard statistics (passing $loggedInOwnerID to filter data specific to this owner's business)
$totalCustomers = $con->getCustomerCount($loggedInOwnerID);
// Removed: $productsInStock = $con->getProductsInStock();
$totalSales = $con->getTotalSales($loggedInOwnerID, 30); // Sales for last 30 days for this owner's business
$totalOrders = $con->getTotalSystemOrders($loggedInOwnerID, 30); // Orders for last 30 days for this owner's business

// Get sales data for charts
$salesData = $con->getSalesData($loggedInOwnerID, 30); // Sales data for chart (last 30 days)
$topProducts = $con->getTopProducts($loggedInOwnerID,  30); // Top 5 products for last 30 days for this owner's business

// Determine the single top-selling product for the summary card
$topSellerName = 'N/A';
if (!empty($topProducts['labels'][0])) {
    $topSellerName = $topProducts['labels'][0]; // Get the name of the top product
}

// Removed: Low stock alerts variable
// $lowStockAlerts = $con->viewInventoryAlerts($loggedInOwnerID);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LoveAmiah - Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: rgba(255, 255, 255, 0.7);
        }
        .main-content {
            flex-grow: 1;
            padding: 1.5rem;
            position: relative;
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
    <!-- Sidebar -->
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
    <!-- Main Content -->
    <div class="main-content flex flex-col">
        <!-- Background Image -->
        <img src="https://storage.googleapis.com/a1aa/image/22cccae8-cc1a-4fb3-7955-287078a4f8d4.jpg" alt="Background image of coffee beans" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover opacity-20 -z-10" />

        <header class="mb-6 flex justify-between items-center z-10">
            <div>
                <p class="text-xs text-gray-700 mb-0.5">Welcome, <?= htmlspecialchars($ownerFirstName); ?></p>
                <h1 class="text-[#4B2E0E] font-semibold text-2xl">Dashboard</h1>
            </div>
            <div class="flex space-x-2">
                <button class="bg-[#C4A07A] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#a17850] transition shadow-md" id="refreshBtn">
                    <i class="fas fa-sync-alt mr-2"></i> Refresh
                </button>
                <button class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition shadow-md" id="exportBtn">
                    <i class="fas fa-download mr-2"></i> Export Sales Report
                </button>
            </div>
        </header>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 z-10">
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="text-lg font-semibold text-gray-700">Total Sales</h5>
                <p class="text-3xl font-bold text-[#4B2E0E]" id="totalSales">₱<?= number_format($totalSales, 2); ?></p>
                <small class="text-gray-500">Last 30 days</small>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="text-lg font-semibold text-gray-700">Total Orders</h5>
                <p class="text-3xl font-bold text-[#4B2E0E]" id="totalOrders"><?= $totalOrders; ?></p>
                <small class="text-gray-500">Last 30 days</small>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="text-lg font-semibold text-gray-700">Total Customers</h5>
                <p class="text-3xl font-bold text-[#4B2E0E]" id="totalCustomers"><?= $totalCustomers; ?></p>
                <small class="text-gray-500">Registered customers</small>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4">
                <h5 class="text-lg font-semibold text-gray-700">Top Seller</h5>
                <p class="text-xl font-bold text-[#4B2E0E] overflow-hidden whitespace-nowrap overflow-ellipsis" title="<?= htmlspecialchars($topSellerName); ?>">
                    <?= htmlspecialchars($topSellerName); ?>
                </p>
                <small class="text-gray-500">Last 30 days</small>
            </div>
        </div>

        <!-- Sales Overview Chart -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6 z-10 max-w-4xl mx-auto w-full">
            <h5 class="text-lg font-semibold text-gray-700 mb-4">Sales Overview (Last 30 Days)</h5>
            <div style="height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        
        <!-- Low Stock Alerts section REMOVED from here -->

    </div>

    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($salesData['labels']); ?>,
                datasets: [{
                    label: 'Sales',
                    data: <?php echo json_encode($salesData['data']); ?>,
                    borderColor: '#C4A07A',
                    backgroundColor: 'rgba(196, 160, 122, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#4B2E0E'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += '₱' + context.parsed.y.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#4B2E0E' },
                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            },
                            color: '#4B2E0E'
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                    }
                }
            }
        });

        // Refresh button functionality
        document.getElementById('refreshBtn').addEventListener('click', function() {
            location.reload();
        });

        // Export button functionality
        document.getElementById('exportBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Export Sales Report',
                text: 'This feature is under development.',
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'bg-[#4B2E0E] text-white'
                },
                buttonsStyling: false
            });
        });

        // Logout button functionality
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
                    window.location.href = "../all/logout.php";
                }
            });
        });
    </script>
</body>
</html>