<?php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nickname = trim($_POST['user_name']);
    $email = trim($_POST['user_email']);
    $password = $_POST['user_paw'];

    // Check for errors
    if (empty($nickname) || empty($email) || empty($password)) {
        $error = "空欄があります。全てのフィールドを入力してください。";
    } elseif (mb_strlen($nickname, "UTF-8") > 20) {
        $error = "ニックネームは20文字以内で入力してください。";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "無効なメールアドレスです。";
    } elseif (mb_strlen($email, "UTF-8") > 30) {
        $error = "メールアドレスは30文字以内で入力してください。";
    } elseif (mb_strlen($password, "UTF-8") > 20) {
        $error = "パスワードは20文字以内で入力してください。";
    } else {
        try {
            // Check for duplicate user
            $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :user_name OR user_email = :user_email");
            $stmt->execute(['user_name' => $nickname, 'user_email' => $email]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                if ($existingUser['user_email'] === $email) {
                    $error = "このメールアドレスは既に使用されています。";
                } elseif ($existingUser['user_name'] === $nickname) {
                    $error = "このニックネームは既に使用されています。";
                }
            } else {
                // Handle image upload パス人によって異なる
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/kyoudou/uploads/';
                $defaultImg = '/kyoudou/uploads/default.png'; // Default image
                $imagePath = $defaultImg;

                // Check if the file is uploaded
                if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    $fileType = mime_content_type($_FILES['profile_pic']['tmp_name']);
                    if (!in_array($fileType, $allowedTypes)) {
                        $error = "無効なファイルタイプです。";
                    } else {
                        $imageName = uniqid() . '-' . basename($_FILES['profile_pic']['name']);
                        $targetFile = $targetDir . $imageName;

                        // Move the uploaded file
                        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
                            // File successfully uploaded パス人によって異なる
                            $imagePath = '/kyoudou/uploads/' . $imageName;
                        } else {
                            $error = "ファイルのアップロードに失敗しました。";
                        }
                    }
                }
                // If no errors, proceed with user registration
                if (!isset($error)) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (user_name, user_email, user_paw, user_img) 
                                           VALUES (:user_name, :user_email, :user_paw, :user_img)");
                    $result = $stmt->execute([
                        'user_name' => $nickname,
                        'user_email' => $email,
                        'user_paw' => $hashedPassword,
                        'user_img' => $imagePath
                    ]);

                    if ($result) {
                        header('Location: index.php');
                        exit();
                    } else {
                        $error = "登録に失敗しました。もう一度試してください。";
                    }
                }
            }
        } catch (Exception $e) {
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
        <form action="register.php" method="POST" enctype="multipart/form-data">
            <?php if (!empty($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <label for="user_name">ニックネーム</label>
            <input type="text" name="user_name" id="user_name" required>
            <label for="user_email">メールアドレス</label>
            <input type="email" name="user_email" id="user_email" required>
            <label for="user_paw">パスワード</label>
            <input type="password" name="user_paw" id="user_paw" required>
            <label for="profile_pic">プロフィール写真</label>
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*">
            <button type="submit">登録</button>
        </form>
        <p>アカウントをお持ちの方はこちら <a href="index.php">ログイン</a></p>
    </div>
</body>
</html>








