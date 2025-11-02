<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "pharmacy_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // 1. Check kung pareho password at confirm password
    if ($password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match!');
                window.location.href='register.html';
              </script>";
        exit();
    }

    // 2. Check kung existing na yung username o email (no get_result!)
    $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result(); // ✅ works even without MySQLnd

    if ($check->num_rows > 0) {
        echo "<script>
                alert('Username or Email already exists!');
                window.location.href='register.html';
              </script>";
        $check->close();
        $conn->close();
        exit();
    }

    // 3. I-save yung bagong account
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>
                alert('Account created successfully! Please login.');
                window.location.href='login_page.html';
              </script>";
    } else {
        echo "<script>
                alert('Error creating account. Please try again.');
                window.location.href='register.html';
              </script>";
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>
