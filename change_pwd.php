<?php
session_start();
require 'db_config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['user_paw'];
    $newpassword = $_POST['new_paw'];
    $confirmpassword = $_POST['new_paw2'];

    // 現在のパスワード確認
    $stmt = $pdo->prepare("SELECT user_paw FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['user_paw'])) {
        $error = "現在のパスワードが正しくありません。";
    } elseif ($newpassword !== $confirmpassword) {
        $error = "新しいパスワードは2回同じものを入力してください。";
    } else {
        // パスワード変更
        $hashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET user_paw = :user_paw WHERE user_id = :user_id");
        $result = $stmt->execute([
            'user_paw' => $hashedPassword,
            'user_id' => $user_id
        ]);

        if ($result) {
            // パスワード変更を完了するとユーザー情報ページにリダイレクトされる
            header('Location: my_page.php');
            exit();
        } else {
            $error = "パスワードの変更中にエラーが発生しました。";
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
    <title>パスワード変更</title>
       <style>
            input[type="password"] {
            padding: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3.5px;
            box-sizing: border-box;
    	}
</style>
</head>
<body>
<div class="container">
        <h2>パスワード変更</h2>
        <form action="change_pwd.php" method="POST">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <label for="user_paw">現在のパスワード:</label>
            <input type="password" name="user_paw" id="user_paw" required>

            <label for="new_paw">新しいパスワード:</label>
            <input type="password" name="new_paw" id="new_paw" required>

            <label for="new_paw2">新しいパスワード（確認用）:</label>
            <input type="password" name="new_paw2" id="new_paw2" required>

            <button type="submit">変更</button>

            <p style="margin-top: 10px;">
                <button type="button" onclick="window.location.href='my_page.php';">戻る</button>
            </p>
        </form>
    </div>
</style>
</body>
</html>