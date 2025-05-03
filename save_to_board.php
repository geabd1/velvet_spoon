<?php
session_start();
include 'db.php'; // adjust if needed

$board_id = $_POST['board_id'];
$recipe_id = $_POST['recipe_id'];

// Prevent duplicate saves
$stmt = $conn->prepare("INSERT IGNORE INTO board_recipes (board_id, recipe_id) VALUES (?, ?)");
$stmt->bind_param("ii", $board_id, $recipe_id);
$stmt->execute();

header("Location: recipe.php?id=$recipe_id");
exit;
