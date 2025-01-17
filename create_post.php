<?php
session_start();
require 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

    $alert = ""; //タイトル文字制限

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    $title = trim($_POST['post_title']);               // 変数名以外のtitleを全てpost_titleに変更
    $content = isset($_POST['post_content']);           // 変数名以外のcontentを全てpost_contentに変更
    $author_id = $_SESSION['user_id'];           // 変数名以外のauthor_idを全てuser_idに変更

    if(empty($title)){  //タイトル文字数の制限 ここから
        $alert="タイトルを入力してください。";
    }else{
        $titleLength = mb_strlen($title, 'UTF-8');
        if($titleLength<2){
            $alert = "タイトルは1文字以上の入力をする必要があります。";
        }elseif($titleLength>30){
            $alert = "タイトルは30文字以下の入力をする必要があります。";
        }else{  //ここまで

    $stmt = $pdo->prepare(
        "INSERT INTO posts (post_title, post_content, user_id) 
         VALUES (:post_title, :post_content, :user_id)");
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
    <div class="post_container">
        <h2>投稿作成</h2>
        <form action="create_post.php" method="POST">

        <!--タイトル文字制限のアラム-->
            <label for="post_title">タイトル</label>
            <?php if(!empty($alert)):?> 
                <p style="color:red; margin-top:-10px; margin-bottom:10px;">
                    <?php echo htmlspecialchars($alert,ENT_QUOTES,'UTF-8');?>
                </p>
            <?php endif;?>
            <input type="text" name="post_title" id="post_title">

        <!--テキストエリア-->
            <div class="textarea">
            <label for="post_content">投稿内容</label>
            <textarea name="post_content" id="post_content"></textarea>

        <!--ボタン-->
                <button type="submit">投稿する</button>
        
        <!--メイン画面に戻る-->
                <p style="padding-top:-10px;" border: 1px solid #ccc;">
                <button type="button" onclick="window.location.href='main.php';">メインに戻る</button></p>
        </form>
    </div>
</body>
</html>