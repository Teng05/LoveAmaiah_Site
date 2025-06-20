
  <?php
  session_start();
  if (!isset($_SESSION['EmployeeID'])) {
      header('Location: ../all/login.php');
      exit();
  }

  $employeeDisplay = isset($_SESSION['E_Username']) ? $_SESSION['E_Username'] : (isset($_SESSION['EmployeeFN']) ? $_SESSION['EmployeeFN'] : 'Employee');
  ?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Employee Main Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
      body {
        font-family: 'Inter', sans-serif;
        background-image: url('../images/LAbg.png');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
      }
      ::-webkit-scrollbar { width: 6px; }
      ::-webkit-scrollbar-thumb { background-color: #c4b09a; border-radius: 10px; }
    </style>
  </head>
  <body class="min-h-screen flex text-[#4B2E0E] bg-[rgba(255,255,255,0.7)]">
    <!-- Sidebar -->
    <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
      <img src="../images/logo.png" alt="Logo" class="w-10 h-10 rounded-full mb-4" />
   <button aria-label="Home" class="text-[#4B2E0E] text-xl" title="Home" type="button" onclick="window.location='../Employee/employesmain.php'"><i class="fas fa-home"></i></button>
   <button aria-label="Cart" class="text-[#4B2E0E] text-xl" title="Cart" type="button" onclick="window.location='../Employee/employeepage.php'"><i class="fas fa-shopping-cart"></i></button>
   <button aria-label="Order List" class="text-[#4B2E0E] text-xl" title="Transaction Records" type="button" onclick="window.location='../all/tranlist.php'"><i class="fas fa-list"></i></button>
   <button aria-label="Box" class="text-[#4B2E0E] text-xl" title="Box" type="button" onclick="window.location='../Employee/productemployee.php'"><i class="fas fa-box"></i></button>
   <button aria-label="Settings" class="text-[#4B2E0E] text-xl" title="Settings" type="button" onclick="window.location='../all/setting.php'"><i class="fas fa-cog"></i></button>
   <button id="logout-btn" aria-label="Logout" name="logout" class="text-[#4B2E0E] text-xl" title="Logout" type="button"><i class="fas fa-sign-out-alt"></i></button>
  </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10 flex items-center justify-center text-center">
      <div class="bg-white bg-opacity-80 backdrop-blur-md rounded-2xl shadow-xl px-10 py-12 max-w-4xl w-100">
        <!-- greeting -->
        <h1 class="text-3xl font-extrabold mb-4">
          Welcome 👋
        </h1>
        <p class="text-gray-700 mb-10">

        </p>
        <form action="../Employee/employeepage.php" method="get" class="flex flex-col items-center gap-6">
          <label class="text-[#4B2E0E] font-semibold">
            Enter your name:
            <input type="text" name="customer_name" required class="mt-2 p-2 rounded border border-gray-300" />
          </label>
          <div class="flex gap-10 mt-6">
            <button type="submit" name="order_type" value="Dine-In" class="bg-white p-6 rounded-xl shadow text-[#4B2E0E] hover:bg-[#f5f5f5]">
              <i class="fas fa-utensils fa-2x"></i>
              <div class="mt-2 font-semibold">Dine-In</div>
            </button>
            <button type="submit" name="order_type" value="Take-Out" class="bg-white p-6 rounded-xl shadow text-[#4B2E0E] hover:bg-[#f5f5f5]">
              <i class="fas fa-shopping-bag fa-2x"></i>
              <div class="mt-2 font-semibold">Take-Out</div>
            </button>
          </div>
        </form>
      </div>
    </main>

    <script>
      document.getElementById('logout-btn').addEventListener('click', function() {
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
            window.location.href = '../all/logout.php';
          }
        });
      });
    </script>
  </body>
  </html>