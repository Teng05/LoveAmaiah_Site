<?php
session_start();
if (!isset($_SESSION['CustomerID'])) {
  header('Location: ../all/login.php');
  exit();
}
$customer = $_SESSION['CustomerFN'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LoveAmiah - Advertisement</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Reset and common body styles */
    * {
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif; /* Keep original font-family if preferred */
      display: flex; /* Makes the body a flex container for sidebar and main content */
      background: url('../images/LAbg.png') no-repeat center center/cover;
      min-height: 100vh;
      background-color: rgba(255, 255, 255, 0.7); /* Consistent overlay/fallback */
    }

    /* Consistent Sidebar Styling */
    .sidebar {
      width: 90px; /* Fixed width as per other sidebars */
      background-color: #fff;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 30px; /* Consistent top padding */
      gap: 35px; /* Consistent spacing between icons */
      box-shadow: 2px 0 5px rgba(0,0,0,0.1);
      z-index: 10; /* Ensure sidebar is above main content */
    }
    /* Styles for sidebar icons/buttons/links (consistent font-size, color, hover) */
    .sidebar a, .sidebar button {
      color: #4B2E0E;
      font-size: 26px; /* Consistent icon size */
      text-decoration: none;
      transition: color 0.3s ease;
      /* Ensure buttons look like links */
      background: none;
      border: none;
      padding: 0;
      cursor: pointer;
    }
    .sidebar a:hover, .sidebar button:hover {
      color: #C4A07A;
    }

    /* Main content styling */
    .main-content {
      flex-grow: 1; /* Allows main content to take remaining width */
      padding: 5vw; /* Keep original padding for this page's layout */
      color: white;
      background: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    /* Original advertisement content styles */
    .hero {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      margin-bottom: 50px;
      gap: 60px;
    }

    .hero img {
      width: 100%;
      max-width: 600px;
      border-radius: 12px;
    }

    .hero-text {
      max-width: 650px;
      text-align: left;
    }

    .hero-text h1 {
      font-size: 3.5em;
      font-weight: bold;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .hero-text h1 span {
      color: #a17850;
    }

    .hero-text p {
      font-size: 1.4em;
      margin-bottom: 30px;
    }

    .hero-text button {
      padding: 14px 32px;
      font-size: 1.1em;
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
      display: flex;
      gap: 35px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .card {
      width: 260px;
      background-color: #333;
      border-radius: 12px;
      overflow: hidden;
      color: white;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-6px);
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
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
      .hero {
        flex-direction: column;
        text-align: center;
      }

      .hero-text {
        max-width: 100%;
      }

      .hero-text h1 {
        font-size: 2.5em;
      }
    }

    @media (max-width: 768px) {
      /* Sidebar is hidden on smaller screens */
      .sidebar {
        display: none;
      }

      .main-content {
        padding: 30px; /* Adjust padding when sidebar is hidden */
      }

      .card {
        width: 100%;
        max-width: 320px;
      }
    }

    @media (max-width: 480px) {
      .hero-text h1 {
        font-size: 1.8em;
      }

      .hero-text button {
        width: 100%;
        margin-top: 10px;
      }

      .coffee-cards {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body class="min-h-screen flex">
  <!-- Sidebar -->
  <aside class="sidebar">
    <a href="advertisement.php" title="Home"><i class="fas fa-home"></i></a>
    <a href="customerpage.php" title="Cart"><i class="fas fa-cart-shopping"></i></a>
    <a href="transactionrecords.php" title="Order List"><i class="fas fa-list"></i></a>
    <a href="../all/setting.php" title="Settings"><i class="fas fa-cog"></i></a>
    <button id="logout-btn" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
  </aside>

  <!-- Main content -->
  <div class="main-content">
    <div class="hero">
      <img src="../images/mainpage_coffee.png" alt="Latte Art">
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
        <img src="../images/iced_shaken_brownie.png" alt="Iced Shaken Brownie">
        <div class="card-body">
          <h3>Iced Brownie Espresso</h3>
          <p>Shaken espresso with rich brownie flavor — bold, cold, and energizing.</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Common logout functionality for all users
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
                // This path is relative to the current file (advertisement.php is in Customer/)
                window.location.href = "../all/logout.php"; 
            }
        });
    });
  </script>
</body>
</html>