<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Main</title>
    </head>
    <body>
    <?php
        // データベース
        include "db_open.php";

        // Postsと Users データの呼び込み
        $sql = "
            SELECT 
                Posts.post_id, 
                Posts.post_title, 
                Posts.post_date, 
                Posts.post_content, 
                Users.user_name 
            FROM Posts JOIN Users        
            ";

        // SQL 実行
        $sql_res = $dbh->query($sql);

        // 結果出力
        while ($rec = $sql_res->fetch(PDO::FETCH_ASSOC)) {
            echo "<div>";
            echo "<p>掲示番号: " . htmlspecialchars($rec['post_id']) . "</p>";
            echo "<p>投稿名: " . htmlspecialchars($rec['user_name']) . "</p>";
            echo "<p>タイトル: " . htmlspecialchars($rec['post_title']) . "</p>";
            echo "<p>投稿日付: " . htmlspecialchars($rec['post_date']) . "</p>";
            echo "<p>投稿内容: " . htmlspecialchars($rec['post_content']) . "</p>";
            echo "</div><hr>";
        }
    ?>
    </body>
</html>

