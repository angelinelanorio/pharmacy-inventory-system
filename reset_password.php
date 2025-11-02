<?php
$conn = new mysqli("localhost", "root", "", "pharmacy_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];

    if ($password !== $confirm) {
        echo "<script>
                alert('Passwords do not match!');
                window.history.back();
              </script>";
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
    $stmt->bind_param("ss", $hashedPassword, $email);

    if ($stmt->execute()) {
        echo "<script>
                alert('Password updated successfully! You can now login.');
                window.location.href='login_page.html';
              </script>";
    } else {
        echo "<script>
                alert('Error updating password.');
                window.location.href='forgot_password.html';
              </script>";
    }

    $stmt->close();
}
$conn->close();
?>
