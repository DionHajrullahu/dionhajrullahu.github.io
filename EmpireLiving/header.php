<style>
        header {
            color: white;
            background-color: black;
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
</style>

<!-- Header HTML -->
<header id="home">
    <div class="logo">
        <img src="images/EmpireLivingLogo-TransparentWhite.png" alt="Empire Living Logo">
        EMPIRE LIVING
    </div>
    <div class="side_buttons">
        <a href="home.php">Home</a>
        <a href="browse.php">Rent</a>
        <a href="sell.php">Sell</a>
        <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
                // so if the user isn't logged in, we allow them to register
            if (!isset($_SESSION['username'])) {
                echo '<a href="login.php">Register / Sign In</a>';
            } 
                // if theyre already logged in they instead go to their profile
            else {
                echo '<a href="profile.php">Profile</a>';
            }
        ?>
    </div>
</header>