<?php
session_start();
require 'db_config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // required
    // 入力し送信されたname="user_name"とname="user_email"とname="user_paw"の値を取得する
    // trim — 文字列の左右にある空白を削除  $変数 = trim(文字列[,削除する文字])
    $nickname = trim($_POST['user_name']);   // 変数名以外のnicknameを全てuser_nameに変更した
    $email = trim($_POST['user_email']);     // 変数名以外のemailを全てuser_emailに変更した
    $password = $_POST['user_paw'];          // 変数名以外のpasswordを全てuser_pawに変更した

    // フィールドが空欄かどうかの確認
    if (empty($nickname) || empty($email) || empty($password)) {
        $error = "空欄があります。全てのフィールドを入力してください。";
    // 文字列の長さの取得(全角文字も1文字と数える場合) mb_strlen( $val, "UTF-8");
    } elseif ( mb_strlen( $nickname, "UTF-8") > 20){
        $error = "ニックネームは20文字以内で入力してください。";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {       // filter_var関数でメールアドレスをバリデーション
        $error = "無効なメールアドレスです。";
    } elseif ( mb_strlen( $email, "UTF-8") > 30){
        $error = "メールアドレスは30文字以内で入力してください。";
    } elseif ( mb_strlen( $password, "UTF-8") > 20){
        $error = "パスワードは20文字以内で入力してください。";
    } else {
        try {
            // user_nameまたはuser_emailが既に存在するかどうかの確認
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :user_name OR user_email = :user_email");
            $stmt->execute(['user_name' => $nickname, 'user_email' => $email]);
            $existingUser = $stmt->fetch();
            if ($existingUser) {
                $error = "このニックネームまたはメールアドレスは既に使用されています。";
            } else {
                // password_hash — パスワードハッシュを作る
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // userをデータベースに挿入
                $stmt = $pdo->prepare("INSERT INTO users (user_name, user_email, user_paw) VALUES (:user_name, :user_email, :user_paw)");
                $result = $stmt->execute([
                    'user_name' => $nickname,
                    'user_email' => $email,
                    'user_paw' => $hashedPassword
                ]);
                if ($result) {
                    // 登録が完了するとログインページにリダイレクトされる
                    header('Location: index.php');
                    exit();
                } else {
                    $error = "登録に失敗しました。もう一度試してください。";
                }
            }
        } catch (Exception $e) {
            // エラーをログに記録します (開発時のデバッグの場合はオプション)
            error_log("登録中のエラー: " . $e->getMessage());
            $error = "エラーが発生しました。もう一度試してください。";
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
            <!-- required属性 — 空欄のままボタンが押された場合、エラーメッセージを表示 -->
            <input type="text" name="user_name" id="user_name" required>
            <label for="user_email">メールアドレス</label>
            <input type="email" name="user_email" id="user_email" required>
            <label for="user_paw">パスワード</label>
            <input type="password" name="user_paw" id="user_paw" required>
            <button type="submit">登録</button>
        </form>
        <p>アカウントをお持ちの方はこちら <a href="index.php">ログイン</a></p>
    </div>
</body>
</html>











