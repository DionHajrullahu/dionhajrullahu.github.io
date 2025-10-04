<?php
session_start();
if (!isset($_SESSION['username'])) { //check if the session is set
    // Not logged in
    header("Location: login.php");
    exit();
}

$link = mysqli_connect("localhost", "root", "", "empire_living"); // connecting with db
//checking if all of these are set
if (isset($_POST['title']) && isset($_POST['price']) && isset($_POST['sqft']) && isset($_POST['bedrooms']) && isset($_POST['bathrooms']) && isset($_POST['location']) && isset($_POST['description']) && isset($_FILES["pic"])) {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $sqft = $_POST['sqft'];
    $bed = $_POST['bedrooms'];
    $bath = $_POST['bathrooms'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $pic = addslashes(file_get_contents($_FILES["pic"]["tmp_name"])); //makes it possible to save a file in db

    // Get the user ID 
    $username = $_SESSION['username'];
    $user_id_result = mysqli_query($link, "SELECT user_id FROM user WHERE username = '$username'");

    if ($row = mysqli_fetch_assoc($user_id_result)) {
        $user_id = $row['user_id'];

        // Insert the property from that user that you are logged in as 
        $sql = "INSERT INTO property (title, user_id, cost, square_feet, bedrooms, bathrooms, image, location, description) VALUES ('$title', '$user_id', '$price', '$sqft', '$bed', '$bath', '$pic', '$location', '$description')";

        mysqli_query($link, $sql);
    }

    mysqli_close($link);
    header("Location: browse.php");
    exit();
}

include('header.php');
?>

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
    <title>Empire Living | Sell Property</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            background-image: url('images/bg_sell.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
            justify-content: center;
        }


        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #000;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        input {
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

        .buttons a button {
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

        a button:hover {
            background-color: #004d99;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .container {
            margin-top: 200px;
            background-color: #F3F0F1;
            padding: 50px;
            border-radius: 10px;
            height: 675px;
        }

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
            background-color: white;
        }

        .submit:hover {
            background-color: #0012;
            cursor: pointer;
        }
    </style>
</head>

<form action="sell.php" method="post" enctype="multipart/form-data" class="container">
    <h2>Sell Your Property</h2>
    <input type="text" name="title" placeholder="Your property's title" required><br>
    <input type="number" name="price" placeholder="The price of your property" required><br>
    <input type="number" name="sqft" placeholder="Size of your property (in sq feet)" required><br>
    <input type="number" name="bedrooms" placeholder="Number of bedrooms" required><br>
    <input type="number" name="bathrooms" placeholder="Number of bathrooms" required><br>
    <input type="text" name="location" placeholder="Location (e.g., SoHo, Upper East Side)" required><br>
    <textarea name="description" placeholder="Describe your property" rows="4" cols="50"
        maxlength="255"></textarea><br><br>
    <input type="file" name="pic" required><br>
    <input type="submit" class="submit"><br>
</form>

</body>
</html>