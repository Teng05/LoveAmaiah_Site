<?php
session_start();
if (!isset($_SESSION['EmployeeID'])) {
  header('Location: login.php');
  exit();
}
require_once('../classes/database.php');
$con = new database();
$sweetAlertConfig = "";
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Product List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">

<!-- Sidebar -->
<aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
   <button aria-label="Home" class="text-[#4B2E0E] text-xl" title="Home" type="button" onclick="window.location='../Employee/employesmain.php'"><i class="fas fa-home"></i></button>
   <button aria-label="Cart" class="text-[#4B2E0E] text-xl" title="Cart" type="button" onclick="window.location='../Employee/employeepage.php'"><i class="fas fa-shopping-cart"></i></button>
   <button aria-label="Order List" class="text-[#4B2E0E] text-xl" title="Transaction Records" type="button" onclick="window.location='../all/tranlist.php'"><i class="fas fa-list"></i></button>
   <button aria-label="Box" class="text-[#4B2E0E] text-xl" title="Box" type="button" onclick="window.location='../Employee/productemployee.php'"><i class="fas fa-box"></i></button>
   <button aria-label="Settings" class="text-[#4B2E0E] text-xl" title="Settings" type="button" onclick="window.location='../all/setting.php'"><i class="fas fa-cog"></i></button>
   <button id="logout-btn" aria-label="Logout" name="logout" class="text-[#4B2E0E] text-xl" title="Logout" type="button"><i class="fas fa-sign-out-alt"></i></button>
  </aside>

<!-- Main Content -->
<main class="flex-1 p-6 relative flex flex-col">
  <header class="mb-4 flex items-center justify-between">
    <div>
      <h1 class="text-[#4B2E0E] font-semibold text-xl mb-1">Product List</h1>
      <p class="text-xs text-gray-400">View all products</p>
    </div>
    <!-- No Add Product button for employees -->
  </header>

  <section class="bg-white rounded-xl p-4 max-w-6xl shadow-lg flex-1 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-[#4B2E0E] border-b">
          <th class="py-2 px-3">#</th>
          <th class="py-2 px-3">Product Name</th>
          <th class="py-2 px-3">Category</th>
          <th class="py-2 px-3">Created At</th>
          <th class="py-2 px-3">Unit Price</th>
          <th class="py-2 px-3">Effective From</th>
          <th class="py-2 px-3">Effective To</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $products = $con->getJoinedProductData();
        foreach ($products as $product) {
        ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="py-2 px-3"><?= $product['ProductID'] ?></td>
          <td class="py-2 px-3"><?= $product['ProductName'] ?></td>
          <td class="py-2 px-3"><?= $product['ProductCategory'] ?></td>
          <td class="py-2 px-3"><?= $product['Created_AT'] ?></td>
          <td class="py-2 px-3">₱<?= number_format($product['UnitPrice'], 2) ?></td>
          <td class="py-2 px-3"><?= $product['Effective_From'] ?></td>
          <td class="py-2 px-3"><?= $product['Effective_To'] ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>

  <?= $sweetAlertConfig ?>
</main>
</body>
</html>