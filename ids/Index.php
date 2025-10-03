<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "cybersecurity_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $latest_intrusion = "No intrusions detected.";
    $result = $conn->query("SELECT * FROM Intrusions ORDER BY IntrusionID DESC LIMIT 1");

    if ($result && $row = $result->fetch_assoc()) {
        $type = isset($row['TypeOfIntrusion']) ? $row['TypeOfIntrusion'] : 'Unknown';
        $time = isset($row['IntrusionTime']) ? $row['IntrusionTime'] : 'Unknown time';
        $date = isset($row['IntrusionDate']) ? $row['IntrusionDate'] : 'Unknown date';

        $latest_intrusion = "Intrusion Detected: $type at $time on $date";
    }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RITK Intrusion Detection System</title>
    <style>
        @keyframes cmatrix {
            0% {transform: translateY(0); opacity: 1;}
            100% {transform: translateY(100vh); opacity: 0;}
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            color: white;
            overflow: hidden;
            background-color: black;
        }

        .cmatrix {
            position: absolute;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            z-index: -1;
        }

        .char {
            position: absolute;
            color: #00ff00;
            font-size: 20px;
            animation: cmatrix linear infinite;
        }

        .marquee-container {
            width: 100%;
            background: #f76902;
            color: white;
            padding: 10px 0;
            text-align: center;
            font-weight: bold;
            position: fixed;
            top: 0;
            z-index: 10;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            padding-top: 60px;
            text-align: center;
        }

        h1 {
            font-size: 48px;
            color: #f76902;
            margin-bottom: 20px;
            text-shadow: 0 0 10px rgba(247, 105, 2, 0.8);
        }

        p.subtitle {
            font-size: 20px;
            margin-bottom: 40px;
            color: #ffffffcc;
        }

        .button {
            padding: 20px 40px;
            font-size: 20px;
            text-align: center;
            background: #f76902;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            margin: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .button:hover {
            background: #e85d00;
            transform: scale(1.05);
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .logo {
            max-width: 160px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 10px rgb(10, 8, 7));
            animation: floatLogo 1.5s ease-in-out infinite;
}

        @keyframes floatLogo {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
    }

    </style>
</head>
<body>
    <div class="cmatrix" id="cmatrix"></div>

    <div class="marquee-container">
        <marquee behavior="scroll" direction="left"><?= htmlspecialchars($latest_intrusion) ?></marquee>
    </div>

    <div class="container">
        <img src="logo.png" alt="RITK Logo" class="logo">
        <h1>RITK University IDS</h1>
        <p class="subtitle">Real-time Cybersecurity Awareness for a Safer Campus</p>
        <div class="button-container">
            <a href="insertdata.php" class="button">Insert Data</a>
            <a href="history.php" class="button">History</a>
            <a href="login.php" class="button">Login</a>
            <a href="support.php" class="button">Contact Cybersecurity Support</a>
        </div>
    </div>

    <script>
        const cmatrix = document.getElementById("cmatrix");
        const chars = "01";
        const total = 100;

        for (let i = 0; i < total; i++) {
            const span = document.createElement("span");
            span.className = "char";
            span.textContent = chars[Math.floor(Math.random() * chars.length)];
            span.style.left = `${Math.random() * 100}%`;
            span.style.top = `-${Math.random() * 100}vh`;
            span.style.animationDuration = `${Math.random() * 5 + 3}s`;
            span.style.animationDelay = `${Math.random() * 5}s`;
            cmatrix.appendChild(span);
        }
    </script>
</body>
</html>