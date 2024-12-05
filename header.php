<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
   <style>
    

/* Global styles */
body {
  margin: 0;
  font-family: Arial, sans-serif;
}

/* Navigation bar styles */
header {
  background-color: #333;
  color: white;
  padding: 10px 0;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 90%;
  margin: 0 auto;
}

.logo a {
  color: white;
  font-size: 24px;
  text-decoration: none;
  font-weight: bold;
}

nav ul {
  list-style: none;
  display: flex;
  gap: 20px;
}

nav ul li a {
  color: white;
  text-decoration: none;
  font-size: 18px;
  padding: 8px 16px;
  border-radius: 4px;
}

nav ul li a:hover {
  background-color: #555;
}

   </style>
</head>
<body>
    <!-- Header Navigation Bar -->
<header>
    <div class="navbar">
        <div class="logo">
            <a href="index.php">Logo</a> <!-- This will link to the homepage -->
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

</body>
</html>