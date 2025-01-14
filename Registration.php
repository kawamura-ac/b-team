<link rel="stylesheet" href="css.php"> 
   <?php
   if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users VALUE ('{$nickname}', '{$email}', '{$password}')";
    $sql_res = $dbh->query( $sql );
   }
  
   
    
    $body =<<<___EOF___

    <header>
        <h1>cyber</h1>
    </header>
    <h2>新規登録</h2>
    <br>
    <br>
    <center>
    <form method="POST">
        <p>ニックネーム：<input type="text" name="nickname" required></p>
        <p>メールアドレス：<input type="email" name="email" required></p>
        <p>パスワード：<input type="password" name="password" required></p>
        <br>
        <input type="submit" value="登録">
    </form>
     </center>
     
    
    ___EOF___;
echo  $body;
?>
   