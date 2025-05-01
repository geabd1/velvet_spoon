<?php
session_start();
include 'db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Handle board creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['board_name'])) {
    $board_name = trim($_POST['board_name']);
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO recipe_boards (user_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $board_name);
    
    if ($stmt->execute()) {
        header("Location: account.php?success=1");
    } else {
        header("Location: account.php?error=1");
    }
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT first_name, last_name, username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch user's boards
$boards_sql = "SELECT * FROM recipe_boards WHERE user_id = ? ORDER BY created_at DESC";
$boards_stmt = $conn->prepare($boards_sql);
$boards_stmt->bind_param("i", $user_id);
$boards_stmt->execute();
$boards_result = $boards_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Velvet Spoon - My Account</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Modal Styles */
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
            background: #F8E9DB; /* Khaki background */
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .modal h3 {
            margin: 0 0 10px 0;
            font-size: 1.5rem;
            color: #902C3E; /* Red text */
        }
        
        .modal p.hint {
            color: #666;
            margin: 0 0 20px 0;
            font-size: 0.9rem;
        }
        
        .modal input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            background: white;
        }
        
        .modal button.create-btn {
            background: #902C3E; /* Red button */
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }
        
        .modal button.create-btn:hover {
            background: #7a2535; /* Darker red on hover */
        }
        
        /* Account specific styles */
        .account-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .account-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .account-header h1 {
            color: #902C3E; /* Red */
            font-size: 2.5rem;
        }
        
        .username {
            color: #666;
            font-size: 1.2rem;
        }
        
        .account-nav {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .account-nav a {
            color: #902C3E; /* Red */
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border: 2px solid #902C3E;
            border-radius: 20px;
            transition: all 0.3s;
        }
        
        .account-nav a:hover {
            background: #902C3E;
            color: white;
        }
        
        .account-content {
            background: #F8E9DB; /* Khaki */
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .account-content h2 {
            color: #902C3E; /* Red */
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        /* Create Board Button */
        .create-board-btn {
            background: #902C3E; /* Red */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 1rem;
            cursor: pointer;
            margin: 20px auto;
            display: block;
            transition: background 0.3s;
        }
        
        .create-board-btn:hover {
            background: #7a2535; /* Darker red */
        }
        
        /* Boards Grid */
        .boards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .board-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .board-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .board-card h3 {
            color: #902C3E; /* Red */
            margin-top: 0;
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
                <li class='active'><a href="login.html">Sign Up/In</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="account-container">
        <div class="account-header">
            <h1><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <p class="username">@<?php echo htmlspecialchars($user['username']); ?></p>
        </div>

        <div class="account-nav">
            <a href="accSetting.php">Account Settings</a>
            <a href="logout.php">Log Out</a>
        </div>

        <div class="account-content">
            <h2>Your Saved Recipes</h2>
            
            <button id="createBoardBtn" class="create-board-btn">Create New Board</button>
            
            <div class="boards-grid">
                <?php if ($boards_result->num_rows > 0): ?>
                    <?php while($board = $boards_result->fetch_assoc()): ?>
                    <div class="board-card">
                        <h3><?php echo htmlspecialchars($board['name']); ?></h3>
                        <p>Created: <?php echo date('M j, Y', strtotime($board['created_at'])); ?></p>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>You haven't created any boards yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Create Board Modal -->
    <div id="boardModal" class="modal">
        <div class="modal-content">
            <h3>Give your board a name</h3>
            <p class="hint">(ie: "Brunch Ideas", "Summer Recipes")</p>
            <form method="POST" action="account.php">
                <input type="text" name="board_name" placeholder="Enter board name" required>
                <button type="submit" class="create-btn">Create</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById("boardModal");
        const btn = document.getElementById("createBoardBtn");
        
        btn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
</body>
</html>