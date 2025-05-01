<?php
session_start();
require 'db.php';

// Get search term
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

// Simple validation
if (empty($searchTerm)) {
    header("Location: recipes.php");
    exit();
}

try {
    // First try FULLTEXT search
    $sql = "SELECT r.*, rd.description 
            FROM recipes r
            LEFT JOIN recipe_details rd ON r.id = rd.recipe_id
            WHERE MATCH(r.title) AGAINST(? IN BOOLEAN MODE)
            ORDER BY r.created_at DESC";
    
    $stmt = $conn->prepare($sql);
    $searchParam = "$searchTerm*";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    // If no results, fall back to LIKE
    if ($result->num_rows === 0) {
        $sql = "SELECT r.*, rd.description 
                FROM recipes r
                LEFT JOIN recipe_details rd ON r.id = rd.recipe_id
                WHERE r.title LIKE ?
                ORDER BY r.created_at DESC";
        
        $stmt = $conn->prepare($sql);
        $likeParam = "%$searchTerm%";
        $stmt->bind_param("s", $likeParam);
        $stmt->execute();
        $result = $stmt->get_result();
    }

} catch (Exception $e) {
    // Log error and show user-friendly message
    error_log("Search error: " . $e->getMessage());
    die("We're experiencing technical difficulties. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - The Velvet Spoon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li class="active"><a href="recipes.php">Recipes</a></li>
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
    
    <main class="container">
        <h1>Search Results for "<?= htmlspecialchars($searchTerm) ?>"</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="recipe-grid">
                <?php while($recipe = $result->fetch_assoc()): ?>
                <article class="recipe-card">
                    <img src="<?= htmlspecialchars($recipe['image_path']) ?>" 
                         alt="<?= htmlspecialchars($recipe['title']) ?>">
                    <div class="recipe-info">
                        <h2><?= htmlspecialchars($recipe['title']) ?></h2>
                        <p><?= htmlspecialchars(substr($recipe['description'] ?? '', 0, 100)) ?>...</p>
                        <div class="recipe-meta">
                            <span>⏱️ <?= $recipe['prep_time'] + $recipe['cook_time'] ?> mins</span>
                            <span>🍽️ <?= $recipe['servings'] ?> servings</span>
                        </div>
                        <a href="recipe.php?id=<?= $recipe['id'] ?>" class="btn">View Recipe</a>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>No recipes found for "<?= htmlspecialchars($searchTerm) ?>"</p>
                <a href="recipes.php" class="btn">Browse All Recipes</a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>