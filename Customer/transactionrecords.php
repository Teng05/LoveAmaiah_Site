<?php
session_start();
ob_start();
 
if (!isset($_SESSION['CustomerID'])) {
  header('Location: ../all/login.php');
  ob_end_clean();
  exit();
}
require_once('../classes/database.php');
$con = new database();
 
$customerID = $_SESSION['CustomerID'];
$customerFN = $_SESSION['CustomerFN'] ?? 'Customer';
 
$customerOrders = $con->getOrdersForCustomer($customerID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($customerFN) ?>'s Transaction Records</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      display: flex;
      background: url('../images/LAbg.png') no-repeat center center/cover;
      min-height: 100vh;
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
      flex-direction: column;
      align-items: center;
      padding: 2rem;
      position: relative;
    }
 
    .main-content-area .bg-image {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.2;
      z-index: -10;
    }
 
    .pagination-bar {
      margin-top: 1.5rem;
      display: flex;
      justify-content: center;
      gap: 0.5rem;
    }
  </style>
</head>
<body class="min-h-screen flex">
<!-- Sidebar -->
<aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <img src="../images/logo.png" alt="Logo" style="width: 56px; height: 56px; border-radius: 9999px; margin-bottom: 25px;" />
  <button title="Home" onclick="window.location='advertisement.php'"><i class="fas fa-home text-xl text-[#4B2E0E]"></i></button>
  <button title="Cart" onclick="window.location='customerpage.php'"><i class="fas fa-shopping-cart text-xl text-[#4B2E0E]"></i></button>
  <button title="Order List" onclick="window.location='transactionrecords.php'"><i class="fas fa-list text-xl text-[#4B2E0E]"></i></button>
  <button title="Settings" onclick="window.location='../all/setting.php'"><i class="fas fa-cog text-xl text-[#4B2E0E]"></i></button>
  <button id="logout-btn" title="Logout"><i class="fas fa-sign-out-alt text-xl text-[#4B2E0E]"></i></button>
</aside>
 
<!-- Main Content -->
<div class="main-content-area">
  <img src="../images/LAbg.png" alt="Background image" class="bg-image" />
  <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg w-[calc(100%-7rem)] max-w-6xl mx-auto relative z-10">
    <h1 class="text-2xl font-bold text-[#4B2E0E] mb-6 flex items-center gap-2">
      <i class="fas fa-history"></i> Your Transaction History
    </h1>
 
    <?php if (empty($customerOrders)): ?>
      <p class="text-gray-700">You have no recorded transactions yet.</p>
    <?php else: ?>
      <div id="transaction-list" class="space-y-6 max-h-[500px] overflow-y-auto pr-2">
        <?php foreach ($customerOrders as $order): ?>
        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
          <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($order['OrderID']) ?></p>
          <p class="text-xs text-gray-600 mb-2">
            Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($order['OrderDate']))) ?>
          </p>
          <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
            <?php if (!empty($order['OrderItems'])): ?>
              <li><?= nl2br(htmlspecialchars($order['OrderItems'])) ?></li>
            <?php else: ?>
              <li>No specific items recorded.</li>
            <?php endif; ?>
          </ul>
          <div class="flex justify-between items-center mt-2">
            <span class="font-bold text-lg text-[#4B2E0E]">₱<?= htmlspecialchars(number_format($order['TotalAmount'], 2)) ?></span>
            <span class="text-sm text-gray-600">Ref: <?= htmlspecialchars($order['ReferenceNo'] ?? 'N/A') ?></span>
          </div>
          <p class="text-xs text-gray-500 mt-1">Payment: <?= htmlspecialchars($order['PaymentMethod'] ?? 'N/A') ?></p>
        </div>
        <?php endforeach; ?>
      </div>
      <div id="pagination" class="pagination-bar"></div>
    <?php endif; ?>
  </div>
</div>
 
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('logout-btn').addEventListener('click', function(e) {
      e.preventDefault();
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


function paginate(containerId, paginationId, itemsPerPage = 3) {
  const container = document.getElementById(containerId);
  const pagination = document.getElementById(paginationId);
  const items = Array.from(container.children);
  const totalItems = items.length;
  const totalPages = Math.ceil(totalItems / itemsPerPage);
  let currentPage = 1;
 
  function showPage(page) {
    items.forEach((item, i) => {
      item.style.display = (i >= (page - 1) * itemsPerPage && i < page * itemsPerPage) ? '' : 'none';
    });
    renderPagination();
  }
 
  function renderPagination() {
    pagination.innerHTML = '';
    const prev = document.createElement('button');
    prev.textContent = 'Prev';
    prev.className = 'px-3 py-1 border rounded hover:bg-gray-200 text-sm';
    prev.disabled = currentPage === 1;
    prev.onclick = () => { if (currentPage > 1) showPage(--currentPage); };
    pagination.appendChild(prev);
 
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.textContent = i;
      btn.className = `px-3 py-1 border rounded text-sm ${i === currentPage ? 'bg-[#4B2E0E] text-white' : 'hover:bg-gray-200'}`;
      btn.onclick = () => {
        currentPage = i;
        showPage(currentPage);
      };
      pagination.appendChild(btn);
    }
 
    const next = document.createElement('button');
    next.textContent = 'Next';
    next.className = 'px-3 py-1 border rounded hover:bg-gray-200 text-sm';
    next.disabled = currentPage === totalPages;
    next.onclick = () => { if (currentPage < totalPages) showPage(++currentPage); };
    pagination.appendChild(next);
  }
 
  if (totalPages > 1) showPage(currentPage);
}
 
document.addEventListener('DOMContentLoaded', () => {
  paginate('transaction-list', 'pagination');  
});
</script>
</body>
</html>