-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql309.phy.lolipop.lan
-- 생성 시간: 24-12-24 09:37
-- 서버 버전: 8.0.35
-- PHP 버전: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `LAA1618183-mydb`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `user_name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `user_email` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `user_paw` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `Users`
--

INSERT INTO `Users` (`user_id`, `user_name`, `user_email`, `user_paw`) VALUES
(1, 'ハンガン', 'tanomi123@fakeemail.jp', '1111'),
(2, 'アントワーヌ・ド・サン＝テグジュペリ', 'kurumi.k@nonexistentmail.com', '2222'),
(3, '村上 春樹', 'natsuro_88@noemail.fake', '3333'),
(4, 'グリム兄弟', 'mikato.sora@outlook.jp', '4444'),
(5, 'ジョージ・オーウェル', 'sorane_ai@notrealmail.net', '5555');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
