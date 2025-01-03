<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch all posts with author information
$stmt = $pdo->prepare("
    SELECT posts.*, users.nickname 
    FROM posts 
    JOIN users ON posts.author_id = users.id 
    ORDER BY posts.created_at DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>投稿一覧</title>
</head>
<body>
    <div class="container">
        <h2>投稿一覧</h2>
        <p>ログイン中のユーザー: <?php echo htmlspecialchars($_SESSION['nickname']); ?></p>
        <div class="actions">
            <a href="create_post.php" class="button">新規投稿</a>
            <a href="logout.php" class="button logout">ログアウト</a>
        </div>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <p><strong>投稿者:</strong> 
                        <a href="posts_by_author.php?author_id=<?php echo $post['author_id']; ?>">
                            <?php echo htmlspecialchars($post['nickname']); ?>
                        </a>
                    </p>
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <p><small>投稿日時: <?php echo $post['created_at']; ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>投稿がまだありません。</p>
        <?php endif; ?>
    </div>
</body>
</html>
