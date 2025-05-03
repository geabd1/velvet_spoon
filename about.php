<?php
session_start();
include 'db.php';

$sql = "SELECT * FROM recipes ORDER BY created_at DESC LIMIT 3";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | The Velvet Spoon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="recipes.php">Recipes</a></li>
            <li class="active"><a href="about.html">About Us</a></li>
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

    <div class="about-container">
        <h1>About Us</h1>
        
        <div class="about-content">
            <p>At The Velvet Spoon, we believe that cooking is more than just nourishment; 
            it's a journey of creativity, connection, and joy. We're a passionate group of
            food enthusiasts who love to explore flavors, experiment in the kitchen, and
            share our culinary adventures with the world.</p>

            <p>Our website is a reflection of this passion, filled with a diverse collection of
            recipes - from simple weeknight meals to elaborate feasts. We strive to make
            cooking accessible and enjoyable for everyone, regardless of skill level. Whether
            you're a seasoned chef or just starting your culinary journey, you'll find
            inspiration, guidance, and deliciousness here.</p>

            <p>Join our community of food lovers as we explore the wonders of the culinary
            world together. Let's cook, let's share, and let's savor every bite!</p>

            <p class="signature">— The Velvet Spoon Team</p>
        </div>
    </div>
</body>
</html>