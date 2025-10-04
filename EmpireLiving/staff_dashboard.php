<?php
session_start();

// doesnt allow normal users to accss the page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
    header('Location: login.php');
    exit;
}

// Connect to sql
$conn = mysqli_connect('localhost', 'root', '', 'empire_living')
    or die('Connection error');

// deletes property from SQL
if (isset($_GET['delete_id'])) {
    $del = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM property WHERE property_id = $del");
    header('Location: staff_dashboard.php');
    exit;
}

// updates property
if (isset($_POST['update'])) {
    $id           = intval($_POST['property_id']);
    $new_user_id  = intval($_POST['user_id']);
    $title        = $_POST['title'];
    $location     = $_POST['location'];
    $address      = $_POST['address'];
    $cost         = intval($_POST['cost']);
    $sqft         = intval($_POST['square_feet']);
    $bedrooms     = intval($_POST['bedrooms']);
    $bathrooms    = intval($_POST['bathrooms']);
    $description  = $_POST['description'];

    // Validate new user_id exists
    $user_ok = false;
    $u_res = mysqli_query($conn, "SELECT user_id FROM user WHERE user_id = $new_user_id");
    if ($u_res && mysqli_num_rows($u_res) > 0) {
        $user_ok = true;
    }

    // Build UPDATE SQL
    $sql = "UPDATE property SET ";
    if ($user_ok) {
        $sql .= "user_id = $new_user_id, ";
    }
    $sql .= 
        "title = '$title',
         location = '$location',
         address = '$address',
         cost = $cost,
         square_feet = $sqft,
         bedrooms = $bedrooms,
         bathrooms = $bathrooms,
         description = '$description'
     WHERE property_id = $id";

    mysqli_query($conn, $sql);
    header('Location: staff_dashboard.php');
    exit;
}

// gets specific property record if requested to edit
$edit = null;
if (isset($_GET['edit_id'])) {
    $e = $_GET['edit_id'];
    $res = mysqli_query($conn, "SELECT * FROM property WHERE property_id = $e");
    $edit = mysqli_fetch_assoc($res);
}

// gets all properties
$all = mysqli_query($conn, "SELECT property_id, user_id, title, location, cost FROM property");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo {
            cursor: pointer;
            height: 40px;
            margin-right: 10px;
        }
        .company-name {
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
            color: #0a033a;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e0e0e0;
        }
        a {
            text-decoration: underline;
            color: #0a033a;
            margin-right: 8px;
        }
        form {
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
        }
        form p {
            margin: 8px 0;
        }
        form input[type="text"],
        form input[type="number"],
        form textarea {
            width: 100%;
            padding: 6px;
            margin-top: 4px;
            border: 1px solid #ccc;
        }
        form input[type="submit"] {
            padding: 6px 12px;
            background: #0a033a;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <img src="images/EmpireLivingLogo-Transparent.png" alt="Logo" class="logo">
        <a href="home.php" class="company-name">EMPIRE LIVING</a>
    </header>

    <h2>Staff Dashboard</h2>

<!--     shows the edit form ONLY if the edit GET variable exists. $edit is not null if so -->
    <?php if ($edit): ?>
        <h3>Edit Property #<?php echo $edit['property_id']; ?></h3>
        <form method="post">
            <input type="hidden" name="property_id" value="<?php echo $edit['property_id']; ?>">
            <p>User ID:<br><input type="number" name="user_id" value="<?php echo $edit['user_id']; ?>"></p>
            <p>Title:<br><input type="text" name="title" value="<?php echo $edit['title']; ?>"></p>
            <p>Location:<br><input type="text" name="location" value="<?php echo $edit['location']; ?>"></p>
            <p>Address:<br><input type="text" name="address" value="<?php echo $edit['address']; ?>"></p>
            <p>Cost:<br><input type="number" name="cost" value="<?php echo $edit['cost']; ?>"></p>
            <p>Square Feet:<br><input type="number" name="square_feet" value="<?php echo $edit['square_feet']; ?>"></p>
            <p>Bedrooms:<br><input type="number" name="bedrooms" value="<?php echo $edit['bedrooms']; ?>"></p>
            <p>Bathrooms:<br><input type="number" name="bathrooms" value="<?php echo $edit['bathrooms']; ?>"></p>
            <p>Description:<br>
               <textarea name="description"><?php echo $edit['description']; ?></textarea>
            </p>
            <p>
                <input type="submit" name="update" value="Update">
                <a href="staff_dashboard.php">Cancel</a>
            </p>
        </form>
    <?php endif; ?>

<!--     displays every property in the db -->
    <h3>All Properties</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Title</th>
            <th>Location</th>
            <th>Cost</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($all)): ?>
        <tr>
            <td><?php echo $row['property_id']; ?></td>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><?php echo $row['cost']; ?></td>
            <td>
                <a href="staff_dashboard.php?edit_id=<?php echo $row['property_id']; ?>">Edit</a>
                <a href="staff_dashboard.php?delete_id=<?php echo $row['property_id']; ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>