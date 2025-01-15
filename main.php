<<<<<<< HEAD
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Main</title>
    </head>
    <body>
    <?php
        // データベース
        include "db_open.php";

        // Postsと Users データの呼び込み
        $sql = "
            SELECT 
                Posts.post_id, 
                Posts.post_title, 
                Posts.post_date, 
                Posts.post_content, 
                Users.user_name 
            FROM Posts JOIN Users        
            ";

        // SQL 実行
        $sql_res = $dbh->query($sql);

        // 結果出力
        while ($rec = $sql_res->fetch(PDO::FETCH_ASSOC)) {
            echo "<div>";
            echo "<p>掲示番号: " . htmlspecialchars($rec['post_id']) . "</p>";
            echo "<p>投稿名: " . htmlspecialchars($rec['user_name']) . "</p>";
            echo "<p>タイトル: " . htmlspecialchars($rec['post_title']) . "</p>";
            echo "<p>投稿日付: " . htmlspecialchars($rec['post_date']) . "</p>";
            echo "<p>投稿内容: " . htmlspecialchars($rec['post_content']) . "</p>";
            echo "</div><hr>";
        }
    ?>
    </body>
</html>

=======
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
>>>>>>> 0fe0e86d0dd450a32e9bc4f005d34158902c7acf
