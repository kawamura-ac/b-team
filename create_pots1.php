<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO posts (title, content, author_id) VALUES (:title, :content, :author_id)");
    $stmt->execute(['title' => $title, 'content' => $content, 'author_id' => $author_id]);

    header('Location: main.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>投稿を作成</title>
</head>
<body>
    <div class="container">
        <h2>投稿を作成</h2>
        <form action="create_post.php" method="POST">
            <label for="title">タイトル</label>
            <input type="text" name="title" id="title" required>
            <label for="content">投稿内容</label>
            <textarea name="content" id="content" required></textarea>
            <button type="submit">投稿</button>
        </form>
    </div>
</body>
</html>