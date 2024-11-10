<?php
$servername = "localhost";
$username = "root";
$password = "kevin1306"; // Ensure this is correct
$dbname = "user_auth";

// Establishing connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // If connection fails
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($action === 'signup') {
        $username = $_POST['username'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert query for signup
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Signup successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } elseif ($action === 'login') {
        // Select query for login
        $sql = "SELECT password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                echo "Login successful!";
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "No account found with that email!";
        }
        $stmt->close();
    }
}

// Only close the connection if it's successfully established
if ($conn) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login/Signup Page</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #000;
      color: gold; 
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      flex-direction: column;
      padding-top: 80px; /* Added padding to avoid overlap with header */
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: black;
      color: gold;
      padding: 10px 0;
      box-shadow: 0 4px 8px rgba(255, 215, 0, 0.2);
      z-index: 1000;
    }
    .header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: auto;
      padding: 0 20px;
    }
    /* Logo and Company Name */
    .company-logo {
      display: flex;
      align-items: center;
    }
    .company-logo img {
      height: 40px;
      margin-right: 10px;
    }
    .company-logo h1 {
      font-size: 24px;
      margin: 0;
    }
    /* Navigation Links */
    nav a {
      color: gold;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
      transition: color 0.3s;
    }
    nav a:hover {
      color: white;
    }
    /* Login/Signup Buttons */
    .toggle-btn {
      flex: 1;
      padding: 10px;
      border: none;
      background-color: black;
      color: gold;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .container {
      width: 400px;
      padding: 20px;
      border-radius: 10px;
      background-color: aliceblue; 
      box-shadow: 0 4px 8px rgba(255, 215, 0, 0.2); 
      text-align: center;
    }
    h2 {
      margin-bottom: 20px;
    }

    #form-title {
      color: #000;
    }
    .form-group {
      margin-bottom: 15px;
      text-align: left;
    }
    .form-group label {
      color: black;
      font-weight: bold;
    }
    .form-group input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .submit-btn {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      border: none;
      border-radius: 5px;
      color: gold;
      background-color: black;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .submit-btn:hover {
      background-color: #333;
    }

    button:hover {
      background-color: white;
      color: #000;
      transform: scale(1.02);
    }
  </style>
</head>
<body>
  <header>
    <div class="header-container">
      <div class="company-logo">
        <img src="logo.png" alt="Company Logo">
        <h1>YourCompany</h1>
      </div>
      <nav>
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#contact">Contact</a>
      </nav>
    </div>
  </header>

  <div>
    <div class="button-container">
      <button class="toggle-btn active" onclick="showLogin()">Login</button>
      <button class="toggle-btn" onclick="showSignup()">Signup</button>
    </div>
    <div class="container">
      <h2 id="form-title">Login<br><hr></h2>
      <form id="auth-form" method="POST" action="auth.php">
        <input type="hidden" name="action" id="form-action" value="login">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="submit-btn">Login</button>
      </form>
    </div>
  </div>

  <script>
    function showLogin() {
      document.getElementById('form-title').innerHTML = 'Login<br><hr>';
      document.getElementById('auth-form').action = 'auth.php';
      document.getElementById('form-action').value = 'login';
      document.getElementById('auth-form').innerHTML = `
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" required>
        </div>
        <button type="submit" class="submit-btn">Login</button>
      `;
      toggleActiveButton('login');
    }

    function showSignup() {
      document.getElementById('form-title').innerHTML = 'Sign up<br><hr>';
      document.getElementById('auth-form').action = 'auth.php';
      document.getElementById('form-action').value = 'signup';
      document.getElementById('auth-form').innerHTML = `
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" name="username" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" required>
        </div>
        <button type="submit" class="submit-btn">Signup</button>
      `;
      toggleActiveButton('signup');
    }

    function toggleActiveButton(formType) {
      const loginBtn = document.querySelectorAll('.toggle-btn')[0];
      const signupBtn = document.querySelectorAll('.toggle-btn')[1];
      if (formType === 'login') {
        loginBtn.classList.add('active');
        signupBtn.classList.remove('active');
      } else {
        signupBtn.classList.add('active');
        loginBtn.classList
