<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT user_name, user_email, user_img FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch();

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
    <style>
        .hidden { display: none; }
        .form-container { margin-top: 20px; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background-color: #f9f9f9; }
        .profile-icon { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #ccc; }
        .update_button { margin-top: 10px; }
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
        <div class="actions">
            <a href="change_pwd.php" class="button">パスワード変更</a>
            <form action="delete_user.php" method="post" onsubmit="return confirm('本当にこのユーザーを削除しますか？');">
                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                <button type="submit" class="form_button">ユーザーを削除</button>
            </form>
            <a href="main.php" class="button">メイン画面に戻る</a>
        </div>
        <div class="post">
            <img src="<?php echo htmlspecialchars($result['user_img'] ?? 'default.jpg'); ?>" alt="プロフィール画像" class="profile-icon">
            <p><strong>ユーザー名:</strong> <?php echo htmlspecialchars($result['user_name']); ?></p>
            <p><strong>メールアドレス:</strong> <?php echo htmlspecialchars($result['user_email']); ?></p>
            <button onclick="toggleForm()" class="update_button">ユーザー情報を変更</button>
        </div>
        <div id="updateForm" class="form-container hidden">
            <form class="update_form" action="update_user.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="user_name">ユーザー名:</label>
                    <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($result['user_name']); ?>" required>
                </div>
                <div>
                    <label for="user_email">メールアドレス:</label>
                    <input type="email" id="user_email" name="user_email" value="<?php echo htmlspecialchars($result['user_email']); ?>" required>
                </div>
                <div>
                    <label for="profile_pic">プロフィール画像:</label>
                    <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
                </div>
                <div>
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                    <button type="submit">変更を保存</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
