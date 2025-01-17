<?php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the post data from the form
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $user_id = $_SESSION['user_id'];

    // Insert the new post with the current date and time for post_date
    $stmt = $pdo->prepare("INSERT INTO Posts (post_title, post_content, user_id, post_date) 
                           VALUES (?, ?, ?, NOW())");
    $stmt->execute([$post_title, $post_content, $user_id]);

    // Redirect to the posts list after inserting
    header('Location:main.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>新規投稿</title>
</head>
<body>
    <div class="container">
        <h2>新規投稿</h2>
        <form action="create_post.php" method="POST">
            <label for="post_title">タイトル:</label>
            <input type="text" name="post_title" required>

            <label for="post_content">内容:</label>
            <textarea name="post_content" rows="5" required></textarea>

            <button type="submit">投稿</button>
        </form>
    </div>
</body>
</html>