-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql309.phy.lolipop.lan
-- 생성 시간: 24-12-24 10:13
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
-- 테이블 구조 `Posts`
--

CREATE TABLE `Posts` (
  `post_id` int NOT NULL,
  `post_title` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `post_content` text COLLATE utf8mb4_general_ci NOT NULL,
  `post_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 테이블의 덤프 데이터 `Posts`
--

INSERT INTO `Posts` (`post_id`, `post_title`, `user_id`, `post_content`, `post_date`) VALUES
(1, '少年が来る', 0, '1980年5月の光州民主化運動を背景に、一人の少年の視点を通じて当時の痛みと傷を描きます。主人公のドンホは友人の死を目撃し、その後の人生を通じて歴史的な悲劇が個人に与える影響を見せます。この作品は、集団的な記憶と個人の苦しみを繊細に表現しています。', '2021-03-15 00:00:00'),
(2, '時の迷宮', 0, '飛行機の故障で砂漠に不時着した「私」は、小さな星から来たという不思議な少年、王子さまと出会う。王子さまは旅の中で出会ったさまざまな人々や体験を通じて、大切なものの本質を語る。最後に王子さまは自分の星に帰るが、「私」の心には永遠に残る存在となる。', '2019-11-08 00:00:00'),
(3, 'ノルウェイの森', 0, '1960年代の東京を舞台に、主人公ワタナベと2人の女性、ナオコとミドリとの関係を描いた青春小説。愛と喪失、孤独をテーマに、人間の成長と複雑な感情の交差が繊細に描かれる。ノルウェイの森という曲が主人公の記憶を呼び起こし、物語が進んでいく。', '2020-07-20 00:00:00'),
(4, '赤ずきん', 0, '森を通って祖母の家に向かう赤ずきんが、大きな悪いオオカミに出会う物語。オオカミに騙され、祖母と一緒に食べられてしまうが、狩人に助けられる。最後には知恵と勇気を学び、無事に帰る。', '2022-04-10 00:00:00'),
(5, '1984年', 0, '極度に監視と支配が行き届いた全体主義国家で、主人公ウィンストンは真実と自由を求める。しかし、体制に抗おうとする試みは次第に自らの存在を危うくしていく。最終的に、思想の自由すら失い、完全に体制の支配下に置かれる結末を迎える。', '2023-01-05 00:00:00');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `Posts`
--
ALTER TABLE `Posts`
  ADD PRIMARY KEY (`post_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `Posts`
--
ALTER TABLE `Posts`
  MODIFY `post_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
