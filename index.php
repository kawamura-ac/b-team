<?php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = $_POST['user_name'];
    $password = $_POST['user_paw'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :user_name");
    $stmt->execute(['user_name' => $nickname]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['user_paw'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['user_name'];
        header('Location: main.php');
        exit();
    } else {
        $error = "Invalid nickname or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>ログイン</title>
</head>
<body>
    <div class="container">
        <h2>ログイン</h2>
        <form method="POST">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <label for="user_name">ニックネーム</label>
            <input type="text" name="user_name" id="user_name" required>
            
            <label for="user_paw">パスワード</label>
            <input type="password" name="user_paw" id="user_paw" required>
            
            <button type="submit">ログイン</button>
        </form>
        <p>アカウントがありませんか？ <a href="register.php">登録する</a></p>
    </div>
</body>
</html>
