<?php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nickname = $_POST['user_name'];    // 変数名以外のnicknameを全てuser_nameに変更
    $password = $_POST['user_paw'];    // 変数名以外のpasswordを全てuser_pawに変更

    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :user_name");
    $stmt->execute(['user_name' => $nickname]);
    $user = $stmt->fetch();

    // password_verify — パスワードがハッシュにマッチするかどうかを調べる
    if ($user && password_verify($password, $user['user_paw'])) {  // データベースの user_pawをvarchar(255)にしないとハッシュ化されたパスワードが入らない
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['user_name'];
        header('Location: main.php');
        exit();
    } else {
        $error = "ニックネームまたはパスワードが違います。";
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
        <form method="POST" onsubmit="return validateForm()">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <!-- div追加 -->
            <div id="nickname-error" style="color: red; display: none;">ニックネームは20文字以内で入力してください。</div>
            <div id="password-error" style="color: red; display: none;">パスワードは20文字以内で入力してください。</div>

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