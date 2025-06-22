<?php
session_start();
if (!isset($_SESSION['CustomerID'])) {
  header('Location: ../all/login.php');
  exit();
}
$customer = $_SESSION['CustomerFN'];
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LoveAmiah - Advertisement</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .main-content {
      flex-grow: 1;
      padding: 5vw;
      color: white;
      background: rgba(0, 0, 0, 0.3);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .hero {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: flex-end;
      margin-bottom: 50px;
      gap: 60px;
      text-align: right;
    }
    .hero img {
      width: 100%;
      max-width: 800px;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    .hero-text {
      max-width: 700px;
      text-align: right;
    }
    .hero-text h1 {
      font-size: 4.5em;
      font-weight: bold;
      margin-bottom: 20px;
      line-height: 1.2;
    }
    .hero-text h1 span {
      color: #a17850;
    }
    .hero-text p {
      font-size: 1.7em;
      margin-bottom: 30px;
      line-height: 1.5;
    }
    .hero-text button {
      padding: 14px 32px;
      font-size: 1.2em;
      border: 2px solid white;
      border-radius: 6px;
      background: transparent;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .hero-text button:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .coffee-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2.5rem;
    }
    .card {
      background-color: #444;
      border-radius: 16px;
      overflow: hidden;
      color: white;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .card-body {
      padding: 16px;
    }
    .card h3 {
      font-size: 1.4em;
      margin-bottom: 10px;
      color: #a17850;
    }
    .card p {
      font-size: 1em;
      line-height: 1.5;
    }
  </style>
</head>
<body class="flex min-h-screen bg-cover bg-center bg-no-repeat" style="background-image: url('../images/LAbg.png');">

<!-- Sidebar (updated using Tailwind) -->
<aside class="w-16 bg-white bg-opacity-90 backdrop-blur-sm flex flex-col items-center py-6 space-y-8 shadow-lg z-10">
  <img src="../images/logo.png" alt="Logo" class="w-14 h-14 rounded-full mb-6" />
  <button title="Home" onclick="window.location='advertisement.php'" class="text-xl">
    <i class="fas fa-home <?= $currentPage === 'advertisement.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button title="Cart" onclick="window.location='customerpage.php'" class="text-xl">
    <i class="fas fa-shopping-cart <?= $currentPage === 'customerpage.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button title="Order List" onclick="window.location='transactionrecords.php'" class="text-xl">
    <i class="fas fa-list <?= $currentPage === 'transactionrecords.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button title="Settings" onclick="window.location='../all/setting.php'" class="text-xl">
    <i class="fas fa-cog <?= $currentPage === 'setting.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
  </button>
  <button id="logout-btn" title="Logout" class="text-xl">
    <i class="fas fa-sign-out-alt text-[#4B2E0E]"></i>
  </button>
</aside>

<!-- Main Content (unchanged) -->
<div class="main-content">
  <div class="hero">
    <img src="../images/mainpage_coffee.png" alt="Latte Art" />
    <div class="hero-text">
      <h1>Sip Happiness<br><span>One Cup at a Time</span></h1>
      <p>Begin your day with a cup of coffee—boost your energy, sharpen your focus, and set the tone for a productive, positive day ahead.</p>
      <button onclick="window.location.href='customerpage.php'">Order Coffee</button>
    </div>
  </div>
  <div class="coffee-cards">
    <div class="card">
      <img src="../images/affogato.png" alt="Affogato">
      <div class="card-body">
        <h3>Affogato</h3>
        <p>Espresso poured over vanilla ice cream — bold, creamy, and decadent.</p>
      </div>
    </div>
    <div class="card">
      <img src="../images/caramel_cloud_latte.png" alt="Caramel Cloud Latte">
      <div class="card-body">
        <h3>Caramel Cloud Latte</h3>
        <p>Fluffy foam, bold espresso, and silky caramel — heavenly in every sip.</p>
      </div>
    </div>
    <div class="card">
      <img src="../images/cinnamon_macchiato.png" alt="Cinnamon Macchiato">
      <div class="card-body">
        <h3>Cinnamon Macchiato</h3>
        <p>Warm cinnamon meets espresso and milk — sweet, spicy, and smooth.</p>
      </div>
    </div>
    <div class="card">
      <img src="../images/iced_shaken_brownie.png" alt="Iced Brownie Espresso">
      <div class="card-body">
        <h3>Iced Brownie Espresso</h3>
        <p>Shaken espresso with rich brownie flavor — bold, cold, and energizing.</p>
      </div>
    </div>
  </div>
</div>

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
</script>
</body>
</html