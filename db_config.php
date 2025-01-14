<?php
$host = 'mysql309.phy.lolipop.lan';     
$dbname = 'LAA1619960-mydb';
$username = 'LAA1619960';          
$password = '58315831';         


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
