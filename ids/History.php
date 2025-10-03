<?php
session_start();

// Reset login status on every page load
$_SESSION['admin_logged_in'] = false;

$conn = new mysqli("localhost", "root", "", "cybersecurity_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === "admin" && $password === "admin") {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['blocked'] = false;
        $_SESSION['robot_verified'] = true;
    } else {
        $_SESSION['blocked'] = true;
        $_SESSION['robot_verified'] = false;
        $_SESSION['admin_logged_in'] = false;

        $type = "Privilege Escalation";
        $date = date("Y-m-d");
        $time = date("H:i:s");

        $stmt = $conn->prepare("INSERT INTO Intrusions (TypeOfIntrusion, IntrusionDate, IntrusionTime) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $type, $date, $time);
        $stmt->execute();
        $stmt->close();
    }
}

if (isset($_GET['unblock'])) {
    $_SESSION['blocked'] = false;
    $_SESSION['robot_verified'] = true;
    $_SESSION['admin_logged_in'] = false;
    header("Location: history.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Intrusion History</title>
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: black;
            color: white;
        }
        .login-panel {
            width: 20%;
            background-color: #ff6a00;
            color: black;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.5);
        }
        .login-panel h2 {
            text-align: center;
        }
        .login-panel form {
            display: flex;
            flex-direction: column;
        }
        .login-panel input {
            margin: 10px 0;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .login-panel button {
            background-color: black;
            color: orange;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logs-panel {
            width: 80%;
            padding: 20px;
            overflow-y: auto;
            background-color: #222;
            transition: filter 0.3s;
        }
        .blur {
            filter: blur(8px);
            pointer-events: none;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }
        .captcha {
            margin-top: 20px;
        }
        .drag-container {
            margin: 20px auto;
            width: 90%;
            height: 50px;
            background: #ddd;
            position: relative;
            border-radius: 25px;
        }
        .drag-block {
            width: 50px;
            height: 50px;
            background: orange;
            border-radius: 50%;
            position: absolute;
            left: 0;
            top: 0;
            cursor: grab;
        }
        .x-marker {
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 24px;
            color: black;
        }
    </style>
</head>
<body>
<div class="login-panel">
    <h2>Admin Login</h2>
    <?php if ($_SESSION['blocked'] && !$_SESSION['robot_verified']): ?>
        <p style="color:red">Access blocked due to intrusion attempt.</p>
        <div class="captcha">
            <p>Drag the block to the X to retry:</p>
            <div class="drag-container" id="drag-container">
                <div class="drag-block" id="drag-block"></div>
                <div class="x-marker">X</div>
            </div>
            <form method="get" id="unblock-form" style="display:none">
                <button name="unblock" id="unblock-button">🧩 Retry Access</button>
            </form>
        </div>
    <?php elseif (!$_SESSION['admin_logged_in']): ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    <?php else: ?>
        <p style="color:#fffff">✅ Logged in as Admin</p> <!-- Updated color here -->
    <?php endif; ?>
</div>
<div class="logs-panel <?= ($_SESSION['admin_logged_in']) ? '' : 'blur' ?>">
    <h2>📜 Intrusion Logs</h2>
    <table>
        <tr>
            <th>IntrusionID</th>
            <th>Type</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM Intrusions ORDER BY IntrusionID DESC");
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['IntrusionID'] ?></td>
                <td><?= $row['TypeOfIntrusion'] ?></td>
                <td><?= $row['IntrusionDate'] ?></td>
                <td><?= $row['IntrusionTime'] ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<script>
    const block = document.getElementById('drag-block');
    const container = document.getElementById('drag-container');
    const form = document.getElementById('unblock-form');
    const unblockBtn = document.getElementById('unblock-button');

    let isDragging = false;

    if (block) {
        block.addEventListener('mousedown', function () {
            isDragging = true;
        });
        window.addEventListener('mouseup', function () {
            isDragging = false;
        });
        window.addEventListener('mousemove', function (e) {
            if (!isDragging) return;

            let rect = container.getBoundingClientRect();
            let x = e.clientX - rect.left;
            if (x < 0) x = 0;
            if (x > rect.width - block.offsetWidth) x = rect.width - block.offsetWidth;

            block.style.left = x + 'px';

            if (x >= rect.width - block.offsetWidth - 10) {
                form.style.display = 'block';
            }
        });
    }
</script>
</body>
</html>
<?php $conn->close(); ?>
