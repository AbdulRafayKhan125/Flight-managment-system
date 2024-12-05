<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login page</title>
    <style>
        
#login-body{
  margin: 0;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, lightskyblue, blue, violet, pink);
  background-size: 400% 400%; /* Smooth animation for gradient */
  animation: gradientMove 6s ease infinite;
  font-family: Arial, sans-serif;
}

@keyframes gradientMove {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
.lform {
  background: rgba(255, 255, 255, 0.2); /* Transparent white background */
  backdrop-filter: blur(10px); /* Blur effect */
  border-radius: 15px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 
              0 6px 10px rgba(0, 0, 0, 0.15); /* Attractive shadows */
  padding: 20px;
  width: 300px;
  display: flex;
  flex-direction: column;
  gap: 15px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover effect for the form */
.lform:hover {
  transform: scale(1.05); /* Slight zoom effect */
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3), 
              0 8px 12px rgba(0, 0, 0, 0.2);
}

/* Input fields styling */
.lform input {
  padding: 10px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  outline: none;
  background: rgba(255, 255, 255, 0.7); /* Light background for input */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  transition: background 0.3s ease, transform 0.2s ease;
}

/* Focus effect for input fields */
.lform input:focus {
  background: rgba(255, 255, 255, 0.9); /* Slightly brighter on focus */
  transform: scale(1.03); /* Slight zoom effect */
}

/* Button styling */
.lform button {
  padding: 10px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  background: linear-gradient(135deg, violet, pink);
  color: white;
  font-weight: bold;
  transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
}

/* Hover effect for button */
.lform button:hover {
  background: linear-gradient(135deg, pink, violet);
  transform: translateY(-3px); /* Slight elevation */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* More pronounced shadow */
}
    </style>
</head>
<body id="login-body">


<form method="POST" action="login.php"  class="lform">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>




    
</body>
</html>

<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that username.";
    }

    $stmt->close();
}
?>
