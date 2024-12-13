<?php
include 'header.php';
include 'db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Get the selected role (user/admin)

    // Prepare SQL to insert user with the selected role
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            color:#fff;
            background:transparent;
            border-radius: 8px;
            display:flex;
            flex-direction:column;
            align-items: center;
            justify-content:space-between;
        }
        .container>h3>a{
            color:red;
            text-decoration:none;
            transition:0.5s all;:1.9vw;
            font-size
        }
        .container>h3>a:hover{
            color:red;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        label {
            font-size: 16px;
            margin-right: 10px;
        }

        .role-selection {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .role-selection input {
            margin-right: 10px;
        }
    </style>
</head>
<body id="login-body">

    <div class="container">
        <h1>Register</h1>
        <form method="POST" action="register.php"  class="lform">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <!-- Role selection section -->
            <div class="role-selection">
                <label for="user_role">User</label>
                <input type="radio" id="user_role" name="role" value="user" checked>
                <label for="admin_role">Admin</label>
                <input type="radio" id="admin_role" name="role" value="admin">
            </div>

            <button type="submit" name="register">Register</button>
        </form>
        <h3>Alredy registered! <a href="login.php">login</a></h3>
    </div>

</body>
</html>
