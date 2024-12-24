
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
     <footer>
        
        <a style="border: 2px solid black;" href="Registration.php">新規登録はこちらから</a>
         
     </footer>  
    
    ___EOF___;
echo  $body;
   
  
    
    
?>
   
     


