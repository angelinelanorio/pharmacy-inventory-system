<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "pharmacy_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values safely
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $_POST["password"];

    // Find user by username
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verify password (hashed or plain fallback)
        if ($password === $row["password"] || password_verify($password, $row["password"])) {
            // Store session info after successful login
            $_SESSION["username"] = $row["username"];
            $_SESSION["user_id"] = $row["id"]; // optional

            echo "<script>
                alert('Login successful! Welcome, $username');
                window.location.href = '../Dashboard/dashboard.php';

            </script>";
            exit();
        } else {
            echo "<script>
                alert('Invalid password');
                window.location.href = 'login_page.html';
            </script>";
            exit();
        }
    } else {
        echo "<script>
            alert('User not found');
            window.location.href = 'login_page.html';
        </script>";
        exit();
    }
}

$conn->close();
?>
