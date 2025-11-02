<?php
$conn = new mysqli("localhost", "root", "", "pharmacy_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email found → go to reset page
        header("Location: reset_password.html?email=" . urlencode($email));
        exit();
    } else {
        echo "<script>
                alert('Email not found!');
                window.location.href='forgot_password.html';
              </script>";
    }

    $stmt->close();
}
$conn->close();
?>
