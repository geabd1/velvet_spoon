<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'error' => 'Authentication required',
        'message' => 'Please log in to modify boards'
    ]);
    exit();
}

// Validate request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request method',
        'message' => 'Only POST requests are allowed'
    ]);
    exit();
}

// Validate required parameters
if (!isset($_POST['recipe_id']) || !isset($_POST['board_id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Missing parameters',
        'message' => 'Both recipe_id and board_id are required'
    ]);
    exit();
}

$recipe_id = (int)$_POST['recipe_id'];
$board_id = (int)$_POST['board_id'];

// Validate IDs
if ($recipe_id <= 0 || $board_id <= 0) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Invalid IDs',
        'message' => 'IDs must be positive integers'
    ]);
    exit();
}

// Verify user owns the board
$stmt = $conn->prepare("SELECT user_id FROM recipe_boards WHERE id = ?");
$stmt->bind_param("i", $board_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Board not found',
        'message' => 'The specified board does not exist'
    ]);
    exit();
}

$board = $result->fetch_assoc();
if ($board['user_id'] != $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'error' => 'Permission denied',
        'message' => 'You do not own this board'
    ]);
    exit();
}

// Verify recipe exists in board
$stmt = $conn->prepare("SELECT 1 FROM board_recipes WHERE recipe_id = ? AND board_id = ?");
$stmt->bind_param("ii", $recipe_id, $board_id);
$stmt->execute();
if ($stmt->get_result()->num_rows === 0) {
    http_response_code(404);
    echo json_encode([
        'success' => false,
        'error' => 'Recipe not in board',
        'message' => 'This recipe is not in the specified board'
    ]);
    exit();
}

// Remove the recipe from the board
$stmt = $conn->prepare("DELETE FROM board_recipes WHERE recipe_id = ? AND board_id = ?");
$stmt->bind_param("ii", $recipe_id, $board_id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Recipe successfully removed from board'
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error',
        'message' => 'Failed to remove recipe',
        'db_error' => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>