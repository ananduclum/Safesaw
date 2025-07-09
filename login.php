<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "SafeSawDB", 3307);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $mysqli->prepare("SELECT user_id, password_hash FROM users WHERE email = ?");
if (!$stmt) {
    die("Query preparation failed: " . $mysqli->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($user_id, $hash);

if ($stmt->fetch()) {
    if (password_verify($password, $hash)) {
        $_SESSION['user_id'] = $user_id;
        header("Location: index.html");  // or dashboard.php
        exit();
    } else {
        echo "❌ Invalid password.";
    }
} else {
    echo "❌ Email not registered.";
}

$stmt->close();
$mysqli->close();
?>
