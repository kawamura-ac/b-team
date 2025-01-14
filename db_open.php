<?php
try {
    $dbserver = "localhost"; // 로컬 개발 환경에서는 localhost
    $dbname = "team_b"; // 데이터베이스 이름
    $dbuser = "root"; // XAMPP 기본 사용자 이름
    $dbpasswd = ""; // XAMPP 기본 비밀번호는 없음

    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ];

    // PDO 연결 설정
    $dbh = new PDO("mysql:host=$dbserver;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpasswd, $opt);

    echo "DB 연결 성공!"; // 연결 확인 메시지
} catch (PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}
?>

