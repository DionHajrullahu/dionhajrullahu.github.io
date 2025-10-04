<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Connect DB
$conn = mysqli_connect('localhost', 'root', '', 'empire_living')
    or die('DB connection error');

// Fetch user info
$username = $_SESSION['username'];
$res = mysqli_query($conn, "SELECT user_id, email, role FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($res);
$role = $user["role"];
$user_id = $user['user_id'];

// Delete property
if (isset($_GET['delete_id'])) {
    $del = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM property WHERE property_id=$del AND user_id=$user_id");
    header('Location: profile.php');
    exit;
}

// Update property
if (isset($_POST['update'])) {
    $id = intval($_POST['property_id']);
    $title = $_POST['title'];
    $location = $_POST['location'];
    $address = $_POST['address'];
    $cost = intval($_POST['cost']);
    $sqft = intval($_POST['square_feet']);
    $bedrooms = intval($_POST['bedrooms']);
    $bathrooms = intval($_POST['bathrooms']);
    $description = $_POST['description'];

    $sql = "UPDATE property SET 
        title='$title', 
        location='$location', 
        address='$address', 
        cost=$cost, 
        square_feet=$sqft, 
        bedrooms=$bedrooms, 
        bathrooms=$bathrooms, 
        description='$description' 
      WHERE property_id=$id AND user_id=$user_id";
    mysqli_query($conn, $sql);
    header('Location: profile.php');
    exit;
}

// Fetch one for edit
$edit = null;
if (isset($_GET['edit_id'])) {
    $e = intval($_GET['edit_id']);
    $res2 = mysqli_query($conn, "SELECT * FROM property WHERE property_id=$e AND user_id=$user_id");
    $edit = mysqli_fetch_assoc($res2);
}

// Fetch all user's properties
$all = mysqli_query($conn, "SELECT * FROM property WHERE user_id=$user_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/EmpireLivingLogo-Transparent.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <title>Empire Living | Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #fafafa;
            padding-top: 60px;
        }

        header {
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background: black;
            z-index: 10;
        }

        .logo {
            height: 40px;
            margin-left: 20px;
        }

        .company-name {
            font-size: 24px;
            color: #fff;
            font-weight: bold;
            margin-left: 10px;
        }

        .container {
            background: #fff;
            border: 1px solid #ccc;
            max-width: 800px;
            margin: 20px auto;
            padding: 16px;
        }

        h2,
        h3 {
            color: #0a033a;
            margin-bottom: 8px;
        }

        p,
        td,
        th {
            font-size: 14px;
        }

        table {
            width: 100%;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #eee;
        }

        form {
            background: #fff;
            border: 1px solid #ccc;
            padding: 16px;
            margin-bottom: 20px;
        }

        form p {
            margin-bottom: 8px;
        }

        form input[type=text],
        form input[type=number],
        form textarea {
            width: 100%;
            padding: 6px;
            border: 1px solid #ccc;
        }

        form input[type=submit] {
            padding: 6px 12px;
            background: #0a033a;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include('header.php'); ?>

<!--     the pain part which greets user and lets them logout -->
    <div class="container">
        <h2>Hello, <?php echo $username; ?>!</h2>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p><br>
        <h3><a href="logout.php">Logout</a></h3><br>
        <?php
        if ($role === 'staff') {
            echo '<h3><a href="staff_dashboard.php">ADMIN DASHBOARD</a></h3>';
        }
        ?>

    </div>

<!--     only shows if the edit get variable exists, basically allows input to change property info -->
    <?php if ($edit): ?>
        <div class="container">
            <h3>Edit Property #<?php echo $edit['property_id']; ?></h3>
            <form method="post">
                <input type="hidden" name="property_id" value="<?php echo $edit['property_id']; ?>">
                <p>Title:<br><input type="text" name="title" value="<?php echo $edit['title']; ?>"></p>
                <p>Location:<br><input type="text" name="location" value="<?php echo $edit['location']; ?>"></p>
                <p>Address:<br><input type="text" name="address" value="<?php echo $edit['address']; ?>"></p>
                <p>Cost:<br><input type="number" name="cost" value="<?php echo $edit['cost']; ?>"></p>
                <p>Square Feet:<br><input type="number" name="square_feet" value="<?php echo $edit['square_feet']; ?>"></p>
                <p>Bedrooms:<br><input type="number" name="bedrooms" value="<?php echo $edit['bedrooms']; ?>"></p>
                <p>Bathrooms:<br><input type="number" name="bathrooms" value="<?php echo $edit['bathrooms']; ?>"></p>
                <p>Description:<br><textarea name="description"><?php echo $edit['description']; ?></textarea></p>
                <p><input type="submit" name="update" value="Update"> <a href="profile.php">Cancel</a></p>
            </form>
        </div>
    <?php endif; ?>

<!--     a table of all properties under logged in user's ID -->
    <div class="container">
        <h2>My Properties</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Location</th>
                <th>Cost</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($all)): ?>
                <tr>
                    <td><?php echo $row['property_id']; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['cost']; ?></td>
                    <td>
                        <a href="profile.php?edit_id=<?php echo $row['property_id']; ?>">Edit</a>
                        <a href="profile.php?delete_id=<?php echo $row['property_id']; ?>" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>