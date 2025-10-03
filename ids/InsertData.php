<?php
    session_start();
    $conn = new mysqli("localhost", "root", "", "cybersecurity_db");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Track SQL Insert Attempts
    if (!isset($_SESSION['insert_attempts'])) {
        $_SESSION['insert_attempts'] = [];
    }

    // Remove expired attempts (older than 5 minutes)
    $_SESSION['insert_attempts'] = array_filter(
        $_SESSION['insert_attempts'],
        fn($timestamp) => $timestamp > (time() - 300)
    );

    // Handle POST request
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_SESSION['insert_attempts'][] = time();

        if (count($_SESSION['insert_attempts']) > 3) {
            $stmt = $conn->prepare("INSERT INTO Intrusions (TypeOfIntrusion, IntrusionDate, IntrusionTime) VALUES (?, CURDATE(), CURTIME())");
            $intrusionType = "SQL Data Injection (Insert Overload)";
            $stmt->bind_param("s", $intrusionType);
            $stmt->execute();
            $stmt->close();

            echo "<p style='color: red;'>Too many insert attempts detected! Possible SQL Injection. Action has been logged.</p>";
        } else {
            $name = $_POST['name'];
            $professor = $_POST['professor'];
            $class = $_POST['class'];
            $grade = $_POST['grade'];

            $sql = "INSERT INTO ClassGrades (Name, Professor, Class, Grade) VALUES ('$name', '$professor', '$class', '$grade')";
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color: green;'>New record created successfully</p>";
            } else {
                echo "<p style='color: red;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
            }
        }
    }

    $result = $conn->query("SELECT * FROM ClassGrades");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #121212;
            color: #ffffff;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #1e1e1e;
            color: #ffffff;
        }
        th, td {
            border: 1px solid #ffffff;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .form-container {
            margin: 20px auto;
            width: 50%;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 123, 255, 0.5);
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #333;
            color: white;
        }
        .button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Class Grades</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Professor</th>
            <th>Class</th>
            <th>Grade</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['ID'] ?></td>
            <td><?= $row['Name'] ?></td>
            <td><?= $row['Professor'] ?></td>
            <td><?= $row['Class'] ?></td>
            <td><?= $row['Grade'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <div class="form-container">
        <h3>Insert New Data</h3>
        <form method="POST">
            <input type="text" name="name" placeholder="Student Name" required>
            <input type="text" name="professor" placeholder="Professor Name" required>
            <input type="text" name="class" placeholder="Class" required>
            <input type="text" name="grade" placeholder="Grade (e.g., A, B+)" required>
            <br>
            <button type="submit" class="button">Insert</button>
        </form>
    </div>

    <br>
    <a href="index.php" class="button">Back to Home</a>
</body>
</html>
<?php $conn->close(); ?>
