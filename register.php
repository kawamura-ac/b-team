<?php
require ''; //데이터 베이스 이름 넣기

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (nickname, email, password) VALUES (:nickname, :email, :password)");
    $stmt->execute(['nickname' => $nickname, 'email' => $email, 'password' => $password]);

    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>登録</title>
</head>
<body>
    <div class="container">
        <h2>新規登録</h2>
        <form action="register.php" method="POST">
            <label for="nickname">ニックネーム</label>
            <input type="text" name="nickname" id="nickname" required>
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" required>
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">登録</button>
        </form>
    </div>
</body>
</html>
