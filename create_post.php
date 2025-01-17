<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['post_title'];               // 変数名以外のtitleを全てpost_titleに変更
    $content = $_POST['post_content'];           // 変数名以外のcontentを全てpost_contentに変更
    $author_id = $_SESSION['user_id'];           // 変数名以外のauthor_idを全てuser_idに変更

    $stmt = $pdo->prepare("INSERT INTO posts (post_title, post_content, user_id) VALUES (:post_title, :post_content, :user_id)");
    $stmt->execute(['post_title' => $title, 'post_content' => $content, 'user_id' => $author_id]);

    header('Location: main.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>投稿を作成</title>
</head>
<body>
    <div class="container">
        <h2>投稿を作成</h2>
        <form action="create_post.php" method="POST">
            <label for="post_title">タイトル</label>
            <input type="text" name="post_title" id="post_title" required>
            <label for="post_content">投稿内容</label>
            <textarea name="post_content" id="post_content" required></textarea>
            <button type="submit">投稿</button>
        </form>
    </div>
</body>
</html>
