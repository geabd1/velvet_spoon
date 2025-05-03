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

// Check if viewing a specific board
$board_id = isset($_GET['board_id']) ? intval($_GET['board_id']) : 0;
$viewing_board = null;
$board_recipes = [];

if ($board_id > 0) {
    // Verify user owns the board
    $stmt = $conn->prepare("SELECT * FROM recipe_boards WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $board_id, $_SESSION['user_id']);
    $stmt->execute();
    $viewing_board = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($viewing_board) {
        // Get recipes in this board
        $stmt = $conn->prepare("SELECT r.* FROM recipes r
                              JOIN board_recipes br ON r.id = br.recipe_id
                              WHERE br.board_id = ?");
        $stmt->bind_param("i", $board_id);
        $stmt->execute();
        $board_recipes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
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
        
        .modal button.create-btn:hover {
            background: #7a2535;
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
            color: #902C3E;
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
            color: #902C3E;
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
            background: #F8E9DB;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .account-content h2 {
            color: #902C3E;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        /* Create Board Button */
        .create-board-btn {
            background: #902C3E;
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
            background: #7a2535;
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
            cursor: pointer;
            position: relative;
        }
        
        .board-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .board-card h3 {
            color: #902C3E;
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .board-card .recipe-count {
            color: #666;
            font-size: 0.9rem;
        }
        
        .board-card .created-date {
            color: #999;
            font-size: 0.8rem;
            margin-top: 5px;
        }
        
        /* Board view styles */
        .board-view {
            margin-top: 2rem;
        }
        
        .board-view-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .back-to-boards {
            color: #902C3E;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .back-to-boards:hover {
            text-decoration: underline;
        }
        
        /* Recipe grid in board view */
        .board-recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .board-recipe-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .board-recipe-card:hover {
            transform: translateY(-5px);
        }
        
        .board-recipe-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .board-recipe-card .recipe-info {
            padding: 15px;
        }
        
        .board-recipe-card h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        
        .remove-from-board {
            color: #902C3E;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            margin-top: 10px;
            padding: 0;
        }
        
        /* Success/error messages */
        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .remove-from-board.processing {
            opacity: 0.7;
            pointer-events: none;
        }
        .remove-from-board {
            color: #902C3E;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            margin-top: 10px;
            padding: 0;
            transition: color 0.2s;
        }
        
        .remove-from-board:hover {
            color: #6a1e2b;
            text-decoration: underline;
        }
        
        .remove-from-board.processing {
            color: #999;
            pointer-events: none;
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
                <li class='active'><a href="login.php">Sign Up/In</a></li>
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
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Board created successfully!
                </div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    Error creating board. Please try again.
                </div>
            <?php endif; ?>

            <?php if ($viewing_board): ?>
                <!-- Board View -->
                <div class="board-view">
                    <div class="board-view-header">
                        <a href="account.php" class="back-to-boards">
                            ← Back to all boards
                        </a>
                        <h2><?php echo htmlspecialchars($viewing_board['name']); ?></h2>
                        <div></div> <!-- Spacer for flex layout -->
                    </div>
                    
                    <?php if (!empty($board_recipes)): ?>
                        <div class="board-recipes-grid">
                            <?php foreach ($board_recipes as $recipe): ?>
                            <div class="board-recipe-card">
                                <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                                <div class="recipe-info">
                                    <h4><?php echo htmlspecialchars($recipe['title']); ?></h4>
                                    <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="view-recipe">View Recipe</a>
                                    <button class="remove-from-board" 
                                            data-recipe-id="<?php echo $recipe['id']; ?>" 
                                            data-board-id="<?php echo $board_id; ?>">
                                        Remove 
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>This board is empty. Save recipes to add them here!</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- All Boards View -->
                <h2>Your Recipe Boards</h2>
                
                <button id="createBoardBtn" class="create-board-btn">Create New Board</button>
                
                <div class="boards-grid">
                    <?php if ($boards_result->num_rows > 0): ?>
                        <?php while($board = $boards_result->fetch_assoc()): 
                            // Get recipe count for this board
                            $count_stmt = $conn->prepare("SELECT COUNT(*) as count FROM board_recipes WHERE board_id = ?");
                            $count_stmt->bind_param("i", $board['id']);
                            $count_stmt->execute();
                            $count_result = $count_stmt->get_result()->fetch_assoc();
                            $count_stmt->close();
                        ?>
                        <div class="board-card" onclick="window.location.href='account.php?board_id=<?php echo $board['id']; ?>'">
                            <h3><?php echo htmlspecialchars($board['name']); ?></h3>
                            <p class="recipe-count"><?php echo $count_result['count']; ?> recipes</p>
                            <p class="created-date">Created: <?php echo date('M j, Y', strtotime($board['created_at'])); ?></p>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>You haven't created any boards yet.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
    
    if (btn && modal) {
        btn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // Enhanced Remove from Board functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Event delegation for remove buttons
        document.addEventListener('click', async function(e) {
            if (e.target.classList.contains('remove-from-board')) {
                e.preventDefault();
                e.stopPropagation();
                
                const button = e.target;
                if (button.classList.contains('processing')) return;
                
                const recipeId = button.dataset.recipeId;
                const boardId = button.dataset.boardId;
                const card = button.closest('.board-recipe-card');
                
                if (!confirm('Are you sure you want to remove this recipe from your board?')) {
                    return;
                }
                
                // Set processing state
                button.classList.add('processing');
                button.textContent = 'Removing...';
                button.style.pointerEvents = 'none';
                
                try {
                    const response = await fetch('remove_from_board.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `recipe_id=${recipeId}&board_id=${boardId}`
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Animate removal
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '0';
                        card.style.height = `${card.offsetHeight}px`;
                        
                        // Trigger reflow
                        void card.offsetHeight;
                        
                        card.style.height = '0';
                        card.style.margin = '0';
                        card.style.padding = '0';
                        card.style.border = 'none';
                        
                        // Wait for animation to complete
                        await new Promise(resolve => setTimeout(resolve, 300));
                        
                        // Remove card from DOM
                        card.remove();
                        
                        // Check if board is now empty
                        const recipeGrid = document.querySelector('.board-recipes-grid');
                        if (recipeGrid && recipeGrid.children.length === 0) {
                            // Create and show empty state message
                            const emptyMessage = document.createElement('p');
                            emptyMessage.textContent = 'This board is empty. Save recipes to add them here!';
                            emptyMessage.style.textAlign = 'center';
                            emptyMessage.style.margin = '2rem 0';
                            emptyMessage.style.color = '#666';
                            recipeGrid.parentNode.insertBefore(emptyMessage, recipeGrid.nextSibling);
                        }
                    } else {
                        throw new Error(data.error || 'Failed to remove recipe');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                } finally {
                    // Reset button state
                    if (button) {
                        button.classList.remove('processing');
                        button.textContent = 'Remove from board';
                        button.style.pointerEvents = 'auto';
                    }
                }
            }
        });
    });
</script>
</body>
</html>