<?php
//CSSとして使うPHPファイルの一番上に記述
header('Content-Type: text/css;', 'charset=utf-8'); ?>
@charset "utf-8";


<?php
$bgcolor = 'cyan';
?>
 
header{
    background: <?php echo $bgcolor; ?>;
}
form{
    justify-content:center
    display: flex;
}
h2{
    margin-left: auto;
    margin-right: auto;
    width: 8em 
   
}
footer{
    position: absolute;/*←絶対位置*/
    bottom: 15;
    

   
}
button{
    

   
}
.divider {
            height: 1px;
            background-color: black;
            margin: 280px 0;
}

