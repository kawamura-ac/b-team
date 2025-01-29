<?php
session_start();
require 'db_config.php';

// Check if the required parameters are set
if (isset($_POST['post_id']) && isset($_SESSION['user_id'])) {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];

    // Check if the user has already liked the post
    $stmt = $pdo->prepare("SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id");
    $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
    $liked = $stmt->fetch();

    if ($liked) {
        // If the user has liked the post, remove the like
        $stmt = $pdo->prepare("DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id");
        $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
        $likedStatus = false;
    } else {
        // If the user hasn't liked the post, add a like
        $stmt = $pdo->prepare("INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)");
        $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
        $likedStatus = true;
    }

    // Get the updated like count
    $stmt = $pdo->prepare("SELECT COUNT(*) AS like_count FROM likes WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $postId]);
    $likeCount = $stmt->fetchColumn();

    // Return the updated like status and like count as a JSON response
    echo json_encode([
        'liked' => $likedStatus,
        'likeCount' => $likeCount
    ]);
}
?>
