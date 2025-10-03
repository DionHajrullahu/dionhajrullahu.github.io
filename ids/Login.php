<?php
session_start();

// Reset login status on every page load  // <<< NOTE: This logic is generally discouraged, see previous improvement suggestions.
$_SESSION['admin_logged_in'] = false;
$_SESSION['user_role'] = null; // Added to track user role

// !! SECURITY WARNING: Using 'root' with no password is highly insecure. Use dedicated credentials. !!
$conn = new mysqli("localhost", "root", "", "cybersecurity_db");
if ($conn->connect_error) {
    // Consider more user-friendly error handling for production
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // !! SECURITY WARNING: Storing plain text passwords is a major vulnerability. Use password hashing. !!
    $valid_users = [
        'Muzafer Shala' => 'prishtina123',
        'Debabrata Samanta' => 'prishtina123',
        'Krenar Kepuska' => 'prishtina123',
        'Korab Jashari' => 'kosovo123',
        'Shendet Avdyli' => 'kosovo123',
        'Dion Hajrullahu' => 'kosovo123'
    ];

    // Check if user is valid and set session values
    if (array_key_exists($username, $valid_users) && $valid_users[$username] === $password) {
        $_SESSION['admin_logged_in'] = true; // Consider renaming to 'logged_in'
        $_SESSION['user_role'] = in_array($username, ['Muzafer Shala', 'Debabrata Samanta', 'Krenar Kepuska']) ? 'professor' : 'student';
        // Missing: session_regenerate_id(true); after successful login
    } else {
        // Immediate block on any failed attempt
        $_SESSION['blocked'] = true;
        $_SESSION['robot_verified'] = false;
        $_SESSION['admin_logged_in'] = false;

        // Logging 'Privilege Escalation' for a failed login is inaccurate.
        $type = "Privilege Escalation"; // Should be "Failed Login Attempt"
        $date = date("Y-m-d");
        $time = date("H:i:s");

        // Prepared statement is good here
        $stmt = $conn->prepare("INSERT INTO Intrusions (TypeOfIntrusion, IntrusionDate, IntrusionTime) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $type, $date, $time);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle the unblock mechanism
if (isset($_GET['unblock'])) {
    $_SESSION['blocked'] = false; // Unblocks immediately
    $_SESSION['robot_verified'] = true;
    $_SESSION['admin_logged_in'] = false;

    // Redirect back to login page after unblock
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIT Secure Login</title>
    <style>
        /* RIT Color Palette */
        :root {
            --rit-orange: #F76902;
            --rit-black: #2D2926; /* Softer Black */
            --rit-white: #FFFFFF;
            --rit-light-gray: #f0f0f0; /* Light gray for background */
            --rit-medium-gray: #CCCCCC; /* For borders */
            --rit-dark-gray: #333333; /* For text on light backgrounds */
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #D45A01;
            color: var(--rit-dark-gray); /* Default text color */
        }
        .login-panel {
            width: 320px;
            /* Changed background to RIT Black */
            background-color: var(--rit-black);
            color: var(--rit-white); /* Text on black panel is white */
            padding: 25px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2); /* Slightly darker shadow */
            border-radius: 12px;
            text-align: center;
        }
        .login-panel h2 {
            margin-bottom: 20px;
            font-weight: normal; /* Normal weight heading */
        }
        .login-panel input, .login-panel button {
            width: 100%; /* Make inputs/button full width */
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box; /* Include padding in width */
        }
        .login-panel input {
             /* White background for input */
            background-color: var(--rit-white);
            /* Dark text for input */
            color: var(--rit-dark-gray);
            border: 1px solid var(--rit-medium-gray); /* Add subtle border */
        }
        .login-panel input:focus {
            outline: none;
             /* Orange border on focus */
            border-color: var(--rit-orange);
            box-shadow: 0 0 5px rgba(247, 105, 2, 0.5);
        }

        .login-panel button {
            /* Changed background to RIT Orange */
            background-color: var(--rit-orange);
            color: var(--rit-white); /* Text on button is white */
            cursor: pointer;
            transition: background-color 0.3s ease; /* Smoother transition */
        }
        .login-panel button:hover {
            /* Darker orange on hover */
            background-color: #D45A01;
            /* Removed transform scale on hover */
        }
        .captcha {
            margin-top: 25px;
            text-align: center;
        }
        .captcha .drag-container {
             /* White background for contrast */
            background: var(--rit-white);
            border-radius: 30px;
            position: relative;
            height: 50px;
            width: 90%;
            margin: 20px auto;
            border: 1px solid var(--rit-medium-gray); /* Border for definition */
        }
        .captcha .drag-block {
            width: 48px; /* Slightly smaller */
            height: 48px;
             /* Changed background to RIT Orange */
            background: var(--rit-orange);
            border-radius: 50%;
            position: absolute;
            left: 1px; /* Start inside */
            top: 1px;
            cursor: grab;
             /* Removed transition */
        }
         .captcha .drag-block:active {
             cursor: grabbing;
         }
        .captcha .x-marker {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 24px;
             /* Changed color to RIT Black */
            color: var(--rit-black);
            font-weight: bold;
        }
         /* Style for the retry button */
         #unblock-button {
             background-color: var(--rit-dark-gray); /* Dark gray button */
             color: var(--rit-white);
         }
         #unblock-button:hover {
             background-color: #555555; /* Lighter gray on hover */
         }
        .login-panel a {
            /* Changed link color to RIT Orange */
            color: var(--rit-orange);
            text-decoration: none; /* No underline by default */
            margin-top: 15px;
            display: block;
            font-size: 0.9em;
        }
        .login-panel a:hover {
            text-decoration: underline; /* Underline on hover */
        }
        /* Style for error/blocked message */
        .message-alert {
            color: var(--rit-orange); /* Orange text for alert */
            font-weight: bold;
            margin-bottom: 15px;
        }
        /* Style for success message */
        .message-success {
            color: var(--rit-white); /* White text */
            margin-bottom: 15px;
        }

        .logoh {
            position: absolute;
            top: 20px;
            right: 20px;
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
<img src="logo.png" alt="rit logo" class="logoh">
<body>
<div class="login-panel">
    <h2>RIT Login</h2> <?php // Combined blocked/unverified check more clearly
          $show_captcha = isset($_SESSION['blocked']) && $_SESSION['blocked'] && (!isset($_SESSION['robot_verified']) || !$_SESSION['robot_verified']);
          $show_login_form = !isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in'];
    ?>

    <?php if ($show_captcha): ?>
        <p class="message-alert">🚨 Access blocked. Please verify below.</p>
        <div class="captcha">
            <p style="color: var(--rit-white); font-size: 0.9em;">Drag the orange circle to the "X":</p>
            <div class="drag-container" id="drag-container">
                <div class="drag-block" id="drag-block"></div>
                <div class="x-marker">X</div>
            </div>
            <form method="get" id="unblock-form" style="display:none; margin-top: 15px;">
                 <button name="unblock" value="1" id="unblock-button">🧩 Verify Identity</button>
            </form>
        </div>
    <?php elseif ($show_login_form): ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required aria-label="Username">
            <input type="password" name="password" placeholder="Password" required aria-label="Password">
            <button type="submit">Login</button>
        </form>
    <?php else: ?>
        <p class="message-success">✅ Logged in as <?php echo htmlspecialchars($_SESSION['user_role'] == 'professor' ? 'Professor' : 'Student'); ?></p>
        <a href="insertdata.php" style="display: inline-block; background-color: var(--rit-orange); color: var(--rit-white); padding: 10px 15px; border-radius: 8px; text-decoration: none; margin-top: 10px;">Go to Insert Data</a>
    <?php endif; ?>

    <?php if ($show_login_form || $show_captcha): ?>
    <div style="margin-top: 20px;">
        <a href="index.php">⬅️ Back to Home</a>
    </div>
    <?php endif; ?>
</div>

<?php // Only include JS if captcha is needed ?>
<?php if ($show_captcha): ?>
<script>
    const block = document.getElementById('drag-block');
    const container = document.getElementById('drag-container');
    const form = document.getElementById('unblock-form');
    // const unblockBtn = document.getElementById('unblock-button'); // Button is inside form

    let isDragging = false;

    if (block && container && form) { // Check all elements exist
        block.addEventListener('mousedown', function () {
            isDragging = true;
            block.style.cursor = 'grabbing'; // Change cursor
            document.body.style.userSelect = 'none'; // Prevent text selection
        });

        window.addEventListener('mouseup', function () {
            if (isDragging) {
                isDragging = false;
                block.style.cursor = 'grab'; // Restore cursor
                document.body.style.userSelect = ''; // Restore selection
            }
        });

        window.addEventListener('mousemove', function (e) {
            if (!isDragging) return;

            let containerRect = container.getBoundingClientRect();
            // Calculate desired position relative to container start
            let x = e.clientX - containerRect.left - (block.offsetWidth / 2); // Center handle on cursor

            // Clamp position within the container
            const minX = 0;
            const maxX = containerRect.width - block.offsetWidth;
            if (x < minX) x = minX;
            if (x > maxX) x = maxX;

            block.style.left = x + 'px';

            // Check if target is reached (allow some tolerance)
            if (x >= maxX - 10) { // Within 10px of the end
                form.style.display = 'block'; // Show the form/button
            } else {
                form.style.display = 'none'; // Hide if not reached
            }
        });
    } else {
        console.error("Captcha drag-and-drop elements not found.");
    }
</script>
<?php endif; ?>

</body>
</html>

<?php
// Close connection at the end of the script
if (isset($conn)) {
    $conn->close();
}
?> 