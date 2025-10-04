<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Empire Estate Sign Up</title>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
        background: url('1.jpg') no-repeat center center/cover;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .container {
        background-color: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.25);
        width: 400px;
        max-width: 90%;
    }

    .container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #003366;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 6px;
        color: #333;
    }

    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    .buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .buttons input[type="submit"], .buttons a button {
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

    .buttons input[type="submit"]:hover, .buttons a button:hover {
        background-color: #004d99;
    }

    .error {
        color: red;
        text-align: center;
        margin-bottom: 15px;
    }

    #back {
        background-color:white;
        color:grey;
        text-decoration:underline;
    }
</style>
</head>
<body>
<?php
session_start();

$conn = new mysqli("localhost", "webmobile", "RITK2025", "webmobile");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Username already taken.";
    } else {
        $stmt->close();
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            header("Location: buy.php"); // or wherever you want to redirect after successful signup
            exit();
        } else {
            $error = "Could not create account. Please try again.";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<div class="container">
    <h2>Sign Up</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <div class="buttons">
            <input type="submit" value="Sign Up">
            <a href="login.php"><button type="button" id="back">Go Back</button></a>
        </div>
    </form>
</div>
</body>
</html>
