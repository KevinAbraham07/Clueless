<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_auth";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($action === 'signup') {
        $username = $_POST['username'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data for signup
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

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login/Signup Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #000;
      color: gold;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 300px;
      padding: 20px;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 4px 8px rgba(255, 215, 0, 0.2);
      text-align: center;
    }
    h2 {
      color: #000;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      font-weight: bold;
    }
    .form-group input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    button {
      width: 100%;
      padding: 10px;
      background-color: black;
      color: gold;
      font-weight: bold;
      cursor: pointer;
      border: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #444;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 id="form-title">Login</h2>
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
      <button type="submit">Login</button>
    </form>

    <button onclick="showSignup()">Go to Signup</button>
  </div>

  <script>
    function showSignup() {
      document.getElementById('form-title').innerText = 'Signup';
      document.getElementById('form-action').value = 'signup';
      document.getElementById('auth-form').action = 'auth.php';

      const formHTML = `
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" name="username" id="username" required>
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Signup</button>
      `;
      
      document.getElementById('auth-form').innerHTML = formHTML;
      document.querySelector("button").textContent = "Go to Login";
      document.querySelector("button").setAttribute('onclick', 'showLogin()');
    }

    function showLogin() {
      document.getElementById('form-title').innerText = 'Login';
      document.getElementById('form-action').value = 'login';
      document.getElementById('auth-form').action = 'auth.php';

      const formHTML = `
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
      `;
      
      document.getElementById('auth-form').innerHTML = formHTML;
      document.querySelector("button").textContent = "Go to Signup";
      document.querySelector("button").setAttribute('onclick', 'showSignup()');
    }
  </script>
</body>
</html>
