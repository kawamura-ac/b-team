<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch all posts with author information
// 変数名以外のnicknameを全てuser_nameに変更 
// author_idを全てuser_idに変更
// titleを全てpost_titleに変更
// contentを全てpost_contentに変更
// created_atを全てpost_dateに変更
// 変数名以外のpasswordを全てuser_pawに変更
$stmt = $pdo->prepare("
    SELECT posts.*, user_name
    FROM posts 
    JOIN users ON posts.user_id = users.user_id 
    ORDER BY posts.post_date DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>投稿一覧</title>
</head>
<body>
    <div class="container">
        <h2>投稿一覧</h2>
        <p>ログイン中のユーザー: <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        <div class="actions">
            <a href="create_post.php" class="button">新規投稿</a>
            <a href="authors_list.php" class="button">投稿者一覧</a>
            <a href="logout.php" class="button logout">ログアウト</a>
        </div>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <p><strong>投稿者:</strong> 
                        <a href="posts_by_author.php?user_id=<?php echo $post['user_id']; ?>">
                            <?php echo htmlspecialchars($post['user_name']); ?>
                        </a>
                    </p>
                    <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                    <p><small>投稿日時: <?php echo $post['post_date']; ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>投稿がまだありません。</p>
        <?php endif; ?>
    </div>
</body>
</html>
