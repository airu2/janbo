-- --------------------------------------------------------
-- ホスト:                          192.168.1.155
-- サーバーのバージョン:                   8.0.11 - MySQL Community Server - GPL
-- サーバー OS:                      Win64
-- HeidiSQL バージョン:               9.3.0.5120
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- pokemon のデータベース構造をダンプしています
CREATE DATABASE IF NOT EXISTS `pokemon` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pokemon`;

--  テーブル pokemon.ms_haihu の構造をダンプしています
CREATE TABLE IF NOT EXISTS `ms_haihu` (
  `USERID` varchar(25) NOT NULL,
  `HAIHU_CODE` varchar(20) NOT NULL,
  `COMMENT` varchar(100) DEFAULT NULL,
  `TOUROKU_DATE` datetime NOT NULL,
  PRIMARY KEY (`HAIHU_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='牌譜コードテーブル';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.ms_member の構造をダンプしています
CREATE TABLE IF NOT EXISTS `ms_member` (
  `USERID` varchar(25) NOT NULL COMMENT 'ユーザＩＤ',
  `PASSWORD` varchar(50) NOT NULL COMMENT 'パスワード',
  `USER_NAME` varchar(50) NOT NULL COMMENT 'ユーザ名',
  `USER_KANA` varchar(50) DEFAULT NULL COMMENT 'ユーザカナ',
  `MEMBER_KBN` tinyint(1) NOT NULL COMMENT '会員区分',
  `RANK` tinyint(1) NOT NULL COMMENT '所属リーグ',
  `RATING` smallint(5) NOT NULL COMMENT 'レーティング',
  `DEL_FLG` tinyint(2) NOT NULL DEFAULT '1' COMMENT '削除フラグ',
  `LOGIN_DATE` datetime DEFAULT NULL COMMENT '最終ログイン日時',
  `TOUROKU_DATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登録日時',
  KEY `USERID` (`USERID`,`USER_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会員管理テーブル';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.ms_tournament の構造をダンプしています
CREATE TABLE IF NOT EXISTS `ms_tournament` (
  `TOURNAMENT_ID` int(10) NOT NULL COMMENT '大会ID',
  `TOURNAMENT_NAME` varchar(100) NOT NULL COMMENT '大会名称',
  `TOURNAMENT_FLG` tinyint(1) NOT NULL COMMENT '大会フラグ',
  `CLOSE_DATE` varchar(10) DEFAULT NULL COMMENT '大会終了日',
  PRIMARY KEY (`TOURNAMENT_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='大会管理テーブル';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.un_log の構造をダンプしています
CREATE TABLE IF NOT EXISTS `un_log` (
  `DAYTIME` datetime NOT NULL COMMENT '日時',
  `DAYTIMESEC` varchar(100) NOT NULL COMMENT '日時ミリ秒（ソート用）',
  `USERID` varchar(50) NOT NULL COMMENT '操作ユーザID',
  `USER_NAME` varchar(50) NOT NULL COMMENT '操作ユーザ名',
  `HOST_NAME` varchar(255) DEFAULT NULL COMMENT 'ホスト名',
  `DISPLAY` varchar(50) NOT NULL COMMENT '画面名',
  `COLFLG` tinyint(2) NOT NULL COMMENT '操作内容表示色',
  `CONTENT` varchar(1000) NOT NULL COMMENT '操作内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作履歴';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.un_ratecalc の構造をダンプしています
CREATE TABLE IF NOT EXISTS `un_ratecalc` (
  `CALC_FLG` tinyint(2) DEFAULT NULL COMMENT '計算フラグ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='レート計算フラグ';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.un_score の構造をダンプしています
CREATE TABLE IF NOT EXISTS `un_score` (
  `RUI_NO` int(10) NOT NULL COMMENT '累積対局NO',
  `NO` int(10) NOT NULL COMMENT '月間対局NO',
  `PLAY_KBN` tinyint(3) NOT NULL COMMENT 'プレイ区分',
  `RANKING` tinyint(1) NOT NULL COMMENT '対局順位',
  `USER_NAME` varchar(50) NOT NULL COMMENT 'ユーザ名',
  `SCORE` int(7) NOT NULL COMMENT 'スコア',
  `GAME_SCORE` float NOT NULL COMMENT 'ゲームスコア',
  `LP` int(10) NOT NULL COMMENT 'リーグポイント',
  `LPSCORE` float NOT NULL COMMENT '対局評点',
  `FIGHTCLUB_SCORE` float NOT NULL COMMENT 'ファイトクラブスコア',
  `USERID` varchar(25) NOT NULL COMMENT 'スコア登録ユーザID',
  `FIRST_SCORE` mediumint(5) NOT NULL COMMENT '原点',
  `RES_SCORE` mediumint(5) NOT NULL COMMENT '返し点',
  `GAME_DATE` date NOT NULL COMMENT '対局日付',
  UNIQUE KEY `RUI_NO` (`RUI_NO`,`RANKING`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='累積対局結果テーブル';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.un_tournament の構造をダンプしています
CREATE TABLE IF NOT EXISTS `un_tournament` (
  `RUI_NO` int(10) NOT NULL COMMENT '累積対局NO',
  `TOURNAMENT_ID` int(10) NOT NULL COMMENT '大会ID',
  `NUM` smallint(3) NOT NULL COMMENT '第○会戦目',
  `PLAY_KBN` tinyint(1) NOT NULL COMMENT 'プレイ区分',
  `RANKING` tinyint(1) NOT NULL COMMENT '対局順位',
  `USER_NAME` varchar(50) NOT NULL COMMENT 'ユーザ名',
  `SCORE` int(7) NOT NULL COMMENT 'スコア',
  `GAME_SCORE` float NOT NULL COMMENT 'ゲームスコア',
  `LP` int(10) NOT NULL COMMENT 'リーグポイント',
  `LPSCORE` float NOT NULL COMMENT '対局評点',
  `FIGHTCLUB_SCORE` float NOT NULL COMMENT 'ファイトクラブスコア',
  `USERID` varchar(25) NOT NULL COMMENT 'スコア登録ユーザID',
  `FIRST_SCORE` mediumint(5) NOT NULL COMMENT '原点',
  `RES_SCORE` mediumint(5) NOT NULL COMMENT '返し点',
  `COMMENT` varchar(100) DEFAULT NULL COMMENT 'コメント',
  `GAME_DATE` date NOT NULL COMMENT '対局日付',
  UNIQUE KEY `RUI_NO` (`RUI_NO`,`RANKING`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='大会対局結果テーブル';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.wk_confrontation の構造をダンプしています
CREATE TABLE IF NOT EXISTS `wk_confrontation` (
  `C_USERID` varchar(25) NOT NULL COMMENT '作成者',
  `RUI_NO` int(10) NOT NULL COMMENT '累計NO',
  `USER_NAME` varchar(50) NOT NULL COMMENT '対戦者',
  `WIN` int(10) NOT NULL COMMENT '勝数',
  `LOSE` int(10) NOT NULL COMMENT '負数',
  `C_TIME` datetime NOT NULL COMMENT '作成日時',
  UNIQUE KEY `C_USERID` (`C_USERID`,`RUI_NO`,`USER_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='対決結果テーブル';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.wk_onetime_pwd の構造をダンプしています
CREATE TABLE IF NOT EXISTS `wk_onetime_pwd` (
  `USERID` varchar(25) NOT NULL COMMENT 'ユーザID',
  `MEMBER_KBN` tinyint(1) NOT NULL COMMENT '区分',
  `PASSWORD` varchar(100) NOT NULL COMMENT 'パスワード',
  `YUKO_TIME` datetime NOT NULL COMMENT '有効期限',
  PRIMARY KEY (`USERID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ワンタイムパスワード';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.wk_performance の構造をダンプしています
CREATE TABLE IF NOT EXISTS `wk_performance` (
  `C_USERID` varchar(25) NOT NULL COMMENT '要求ユーザID',
  `USER_NAME` varchar(50) NOT NULL COMMENT 'ユーザ名',
  `RANK` tinyint(1) NOT NULL COMMENT 'ランク',
  `NUM` int(10) NOT NULL COMMENT '対局数',
  `SORT_JUN` tinyint(1) NOT NULL COMMENT 'ソート順',
  `AVG_JUN` float NOT NULL COMMENT '平均順位',
  `NO1` mediumint(5) NOT NULL COMMENT '1位回数',
  `NO2` mediumint(5) NOT NULL COMMENT '2位回数',
  `NO3` mediumint(5) NOT NULL COMMENT '3位回数',
  `NO4` mediumint(5) NOT NULL COMMENT '4位回数',
  `GAME_SCORE` float NOT NULL COMMENT 'ゲームスコア',
  `LP` int(10) NOT NULL COMMENT 'リーグポイント',
  `LPSCORE` float NOT NULL COMMENT '対局評点',
  `FIGHTCLUB_SCORE` float NOT NULL COMMENT 'ファイトクラブスコア',
  `FROM` date NOT NULL COMMENT '集計期間（ＦＲＯＭ）',
  `TO` date NOT NULL COMMENT '集計期間（ＴＯ）',
  `C_TIME` datetime NOT NULL COMMENT '作成日時',
  UNIQUE KEY `RUI_NO` (`C_USERID`,`USER_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='成績一時保存テーブル';

-- エクスポートするデータが選択されていません
--  テーブル pokemon.wk_tournamentsub の構造をダンプしています
CREATE TABLE IF NOT EXISTS `wk_tournamentsub` (
  `C_USERID` varchar(25) NOT NULL COMMENT '作成者ID',
  `SORT_JUN` int(11) NOT NULL COMMENT 'ソート順',
  `USER_NAME` varchar(50) NOT NULL COMMENT '対局者',
  `C_TIME` datetime NOT NULL COMMENT '作成日時',
  UNIQUE KEY `C_USERID` (`C_USERID`,`USER_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='大会結果抽出テーブル';

-- エクスポートするデータが選択されていません
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
