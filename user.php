
   <link rel="stylesheet" href="css.php"> 
   <?php
    $body =<<<___EOF___

    <header>
        <h1>cyber</h1>
    </header>
    <h2>ログイン</h2>
    <br>
    <br>
    <center>
    <form method="POST" action="main.php">
        <p>ニックネーム：<input type="text" name="name" required></p>
        <p>メールアドレス：<input type="email" name="email" required></p>
        <p>パスワード：<input type="password" name="password" required></p>
        <br>
        <input type="submit" value="ログイン">
     </form>
     </center>
     <div class="divider"></div>
     <footer>
        <form action="Registration.php" method="GET">
            <button type="submit">新規登録はこちらから</button>
        </form>
     </footer>  
    
    ___EOF___;
echo  $body   
  
    
    
?>
   
     


