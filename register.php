<?php
session_start();
require 'db_config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 入力をサニタイズ（内容をチェックし有害な文字や文字列を検知し無害化）する
    $nickname = trim($_POST['user_name']);   // 変数名以外のnicknameを全てuser_nameに変更
    $email = trim($_POST['user_email']);     // 変数名以外のemailを全てuser_emailに変更
    $password = $_POST['user_paw'];          // 変数名以外のpasswordを全てuser_pawに変更　
    // Check if fields are empty
    if (empty($nickname) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {       // filter_var関数でメールアドレスをバリデーション
        $error = "Invalid email address.";
    } else {
        try {
            // Check if the user_name or user_email already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :user_name OR user_email = :user_email");
            $stmt->execute(['user_name' => $nickname, 'user_email' => $email]);
            $existingUser = $stmt->fetch();
            if ($existingUser) {
                $error = "nickname or email is already taken.";
            } else {
                // password_hash — パスワードハッシュを作る
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // Insert user into the database
                $stmt = $pdo->prepare("INSERT INTO users (user_name, user_email, user_paw) VALUES (:user_name, :user_email, :user_paw)");
                $result = $stmt->execute([
                    'user_name' => $nickname,
                    'user_email' => $email,
                    'user_paw' => $hashedPassword
                ]);
                if ($result) {
                    // Redirect to the login page after successful registration
                    header('Location: index.php');
                    exit();
                } else {
                    $error = "Failed to register. Please try again.";
                }
            }
        } catch (Exception $e) {
            // Log the error (optional for debugging in development)
            error_log("Error during registration: " . $e->getMessage());
            $error = "An error occurred. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>新規登録</title>
</head>
<body>
    <div class="container">
        <h2>新規登録</h2>
        <form action="register.php" method="POST">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="user_name">ニックネーム</label>
            <input type="text" name="user_name" id="user_name" required>
            <label for="user_email">メールアドレス</label>
            <input type="email" name="user_email" id="user_email" required>
            <label for="user_paw">パスワード</label>
            <input type="password" name="user_paw" id="user_paw" required>
            <button type="submit">登録</button>
        </form>
        <p>すでにアカウントをお持ちですか？ <a href="index.php">ログイン</a></p>
    </div>
</body>
</html>











