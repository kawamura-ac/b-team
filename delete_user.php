<?php
session_start();
require 'db_config.php'; // データベース接続ファイル

// セッションにユーザーIDがなければリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// POSTからユーザーIDを取得
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']); // セキュリティのために整数値に変換

    try {
        // ユーザー削除クエリ
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // セッションを終了し、ログインページにリダイレクト
            session_destroy();
            header('Location: index.php?message=ユーザーが削除されました');
            exit();
        } else {
            echo "ユーザー削除に失敗しました。";
        }
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    header('Location: my_page.php');
    exit();
}
?>
