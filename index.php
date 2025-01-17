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
<<<<<<< HEAD
            <label for="user_name">ニックネーム</label>
            <input type="text" name="user_name" id="user_name" required>
            
=======
            
            <div id="nickname-error" style="color: red; display: none;">ニックネームは20文字以内で入力してください。</div>
            <div id="email-error" style="color: red; display: none;">メールアドレスは30文字以内で入力してください。</div>
            <div id="password-error" style="color: red; display: none;">パスワードは20文字以内で入力してください。</div>

            <label for="user_name">ニックネーム</label>
            <input type="text" name="user_name" id="user_name" required>

            <label for="user_email">メールアドレス</label>
            <input type="email" name="user_email" id="user_email" required>
            
>>>>>>> f58c18b8ebd8b68200e9be25b533f7f865a61999
            <label for="user_paw">パスワード</label>
            <input type="password" name="user_paw" id="user_paw" required>
            
            <button type="submit">ログイン</button>
        </form>
        <p>アカウントがありませんか？ <a href="register.php">登録する</a></p>
    </div>
</body>
<script>
    function validateForm() {
        const nicknameInput = document.getElementById("user_name").value.trim();
        const emailInput = document.getElementById("user_email").value.trim();
        const passwdInput = document.getElementById("user_paw").value.trim();

        const nicknameError = document.getElementById("nickname-error");
        const emailError = document.getElementById("email-error");
        const passwordError = document.getElementById("password-error");

        // 初期化
        nicknameError.style.display = "none";
        emailError.style.display = "none";
        passwordError.style.display = "none";

        // 入力チェックフラグ
        let isValid = true;

        // ニックネームの文字数制限チェック
        if (nicknameInput.length > 20) {
            nicknameError.style.display = "block";
            isValid = false;
            
        } 

        // メールアドレスの文字数制限チェック
        if (emailInput.length > 30) {
            emailError.style.display = "block";
            isValid = false;
        }

        // パスワードの文字数制限チェック
         if (passwdInput.length > 20) {
            passwordError.style.display = "block";
            isValid = false;
        }

        // 検証結果
        if (isValid) {
            alert("入力が確認されました！");
        }
        return isValid; 
    }
</script>
