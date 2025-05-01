<?php
session_start();
include 'db.php';

// Fetch all recipes from recipes table
$sql = "SELECT * FROM recipes ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Velvet Spoon - All Recipes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Add this new style to make the entire card clickable */
        .recipe-card-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        
        .recipe-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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

    <div class="recipes-container">
        <div class="recipes-header">
            <h1>Explore All Our Special Recipes!</h1>
        </div>

        <div class="recipe-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($recipe = $result->fetch_assoc()): ?>
                <a href="recipe.php?id=<?php echo $recipe['id']; ?>" class="recipe-card-link">
                    <div class="recipe-card">
                        <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image">
                        <div class="recipe-info">
                            <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            <div class="recipe-rating">
                                <?php
                                // Display star ratings
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
                                <span class="rating-count">(<?php echo $recipe['rating_count']; ?>)</span>
                            </div>
                            <div class="recipe-meta">
                                <span>Total Time: <?php echo ($recipe['prep_time'] + $recipe['cook_time']); ?> mins</span>
                                <span>Servings: <?php echo $recipe['servings']; ?></span>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="error">No recipes found in the database.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>