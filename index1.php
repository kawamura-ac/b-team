<?php
session_start();
require 'db_config.php';
 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = $_POST['nickname'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE nickname = :nickname");
    $stmt->execute(['nickname' => $nickname]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nickname'] = $user['nickname'];
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
    <link rel="stylesheet" href="css/styles.css">
    <title>ログイン</title>
</head>
<body>
    <div class="container">
        <h2>ログイン</h2>
        <form method="POST">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <label for="nickname">ニックネーム</label>
            <input type="text" name="nickname" id="nickname" required>
            
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">ログイン</button>
        </form>
        <p>アカウントがありませんか？ <a href="register.php">登録する</a></p>
    </div>
</body>
</html>