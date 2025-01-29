<?php
session_start();
require 'db_config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Check if the post exists
if (!isset($_GET['post_id'])) {
    die('Post ID is missing.');
}

$post_id = $_GET['post_id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id AND user_id = :user_id");
$stmt->execute(['post_id' => $post_id, 'user_id' => $_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    die('Post not found or you are not authorized to delete this post.');
}

// Delete the post
$stmt = $pdo->prepare("DELETE FROM posts WHERE post_id = :post_id");
$stmt->execute(['post_id' => $post_id]);

header('Location: main.php');
exit();
?>
