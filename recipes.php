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
    <link rel="stylesheet" href="style.css">
    <!-- Your existing font links -->
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
                <?php endwhile; ?>
            <?php else: ?>
                <div class="error">No recipes found in the database.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>