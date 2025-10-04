<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "empire_living");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// if id not provided return to browsing
if (!isset($_GET['id'])) {
    header("Location: browse.php");
    exit();
}

$property_id = $_GET['id'];
$property_result = mysqli_query($conn, "SELECT * FROM property WHERE property_id = $property_id");
$property = mysqli_fetch_assoc($property_result);

// if this property id isnt in the database then we return to the browsing
if (!$property) {
    header("Location: browse.php");
    exit();
}

//  get info about user from session & sql
$username = $_SESSION['username'];
$user_result = mysqli_query($conn, "SELECT user_id FROM user WHERE username = '$username'");
$user = mysqli_fetch_assoc($user_result);
$user_id = $user['user_id'];

// prevents user from booking their own property
if ($property['user_id'] == $user_id) {
    header("Location: browse.php");
    exit();
}

$agents_result = mysqli_query($conn, "SELECT * FROM agent");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $agent_id = intval($_POST['agent_id']);
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    // updates and creates an appointment in the database
    $insert = "INSERT INTO appointment (user_id, property_id, agent_id, appointment_date, appointment_time) VALUES ('$user_id', '$property_id', '$agent_id', '$date', '$time')";
    mysqli_query($conn, $insert);
    header("Location: home.php");
    exit();
}

include('header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empire Living | Book Viewing</title>
    <link rel="icon" href="images/EmpireLivingLogo-Transparent.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: flex-start;

            background-image: url('images/bg_booking.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .container {
            margin-top: 100px;
            background-color: #F3F0F1;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            width: 100%;
            box-sizing: border-box;
        }


        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        .property-details {
            text-align: center;
            margin-bottom: 20px;
        }

        .property-details img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: rgb(10, 3, 58);
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #004d99;
        }
    </style>
</head>

<body>
    <form action="buy.php?id=<?php echo $property_id; ?>" method="post" class="container">
        <!-- Property's Image -->
        <?php if ($property['image']): ?>
            <?php $imgData = base64_encode($property['image']); ?>
            <div class="property-image" style="width:100%; margin-bottom:16px;">
                <img src="data:image/jpeg;base64,<?php echo $imgData; ?>" alt="Property Image"
                    style="width:100%; height:auto; border-radius:8px;">
            </div>
        <?php endif; ?>

        <!-- All Property Details -->
        <div class="property-details" style="text-align:left; margin-bottom:16px; line-height:1.4;">
            <p style="margin:4px 0;"><strong>Title:</strong> <?php echo $property['title']; ?></p>
            <p style="margin:4px 0;"><strong>Location:</strong> <?php echo $property['location']; ?></p>
            <p style="margin:4px 0;"><strong>Cost:</strong> $<?php echo number_format($property['cost']); ?></p>
            <p style="margin:4px 0;"><strong>Square Feet:</strong> <?php echo $property['square_feet']; ?> sq ft</p>
            <p style="margin:4px 0;"><strong>Bedrooms:</strong> <?php echo $property['bedrooms']; ?></p>
            <p style="margin:4px 0;"><strong>Bathrooms:</strong> <?php echo $property['bathrooms']; ?></p>
            <p style="margin:4px 0;"><strong>Description:</strong><br><?php echo $property['description']; ?>
            </p>
        </div>

        <!-- Booking Fields -->
        <label for="appointment_date">Preferred Date</label>
        <input type="date" id="appointment_date" name="appointment_date" required style="margin-bottom:12px;">

        <label for="appointment_time">Preferred Time</label>
        <input type="time" min="09:00" max="17:00" id="appointment_time" name="appointment_time" required style="margin-bottom:12px;">

        <label for="agent_id">Select Agent</label>
        <select id="agent_id" name="agent_id" required style="margin-bottom:16px;">
            <option value="">-- Choose an Agent --</option>
            <?php while ($agent = mysqli_fetch_assoc($agents_result)): ?>
                <option value="<?php echo $agent['agent_id']; ?>">
                    <?php echo $agent['first_name'] . ' ' . $agent['last_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!--  Submit Button -->
        <button type="submit" class="submit-btn" style="padding:10px 0;">Book Appointment</button>
    </form>
</body>
</html>