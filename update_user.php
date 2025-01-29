<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_POST['user_id'];
$user_name = $_POST['user_name'];
$user_email = $_POST['user_email'];

// プロフィール画像のアップロード処理
$imagePath = null;

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
    $fileName = $_FILES['profile_pic']['name'];
    $fileSize = $_FILES['profile_pic']['size'];
    $fileType = $_FILES['profile_pic']['type'];

    // ファイル拡張子の確認
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowedExtensions)) {
        // 新しいファイル名を生成（重複防止のためにランダムな文字列を使用）
        $newFileName = uniqid() . '.' . $fileExtension;
        $uploadDir = 'uploads/';
        $uploadPath = $uploadDir . $newFileName;

        // ファイルをアップロードディレクトリに移動
        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            $imagePath = $uploadPath;
        } else {
            echo "ファイルのアップロードに失敗しました。";
            exit();
        }
    } else {
        echo "無効なファイル形式です。許可されている形式: jpg, jpeg, png, gif";
        exit();
    }
    // 現在のファイルの絶対パスを取得
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/kyoudou/uploads/';

}

// データベースの更新処理
$stmt = $pdo->prepare(
    "UPDATE users 
     SET user_name = :user_name, user_email = :user_email" .
    ($imagePath ? ", user_img = :user_img" : "") .
    " WHERE user_id = :user_id"
);
$stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
$stmt->bindParam(':user_email', $user_email, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

if ($imagePath) {
    $stmt->bindParam(':user_img', $imagePath, PDO::PARAM_STR);
}

if ($stmt->execute()) {
    header('Location: user_info.php');
    exit();
} else {
    echo "ユーザー情報の更新に失敗しました。";
    exit();
}
?>
