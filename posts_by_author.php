<?php
session_start();
require 'db_open.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index1.php');
    exit();
}

$author_id = $_GET['author_id'];

$stmt = $pdo->prepare("SELECT * FROM posts WHERE author_id = :author_id ORDER BY created_at DESC");
$stmt->execute(['author_id' => $author_id]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style1.css">
    <title>投稿者の記事</title>
</head>
<body>
    <div class="container">
        <h2>投稿者の記事一覧</h2>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <p>投稿日: <?php echo $post['created_at']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
