<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!-- <link rel="stylesheet" href="css/home.css"> -->
    <title>Empire Living | Real Estate</title>
    <link rel="icon" href="images/EmpireLivingLogo-Transparent.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&
          family=Playfair+Display:ital,wght@0,400..900;1,400..900&
          family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
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

        header {
            color: white;
            background-color: transparent;
            font-size: 24px;
            position: absolute;
            width: 100%;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: "Montserrat", sans-serif;
            font-weight: 600;
            z-index: 10;
        }

        header .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            color: white;
        }

        header .logo img {
            height: 40px;
            width: 40px;
            margin-right: 10px;
        }

        header .side_buttons {
            display: flex;
            align-items: center;
            height: 100%;
        }

        header .side_buttons a {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 0 20px;
            text-decoration: none;
            color: white;
            transition: background-color 0.3s ease, color 0.3s ease;
            cursor: pointer;
        }

        header .side_buttons a:hover {
            background-color: white;
            color: black;
        }

        .quote {
            font-family: "Playfair Display", serif;
            font-weight: 400;
            font-style: normal;
        }

        .bg_image {
            width: 100%;
            height: 625px;
            overflow: hidden;
            position: relative;
        }

        .bg_image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .bg_image::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 90%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
            z-index: 2;
            pointer-events: none;
        }

        .bg_image_text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-family: "Playfair Display", serif;
            font-size: 64px;
            font-weight: 900;
            letter-spacing: 2px;
            text-align: center;
            z-index: 5;
            padding: 10px 20px;
        }

        .intro_text {
            text-align: center;
            padding: 50px 20px;
            font-family: "Poppins", sans-serif;
            color: #1D2731;
        }

        .intro_text h2 {
            font-family: "Playfair Display", serif;
            font-size: 36px;
            margin-bottom: 20px;
        }

        .intro_text p {
            max-width: 800px;
            margin: 0 auto;
            font-size: 18px;
        }

        #services,
        .services {
            padding: 60px 20px;
            background-color: #fafafa;
            text-align: center;
        }

        .services h2 {
            font-family: "Playfair Display", serif;
            font-size: 36px;
            margin-bottom: 30px;
            color: #1D2731;
        }

        .services_grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
        }

        .service {
            flex: 1 1 200px;
            max-width: 250px;
        }

        .service h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #1D2731;
            font-family: "Montserrat", sans-serif;
        }

        .service p {
            font-size: 16px;
            color: #555;
        }

        .preview_properties {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px 20px;
            padding: 20px;
        }

        .preview_properties h2 {
            flex-basis: 100%;
            width: 100%;
            font-family: "Playfair Display", serif;
            font-size: 36px;
            color: #1D2731;
            text-align: center;
            margin-bottom: 20px;
        }

        .property {
            position: relative;
            width: 400px;
            height: 300px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .property:hover {
            cursor: pointer;
            transform: scale(1.05);
            transition: transform 0.3s;
        }

        .property_img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .property_text {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));
            color: white;
            padding: 20px;
        }

        .property_text h3 {
            margin-bottom: 8px;
            font-size: 22px;
        }

        .property_text p {
            margin: 4px 0;
            font-size: 16px;
        }

        #reviews,
        .reviews {
            padding: 60px 20px;
            background-color: #fff;
            text-align: center;
        }

        .reviews h2 {
            font-family: "Playfair Display", serif;
            font-size: 36px;
            margin-bottom: 30px;
            color: #1D2731;
        }

        .testimonial_cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .testimonial {
            max-width: 300px;
            font-style: italic;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .testimonial .author {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            font-style: normal;
            color: #1D2731;
        }

        .why_choose_us {
            padding: 80px 20px;
            text-align: center;
            background-color: #f1f3f5;
        }

        .why_choose_us h2 {
            font-family: "Playfair Display", serif;
            font-size: 36px;
            margin-bottom: 50px;
            color: #1D2731;
        }

        .reasons_grid {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .reason {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            width: 280px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .reason h3 {
            font-family: "Montserrat", sans-serif;
            font-size: 22px;
            margin-bottom: 15px;
            color: #1D2731;
        }

        .reason p {
            font-family: "Poppins", sans-serif;
            font-size: 16px;
            color: #3C4A57;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header id="home">
        <div class="logo">
            <img src="images/EmpireLivingLogo-TransparentWhite.png" alt="Empire Living Logo">
            EMPIRE LIVING
        </div>
        <div class="side_buttons">
            <a href="home.php">Home</a>
            <a href="browse.php">Rent</a>
            <a href="sell.php">Sell</a>
<!--             Changes the header if user is logged in to redirect to profile -->
            <?php if (!isset($_SESSION['username'])): ?>
                <a href="login.php">Register / Sign In</a>
            <?php else: ?>
                <a href="profile.php">Profile</a>
            <?php endif; ?>
        </div>
    </header>

<!--     main backgorund image -->
    <div class="bg_image">
        <img src="images/buildings_background.jfif" alt="City skyline">
        <div class="bg_image_text">DISCOVER YOUR EMPIRE.</div>
    </div>

    <!-- About Section -->
    <section id="about" class="intro_text">
        <h2>About Empire Living</h2>
        <p>
            At Empire Living, we redefine luxury living in New York City.<br>
            Discover properties that offer beauty, luxury, and a sense of home!
        </p>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <h2>Our Services</h2>
        <div class="services_grid">
            <div class="service">
                <h3>Buying</h3>
                <p>We match you with your dream home, from penthouses to apartments.</p>
            </div>
            <div class="service">
                <h3>Selling</h3>
                <p>Showcase and sell your property to interested buyers.</p>
            </div>
            <div class="service">
                <h3>Property Management</h3>
                <p>We find tenants and take care of your property.</p>
            </div>
        </div>
    </section>

    <!-- Default Property Cards -->
    <section id="properties" class="preview_properties">
        <h2>Featured Listings</h2>
        <div class="property" onclick="location.href='buy.php?id=1'">
            <img src="images/properties/luxury_penthouse.png" class="property_img" alt="Luxury Penthouse">
            <div class="property_text">
                <h3>Luxury Penthouse</h3>
                <p class="location">Upper East Side</p>
                <p class="price">$5,500,000</p>
                <p class="details">3 Bed • 3 Bath • 2800 sqft</p>
            </div>
        </div>
        <div class="property" onclick="location.href='buy.php?id=2'">
            <img src="images/properties/modern_loft.png" class="property_img" alt="Modern Loft">
            <div class="property_text">
                <h3>Modern Loft</h3>
                <p class="location">SoHo</p>
                <p class="price">$3,200,000</p>
                <p class="details">2 Bed • 2 Bath • 1800 sqft</p>
            </div>
        </div>
    </section>

    <!-- client reviews section -->
    <section id="reviews" class="reviews">
        <h2>What Our Clients Say</h2>
        <div class="testimonial_cards">
            <div class="testimonial">
                <p>"Empire Living found me the BEST penthouse in under two weeks!"</p>
                <p class="author">- Jeff Bezos, Soho</p>
            </div>
            <div class="testimonial">
                <p>"Their team made selling my condo effortless and quick."</p>
                <p class="author">- Bill Gates, Chelsea</p>
            </div>
            <div class="testimonial">
                <p>“Amazing from start to finish!! Highly recommend."</p>
                <p class="author">- Donald Trump, Upper East Side</p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="why_choose_us" class="why_choose_us">
        <h2>Why Choose Empire Living</h2>
        <div class="reasons_grid">
            <div class="reason">
                <h3>Luxury Properties</h3>
                <p>We deal with the most exclusive homes and residences in New York City, offering beauty and modern living.</p>
            </div>
            <div class="reason">
                <h3>Expert Agents</h3>
                <p>Our experienced professionals provide great service to all of your needs.</p>
            </div>
            <div class="reason">
                <h3>Unmatched Locations</h3>
                <p>From Upper East Side penthouses to SoHo lofts, we connect you with properties in the best neighborhoods in Manhattan.</p>
            </div>
        </div>
    </section>
</body>

</html>