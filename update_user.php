<?php
session_start();
require 'db_config.php'; // データベース接続ファイル

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_img = $_POST['user_img'];

    try {
        // データベースを更新
        $stmt = $pdo->prepare("UPDATE users SET user_name = :user_name, user_email = :user_email, user_img = :user_img WHERE user_id = :user_id");
        $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
        $stmt->bindParam(':user_email', $user_email, PDO::PARAM_STR);
        $stmt->bindParam(':user_img', $user_img, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header('Location: my_page.php?message=変更が保存されました');
            exit();
        } else {
            echo "変更の保存に失敗しました。";
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    header('Location: my_page.php');
    exit();
}
?>
