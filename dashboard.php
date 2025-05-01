<?php
session_start();
include 'db.php';

// Fetch all recipes from homepage_recipes table
$sql = "SELECT * FROM homepage_recipes ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Velvet Spoon - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul>
            <li class="active"><a href="dashboard.php">Home</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li><a href="about.php">About Us</a></li>
            <li class="search-bar">
                <form action="search.php" method="GET">
                    <input type="text" placeholder="Search recipes..." name="q">
                    <button type="submit"></button>
                </form>
            </li>
            <?php if(isset($_SESSION['username'])): ?>
                <li><span style="color: white; font-size: 2.0rem;">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></span></li>
            <?php else: ?>
                <li><a href="login.html">Sign Up/In</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="dashboard">
        <div class="dashboard-header">
            <h1 class="dashboard-title">The Velvet Spoon</h1>
            <p class="dashboard-subtitle">Savor the Art of Cooking - One Velvet Bite at a Time</p>
        </div>
        
        <div class="featured-section">
            <h2 class="section-title">The Latest Craze</h2>
            <p style="font-size: 1.2rem; margin-bottom: 2rem; text-align: center;">Take a look at <?php echo date('F'); ?>'s most popular recipes right now. Join the magic!</p>
            
            <div class="recipe-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($recipe = $result->fetch_assoc()): ?>
                    <div class="recipe-card">
                        <img src="<?php echo htmlspecialchars($recipe['image_path']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image">
                        <div class="recipe-info">
                            <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            <p class="recipe-category"><?php echo htmlspecialchars($recipe['category']); ?></p>
                            <p class="recipe-description"><?php echo htmlspecialchars($recipe['description']); ?></p>
                            <div class="recipe-meta">
                                <span><?php echo ($recipe['prep_time'] + $recipe['cook_time']); ?> mins</span>
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
        
        <div class="cta-section">
            <p class="cta-text">Discover more delicious recipes in our collection</p>
            <a href="recipes.php" class="btn">Browse All Recipes</a>
        </div>
    </div>
</body>
</html>
