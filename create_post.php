<?php
session_start();
require 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // フォームから投稿データを取得する
    $post_title = $_POST['post_title'];
    $post_content = $_POST['post_content'];
    $user_id = $_SESSION['user_id'];
    $post_image = null;

    // タイトルの文字数チェック
    if (mb_strlen($post_title, "UTF-8") > 30) {
        $error = "タイトルは30文字以内で入力してください。";
    } else {
        // 画像アップロードの処理
        if (!empty($_FILES['post_image']['name'])) {
            $uploadDir = 'uploads/'; // アップロード先のディレクトリ
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true); // ディレクトリが存在しない場合は作成
            }

            $fileName = basename($_FILES['post_image']['name']);
            $targetPath = $uploadDir . time() . "_" . $fileName;

            // 画像ファイルの検証と保存
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['post_image']['type'], $allowedTypes) && move_uploaded_file($_FILES['post_image']['tmp_name'], $targetPath)) {
                $post_image = $targetPath;
            } else {
                $error = "画像のアップロードに失敗しました。";
            }
        }

        // データベースに投稿を挿入
        if (!isset($error)) {
            $stmt = $pdo->prepare("INSERT INTO posts (post_title, post_content, user_id, post_date, post_img) 
                                   VALUES (?, ?, ?, NOW(), ?)");
            $stmt->execute([$post_title, $post_content, $user_id, $post_image]);

            // 挿入後に投稿リストにリダイレクト
            header('Location: main.php');
            exit();
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
    <title>新規投稿</title>
</head>
<body>
    <div class="post_container">
        <h2>新規投稿</h2>
        <form action="create_post.php" method="POST" enctype="multipart/form-data">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <label for="post_title">タイトル</label>
            <input type="text" name="post_title" id="post_title" required>

            <label for="post_content">投稿内容</label>
            <textarea name="post_content" id="post_content" required></textarea>

            <label for="post_image">画像を選択</label>
            <input type="file" name="post_image" id="post_image" accept="image/*">

            <button type="submit">投稿</button><br>
            <button type="button" onclick="window.location.href='main.php';">メインに戻る</button>
        </form>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.querySelector("form");
        const postTitle = document.getElementById("post_title");
        const postContent = document.getElementById("post_content");
        form.addEventListener("submit", (e) => {
            const title = postTitle.value.trim();
            const content = postContent.value.trim();
            if (title === "" || content === "") {
                e.preventDefault();
                alert("タイトルと投稿内容を入力してください。（スペースや改行のみは無効です）");
            }
        });
    });
    </script>
</body>
</html>