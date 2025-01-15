<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Fetch all registered users
$stmt = $pdo->prepare("SELECT id, nickname FROM users ORDER BY nickname ASC");
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
</head>
<body>
    <div class="container">
        <h2>投稿者一覧</h2>
        <a href="index.php" class="button">戻る</a>
        
        <!-- Display Registered Users -->
        <ul>
            <?php foreach ($users as $user): ?>
                <li>
                    <a href="posts_by_author.php?author_id=<?php echo $user['id']; ?>">
                        <?php echo htmlspecialchars($user['nickname']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
