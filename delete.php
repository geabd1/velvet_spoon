<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE id = ? AND email = ?");
        $stmt->bind_param("is", $user_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password'])) {
            $conn->begin_transaction();
            try {
                $conn->query("DELETE FROM comments WHERE user_id = $user_id");
                $conn->query("DELETE br FROM board_recipes br JOIN recipe_boards rb ON br.board_id = rb.id WHERE rb.user_id = $user_id");
                $conn->query("DELETE FROM recipe_boards WHERE user_id = $user_id");
                $conn->query("DELETE FROM users WHERE id = $user_id");
                $conn->commit();
                session_destroy();
                header("Location: goodbye.php");
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error = "Account deletion failed: " . $e->getMessage();
            }
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account - The Velvet Spoon</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .delete-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 2.5rem;
            background-color: #F8E9DB;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .delete-container h1 {
            font-family: 'Jacques Francois Shadow', serif;
            color: #902C3E;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .warning-message {
            color: #cc0000;
            font-weight: bold;
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: rgba(204,0,0,0.1);
            border-radius: 8px;
        }
        
        .delete-form .form-group {
            margin-bottom: 1.75rem;
        }
        
        .delete-form label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 500;
            font-size: 1.2rem;
            color: #333;
        }
        
        .delete-form input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 24px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .delete-form input:focus {
            border-color: #902C3E;
            outline: none;
        }
        
        .delete-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2.5rem;
            gap: 1rem;
        }
        
        .btn-delete {
            background-color: #cc0000;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 24px;
            font-size: 1.1rem;
            cursor: pointer;
            flex: 1;
            transition: all 0.3s;
        }
        
        .btn-delete:hover {
            background-color: #a30000;
        }
        
        .btn-cancel {
            background-color: transparent;
            color: #902C3E;
            border: 2px solid #902C3E;
            padding: 1rem 2rem;
            border-radius: 24px;
            font-size: 1.1rem;
            text-decoration: none;
            text-align: center;
            flex: 1;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background-color: #902C3E;
            color: white;
        }
        
        .error-message {
            color: #cc0000;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 500;
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

    <div class="delete-container">
        <h1>Delete Your Account</h1>
        <p class="warning-message">Warning: This will permanently delete all your data including saved recipes and boards.</p>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form class="delete-form" method="POST" action="delete.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <div class="delete-actions">
                <button type="submit" class="btn-delete" onclick="return confirm('Are you absolutely sure? This cannot be undone.')">
                    Permanently Delete
                </button>
                <a href="account.php" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>