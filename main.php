<?php
session_start();
require 'db_config.php';

// Redirect to login if not logged in ログインしていない場合はログインにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Handle comment submission コメントの送信を処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content']) && isset($_POST['comment_post_id'])) {
    $comment_content = trim($_POST['comment_content']);
    $post_id = $_POST['comment_post_id'];
    $user_id = $_SESSION['user_id'];

    // Insert comment into database データベースにコメントを挿入
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, comment_content, comment_date) 
                           VALUES (:user_id, :post_id, :comment_content, NOW())");
    $stmt->execute([
        'user_id' => $user_id,
        'post_id' => $post_id,
        'comment_content' => $comment_content
    ]);

    // Redirect to the same page to prevent re-posting if page is refreshed ページが更新された場合に再投稿を防ぐために同じページにリダイレクト
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Fetch posts with like and comment counts いいね数とコメント数を含む投稿を取得
$stmt = $pdo->prepare("
    SELECT posts.*, users.user_name, users.user_img, 
           (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.post_id) AS like_count,
           (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.post_id) AS comment_count
    FROM posts 
    JOIN users ON posts.user_id = users.user_id 
    ORDER BY like_count DESC, posts.post_date DESC
");
$stmt->execute();
$posts = $stmt->fetchAll();

// Fetch comments for each post 各投稿のコメントを取得
$commentsByPost = [];
$stmt = $pdo->prepare("SELECT comments.*, users.user_name FROM comments JOIN users ON comments.user_id = users.user_id WHERE post_id = :post_id ORDER BY comment_date ASC");
foreach ($posts as $post) {
    $stmt->execute(['post_id' => $post['post_id']]);
    $commentsByPost[$post['post_id']] = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>投稿一覧</title>
    <style>
        .author-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .author-info strong {
            margin-right: 10px;
        }

        .author-actions {
            display: flex;
            gap: 10px;
        }

        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
    </style>
    <script>
        function likePost(postId) {
    const likeButton = document.getElementById('like-button-' + postId);
    const likeCountSpan = document.getElementById('like-count-' + postId);

    // Send AJAX request to like/unlike the post リクエストを送信して投稿にいいねを付ける
    fetch('like_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'post_id=' + postId
    })
    .then(response => response.json())
    .then(data => {
        // Update the button and like count dynamically ボタンといいね数を動的に更新
        if (data.liked) {
            likeButton.innerHTML = '👍';  // Liked state
        } else {
            likeButton.innerHTML = '👍🏼'; // Unliked state
        }
        likeCountSpan.textContent = data.likeCount + " Likes";
    })
    .catch(error => console.error('Error:', error));
    }

        // Function to toggle the visibility of comments コメントの表示/非表示を切り替える
        function toggleComments(postId) {
            const commentsSection = document.getElementById('comments-' + postId);
            commentsSection.style.display = (commentsSection.style.display === 'none' || commentsSection.style.display === '') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>投稿一覧</h2>
        <p>ログイン中のユーザー: <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
        <div class="actions">
            <a href="create_post.php" class="button">新規投稿</a>
            <a href="authors_list.php" class="button">投稿者一覧</a>
            <a href="user_info.php" class="button">ユーザー情報</a>
            <a href="logout.php" class="button logout">ログアウト</a>
        </div>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <?php if (!empty($post['post_img'])): ?>
                        <img src="<?php echo htmlspecialchars($post['post_img']); ?>" alt="投稿画像" class="post-image">
                    <?php endif; ?>

                    <div class="author-info">
                        <?php
                        $profileImg = !empty($post['user_img']) ? $post['user_img'] : 'default.png';
                        ?>
                        <div>
                            <img src="<?php echo htmlspecialchars($profileImg); ?>" alt="プロフィール画像" class="profile-picture">
                            <strong>投稿者:</strong>
                            <a href="posts_by_author.php?user_id=<?php echo $post['user_id']; ?>">
                                <?php echo htmlspecialchars($post['user_name']); ?>
                            </a>
                        </div>

                        <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
                            <div class="author-actions">
                                <a href="edit_post.php?post_id=<?php echo $post['post_id']; ?>" class="button update">更新</a>
                                <a href="delete_post.php?post_id=<?php echo $post['post_id']; ?>" 
                                   class="button delete" 
                                   onclick="return confirm('本当に削除しますか?');">削除</a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                    <p><small>投稿日時: <?php echo $post['post_date']; ?></small></p>

                    <!-- Display Like Button and Count -->
                    <button id="like-button-<?php echo $post['post_id']; ?>" class="button like" onclick="likePost(<?php echo $post['post_id']; ?>)">
                        <span class="like-icon">
                            <?php echo $post['like_count'] > 0 ? '👍' : '👍🏼'; ?>
                        </span>
                        Like
                    </button>
                    <span id="like-count-<?php echo $post['post_id']; ?>" class="like-count"><?php echo $post['like_count']; ?> Likes</span>

                    <!-- Comment Button -->
                    <button class="button comment" onclick="toggleComments(<?php echo $post['post_id']; ?>)">
                        💬 コメント
                    </button>
                    <span class="comment-count"><?php echo $post['comment_count']; ?> Comments</span>

                    <!-- Comments Section -->
                    <div id="comments-<?php echo $post['post_id']; ?>" class="comments-section" style="display:none;">
                        <h4>コメント一覧</h4>
                        <?php if (!empty($commentsByPost[$post['post_id']])): ?>
                            <?php foreach ($commentsByPost[$post['post_id']] as $comment): ?>
                                <div class="comment">
                                    <p><strong><?php echo htmlspecialchars($comment['user_name']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_content']); ?></p>
                                    <p><small><?php echo $comment['comment_date']; ?></small></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>コメントがまだありません。</p>
                        <?php endif; ?>

                        <!-- Add Comment Form -->
                        <form action="" method="POST">
                            <input type="hidden" name="comment_post_id" value="<?php echo $post['post_id']; ?>">
                            <textarea name="comment_content" rows="2" placeholder="コメントを入力..." required></textarea>
                            <button type="submit">コメントを追加</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>投稿がまだありません。</p>
        <?php endif; ?>
    </div>
</body>
</html>