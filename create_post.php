<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

    $alert = ""; //タイトル文字制限

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['post_title']);               // 変数名以外のtitleを全てpost_titleに変更
    $content = trim($_POST['post_content']);           // 変数名以外のcontentを全てpost_contentに変更
    $author_id = $_SESSION['user_id'];           // 変数名以外のauthor_idを全てuser_idに変更

    $titleLength = mb_strlen($title, 'UTF-8');  //タイトル文字数の制限 ここから
    
    if($titleLength<2){
        $alert = "タイトルは1文字以上の入力をする必要があります。";
    }elseif($titleLength>30){
        $alert = "タイトルは30文字以下の入力をする必要があります。";
    }else{  //ここまで

    $stmt = $pdo->prepare("INSERT INTO posts (post_title, post_content, user_id) VALUES (:post_title, :post_content, :user_id)");
    $stmt->execute([
        'post_title' => $title, 
        'post_content' => $content, 
        'user_id' => $author_id]);

    header('Location: main.php');
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
    <title>投稿を作成</title>
</head>
<body>
    <div class="container">
        <h2>投稿作成画面</h2>
        <form action="create_post.php" method="POST">
            <label for="post_title">タイトル</label>

            <!--タイトル文字制限のアラム-->
            <?php if(!empty($alert)):?> 
                <p style="color:red; margin-top:-10px; margin-bottom:10px;">
                    <?php echo htmlspecialchars($alert,ENT_QUOTES,'UTF-8');?>
                </p>
            <?php endif;?>

            <input type="text" name="post_title" id="post_title">
            <label for="post_content">投稿内容</label>
            <textarea name="post_content" id="post_content"></textarea>
            <button type="submit">投稿</button>
        </form>
    </div>
</body>
</html>
