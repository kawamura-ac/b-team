<?php
session_start();
require 'db_config.php'; // データベース接続ファイル

// セッションにユーザーIDがなければリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// ユーザーIDをセッションから取得
$user_id = $_SESSION['user_id'];

// データベースからユーザー情報を取得
$stmt = $pdo->prepare("SELECT user_name, user_email, user_img FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch();

// ユーザー情報が見つからなかった場合
if (!$result) {
    echo "ユーザー情報が見つかりませんでした。";
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>ユーザー情報</title>
</head>
<body>
<style>
        .hidden {
            display: none;
        }
        .form-container {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
    <script>
        function toggleForm() {
            const form = document.getElementById('updateForm');
            form.classList.toggle('hidden');
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>ユーザー情報</h2>
        <div>
            <a href="password_change.php" class="button">パスワード変更</a>
            <form action="delete_user.php" method="post" onsubmit="return confirm('本当にこのユーザーを削除しますか？');">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <button type="submit" class="button">ユーザーを削除</button>
            </form>
            <a href="main.php" class="button">メイン画面に戻る</a>      
        </div>
        
        <!-- ユーザー情報表示 -->
        <div class="post">
            <?php if (!empty($result['user_img'])): ?>
                <img src="<?php echo htmlspecialchars($result['user_img']); ?>" alt="プロフィール画像" style="max-width: 200px;">
            <?php else: ?>
                <p>画像が設定されていません。</p>
            <?php endif; ?>    
            <p><strong>ユーザー名:</strong> <?php echo htmlspecialchars($result['user_name']); ?></p>
            <p><strong>メールアドレス:</strong> <?php echo htmlspecialchars($result['user_email']); ?></p>
            <button onclick="toggleForm()" class="button">ユーザー情報を変更</button>
            <!-- ユーザー情報変更ボタン -->
        </div>

        <!-- ユーザー情報変更フォーム -->
        <div id="updateForm" class="form-container hidden">
            <form action="update_user.php" method="post">
                <div>
                    <label for="user_name">ユーザー名:</label>
                    <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($result['user_name']); ?>" required>
                </div>
                <div>
                    <label for="user_email">メールアドレス:</label>
                    <input type="email" id="user_email" name="user_email" value="<?php echo htmlspecialchars($result['user_email']); ?>" required>
                </div>
                <div>
                    <label for="user_img">プロフィール画像URL:</label>
                    <input type="file" id="user_img" name="user_img" value="<?php echo htmlspecialchars($result['user_img']); ?>">
                </div>
                <div>
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button type="submit" class="button">変更を保存</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>