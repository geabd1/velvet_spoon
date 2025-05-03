<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_username'])) {
        $username = trim($_POST['username']);
        
        // Check if username exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check_stmt->bind_param("si", $username, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $_SESSION['error'] = "Username already taken.";
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $username, $user_id);
            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "Username updated successfully!";
            } else {
                $_SESSION['error'] = "Error updating username.";
            }
            $stmt->close();
        }
        $check_stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    
    if (isset($_POST['change_email'])) {
        $email = trim($_POST['email']);
        
        // Check if email exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_stmt->bind_param("si", $email, $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $_SESSION['error'] = "Email already in use.";
        } else {
            $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->bind_param("si", $email, $user_id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Email updated successfully!";
            } else {
                $_SESSION['error'] = "Error updating email.";
            }
            $stmt->close();
        }
        $check_stmt->close();
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $hashed_password, $user_id);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Password updated successfully!";
                } else {
                    $_SESSION['error'] = "Error updating password.";
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "New passwords don't match.";
            }
        } else {
            $_SESSION['error'] = "Current password is incorrect.";
        }
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings | The Velvet Spoon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .account-settings-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .settings-header h1 {
            color: #902C3E;
            font-family: 'Jacques Francois Shadow', serif;
            font-size: 2.5rem;
            margin: 0;
        }
        
        .back-link {
            color: #902C3E;
            text-decoration: none;
            font-size: 1.1rem;
            border: 2px solid #902C3E;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            background-color: #902C3E;
            color: white;
        }
        
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 0;
            border-bottom: 1px solid #ddd;
        }
        
        .setting-label {
            font-weight: bold;
            font-size: 1.2rem;
            color: #333;
            flex: 1;
        }
        
        .setting-value {
            flex: 2;
            padding: 0 1rem;
            font-size: 1.1rem;
        }
        
        .setting-action {
            color: #902C3E;
            text-decoration: none;
            font-weight: 500;
            border: 2px solid #902C3E;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .setting-action:hover {
            background-color: #902C3E;
            color: white;
        }
        
        .delete-account {
            display: block;
            margin-top: 2rem;
            color: #cc0000;
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            text-align: center;
        }
        
        .delete-account:hover {
            text-decoration: underline;
        }
        
        .footer-motto {
            text-align: center;
            margin-top: 3rem;
            font-style: italic;
            color: #666;
            font-size: 1.2rem;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        
        .modal-content {
            background: #F8E9DB;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .modal h3 {
            margin: 0 0 10px 0;
            font-size: 1.5rem;
            color: #902C3E;
        }
        
        .modal p.hint {
            color: #666;
            margin: 0 0 20px 0;
            font-size: 0.9rem;
        }
        
        .modal input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background: white;
        }
        
        .modal button {
            background: #902C3E;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        
        .modal button:hover {
            background: #7a2535;
        }

        /* Message styles */
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="about.php">About Us</a></li>
            <li class="search-bar">
                <form action="search.php" method="GET">
                    <input type="text" placeholder="Search recipes..." name="q">
                    <button type="submit"></button>
                </form>
            </li>
            <?php if(isset($_SESSION['username'])): ?>
                <li><a href="account.php" class="username-link">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
            <?php else: ?>
                <li><a href="login.php">Sign Up/In</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="account-settings-container">
        <div class="settings-header">
            <h1>Account Settings</h1>
            <a href="account.php" class="back-link">Back to Account Page</a>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="message success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="message error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="setting-item">
            <div class="setting-label">Username</div>
            <div class="setting-value">@<?php echo htmlspecialchars($user['username']); ?></div>
            <a href="#" class="setting-action" onclick="openModal('usernameModal')">Change Username</a>
        </div>

        <div class="setting-item">
            <div class="setting-label">Email:</div>
            <div class="setting-value"><?php echo htmlspecialchars($user['email']); ?></div>
            <a href="#" class="setting-action" onclick="openModal('emailModal')">Change Email</a>
        </div>

        <div class="setting-item">
            <div class="setting-label">Password</div>
            <div class="setting-value">**********</div>
            <a href="#" class="setting-action" onclick="openModal('passwordModal')">Change Password</a>
        </div>

        <a href="delete.php" class="delete-account">Delete Account</a>

        <p class="footer-motto">Embrace the art of flavor</p>
    </div>

    <!-- Username Change Modal -->
    <div id="usernameModal" class="modal">
        <div class="modal-content">
            <h3>Change Your Username</h3>
            <p class="hint">Choose a unique username</p>
            <form method="POST" action="">
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                <button type="submit" name="change_username">Update Username</button>
            </form>
        </div>
    </div>

    <!-- Email Change Modal -->
    <div id="emailModal" class="modal">
        <div class="modal-content">
            <h3>Change Your Email</h3>
            <p class="hint">We'll send a verification email</p>
            <form method="POST" action="">
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <button type="submit" name="change_email">Update Email</button>
            </form>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3>Change Your Password</h3>
            <form method="POST" action="">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit" name="change_password">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }
        
        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target.className === 'modal') {
                e.target.style.display = 'none';
            }
        });
    </script>
</body>
</html>