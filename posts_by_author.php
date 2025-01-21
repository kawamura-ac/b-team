<?php
session_start();
require 'db_config.php'; //データベース名を入れる

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$author_id = $_GET['user_id'];    // 変数名以外のauthor_idを全てuser_idに変更
// titleを全てpost_titleに変更
// contentを全てpost_contentに変更
// created_atを全てpost_dateに変更
$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = :user_id ORDER BY post_date DESC");
$stmt->execute(['user_id' => $author_id]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>投稿者ごとの記事</title>
</head>
<body>
    <div class="container">
        <h2>投稿者ごとの記事一覧</h2>
        <a href="authors_list.php" class="button">投稿者一覧画面に戻る</a>
        <a href="main.php" class="button">メイン画面に戻る</a>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                <p>投稿日: <?php echo $post['post_date']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
