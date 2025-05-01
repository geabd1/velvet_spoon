<?php
session_start();
include 'db.php';

// Get recipe ID from URL
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch recipe details
$recipe = [];
$ingredients = [];
$instructions = [];

if ($recipe_id > 0) {
    // Get basic recipe info
    $stmt = $conn->prepare("SELECT r.*, rd.description FROM recipes r 
                          LEFT JOIN recipe_details rd ON r.id = rd.recipe_id 
                          WHERE r.id = ?");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $recipe = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Get ingredients
    $stmt = $conn->prepare("SELECT * FROM recipe_ingredients WHERE recipe_id = ? ORDER BY `order`");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $ingredients = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get instructions
    $stmt = $conn->prepare("SELECT * FROM recipe_instructions WHERE recipe_id = ? ORDER BY step_number");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $instructions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get comments
    $stmt = $conn->prepare("SELECT c.*, u.username FROM comments c 
                          JOIN users u ON c.user_id = u.id 
                          WHERE c.recipe_id = ? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $comments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_comment']) && isset($_SESSION['user_id'])) {
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    
    if (!empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO comments (recipe_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $recipe_id, $user_id, $comment);
        $stmt->execute();
        $stmt->close();
        
        // Refresh to show new comment
        header("Location: recipe.php?id=$recipe_id");
        exit();
    }
}

// Handle save to board
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_to_board']) && isset($_SESSION['user_id'])) {
    $board_id = intval($_POST['board_id']);
    $user_id = $_SESSION['user_id'];
    
    // Verify user owns the board
    $stmt = $conn->prepare("SELECT id FROM recipe_boards WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $board_id, $user_id);
    $stmt->execute();
    $valid_board = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    
    if ($valid_board) {
        // Check if recipe already exists in board
        $stmt = $conn->prepare("SELECT id FROM board_recipes WHERE board_id = ? AND recipe_id = ?");
        $stmt->bind_param("ii", $board_id, $recipe_id);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        
        if (!$exists) {
            $stmt = $conn->prepare("INSERT INTO board_recipes (board_id, recipe_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $board_id, $recipe_id);
            $stmt->execute();
            $stmt->close();
            
            $_SESSION['success'] = "Recipe saved to your board!";
        } else {
            $_SESSION['error'] = "Recipe already exists in this board";
        }
    }
    
    header("Location: recipe.php?id=$recipe_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title'] ?? 'Recipe'); ?> | The Velvet Spoon</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .recipe-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .recipe-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .recipe-title {
            font-family: 'Jacques Francois Shadow', serif;
            color: #902C3E;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .recipe-meta {
            display: flex;
            justify-content: center;
            gap: 1rem;
            color: #666;
            margin-bottom: 1rem;
        }
        
        .recipe-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .recipe-description {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        
        .recipe-section {
            margin-bottom: 3rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            color: #902C3E;
            border-bottom: 2px solid #902C3E;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .ingredient-list, .instruction-list {
            list-style-type: none;
            padding: 0;
        }
        
        .ingredient-item, .instruction-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        
        .instruction-number {
            font-weight: bold;
            color: #902C3E;
            margin-right: 0.5rem;
        }
        
        /* Comments Section */
        .comments-section {
            margin-top: 3rem;
        }
        
        .comment {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            margin-bottom: 1rem;
        }
        
        .comment-user {
            font-weight: bold;
            color: #902C3E;
        }
        
        .comment-content {
            margin: 0.5rem 0;
        }
        
        .comment-form {
            margin-top: 2rem;
        }
        
        .comment-form textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 1rem;
            min-height: 100px;
        }
        
        /* Save Recipe Button */
        .save-recipe {
            background: #902C3E;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1rem;
            margin-bottom: 2rem;
            transition: background 0.3s;
        }
        
        .save-recipe:hover {
            background: #7a2535;
        }
        
        /* Save to Board Modal */
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
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
        }
        
        .modal-title {
            margin-top: 0;
            color: #902C3E;
        }
        
        .board-list {
            list-style: none;
            padding: 0;
        }
        
        .board-item {
            padding: 0.75rem;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .board-item:hover {
            background: #eee;
        }
        
        .close-modal {
            float: right;
            cursor: pointer;
            font-size: 1.5rem;
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
                <li><a href="login.html">Sign Up/In</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="recipe-container">
        <?php if (!empty($recipe)): ?>
            <div class="recipe-header">
                <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
                <div class="recipe-meta">
                    <span>Prep: <?php echo $recipe['prep_time']; ?> mins</span>
                    <span>Cook: <?php echo $recipe['cook_time']; ?> mins</span>
                    <span>Servings: <?php echo $recipe['servings']; ?></span>
                    <span>Rating: <?php echo $recipe['rating']; ?>/5 (<?php echo $recipe['rating_count']; ?>)</span>
                </div>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <button class="save-recipe" onclick="openSaveModal()">Save Recipe</button>
                <?php endif; ?>
                <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image">
            </div>
            
            <div class="recipe-description">
                <?php echo nl2br(htmlspecialchars($recipe['description'])); ?>
            </div>
            
            <div class="recipe-section">
                <h2 class="section-title">Ingredients</h2>
                <ul class="ingredient-list">
                    <?php foreach ($ingredients as $ingredient): ?>
                        <li class="ingredient-item">
                            <?php if (!empty($ingredient['amount'])): ?>
                                <span><?php echo htmlspecialchars($ingredient['amount']); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($ingredient['unit'])): ?>
                                <span><?php echo htmlspecialchars($ingredient['unit']); ?></span>
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($ingredient['ingredient']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="recipe-section">
                <h2 class="section-title">Instructions</h2>
                <ol class="instruction-list">
                    <?php foreach ($instructions as $instruction): ?>
                        <li class="instruction-item">
                            <span class="instruction-number"><?php echo $instruction['step_number']; ?>.</span>
                            <?php echo nl2br(htmlspecialchars($instruction['instruction'])); ?>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
            
            <div class="comments-section">
                <h2 class="section-title">Comments</h2>
                
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-user">@<?php echo htmlspecialchars($comment['username']); ?></div>
                            <div class="comment-content"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></div>
                            <div class="comment-date"><?php echo date('M j, Y', strtotime($comment['created_at'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet. Be the first to comment!</p>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <form class="comment-form" method="POST">
                        <textarea name="comment" placeholder="Add your comment..." required></textarea>
                        <button type="submit" name="post_comment" class="save-recipe">Post Comment</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.html">Log in</a> to leave a comment</p>
                <?php endif; ?>
            </div>
            
            <!-- Save to Board Modal -->
            <div id="saveModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closeSaveModal()">&times;</span>
                    <h3 class="modal-title">Save to Board</h3>
                    <p>Choose a board to save this recipe:</p>
                    
                    <?php
                    if(isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $stmt = $conn->prepare("SELECT * FROM recipe_boards WHERE user_id = ?");
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $boards = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                        $stmt->close();
                        
                        if (!empty($boards)): ?>
                            <form method="POST">
                                <ul class="board-list">
                                    <?php foreach ($boards as $board): ?>
                                        <li class="board-item" onclick="selectBoard(<?php echo $board['id']; ?>)">
                                            <?php echo htmlspecialchars($board['name']); ?>
                                            <input type="radio" name="board_id" value="<?php echo $board['id']; ?>" style="display: none;">
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="submit" name="save_to_board" class="save-recipe">Save</button>
                            </form>
                        <?php else: ?>
                            <p>You don't have any boards yet. <a href="account.php">Create one</a> first.</p>
                        <?php endif;
                    }
                    ?>
                </div>
            </div>
            
            <script>
                function openSaveModal() {
                    document.getElementById('saveModal').style.display = 'flex';
                }
                
                function closeSaveModal() {
                    document.getElementById('saveModal').style.display = 'none';
                }
                
                function selectBoard(boardId) {
                    document.querySelector(`input[value="${boardId}"]`).checked = true;
                }
                
                // Close modal when clicking outside
                window.onclick = function(event) {
                    if (event.target.className === 'modal') {
                        closeSaveModal();
                    }
                }
            </script>
            
        <?php else: ?>
            <h1>Recipe Not Found</h1>
            <p>The requested recipe could not be found.</p>
        <?php endif; ?>
    </div>
</body>
</html>