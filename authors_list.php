<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// すべての登録ユーザーを取得
$stmt = $pdo->prepare("SELECT user_id, user_name FROM users ORDER BY user_name ASC");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>投稿者一覧</title>
    <style>
        @media screen and (max-width: 580px) {
            li{
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>投稿者一覧</h2>
        <a href="main.php" class="button">戻る</a>
        
        <!-- 登録ユーザーを表示 -->
        <ul>
            <?php foreach ($users as $user): ?>
                    <li>
                        <a href="posts_by_author.php?user_id=<?php echo $user['user_id']; ?>">
                            <?php echo htmlspecialchars($user['user_name']); ?>
                        </a>
                    </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>