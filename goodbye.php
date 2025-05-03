<?php
// No session needed as account is already deleted
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodbye - The Velvet Spoon</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jacques+Francois+Shadow&family=Josefin+Slab:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .goodbye-container {
            max-width: 600px;
            margin: 4rem auto;
            padding: 3rem;
            background-color: #F8E9DB;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .goodbye-container h1 {
            font-family: 'Jacques Francois Shadow', serif;
            color: #902C3E;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        
        .goodbye-container p {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-home {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: #902C3E;
            color: white;
            text-decoration: none;
            border-radius: 24px;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .btn-home:hover {
            background-color: #7a2535;
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
            <li><a href="login.php">Sign Up/In</a></li>
        </ul>
    </nav>

    <div class="goodbye-container">
        <h1>We're Sorry to See You Go</h1>
        <p>Your account and all associated data have been permanently deleted.</p>
        <p>Thank you for being part of The Velvet Spoon community. You're always welcome back!</p>
        <a href="dashboard.php" class="btn-home">Return to Homepage</a>
    </div>
</body>
</html>