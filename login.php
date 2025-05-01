<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "root"; // Default MAMP MySQL password
$dbname = "velvet_spoon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (filter_var($userInput, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT id, username, password FROM users WHERE email = ?";
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userInput);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_username, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "No account found with that username or email.";
    }
    $stmt->close();
}
$conn->close();
?>