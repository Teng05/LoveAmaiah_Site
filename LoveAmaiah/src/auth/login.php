<?php
session_start();
require_once('../../classes/database.php');
require_once('../../classes/user.php');
require_once('../../classes/employee.php');

$sweetAlertConfig = "";
$con = new database();

if (isset($_SESSION['OwnerID'])) {
    header('Location: ../owner/dashboard.php');
    exit();
} elseif (isset($_SESSION['EmployeeID'])) {
    header('Location: ../employee/dashboard.php');
    exit();
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user = $con->loginUser($username, $password);

    if ($user) {
        if ($user['role'] === 'owner' || $user['role'] === 'admin') {
            $_SESSION['OwnerID'] = $user['OwnerID'];
            $_SESSION['OwnerFN'] = $user['OwnerFN'];
            header('Location: ../owner/dashboard.php');
        } elseif ($user['role'] === 'employee') {
            $_SESSION['EmployeeID'] = $user['EmployeeID'];
            $_SESSION['EmployeeName'] = $user['EmployeeName'];
            header('Location: ../employee/dashboard.php');
        }
        exit();
    } else {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
          icon: 'error',
          title: 'Login Failed',
          text: 'Invalid username or password.',
          confirmButtonText: 'Try Again'
        });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Amaiah</title>
  <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../../package/dist/sweetalert2.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-image: url('../../images/LAbg.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.3);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      padding: 60px 40px 40px 40px;
      width: 420px;
      text-align: center;
      color: white;
      position: relative;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .logo {
      position: absolute;
      top: -55px;
      left: 50%;
      transform: translateX(-50%);
      background-color: white;
      border-radius: 50%;
      padding: 6px;
      border: 6px solid white;
    }

    .logo img {
      width: 110px;
      height: 110px;
      object-fit: contain;
      border-radius: 50%;
    }

    h2 {
      margin-top: 70px;
      margin-bottom: 25px;
      font-weight: bold;
    }

    .form-control {
      border-radius: 25px;
      padding: 14px;
      border: 1px solid rgba(255, 255, 255, 0.5);
      background-color: rgba(255, 255, 255, 0.3);
      color: black;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .btn-primary {
      background-color: #c19a6b;
      border: none;
      color: white;
      padding: 12px;
      width: 100%;
      margin-top: 15px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #a17850;
    }

    .text-center.mt-3 a {
      color: #fff;
      font-weight: bold;
      text-decoration: underline;
    }

    .text-center.mt-3 a:hover {
      color: #e0b083;
    }
  </style>
</head>
<body>
<div class="login-container">
  <div class="logo">
    <img src="../../images/logo.png" alt="Amaiah logo" />
  </div>
  <h2>Login</h2>
  <form method="POST" action="">
    <div class="mb-3">
      <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
    </div>
    <button type="submit" name="login" class="btn btn-primary">Login</button>
    <div class="text-center mt-3">
      Don't have an account? <a href="../registration.php">Register</a>
    </div>
  </form>
</div>

<script src="../../bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php echo $sweetAlertConfig; ?>
</body>
</html>