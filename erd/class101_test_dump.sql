-- MySQL dump 10.13  Distrib 5.7.31, for Linux (x86_64)
--
-- Host: class101.c66ai5gyrco2.ap-northeast-2.rds.amazonaws.com    Database: test
-- ------------------------------------------------------
-- Server version	5.7.30-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED='';

--
-- Table structure for table `Class`
--

DROP TABLE IF EXISTS `Class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Class` (
  `class_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '클래스 id',
  `class_name` varchar(50) NOT NULL COMMENT '클래스명',
  `class_ctg` varchar(10) NOT NULL COMMENT '공예미술 등',
  `creator_id` int(11) NOT NULL COMMENT '강사(크리에이터) ID',
  `open_date` timestamp NOT NULL COMMENT '개강 날짜',
  `share_url` text NOT NULL COMMENT '공유 링크',
  `target` varchar(10) NOT NULL COMMENT '입문자대상 ; 초급자대상',
  `is_early_bird` char(1) NOT NULL DEFAULT 'N' COMMENT '얼리버드 여부',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` char(1) NOT NULL DEFAULT 'N',
  `class_thumb` text NOT NULL COMMENT '썸네일 링크',
  `discount_deadline` timestamp NULL DEFAULT NULL COMMENT 'now()와 비교해서 할인 남은시간 계산',
  PRIMARY KEY (`class_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='클래스';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Class`
--

LOCK TABLES `Class` WRITE;
/*!40000 ALTER TABLE `Class` DISABLE KEYS */;
INSERT INTO `Class` VALUES (1,'스마트스토어로 월 100만원 만들기, 평범한 사람이 돈을 만드는 비법','부업/창업/재테크',14,'2020-06-30 11:56:25','https://...','입문자 대상','N','2020-08-31 11:58:57','2020-08-31 12:13:43','N','https://...','2020-07-18 11:56:25'),(2,'딱 3개월 만에 영어 프리토킹! 딱 이만큼 영어회화, 딱영어','자기계발',12,'2020-10-20 15:00:00','https://...','초급자 대상','Y','2020-08-31 12:03:10','2020-08-31 12:05:04','N','https://...','2020-10-30 11:56:25'),(3,'15만 유튜버 \'대학생김머신\'의 무엇이든 팔 수 있는 쇼핑몰 창업 가이드','부업/창업/재테크',13,'2020-07-30 11:56:25','https://...','초급자 대상','N','2020-08-31 12:13:43','2020-08-31 12:13:43','N','https://...','2020-08-05 11:56:25'),(4,'연필 하나만으로 모든 분위기를 담아내요, 둡의 연필 드로잉','미술',9,'2020-08-27 11:56:25','https://...','입문자 대상','Y','2020-08-31 12:15:46','2020-08-31 12:15:46','N','https://...','2020-09-05 11:56:25'),(5,'운동 효과가 눈으로 증명되는 제이제이의 고강도 홈트레이닝','운동',11,'2020-09-17 11:56:25','https://...','초급자 대상','Y','2020-08-31 12:22:49','2020-08-31 12:22:49','N','https://...','2020-09-30 11:56:25'),(6,'초보도 하루면 뚝딱! 나무와 가죽으로 로망이 실현되는 감성 캠핑 소품 만들기','공예',10,'2020-10-20 11:56:25','https://...','중급자 대상','Y','2020-08-31 12:25:17','2020-08-31 12:25:17','N','https://...','2020-10-30 11:56:25');
/*!40000 ALTER TABLE `Class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Class_img`
--

DROP TABLE IF EXISTS `Class_img`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Class_img` (
  `image_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '이미지 idx',
  `class_idx` int(11) NOT NULL COMMENT '클래스 idx',
  `class_img` text NOT NULL,
  PRIMARY KEY (`image_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Class_img`
--

LOCK TABLES `Class_img` WRITE;
/*!40000 ALTER TABLE `Class_img` DISABLE KEYS */;
INSERT INTO `Class_img` VALUES (1,1,'http://1.1'),(2,1,'http://1.2'),(3,1,'http://1.3'),(4,1,'http://1.4'),(5,1,'http://1.5'),(6,2,'http://2.1'),(7,2,'http://2.2'),(8,2,'http://2.3'),(9,2,'http://2.4'),(10,2,'http://2.5'),(11,3,'http://3.1'),(12,3,'http://3.2'),(13,3,'http://3.3'),(14,3,'http://3.4'),(15,3,'http://3.5'),(16,4,'http://4.1'),(17,4,'http://4.2'),(18,4,'http://4.3'),(19,4,'http://4.4'),(20,4,'http://4.5'),(21,5,'http://5.1'),(22,5,'http://5.2'),(23,5,'http://5.3'),(24,5,'http://5.4'),(25,5,'http://5.5'),(26,6,'http://1.1'),(27,6,'http://1.2'),(28,6,'http://1.3'),(29,6,'http://1.4'),(30,6,'http://1.5');
/*!40000 ALTER TABLE `Class_img` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Class_review`
--

DROP TABLE IF EXISTS `Class_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Class_review` (
  `c_review_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '클래스 리뷰 인덱스',
  `class_idx` int(11) NOT NULL COMMENT '클래스 인덱스',
  `user_idx` int(11) NOT NULL COMMENT '사용자 인덱스',
  `is_satisfied` tinyint(1) NOT NULL COMMENT '만족/불만족 여부',
  `c_contents` varchar(45) NOT NULL COMMENT '리뷰 내용',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  PRIMARY KEY (`c_review_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='클래스 리뷰';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Class_review`
--

LOCK TABLES `Class_review` WRITE;
/*!40000 ALTER TABLE `Class_review` DISABLE KEYS */;
INSERT INTO `Class_review` VALUES (11,1,1,1,'굿','2020-08-31 14:41:52','2020-08-31 14:41:52','N'),(12,1,2,1,'다양한 의문들과 서로의 경험을 나누는것이 좋아요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(13,1,4,1,'너무 길지않은 1강 길이.','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(14,1,5,1,'하나하나의 예시를들어주셔서 좋습니다','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(15,1,7,0,'반품방법이 자세히 안나와있네요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(16,1,8,1,'정말 공감되는 얘기가 많네요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(17,4,12,1,'친절하고 자세해요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(18,4,9,1,'강사님 목소리가 좋아요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(19,4,1,0,'별로에요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(20,4,10,1,'수업도 좋고 수업자료도 좋아요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(21,4,2,1,'좋아요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(22,4,4,1,'대박','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(23,4,5,1,'굿','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(24,4,7,1,'최고','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(25,4,1,0,'별로에요','2020-08-31 14:42:44','2020-08-31 14:42:44','N'),(26,4,10,1,'수업도 좋고 수업자료도 좋아요','2020-08-31 14:42:45','2020-08-31 14:42:45','N'),(27,4,2,1,'좋아요','2020-08-31 14:42:45','2020-08-31 14:42:45','N'),(28,4,4,1,'대박','2020-08-31 14:42:45','2020-08-31 14:42:45','N'),(29,4,5,1,'굿','2020-08-31 14:42:45','2020-08-31 14:42:45','N'),(30,4,7,1,'최고','2020-08-31 14:42:45','2020-08-31 14:42:45','N');
/*!40000 ALTER TABLE `Class_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Community_comment`
--

DROP TABLE IF EXISTS `Community_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Community_comment` (
  `post_idx` int(11) NOT NULL COMMENT '글 인덱스',
  `user_idx` int(11) NOT NULL COMMENT '사용자 인덱스',
  `comment_contents` varchar(45) NOT NULL COMMENT '댓글 내용',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  `comment_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '댓글 인덱스',
  PRIMARY KEY (`comment_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='커뮤니티 글 댓글 테이블';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Community_comment`
--

LOCK TABLES `Community_comment` WRITE;
/*!40000 ALTER TABLE `Community_comment` DISABLE KEYS */;
INSERT INTO `Community_comment` VALUES (7,1,'반갑습니다!','2020-08-31 14:50:01','2020-08-31 14:50:01','N',1),(7,3,'안녕하세요 ㅎㅎ','2020-08-31 14:50:01','2020-08-31 14:50:01','N',2),(7,7,'ㅎㅇ','2020-08-31 14:50:01','2020-08-31 14:50:01','N',3),(7,4,'ㅋㅋㅋ','2020-08-31 14:50:01','2020-08-31 14:50:01','N',4),(8,15,'헐','2020-08-31 14:50:01','2020-08-31 14:50:01','N',5),(8,16,'대박','2020-08-31 14:50:01','2020-08-31 14:50:01','N',6),(8,10,'좋아요','2020-08-31 14:50:01','2020-08-31 14:50:01','N',7),(1,14,'반가워요~~~','2020-08-31 14:52:57','2020-08-31 14:52:57','N',8),(2,14,'알아서 잘 사용하세요','2020-08-31 14:53:19','2020-08-31 14:53:19','N',9),(4,14,'환불 안돼요~~~~','2020-08-31 14:53:53','2020-08-31 14:53:53','N',10),(6,14,'감사합니다!!','2020-08-31 14:53:53','2020-08-31 14:53:53','N',11),(10,9,'안녕','2020-08-31 14:54:36','2020-08-31 14:54:36','N',12),(10,11,'하이','2020-08-31 14:54:36','2020-08-31 14:54:36','N',13),(11,14,'반가워','2020-08-31 14:54:36','2020-08-31 14:54:36','N',14),(11,2,'ㅋㅋㅋㅋ','2020-08-31 14:54:36','2020-08-31 14:54:36','N',15),(12,12,'감사합니다!!','2020-08-31 14:54:50','2020-08-31 14:54:50','N',16),(13,12,'감사합니다!!','2020-08-31 14:55:13','2020-08-31 14:55:13','N',17),(14,12,'죄송합니다','2020-08-31 14:55:13','2020-08-31 14:55:13','N',18),(15,12,'ㅋㅋㅋ','2020-08-31 14:55:13','2020-08-31 14:55:13','N',19),(18,1,'안녕하세요~~`','2020-08-31 14:57:59','2020-08-31 14:57:59','N',20),(19,13,'저도 잘 모르겠네요. 죄송합니다.','2020-08-31 14:57:59','2020-08-31 14:57:59','N',21),(20,13,'아뇨','2020-08-31 14:57:59','2020-08-31 14:57:59','N',22),(20,9,'ㅠㅠㅠㅠ','2020-08-31 14:58:19','2020-08-31 14:58:19','N',23),(19,15,'알겠어요~~~','2020-08-31 14:58:47','2020-08-31 14:58:47','N',24),(24,2,'안녕2','2020-08-31 15:01:23','2020-08-31 15:01:23','N',25),(24,3,'안녕3','2020-08-31 15:01:23','2020-08-31 15:01:23','N',26),(24,4,'안녕4','2020-08-31 15:01:23','2020-08-31 15:01:23','N',27),(24,5,'안녕5','2020-08-31 15:01:24','2020-08-31 15:01:24','N',28),(24,7,'안녕7','2020-08-31 15:01:24','2020-08-31 15:01:24','N',29),(24,6,'안녕6','2020-08-31 15:01:24','2020-08-31 15:01:24','N',30),(24,8,'안녕8','2020-08-31 15:01:24','2020-08-31 15:01:24','N',31);
/*!40000 ALTER TABLE `Community_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Community_post`
--

DROP TABLE IF EXISTS `Community_post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Community_post` (
  `post_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '글 인덱스',
  `class_idx` int(11) NOT NULL COMMENT '클래스 인덱스',
  `user_idx` int(11) NOT NULL COMMENT '사용자 인덱스',
  `post_contents` varchar(45) NOT NULL COMMENT '내용',
  `post_photo` text COMMENT '사진첨부',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  PRIMARY KEY (`post_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='클래스별 커뮤니티 글 테이블';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Community_post`
--

LOCK TABLES `Community_post` WRITE;
/*!40000 ALTER TABLE `Community_post` DISABLE KEYS */;
INSERT INTO `Community_post` VALUES (1,1,1,'안녕하세요! 반갑습니다',NULL,'2020-08-31 14:44:58','2020-08-31 14:45:04','N'),(2,1,2,'코칭권 사용법 알려주세요\r\n',NULL,'2020-08-31 14:45:21','2020-08-31 14:45:21','N'),(3,1,3,'이거 이렇게 하는거 맞나요?','https://...','2020-08-31 14:46:08','2020-08-31 14:46:08','N'),(4,1,4,'환불하고싶어요~',NULL,'2020-08-31 14:46:23','2020-08-31 14:46:23','N'),(5,1,5,'안녕하세요! 유튜브랑 다른가요?',NULL,'2020-08-31 14:46:39','2020-08-31 14:46:39','N'),(6,1,6,'수업 잘 듣고있어요!','https://...','2020-08-31 14:48:07','2020-08-31 14:48:07','N'),(7,1,14,'안녕하세요, 신사임당입니다.',NULL,'2020-08-31 14:48:07','2020-08-31 14:48:07','N'),(8,1,14,'안녕하세요, 신사임당입니다.','https://...','2020-08-31 14:48:23','2020-08-31 14:48:23','N'),(9,2,12,'공지사항입니다.',NULL,'2020-08-31 14:52:26','2020-08-31 14:52:26','N'),(10,2,12,'안녕하세요 수강생 여려분들',NULL,'2020-08-31 14:52:26','2020-08-31 14:52:26','N'),(11,2,12,'안녕하세요 김영익소장 입니다.',NULL,'2020-08-31 14:52:26','2020-08-31 14:52:26','N'),(12,2,2,'더 빨리 오픈했으면 좋겠어요',NULL,'2020-08-31 14:52:26','2020-08-31 14:52:26','N'),(13,2,8,'기대됩니다',NULL,'2020-08-31 14:52:26','2020-08-31 14:52:26','N'),(14,2,14,'응원했는데 카톡 안옴 ㅠㅠ',NULL,'2020-08-31 14:52:26','2020-08-31 14:52:26','N'),(15,2,5,'오늘도 딱!!! 뿜뿜',NULL,'2020-08-31 14:52:26','2020-08-31 14:52:26','N'),(16,3,13,'김머신입니다.','https://...','2020-08-31 14:57:06','2020-08-31 14:57:06','N'),(17,3,13,'공지사항입니다.','https://...','2020-08-31 14:57:06','2020-08-31 14:57:06','N'),(18,3,10,'도움 많이 되요~~~',NULL,'2020-08-31 14:57:06','2020-08-31 14:57:06','N'),(19,3,15,'질문있어요 ㅠㅠ','https://...','2020-08-31 14:57:06','2020-08-31 14:57:06','N'),(20,3,9,'제가 들어도 될까요?',NULL,'2020-08-31 14:57:06','2020-08-31 14:57:06','N'),(21,5,11,'안녕하세요! 제이제이입니다.',NULL,'2020-08-31 15:00:50','2020-08-31 15:00:50','N'),(22,5,11,'안녕하세요 제이제이입니다.',NULL,'2020-08-31 15:00:50','2020-08-31 15:00:50','N'),(23,5,6,'지금 신청하면 되나요?',NULL,'2020-08-31 15:00:50','2020-08-31 15:00:50','N'),(24,5,1,'안녕 1',NULL,'2020-08-31 15:00:50','2020-08-31 15:00:50','N'),(25,6,10,'빨리 오픈하겠습니다!','https://...','2020-08-31 15:01:52','2020-08-31 15:01:52','N');
/*!40000 ALTER TABLE `Community_post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Component`
--

DROP TABLE IF EXISTS `Component`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Component` (
  `component_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '구성품 idx',
  `package_idx` int(11) NOT NULL COMMENT '패키지 idx',
  `component_name` varchar(50) NOT NULL COMMENT '구성품 명',
  `origin_price` int(11) NOT NULL DEFAULT '0' COMMENT '원가',
  `discount_rate` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '할인률',
  `component_thumb` text NOT NULL COMMENT '썸네일 링크',
  PRIMARY KEY (`component_idx`),
  KEY `FK_Component_package_idx_Package_package_idx` (`package_idx`),
  CONSTRAINT `FK_Component_package_idx_Package_package_idx` FOREIGN KEY (`package_idx`) REFERENCES `Package` (`package_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='패키지 구성품';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Component`
--

LOCK TABLES `Component` WRITE;
/*!40000 ALTER TABLE `Component` DISABLE KEYS */;
INSERT INTO `Component` VALUES (1,1,'신사임당 온라인 클래스 수강권 (20주)',199000,0.35,'https://...'),(2,2,'신사임당 온라인 클래스 수강권 (20주)',199000,0.50,'https://...'),(3,2,'신사임당 1:1 코칭권(1회)',173300,0.03,'https://...'),(4,3,'딱영어 온라인수강권 (20주)',149000,0.33,'https://...'),(5,3,'딱영어 1:1 코칭권 (2회)',169700,0.02,'https://...'),(6,4,'딱영어 온라인수강권 (20주)',149000,0.13,'https://...'),(7,5,'대학생 김머신 온라인 수강권 (20주)',450000,0.44,'https://...'),(8,5,'대학생 김머신 1:1 코칭권 (1회)',132600,0.03,'https://...'),(9,6,'대학생 김머신 온라인 수강권 (20주)',450000,0.32,'https://...'),(10,7,'둡 온라인 수강권 (20주)',298350,0.38,'https://...'),(11,7,'둡 1차 올인원 패키지',40330,0.02,'https://...'),(12,8,'제이제이살롱드핏 온라인 수강권 (20주)',309000,0.32,'https://...'),(13,9,'[예약판매] 제이제이살롱드핏 온라인 수강권 (24주)',309000,0.25,'https://...'),(14,9,'[예약판매] 제이제이 덤벨 + 플레이트세트 올인원 패키지',111000,0.25,'https://...'),(15,10,'초록스튜디오 온라인 수강권 (12주)',264000,0.55,'https://...'),(16,10,'초록스튜디오 올인원 키트',169000,0.06,'https://...'),(17,11,'초록스튜디오 온라인 수강권 (12주)',264000,0.55,'https://...'),(18,11,'초록스튜디오 베이직 키트',134600,0.07,'https://...'),(19,12,'초록스튜디오 온라인 수강권 (12주)',264000,0.55,'https://...'),(20,12,'초록스튜디오 디럭스 키트',260300,0.05,'https://...');
/*!40000 ALTER TABLE `Component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Coupon`
--

DROP TABLE IF EXISTS `Coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Coupon` (
  `coupon_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '쿠폰 인덱스',
  `coupon_name` varchar(45) NOT NULL COMMENT '쿠폰명',
  `coupon_price` varchar(45) NOT NULL COMMENT '할인금액',
  `due_date` date NOT NULL COMMENT '만료날짜',
  `coupon_ctg` varchar(45) DEFAULT NULL COMMENT '카테고리',
  `class_idx` int(11) DEFAULT NULL COMMENT '클래스 인덱스(여기서만 사용가능한 쿠폰)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  `user_idx` int(11) NOT NULL COMMENT '사용자 인덱스',
  `is_used` char(1) NOT NULL DEFAULT 'N' COMMENT '사용여부',
  PRIMARY KEY (`coupon_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='쿠폰 테이블';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Coupon`
--

LOCK TABLES `Coupon` WRITE;
/*!40000 ALTER TABLE `Coupon` DISABLE KEYS */;
INSERT INTO `Coupon` VALUES (1,'[8월 신규회원 혜택]미술 20,000원 할인','20000','2020-09-05','미술',NULL,'2020-08-31 15:56:20','2020-08-31 15:56:20','N',1,'N'),(2,'[8월 신규회원 혜택]운동 20,000원 할인','20000','2020-09-05','운동',NULL,'2020-08-31 15:58:12','2020-08-31 15:58:12','N',1,'N'),(3,'[8월 신규회원 혜택]공예 20,000원 할인 쿠폰','20000','2020-09-05','공예',NULL,'2020-08-31 15:58:12','2020-08-31 15:58:12','N',1,'N'),(4,'[8월 신규회원 혜택]미술 20,000원 할인','20000','2020-09-05','미술',NULL,'2020-08-31 16:03:19','2020-08-31 16:03:19','N',2,'N'),(5,'[8월 신규회원 혜택]운동 20,000원 할인','20000','2020-09-05','운동',NULL,'2020-08-31 16:03:19','2020-08-31 16:03:19','N',2,'N'),(6,'[8월 신규회원 혜택]공예 20,000원 할인 쿠폰','20000','2020-09-05','공예',NULL,'2020-08-31 16:03:19','2020-08-31 16:03:19','N',2,'N'),(7,'[8월 신규회원 혜택]미술 20,000원 할인','20000','2020-09-05','미술',NULL,'2020-08-31 16:03:19','2020-08-31 16:03:19','N',3,'N'),(8,'[8월 신규회원 혜택]미술 20,000원 할인','20000','2020-09-05','미술',NULL,'2020-08-31 16:03:19','2020-08-31 16:03:19','N',3,'N');
/*!40000 ALTER TABLE `Coupon` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Creator`
--

DROP TABLE IF EXISTS `Creator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Creator` (
  `creator_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '크리에이터 인덱스',
  `user_idx` int(11) NOT NULL COMMENT '사용자 인덱스',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  PRIMARY KEY (`creator_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='크리에이터 테이블';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Creator`
--

LOCK TABLES `Creator` WRITE;
/*!40000 ALTER TABLE `Creator` DISABLE KEYS */;
INSERT INTO `Creator` VALUES (1,14,'2020-06-30 11:56:25','2020-08-31 11:56:25','N'),(2,12,'2020-07-20 12:00:54','2020-08-31 12:00:54','N'),(3,13,'2020-08-31 12:12:02','2020-08-31 12:12:02','N'),(4,9,'2020-08-31 12:14:39','2020-08-31 12:14:39','N'),(5,11,'2020-08-31 12:21:58','2020-08-31 12:21:58','N'),(6,10,'2020-08-31 12:24:18','2020-08-31 12:24:18','N');
/*!40000 ALTER TABLE `Creator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Delivery`
--

DROP TABLE IF EXISTS `Delivery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Delivery` (
  `delivery_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '배송 idx',
  `purchase_idx` int(11) NOT NULL COMMENT '구매 idx',
  `component_idx` int(11) NOT NULL COMMENT '구성품idx',
  `delivery_status` varchar(20) NOT NULL COMMENT '배송 상태',
  `address` varchar(100) NOT NULL COMMENT '배송지',
  `user_request` varchar(50) DEFAULT NULL COMMENT '배송 요청사항',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`delivery_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='배';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Delivery`
--

LOCK TABLES `Delivery` WRITE;
/*!40000 ALTER TABLE `Delivery` DISABLE KEYS */;
/*!40000 ALTER TABLE `Delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Likes`
--

DROP TABLE IF EXISTS `Likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Likes` (
  `seleted_idx` int(11) NOT NULL COMMENT '클래스 인덱스/상품 인덱스',
  `idx_type` varchar(45) NOT NULL COMMENT '인덱스 타입',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `user_idx` int(11) NOT NULL COMMENT '사용자 인덱스',
  `likes_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '찜 인덱스',
  PRIMARY KEY (`likes_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='찜 테이블';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Likes`
--

LOCK TABLES `Likes` WRITE;
/*!40000 ALTER TABLE `Likes` DISABLE KEYS */;
INSERT INTO `Likes` VALUES (1,'class','2020-08-31 15:03:19','N','2020-08-31 15:03:19',1,1),(1,'product','2020-08-31 15:03:46','N','2020-08-31 15:03:46',1,2),(2,'class','2020-08-31 15:04:03','N','2020-08-31 15:04:03',1,3),(3,'class','2020-08-31 15:04:03','N','2020-08-31 15:04:03',1,4),(4,'class','2020-08-31 15:04:03','N','2020-08-31 15:04:03',1,5),(5,'class','2020-08-31 15:04:03','N','2020-08-31 15:04:03',1,6),(3,'product','2020-08-31 15:04:19','N','2020-08-31 15:04:19',1,7),(4,'product','2020-08-31 15:04:19','N','2020-08-31 15:04:19',1,8),(5,'product','2020-08-31 15:04:19','N','2020-08-31 15:04:19',1,9),(4,'class','2020-08-31 15:04:48','N','2020-08-31 15:04:48',2,10),(10,'product','2020-08-31 15:04:48','N','2020-08-31 15:04:48',2,11),(6,'class','2020-08-31 15:05:10','N','2020-08-31 15:05:10',4,12),(8,'product','2020-08-31 15:05:10','N','2020-08-31 15:05:10',4,13),(10,'product','2020-08-31 15:05:10','N','2020-08-31 15:05:10',4,14),(2,'class','2020-08-31 15:05:37','N','2020-08-31 15:05:37',6,15),(4,'class','2020-08-31 15:05:37','N','2020-08-31 15:05:37',6,16),(5,'class','2020-08-31 15:05:37','N','2020-08-31 15:05:37',6,17),(1,'product','2020-08-31 15:05:37','N','2020-08-31 15:05:37',6,18),(1,'class','2020-08-31 15:07:59','N','2020-08-31 15:07:59',14,19);
/*!40000 ALTER TABLE `Likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Not_opened_class`
--

DROP TABLE IF EXISTS `Not_opened_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Not_opened_class` (
  `class_idx` int(11) NOT NULL COMMENT '클래스 idx',
  `cheer_goal` int(11) NOT NULL COMMENT '목표 응원 수',
  `cheer_count` int(11) NOT NULL COMMENT '응원 수',
  PRIMARY KEY (`class_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Not_opened_class`
--

LOCK TABLES `Not_opened_class` WRITE;
/*!40000 ALTER TABLE `Not_opened_class` DISABLE KEYS */;
INSERT INTO `Not_opened_class` VALUES (2,200,210),(5,100,94),(6,250,270);
/*!40000 ALTER TABLE `Not_opened_class` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Package`
--

DROP TABLE IF EXISTS `Package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Package` (
  `package_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '패키지 idx',
  `class_idx` int(11) NOT NULL COMMENT '클래스 idx',
  `package_name` varchar(50) NOT NULL COMMENT '패키지 명',
  `discount_rate` decimal(3,2) NOT NULL DEFAULT '0.00' COMMENT '할인률',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '재고',
  `package_thumb` text NOT NULL COMMENT '썸네일 링크',
  `installment_month` int(11) NOT NULL COMMENT '할부 개월 수',
  PRIMARY KEY (`package_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='클래스의 패키지';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Package`
--

LOCK TABLES `Package` WRITE;
/*!40000 ALTER TABLE `Package` DISABLE KEYS */;
INSERT INTO `Package` VALUES (1,1,'신사임당 수강권 Only',0.35,100,'http://...',5),(2,1,'신사임당 코칭 패키지',0.28,0,'http://...',5),(3,2,'딱영어 코칭패키지',0.17,100,'http://...',5),(4,2,'딱영어 수강권Only',0.13,100,'http://...',5),(5,3,'대학생 김머신 코칭 패키지',0.34,10,'http://...',5),(6,3,'대학생 김머신 수강권 only',0.32,2,'http://...',5),(7,4,'[선착순] 준비물 0원 패키지',0.33,100,'http://...',5),(8,5,'[슈퍼 얼리버드] 수강권 Only',0.32,100,'http://...',5),(9,5,'[슈퍼 얼리버드] 제이제이 홈트레이닝 패키지',0.25,100,'http://...',5),(10,6,'[슈퍼 얼리버드] 초록스튜디오 올인원 패키지',0.35,100,'http://...',3),(11,6,'[슈퍼 얼리버드] 초록스튜디오 베이직 패키지',0.38,100,'http://...',3),(12,6,'[슈퍼 얼리버드] 초록스튜디오 디럭스 패키지',0.30,100,'http://...',3);
/*!40000 ALTER TABLE `Package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Product`
--

DROP TABLE IF EXISTS `Product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product` (
  `product_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '상품 인덱스',
  `product_name` varchar(45) NOT NULL COMMENT '상품 이름',
  `product_thumb` text COMMENT '상품 썸네일',
  `product_ctg` varchar(45) NOT NULL COMMENT '상품 카테고리',
  `product_price` int(11) NOT NULL COMMENT '상품 가격',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  `discount_rate` int(11) DEFAULT NULL COMMENT '할인률',
  `seller` varchar(45) NOT NULL COMMENT '판매업체',
  `share_url` text COMMENT '공유링크',
  `installment` int(11) DEFAULT '0',
  PRIMARY KEY (`product_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='store에서 판매되는 상품들 table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Product`
--

LOCK TABLES `Product` WRITE;
/*!40000 ALTER TABLE `Product` DISABLE KEYS */;
INSERT INTO `Product` VALUES (1,'DIY 꽃잎 수채화 컬러링 키트','http://...','EASY DIY',25000,'2020-08-31 14:30:11','2020-08-31 14:30:11','N',8,'버드인페이지','https://class101.net/products/vCIG1Q5qfPv34kctv9L7',0),(2,'DIY 미니어쳐 미니벽돌 벽난로 키트','http://...','EASY DIY',24900,'2020-08-31 14:33:14','2020-08-31 14:33:14','N',0,'리얼브릭','https://class101.net/products/91XV47DJl7OWdkKvXAnp',0),(3,'이렇게 멋진 수채화 키트,어때요?','http://...','미술재료',45000,'2020-08-31 14:37:21','2020-08-31 14:37:21','N',0,'클래스101','https://class101.net/products/AzlrVoz18raGYZhvKrVx',0),(4,'작고 쉽게 그려요, 수채화 미니 파레트','http://...','미술재료',19000,'2020-08-31 14:39:35','2020-08-31 14:39:35','N',15,'클래스101','https://class101.net/products/pSOyGMWotae4DUax3Uq7',0),(5,'가격 절약 아이패드 특가전','http://...','디지털기기/ACC',165000,'2020-08-31 14:46:17','2020-08-31 14:46:17','N',0,'클래스101','https://class101.net/products/49Hh90jgnhi4jFyir3wM',5),(6,'New iPad Pro+취미 자유이용권+선착순 반값 애플펜슬!','http://...','디지털기기/ACC',3335900,'2020-08-31 14:55:36','2020-08-31 14:55:36','N',36,'클래스101','https://class101.net/products/rmocLpmxjZ8PbPaptCaC',12),(7,'롤 티슈 커버 만들기','http://...','공예재료',22900,'2020-08-31 14:57:14','2020-08-31 14:57:14','N',0,'올라라탄','https://class101.net/products/Jz4spdvTJX9rpZ5Hs7RH',0),(8,'룹이 추천하는 사랑스런 색조합의 양말뜨기 실 SET','http://...','공예재료',15000,'2020-08-31 14:58:34','2020-08-31 14:58:34','N',0,'클래스101','https://class101.net/products/88JLLS6kWBaniysrxQFL',0),(9,'솔쌤 별쌤의 체형교정 홈 필라테스 도구 \"뷰릿벨트\"','http://...','헬스/뷰티/ACC',72000,'2020-08-31 15:00:39','2020-08-31 15:00:39','N',0,'별쌤&솔쌤','https://class101.net/products/DVmiUEggNRw09z4zO3i9',0),(10,'솔쌤 별쌤의 체형교정 필라테스도구 \'뷰릿\'','http://...','헬스/뷰티/ACC',112000,'2020-08-31 15:02:06','2020-08-31 15:02:06','N',12,'별쌤&솔쌤','https://class101.net/products/HHGRzJr7godgxTVdYexB',0),(11,'크리에이터가 선택한 루아루/네코즈 칼림바 4종,최대 22%할인','http://...','악기/음악',45000,'2020-08-31 15:06:01','2020-08-31 15:06:01','N',22,'클래스101','https://class101.net/products/bYTvde9T8Qhqm0egZ17W',0);
/*!40000 ALTER TABLE `Product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Product_option`
--

DROP TABLE IF EXISTS `Product_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product_option` (
  `product_idx` int(11) NOT NULL COMMENT '상품 인덱스',
  `option_name` varchar(45) NOT NULL COMMENT '옵션 이름',
  `option_thumb` varchar(45) NOT NULL COMMENT '옵션 썸네일',
  `option_price` int(11) NOT NULL COMMENT '가격',
  `discount_rate` int(11) DEFAULT '0' COMMENT '할인률',
  `compo` varchar(45) DEFAULT NULL COMMENT '구성품',
  `option_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '옵션 인덱스',
  `installment` int(11) DEFAULT '0',
  PRIMARY KEY (`option_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Product_option`
--

LOCK TABLES `Product_option` WRITE;
/*!40000 ALTER TABLE `Product_option` DISABLE KEYS */;
INSERT INTO `Product_option` VALUES (5,'New iPad 11+자유이용권패키지','http://..',3335900,36,'[자유이용권]+애플펜슬(2세대)',1,12),(5,'New iPad 12.9+자유이용권 패키지','http://..',3605900,33,'[자유이용권]+애플펜슬(2세대)',2,12),(6,'iPad Pro 11형 3세대 Wifi 256GB(스페이스그레이)','http://..',1199000,17,NULL,3,0),(6,'iPad Pro 11형 3세대 Wifi 64GB(스페이스그레이)','http://..',1199000,17,NULL,4,0),(8,'뷰릿 자세교정 벨트S-M','http://..',72000,0,NULL,5,0),(8,'뷰릿 자세교정 벨트L','http://..',72000,0,NULL,6,0),(9,'[스토어101]뷰릿 홈트 세트','http://..',112000,12,'베이직바+튜빙밴드',7,0);
/*!40000 ALTER TABLE `Product_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Product_review`
--

DROP TABLE IF EXISTS `Product_review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Product_review` (
  `p_review_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '상품 리뷰 인덱스',
  `product_idx` int(11) NOT NULL COMMENT '상품 인덱스',
  `user_idx` int(11) NOT NULL COMMENT '사용자 인덱스',
  `p_contents` varchar(100) NOT NULL COMMENT '리뷰 내용',
  `star` int(11) NOT NULL COMMENT '리뷰 별점',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  `help_count` int(11) NOT NULL DEFAULT '0' COMMENT '도움수',
  PRIMARY KEY (`p_review_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='상품 리뷰';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Product_review`
--

LOCK TABLES `Product_review` WRITE;
/*!40000 ALTER TABLE `Product_review` DISABLE KEYS */;
INSERT INTO `Product_review` VALUES (1,1,1,'설명이 각 페이지마다 자세히 되어있어서 미니레슨카드만 보고도 충분히 잘 따라갈 수 있었습니다!',5,'2020-08-31 15:16:41','2020-08-31 15:16:41','N',1),(2,2,2,'처음 구매하고 만들어본건데 설명서도 잘되어있고 초보자도 하기 쉬운듯합니다. 조금 더 큰 사이즈로 나와도 만드는데 재밌을거 같아요',5,'2020-08-31 15:19:29','2020-08-31 15:19:29','N',2),(3,10,3,'활용도가 너무 좋아서 잘 산거 같아요~',5,'2020-08-31 15:28:58','2020-08-31 15:28:58','N',0);
/*!40000 ALTER TABLE `Product_review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Purchase_package`
--

DROP TABLE IF EXISTS `Purchase_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Purchase_package` (
  `pkg_purchase_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '구매 idx',
  `package_idx` int(11) NOT NULL COMMENT '상품 idx',
  `user_idx` int(11) NOT NULL COMMENT '사용자 idx',
  `user_phone` varchar(45) NOT NULL COMMENT '사용자 전화번호',
  `coupon_idx` int(11) DEFAULT NULL COMMENT '어떤 쿠폰 사용했는지',
  `payment_type` varchar(10) NOT NULL COMMENT '결제수단',
  `discount` int(11) NOT NULL DEFAULT '0' COMMENT '????',
  `delivery_price` int(11) NOT NULL DEFAULT '0' COMMENT '배송료',
  `origin_price` int(11) NOT NULL DEFAULT '0' COMMENT '원가',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`pkg_purchase_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Purchase_package`
--

LOCK TABLES `Purchase_package` WRITE;
/*!40000 ALTER TABLE `Purchase_package` DISABLE KEYS */;
INSERT INTO `Purchase_package` VALUES (1,1,1,'01011224897',NULL,'카드',1500,0,199000,'2020-07-31 15:50:27','2020-07-31 15:50:27','N'),(2,2,1,'01011224897',NULL,'카드',1500,0,372300,'2020-08-31 15:52:22','2020-08-31 15:52:22','N'),(3,3,2,'01022554499',NULL,'카드',0,1000,318700,'2020-08-31 15:57:06','2020-08-31 15:57:06','N'),(4,4,2,'01022554499',NULL,'카드',0,0,149000,'2020-08-31 15:58:06','2020-08-31 15:58:06','N'),(5,5,3,'01099998888',NULL,'카드',1500,1000,582600,'2020-08-31 16:01:29','2020-08-31 16:01:29','N'),(6,6,3,'01099998888',NULL,'카드',1500,0,450000,'2020-08-31 16:02:12','2020-08-31 16:02:12','N');
/*!40000 ALTER TABLE `Purchase_package` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Purchase_product`
--

DROP TABLE IF EXISTS `Purchase_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Purchase_product` (
  `prod_purchase_idx` int(11) NOT NULL AUTO_INCREMENT,
  `product_idx` int(11) NOT NULL,
  `user_idx` int(11) NOT NULL,
  `user_phone` varchar(45) NOT NULL,
  `coupon_idx` int(11) NOT NULL,
  `payment_type` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `delivery_price` int(11) NOT NULL,
  `origin_price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`prod_purchase_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Purchase_product`
--

LOCK TABLES `Purchase_product` WRITE;
/*!40000 ALTER TABLE `Purchase_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `Purchase_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Review_photos`
--

DROP TABLE IF EXISTS `Review_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Review_photos` (
  `p_review_idx` int(11) NOT NULL COMMENT '상품 리뷰 인덱스',
  `photo` text NOT NULL COMMENT '리뷰 사진',
  `photo_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '사진 인덱스',
  PRIMARY KEY (`photo_idx`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='product review table에 필요한 사진';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Review_photos`
--

LOCK TABLES `Review_photos` WRITE;
/*!40000 ALTER TABLE `Review_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `Review_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `user_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '사용자 인덱스',
  `user_email` varchar(45) NOT NULL COMMENT '사용자 이메일',
  `user_name` varchar(45) NOT NULL COMMENT '사용자 이름',
  `user_phone` varchar(45) NOT NULL COMMENT '사용자 번호',
  `user_pwd` varchar(45) NOT NULL COMMENT '비밀번호',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '생성',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '수정',
  `is_deleted` char(1) NOT NULL DEFAULT 'N' COMMENT '삭제',
  `profile_img` text COMMENT '사용자 프로필사진',
  `membership` varchar(45) DEFAULT NULL COMMENT '멤버쉽',
  `nickname` varchar(45) NOT NULL COMMENT '닉네임',
  PRIMARY KEY (`user_idx`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='사용자 table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'asdf@naver.com','안녕','01011224897','1234','2020-08-31 14:30:33','2020-08-31 14:30:33','N','https://...','일반','안녕'),(2,'zxcv@naver.com','하이','01022554499','1234','2020-08-31 14:32:23','2020-08-31 14:32:23','N','https://...','일반','하이'),(3,'qwer@naver.com','치킨','01099998888','1234','2020-08-31 14:32:23','2020-08-31 14:32:23','N','https://...','일반','치킨'),(4,'1234321@naver.com','피자','01022554466','1234','2020-08-31 14:32:23','2020-08-31 14:32:23','N','https://...','일반','피자'),(5,'40523@naver.com','삼겹살','01022223333','1234','2020-08-31 14:32:23','2020-08-31 14:32:23','N','https://...','일반','삼겹살'),(6,'fdsaklj@gmail.com','초밥','01000001111','1234','2020-08-31 14:32:23','2020-08-31 14:32:23','N','https://','일반','초밥'),(7,'fjsdkla@gmail.com','우유','01022223333','1234','2020-08-31 14:32:23','2020-08-31 14:32:23','N','https://...','일반','우유'),(8,'jklg@naver.com','커피','01000001111','1234','2020-08-31 14:32:23','2020-08-31 14:32:23','N','https://...','일반','커피'),(9,'doop@gmail.com','둡','01011223344','1234','2020-08-31 12:14:23','2020-08-31 12:14:23','N','https://...','일반','둡'),(10,'green@naevr.com','초록','01098789878','1234','2020-08-31 12:24:03','2020-08-31 12:24:03','N','https://...','일반','초록스튜디오'),(11,'jj@naver.com','제이제이','01022554499','1234','2020-08-31 12:21:43','2020-08-31 12:21:43','N','https://...','일반','제이제이살롱드핏'),(12,'kim0ick@gmail.com','김영익','01099998888','1234','2020-08-31 12:00:31','2020-08-31 12:00:31','N','https://...','일반','딱영어 김영익 소장'),(13,'machinekim@naver.com','김머신','01022554466','1234','2020-08-31 12:11:49','2020-08-31 12:11:49','N','https://...','일반','김머신'),(14,'sinsaimdang@naver.com','신사임당','01022223333','1234','2020-08-31 11:54:13','2020-08-31 11:54:13','N','https://...','일반','신사임당'),(15,'test2@email.com','테스트2','01000001111','1234','2020-08-31 11:52:43','2020-08-31 11:52:43','N','https://','일반','테스트'),(16,'test@email.com','테스트','01012341234','1234','2020-08-31 11:51:46','2020-08-31 11:51:46','N','https://...','일반','테스트');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-08-31 16:52:46
