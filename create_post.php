<?php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから投稿データを取得する
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $user_id = $_SESSION['user_id'];

    // 文字列の長さの取得(全角文字も1文字と数える場合) mb_strlen( $val, "UTF-8");
    if ( mb_strlen( $post_title, "UTF-8") > 30){
        $error = "タイトルは30文字以内で入力してください。";
    } else {
        // post_date に現在の日時を指定して新しい投稿を挿入する
        $stmt = $pdo->prepare("INSERT INTO posts (post_title, post_content, user_id, post_date) 
                           VALUES (?, ?, ?, NOW())");           // prepare — SQL文の実行準備
        $stmt->execute([$post_title, $post_content, $user_id]);     // execute — プリペアドステートメント（SQL文で値が変わる可能性がある箇所に対して、変数のように別の文字列を入れておき、後で置き換える仕組み）を実行する際に使われる関数

        // 挿入後に投稿リストにリダイレクトする
        header('Location:main.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>新規投稿</title>
</head>
<body>
    <div class="post_container">
        <h2>新規投稿</h2>
        <form action="create_post.php" method="POST">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <label for="post_title">タイトル</label>
            <input type="text" name="post_title" id="post_title" required>

            <div class="textarea">
            <label for="post_content">投稿内容</label>
            <textarea name="post_content" id="post_content" required></textarea>

            <button type="submit">投稿</button>
        
            <p style="padding-top:-10px;" border: 1px solid #ccc;>
            <button type="button" onclick="window.location.href='main.php';">メインに戻る</button></p>
        </form>
    </div>
</body>
</html>