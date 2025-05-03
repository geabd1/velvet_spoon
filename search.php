<?php
session_start();
require 'db.php';

// Debug mode - set to false for production
define('DEBUG', false);

// Get search term
$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($searchTerm)) {
    header("Location: recipes.php");
    exit();
}

try {
    // Verify database connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Search only the recipes table now
    $sql = "SELECT r.id, r.title, r.image_path, r.prep_time, r.cook_time, r.servings, 
                   r.rating, r.rating_count, rd.description, r.created_at
            FROM recipes r
            LEFT JOIN recipe_details rd ON r.id = rd.recipe_id
            WHERE r.title LIKE ?
            ORDER BY created_at DESC";

    $stmt = $conn->prepare($sql);
    $likeParam = "%$searchTerm%";
    $stmt->bind_param("s", $likeParam);
    
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();

} catch (Exception $e) {
    $errorMessage = "We're experiencing technical difficulties. Please try again later.";
    error_log("Search error: " . $e->getMessage());
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
    <style>
        /* Match the styling from recipes.php */
        .search-header {
            text-align: center;
            font-size: 1.7rem;
            margin-bottom: 2rem;
        }
        
        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 0 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .recipe-card-link {
            display: block;
            text-decoration: none;
            color: inherit;
            height: 100%;
        }
        
        .recipe-card {
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .recipe-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .recipe-info {
            padding: 1rem;
        }
        
        .recipe-title {
            font-size: 1.2rem;
            margin: 0 0 0.5rem 0;
            color: #333;
        }
        
        .recipe-rating {
            color: #ffc107;
            margin-bottom: 0.5rem;
        }
        
        .rating-count {
            color: #666;
            font-size: 0.8rem;
        }
        
        .recipe-meta {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 0.9rem;
        }
        
        .no-results {
            text-align: center;
            padding: 2rem;
            font-size: 1.2rem;
        }
        
        .no-results a {
            color: #902C3E;
            text-decoration: none;
        }
        
        .no-results a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li class="active"><a href="recipes.php">Recipes</a></li>
            <li><a href="about.php">About Us</a></li>
            <li class="search-bar">
                <form action="search.php" method="GET">
                    <input type="text" placeholder="Search recipes..." name="q" value="<?= htmlspecialchars($searchTerm) ?>">
                    <button type="submit"></button>
                </form>
            </li>
            <?php if(isset($_SESSION['username'])): ?>
                <li><a href="account.php" class="username-link">Hello, <?= htmlspecialchars($_SESSION['username']) ?></a></li>
            <?php else: ?>
                <li><a href="login.php">Sign Up/In</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <main class="container">
        <div class="search-header">
            <h1>Search Results for "<?= htmlspecialchars($searchTerm) ?>"</h1>
        </div>
        
        <?php if (isset($errorMessage)): ?>
            <div class="error-message">
                <p><?= $errorMessage ?></p>
            </div>
        <?php elseif ($result->num_rows > 0): ?>
            <div class="recipe-grid">
                <?php while($recipe = $result->fetch_assoc()): ?>
                <a href="recipe.php?id=<?= $recipe['id'] ?>" class="recipe-card-link">
                    <div class="recipe-card">
                        <img src="<?= htmlspecialchars($recipe['image_path']) ?>" 
                             alt="<?= htmlspecialchars($recipe['title']) ?>" class="recipe-image">
                        <div class="recipe-info">
                            <h3 class="recipe-title"><?= htmlspecialchars($recipe['title']) ?></h3>
                            <div class="recipe-rating">
                                <?php
                                // Display star ratings (matches recipes.php)
                                $fullStars = floor($recipe['rating']);
                                $hasHalfStar = ($recipe['rating'] - $fullStars) >= 0.5;
                                
                                for ($i = 0; $i < 5; $i++) {
                                    if ($i < $fullStars) {
                                        echo '★';
                                    } elseif ($i == $fullStars && $hasHalfStar) {
                                        echo '½';
                                    } else {
                                        echo '☆';
                                    }
                                }
                                ?>
                                <span class="rating-count">(<?= $recipe['rating_count'] ?>)</span>
                            </div>
                            <div class="recipe-meta">
                                <span>⏱️ <?= ($recipe['prep_time'] + $recipe['cook_time']) ?> mins</span>
                                <span>🍽️ <?= $recipe['servings'] ?> servings</span>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>No recipes found for "<?= htmlspecialchars($searchTerm) ?>"</p>
                <p>Try different keywords or <a href="recipes.php">browse all recipes</a>.</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>