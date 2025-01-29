<?php
session_start();
require 'db_config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_content']) && isset($_POST['comment_post_id'])) {
    $comment_content = trim($_POST['comment_content']);
    $post_id = $_POST['comment_post_id'];
    $user_id = $_SESSION['user_id'];

    // Insert comment into database
    $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, comment_content, comment_date) 
                           VALUES (:user_id, :post_id, :comment_content, NOW())");
    $stmt->execute([
        'user_id' => $user_id,
        'post_id' => $post_id,
        'comment_content' => $comment_content
    ]);

    // Redirect to the same page to prevent re-posting if page is refreshed
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

//最新記事といいね多い順番
$sortOption = isset($_POST['sortOption']) ? $_POST['sortOption'] : 'post_date';
$sortSql = "posts.post_date DESC"; //基本は最新記事順

if ($sortOption == "likes") {
    $sortSql = "like_count DESC"; // いいねが多い順
}

// ！！重要記事を表示させる
$stmt = $pdo->prepare("
    SELECT posts.*, users.user_name, users.user_img, 
           COUNT(likes.likes_id) AS like_count,
           (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.post_id) AS comment_count
    FROM posts 
    JOIN users ON posts.user_id = users.user_id 
    LEFT JOIN likes ON posts.post_id = likes.post_id 
    GROUP BY posts.post_id
    ORDER BY $sortSql
");
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch comments for each post
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
        .sort-icon {/*最新記事といいね順のスタイル*/
            cursor: pointer;
            width: 27px;
            height: 27px;
            transition: transform 0.3s;
        }
    </style>
    <script>
        function likePost(postId) {
    const likeButton = document.getElementById('like-button-' + postId);
    const likeCountSpan = document.getElementById('like-count-' + postId);

    // Send AJAX request to like/unlike the post
    fetch('like_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'post_id=' + postId
    })
    .then(response => response.json())
    .then(data => {
        // Update the button and like count dynamically
        if (data.liked) {
            likeButton.innerHTML = '👍';  // Liked state
        } else {
            likeButton.innerHTML = '👍🏼'; // Unliked state
        }
        likeCountSpan.textContent = data.likeCount + " Likes";
    })
    .catch(error => console.error('Error:', error));
    }

        // Function to toggle the visibility of comments
        function toggleComments(postId) {
            const commentsSection = document.getElementById('comments-' + postId);
            commentsSection.style.display = (commentsSection.style.display === 'none' || commentsSection.style.display === '') ? 'block' : 'none';
        }
    </script>
    <script>
        function toggleLike(postId, icon) {
            if (icon.src.includes('nolike.png')) {// いいねを押す
            icon.src = 'like.png';
        } else {// いいねを取り消す
        icon.src = 'nolike.png';
        }
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
            <a href="logout.php" class="button logout">ログアウト</a>
        </div>

        <!--最新記事といいね多い順番のイメージボタン-->
        <form method="POST" id="sortForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="sortOption" id="sortOption" value="<?php echo $sortOption; ?>">
            <img src="Sequence.png" id="sortIcon" class="sort-icon" onclick="toggleSort()">
        </form>
        <script>
            function toggleSort() {
            let currentSort = document.getElementById('sortOption').value;
            let newSort = (currentSort === 'post_date') ? 'likes' : 'post_date';
            document.getElementById('sortOption').value = newSort;
            document.getElementById('sortForm').submit();
        }
        window.onload = function() {
            let currentSort = "<?php echo $sortOption; ?>";
            let icon = document.getElementById('sortIcon');

            if (currentSort === "likes") {
                icon.style.transform = "rotate(180deg)"; //いいね順にクリックしたら回転
            } else {
                icon.style.transform = "rotate(0deg)"; //最新順にクリックしたら回転
            }
        };
        </script>

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
                    <form style="display:inline;">
                        <img id="like-icon-<?php echo $post['post_id']; ?>"
                        src="<?php echo $liked ? 'like.png' : 'nolike.png'; ?>"
                        alt="Like Icon" class="like-icon" style="cursor:pointer;"
                        onclick="toggleLike(<?php echo $post['post_id']; ?>, this);"/>
                    </form>
                    <span id="like-count-<?php echo $post['post_id']; ?>" class="like-count"><?php echo $post['like_count']; ?> Likes</span>

                    <!-- Comment Button -->
                    <img src="comment-icon.png" alt="Comment Icon" class="comment-icon" style="cursor:pointer;"
                    onclick="toggleComments(<?php echo $post['post_id']; ?>);" />
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
