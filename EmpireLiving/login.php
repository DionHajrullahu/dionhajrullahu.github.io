<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Real Estate Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Playfair+Display&display=swap" rel="stylesheet">
<style>
/* Body */
body {
    font-family: "Poppins", sans-serif;
    background: url('1.jpg') no-repeat center center/cover;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Container */
.container {
    background: white;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    width: 420px;
    text-align: center;
}

.container h2 {
    margin-bottom: 20px;
    font-size: 32px;
    color: #1D2731;
    font-family: "Playfair Display", serif;
}

/* Form */
form {
    display: flex;
    flex-direction: column;
}

label {
    text-align: left;
    margin-bottom: 6px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

input[type="text"], input[type="password"] {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
}

input[type="checkbox"] {
    margin-right: 8px;
}

.remember {
    font-size: 0.9em;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    color: #333;
}

/* Buttons */
input[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color:rgb(10, 3, 58);
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #004d99;
}

.signup-button {
    width: 100%;
    padding: 12px;
    background-color: #FFFFFF;
    border: none;
    color: grey;
    text-decoration:underline;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.signup-link {
    margin-top: 20px;
    font-size: 14px;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 15px;
    font-size: 14px;
}
</style>
</head>
<body>
<?php
session_start();

$conn = new mysqli("localhost", "webmobile", "RITK2025", "webmobile");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    $username = $_COOKIE['username'];
    $password = $_COOKIE['password'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($dbPassword);
        $stmt->fetch();

        if ($password === $dbPassword) {
            $_SESSION["username"] = $username;
            if (isset($_POST['remember_me'])) {
                setcookie('username', $username, time() + (86400 * 30));
                setcookie('password', $password, time() + (86400 * 30));
            }
            header("Location: profile.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }

    $stmt->close();
}

$conn->close();
?>

<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <div class="remember">
            <input type="checkbox" name="remember_me" id="remember_me">
            <label for="remember_me">Remember Me</label>
        </div>

        <input type="submit" value="Login">
    </form>

    <div class="signup-link">
        <p>Don't have an account?</p>
        <a href="SignUp.php"><button class="signup-button">Sign Up</button></a>
    </div>
</div>

</body>
</html>
