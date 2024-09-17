-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июн 17 2024 г., 00:48
-- Версия сервера: 8.0.37-0ubuntu0.20.04.3
-- Версия PHP: 7.4.3-4ubuntu2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pub`
--

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_articles`
--

CREATE TABLE `wp_ab_articles` (
  `ID_Article` smallint NOT NULL,
  `Title` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ID_Issue` smallint NOT NULL,
  `PageFrom` smallint NOT NULL,
  `PageTo` smallint NOT NULL,
  `DOI` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `TitleAlt` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Keywords` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ID_Conference` smallint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_articles_links`
--

CREATE TABLE `wp_ab_articles_links` (
  `ID_Link` int NOT NULL,
  `ID_Article` smallint NOT NULL,
  `ID_Author` smallint NOT NULL,
  `SeqNo` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_authors`
--

CREATE TABLE `wp_ab_authors` (
  `ID_Author` smallint NOT NULL,
  `Surname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Initials` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `RSCI` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Scopus` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_conferences`
--

CREATE TABLE `wp_ab_conferences` (
  `ID_Conference` smallint NOT NULL,
  `Title` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Country` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `City` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `DateFrom` date NOT NULL,
  `DateTo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_gratz`
--

CREATE TABLE `wp_ab_gratz` (
  `ID_Gratz` smallint NOT NULL,
  `TextRus` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `TextEng` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `DateTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_hides`
--

CREATE TABLE `wp_ab_hides` (
  `ID` int UNSIGNED NOT NULL,
  `Title` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_issues`
--

CREATE TABLE `wp_ab_issues` (
  `ID_Issue` smallint NOT NULL,
  `ID_Journal` smallint NOT NULL,
  `Year` smallint NOT NULL,
  `VolumeNo` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `IssueNo` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_journals`
--

CREATE TABLE `wp_ab_journals` (
  `ID_Journal` smallint NOT NULL,
  `Title` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `City` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Publisher` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Indexing` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_programs`
--

CREATE TABLE `wp_ab_programs` (
  `ID_Program` smallint NOT NULL,
  `RegNo` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `TitleRus` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `TitleEng` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Year` smallint NOT NULL,
  `Owner` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_programs_links`
--

CREATE TABLE `wp_ab_programs_links` (
  `ID_Link` int NOT NULL,
  `ID_Program` smallint NOT NULL,
  `ID_Author` smallint NOT NULL,
  `SeqNo` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_templates`
--

CREATE TABLE `wp_ab_templates` (
  `ID_Template` tinyint NOT NULL,
  `Title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Format` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Type` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_ab_twins`
--

CREATE TABLE `wp_ab_twins` (
  `ID_Twin` int NOT NULL,
  `ID_Main` smallint NOT NULL,
  `ID_Alias` smallint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_aryo_activity_log`
--

CREATE TABLE `wp_aryo_activity_log` (
  `histid` int NOT NULL,
  `user_caps` varchar(70) NOT NULL DEFAULT 'guest',
  `action` varchar(255) NOT NULL,
  `object_type` varchar(255) NOT NULL,
  `object_subtype` varchar(255) NOT NULL DEFAULT '',
  `object_name` varchar(255) NOT NULL,
  `object_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `hist_ip` varchar(55) NOT NULL DEFAULT '127.0.0.1',
  `hist_time` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_commentmeta`
--

CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_comments`
--

CREATE TABLE `wp_comments` (
  `comment_ID` bigint UNSIGNED NOT NULL,
  `comment_post_ID` bigint UNSIGNED NOT NULL DEFAULT '0',
  `comment_author` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_author_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment_karma` int NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_links`
--

CREATE TABLE `wp_links` (
  `link_id` bigint UNSIGNED NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint UNSIGNED NOT NULL DEFAULT '1',
  `link_rating` int NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_rss` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_options`
--

CREATE TABLE `wp_options` (
  `option_id` bigint UNSIGNED NOT NULL,
  `option_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wp_options`
--

INSERT INTO `wp_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://localhost', 'yes'),
(2, 'home', 'http://localhost', 'yes'),
(3, 'blogname', 'Архив публикаций', 'yes'),
(4, 'blogdescription', '', 'yes'),
(5, 'users_can_register', '0', 'yes'),
(6, 'admin_email', 'admin@example.com', 'yes'),
(7, 'start_of_week', '1', 'yes'),
(8, 'use_balanceTags', '0', 'yes'),
(9, 'use_smilies', '1', 'yes'),
(10, 'require_name_email', '1', 'yes'),
(11, 'comments_notify', '1', 'yes'),
(12, 'posts_per_rss', '10', 'yes'),
(13, 'rss_use_excerpt', '0', 'yes'),
(14, 'mailserver_url', 'mail.example.com', 'yes'),
(15, 'mailserver_login', 'login@example.com', 'yes'),
(16, 'mailserver_pass', 'password', 'yes'),
(17, 'mailserver_port', '110', 'yes'),
(18, 'default_category', '1', 'yes'),
(19, 'default_comment_status', 'open', 'yes'),
(20, 'default_ping_status', 'open', 'yes'),
(21, 'default_pingback_flag', '0', 'yes'),
(22, 'posts_per_page', '10', 'yes'),
(23, 'date_format', 'd.m.Y', 'yes'),
(24, 'time_format', 'H:i', 'yes'),
(25, 'links_updated_date_format', 'd.m.Y H:i', 'yes'),
(26, 'comment_moderation', '0', 'yes'),
(27, 'moderation_notify', '1', 'yes'),
(28, 'permalink_structure', '/archives/%post_id%', 'yes'),
(29, 'rewrite_rules', 'a:92:{s:11:\"^wp-json/?$\";s:22:\"index.php?rest_route=/\";s:14:\"^wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:21:\"^index.php/wp-json/?$\";s:22:\"index.php?rest_route=/\";s:24:\"^index.php/wp-json/(.*)?\";s:33:\"index.php?rest_route=/$matches[1]\";s:17:\"^wp-sitemap\\.xml$\";s:23:\"index.php?sitemap=index\";s:17:\"^wp-sitemap\\.xsl$\";s:36:\"index.php?sitemap-stylesheet=sitemap\";s:23:\"^wp-sitemap-index\\.xsl$\";s:34:\"index.php?sitemap-stylesheet=index\";s:48:\"^wp-sitemap-([a-z]+?)-([a-z\\d_-]+?)-(\\d+?)\\.xml$\";s:75:\"index.php?sitemap=$matches[1]&sitemap-subtype=$matches[2]&paged=$matches[3]\";s:34:\"^wp-sitemap-([a-z]+?)-(\\d+?)\\.xml$\";s:47:\"index.php?sitemap=$matches[1]&paged=$matches[2]\";s:56:\"archives/category/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:51:\"archives/category/(.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:52:\"index.php?category_name=$matches[1]&feed=$matches[2]\";s:32:\"archives/category/(.+?)/embed/?$\";s:46:\"index.php?category_name=$matches[1]&embed=true\";s:44:\"archives/category/(.+?)/page/?([0-9]{1,})/?$\";s:53:\"index.php?category_name=$matches[1]&paged=$matches[2]\";s:26:\"archives/category/(.+?)/?$\";s:35:\"index.php?category_name=$matches[1]\";s:53:\"archives/tag/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:48:\"archives/tag/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?tag=$matches[1]&feed=$matches[2]\";s:29:\"archives/tag/([^/]+)/embed/?$\";s:36:\"index.php?tag=$matches[1]&embed=true\";s:41:\"archives/tag/([^/]+)/page/?([0-9]{1,})/?$\";s:43:\"index.php?tag=$matches[1]&paged=$matches[2]\";s:23:\"archives/tag/([^/]+)/?$\";s:25:\"index.php?tag=$matches[1]\";s:54:\"archives/type/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:49:\"archives/type/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?post_format=$matches[1]&feed=$matches[2]\";s:30:\"archives/type/([^/]+)/embed/?$\";s:44:\"index.php?post_format=$matches[1]&embed=true\";s:42:\"archives/type/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?post_format=$matches[1]&paged=$matches[2]\";s:24:\"archives/type/([^/]+)/?$\";s:33:\"index.php?post_format=$matches[1]\";s:48:\".*wp-(atom|rdf|rss|rss2|feed|commentsrss2)\\.php$\";s:18:\"index.php?feed=old\";s:20:\".*wp-app\\.php(/.*)?$\";s:19:\"index.php?error=403\";s:18:\".*wp-register.php$\";s:23:\"index.php?register=true\";s:32:\"feed/(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:27:\"(feed|rdf|rss|rss2|atom)/?$\";s:27:\"index.php?&feed=$matches[1]\";s:8:\"embed/?$\";s:21:\"index.php?&embed=true\";s:20:\"page/?([0-9]{1,})/?$\";s:28:\"index.php?&paged=$matches[1]\";s:27:\"comment-page-([0-9]{1,})/?$\";s:38:\"index.php?&page_id=2&cpage=$matches[1]\";s:41:\"comments/feed/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:36:\"comments/(feed|rdf|rss|rss2|atom)/?$\";s:42:\"index.php?&feed=$matches[1]&withcomments=1\";s:17:\"comments/embed/?$\";s:21:\"index.php?&embed=true\";s:44:\"search/(.+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:39:\"search/(.+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?s=$matches[1]&feed=$matches[2]\";s:20:\"search/(.+)/embed/?$\";s:34:\"index.php?s=$matches[1]&embed=true\";s:32:\"search/(.+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?s=$matches[1]&paged=$matches[2]\";s:14:\"search/(.+)/?$\";s:23:\"index.php?s=$matches[1]\";s:56:\"archives/author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:51:\"archives/author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:50:\"index.php?author_name=$matches[1]&feed=$matches[2]\";s:32:\"archives/author/([^/]+)/embed/?$\";s:44:\"index.php?author_name=$matches[1]&embed=true\";s:44:\"archives/author/([^/]+)/page/?([0-9]{1,})/?$\";s:51:\"index.php?author_name=$matches[1]&paged=$matches[2]\";s:26:\"archives/author/([^/]+)/?$\";s:33:\"index.php?author_name=$matches[1]\";s:83:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:78:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:80:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]\";s:59:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/embed/?$\";s:74:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&embed=true\";s:71:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:81:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]\";s:53:\"archives/date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$\";s:63:\"index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]\";s:70:\"archives/date/([0-9]{4})/([0-9]{1,2})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:65:\"archives/date/([0-9]{4})/([0-9]{1,2})/(feed|rdf|rss|rss2|atom)/?$\";s:64:\"index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]\";s:46:\"archives/date/([0-9]{4})/([0-9]{1,2})/embed/?$\";s:58:\"index.php?year=$matches[1]&monthnum=$matches[2]&embed=true\";s:58:\"archives/date/([0-9]{4})/([0-9]{1,2})/page/?([0-9]{1,})/?$\";s:65:\"index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]\";s:40:\"archives/date/([0-9]{4})/([0-9]{1,2})/?$\";s:47:\"index.php?year=$matches[1]&monthnum=$matches[2]\";s:57:\"archives/date/([0-9]{4})/feed/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:52:\"archives/date/([0-9]{4})/(feed|rdf|rss|rss2|atom)/?$\";s:43:\"index.php?year=$matches[1]&feed=$matches[2]\";s:33:\"archives/date/([0-9]{4})/embed/?$\";s:37:\"index.php?year=$matches[1]&embed=true\";s:45:\"archives/date/([0-9]{4})/page/?([0-9]{1,})/?$\";s:44:\"index.php?year=$matches[1]&paged=$matches[2]\";s:27:\"archives/date/([0-9]{4})/?$\";s:26:\"index.php?year=$matches[1]\";s:37:\"archives/[0-9]+/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:47:\"archives/[0-9]+/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:67:\"archives/[0-9]+/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"archives/[0-9]+/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:62:\"archives/[0-9]+/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:43:\"archives/[0-9]+/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:26:\"archives/([0-9]+)/embed/?$\";s:34:\"index.php?p=$matches[1]&embed=true\";s:30:\"archives/([0-9]+)/trackback/?$\";s:28:\"index.php?p=$matches[1]&tb=1\";s:50:\"archives/([0-9]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?p=$matches[1]&feed=$matches[2]\";s:45:\"archives/([0-9]+)/(feed|rdf|rss|rss2|atom)/?$\";s:40:\"index.php?p=$matches[1]&feed=$matches[2]\";s:38:\"archives/([0-9]+)/page/?([0-9]{1,})/?$\";s:41:\"index.php?p=$matches[1]&paged=$matches[2]\";s:45:\"archives/([0-9]+)/comment-page-([0-9]{1,})/?$\";s:41:\"index.php?p=$matches[1]&cpage=$matches[2]\";s:34:\"archives/([0-9]+)(?:/([0-9]+))?/?$\";s:40:\"index.php?p=$matches[1]&page=$matches[2]\";s:26:\"archives/[0-9]+/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:36:\"archives/[0-9]+/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:56:\"archives/[0-9]+/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"archives/[0-9]+/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:51:\"archives/[0-9]+/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:32:\"archives/[0-9]+/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:27:\".?.+?/attachment/([^/]+)/?$\";s:32:\"index.php?attachment=$matches[1]\";s:37:\".?.+?/attachment/([^/]+)/trackback/?$\";s:37:\"index.php?attachment=$matches[1]&tb=1\";s:57:\".?.+?/attachment/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/(feed|rdf|rss|rss2|atom)/?$\";s:49:\"index.php?attachment=$matches[1]&feed=$matches[2]\";s:52:\".?.+?/attachment/([^/]+)/comment-page-([0-9]{1,})/?$\";s:50:\"index.php?attachment=$matches[1]&cpage=$matches[2]\";s:33:\".?.+?/attachment/([^/]+)/embed/?$\";s:43:\"index.php?attachment=$matches[1]&embed=true\";s:16:\"(.?.+?)/embed/?$\";s:41:\"index.php?pagename=$matches[1]&embed=true\";s:20:\"(.?.+?)/trackback/?$\";s:35:\"index.php?pagename=$matches[1]&tb=1\";s:40:\"(.?.+?)/feed/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:35:\"(.?.+?)/(feed|rdf|rss|rss2|atom)/?$\";s:47:\"index.php?pagename=$matches[1]&feed=$matches[2]\";s:28:\"(.?.+?)/page/?([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&paged=$matches[2]\";s:35:\"(.?.+?)/comment-page-([0-9]{1,})/?$\";s:48:\"index.php?pagename=$matches[1]&cpage=$matches[2]\";s:24:\"(.?.+?)(?:/([0-9]+))?/?$\";s:47:\"index.php?pagename=$matches[1]&page=$matches[2]\";}', 'yes'),
(30, 'hack_file', '0', 'yes'),
(31, 'blog_charset', 'UTF-8', 'yes'),
(32, 'moderation_keys', '', 'no'),
(33, 'active_plugins', 'a:3:{i:0;s:39:\"aryo-activity-log/aryo-activity-log.php\";i:2;s:33:\"wp-force-login/wp-force-login.php\";i:3;s:43:\"wp-maintenance-mode/wp-maintenance-mode.php\";}', 'yes'),
(34, 'category_base', '', 'yes'),
(35, 'ping_sites', 'http://rpc.pingomatic.com/', 'yes'),
(36, 'comment_max_links', '2', 'yes'),
(37, 'gmt_offset', '3', 'yes'),
(38, 'default_email_category', '1', 'yes'),
(40, 'template', 'simplified', 'yes'),
(41, 'stylesheet', 'simplified', 'yes'),
(44, 'comment_registration', '0', 'yes'),
(45, 'html_type', 'text/html', 'yes'),
(46, 'use_trackback', '0', 'yes'),
(47, 'default_role', 'subscriber', 'yes'),
(48, 'db_version', '49752', 'yes'),
(49, 'uploads_use_yearmonth_folders', '1', 'yes'),
(50, 'upload_path', '', 'yes'),
(51, 'blog_public', '0', 'yes'),
(52, 'default_link_category', '2', 'yes'),
(53, 'show_on_front', 'page', 'yes'),
(54, 'tag_base', '', 'yes'),
(55, 'show_avatars', '1', 'yes'),
(56, 'avatar_rating', 'G', 'yes'),
(57, 'upload_url_path', '', 'yes'),
(58, 'thumbnail_size_w', '150', 'yes'),
(59, 'thumbnail_size_h', '150', 'yes'),
(60, 'thumbnail_crop', '1', 'yes'),
(61, 'medium_size_w', '300', 'yes'),
(62, 'medium_size_h', '300', 'yes'),
(63, 'avatar_default', 'mystery', 'yes'),
(64, 'large_size_w', '1024', 'yes'),
(65, 'large_size_h', '1024', 'yes'),
(66, 'image_default_link_type', 'none', 'yes'),
(67, 'image_default_size', '', 'yes'),
(68, 'image_default_align', '', 'yes'),
(69, 'close_comments_for_old_posts', '0', 'yes'),
(70, 'close_comments_days_old', '14', 'yes'),
(71, 'thread_comments', '1', 'yes'),
(72, 'thread_comments_depth', '5', 'yes'),
(73, 'page_comments', '0', 'yes'),
(74, 'comments_per_page', '50', 'yes'),
(75, 'default_comments_page', 'newest', 'yes'),
(76, 'comment_order', 'asc', 'yes'),
(77, 'sticky_posts', 'a:0:{}', 'yes'),
(78, 'widget_categories', 'a:2:{i:2;a:4:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:12:\"hierarchical\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(79, 'widget_text', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(80, 'widget_rss', 'a:2:{i:1;a:0:{}s:12:\"_multiwidget\";i:1;}', 'yes'),
(81, 'uninstall_plugins', 'a:1:{s:39:\"aryo-activity-log/aryo-activity-log.php\";a:2:{i:0;s:15:\"AAL_Maintenance\";i:1;s:9:\"uninstall\";}}', 'no'),
(82, 'timezone_string', '', 'yes'),
(83, 'page_for_posts', '0', 'yes'),
(84, 'page_on_front', '2', 'yes'),
(85, 'default_post_format', '0', 'yes'),
(86, 'link_manager_enabled', '0', 'yes'),
(87, 'finished_splitting_shared_terms', '1', 'yes'),
(88, 'site_icon', '0', 'yes'),
(89, 'medium_large_size_w', '768', 'yes'),
(90, 'medium_large_size_h', '0', 'yes'),
(91, 'initial_db_version', '38590', 'yes'),
(92, 'wp_user_roles', 'a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:62:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;s:26:\"view_all_aryo_activity_log\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}', 'yes'),
(93, 'fresh_site', '0', 'yes'),
(94, 'WPLANG', '', 'yes'),
(95, 'widget_search', 'a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}', 'yes'),
(96, 'widget_recent-posts', 'a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(97, 'widget_recent-comments', 'a:2:{i:2;a:2:{s:5:\"title\";s:0:\"\";s:6:\"number\";i:5;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(98, 'widget_archives', 'a:2:{i:2;a:3:{s:5:\"title\";s:0:\"\";s:5:\"count\";i:0;s:8:\"dropdown\";i:0;}s:12:\"_multiwidget\";i:1;}', 'yes'),
(99, 'widget_meta', 'a:2:{i:2;a:1:{s:5:\"title\";s:0:\"\";}s:12:\"_multiwidget\";i:1;}', 'yes'),
(100, 'sidebars_widgets', 'a:2:{s:19:\"wp_inactive_widgets\";a:6:{i:0;s:10:\"archives-2\";i:1;s:6:\"meta-2\";i:2;s:8:\"search-2\";i:3;s:12:\"categories-2\";i:4;s:14:\"recent-posts-2\";i:5;s:17:\"recent-comments-2\";}s:13:\"array_version\";i:3;}', 'yes'),
(101, 'widget_pages', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(102, 'widget_calendar', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(103, 'widget_media_audio', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(104, 'widget_media_image', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(105, 'widget_media_gallery', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(106, 'widget_media_video', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(107, 'widget_tag_cloud', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(108, 'widget_nav_menu', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(109, 'widget_custom_html', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(110, 'cron', 'a:9:{i:1686487429;a:1:{s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1686498098;a:1:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1686520280;a:1:{s:18:\"wp_https_detection\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1686520732;a:3:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1686563972;a:2:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1686564642;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1686672533;a:1:{s:23:\"updraft_backup_database\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}i:1686995480;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}s:7:\"version\";i:2;}', 'yes'),
(138, 'current_theme', 'simplified', 'yes'),
(140, 'theme_switched', '', 'yes'),
(145, 'nav_menu_options', 'a:1:{s:8:\"auto_add\";a:0:{}}', 'yes'),
(161, 'wpmm_settings', 'a:5:{s:7:\"general\";a:9:{s:6:\"status\";i:0;s:11:\"bypass_bots\";i:0;s:11:\"meta_robots\";i:0;s:11:\"redirection\";s:0:\"\";s:7:\"exclude\";a:3:{i:0;s:4:\"feed\";i:1;s:8:\"wp-login\";i:2;s:5:\"login\";}s:6:\"notice\";i:1;s:10:\"admin_link\";i:0;s:12:\"backend_role\";a:0:{}s:13:\"frontend_role\";a:0:{}}s:6:\"design\";a:12:{s:5:\"title\";s:60:\"Режим технического обслуживания\";s:7:\"heading\";s:31:\"Сайт обновляется\";s:13:\"heading_color\";s:7:\"#ffffff\";s:4:\"text\";s:98:\"<p>Всё к лучшему - попробуйте зайти чуточку попозже...</p>\";s:10:\"text_color\";s:7:\"#ffffff\";s:7:\"bg_type\";s:6:\"custom\";s:8:\"bg_color\";s:0:\"\";s:9:\"bg_custom\";s:73:\"http://89.186.234.80/PUBMaster/wp-content/uploads/2017/12/maintenance.jpg\";s:13:\"bg_predefined\";s:7:\"bg1.jpg\";s:10:\"custom_css\";a:3:{s:13:\"heading_color\";s:28:\".wrap h1 { color: #ffffff; }\";s:10:\"text_color\";s:28:\".wrap h2 { color: #ffffff; }\";s:6:\"bg_url\";s:158:\".background { background: url(http://89.186.234.80/PUBMaster/wp-content/uploads/2017/12/maintenance.jpg) no-repeat center top fixed; background-size: cover; }\";}s:16:\"other_custom_css\";s:0:\"\";s:18:\"footer_links_color\";s:0:\"\";}s:7:\"modules\";a:23:{s:16:\"countdown_status\";i:0;s:15:\"countdown_start\";s:19:\"2017-12-30 14:07:56\";s:17:\"countdown_details\";a:3:{s:4:\"days\";i:0;s:5:\"hours\";i:1;s:7:\"minutes\";i:0;}s:15:\"countdown_color\";s:0:\"\";s:16:\"subscribe_status\";i:0;s:14:\"subscribe_text\";s:40:\"Сообщить о готовности\";s:20:\"subscribe_text_color\";s:0:\"\";s:13:\"social_status\";i:0;s:13:\"social_target\";i:1;s:13:\"social_github\";s:0:\"\";s:15:\"social_dribbble\";s:0:\"\";s:14:\"social_twitter\";s:0:\"\";s:15:\"social_facebook\";s:0:\"\";s:16:\"social_pinterest\";s:0:\"\";s:14:\"social_google+\";s:0:\"\";s:15:\"social_linkedin\";s:0:\"\";s:14:\"contact_status\";i:0;s:13:\"contact_email\";s:14:\"admin@example.com\";s:15:\"contact_effects\";s:20:\"move_top|move_bottom\";s:9:\"ga_status\";i:0;s:7:\"ga_code\";s:0:\"\";s:10:\"custom_css\";a:0:{}s:15:\"ga_anonymize_ip\";i:0;}s:3:\"bot\";a:6:{s:6:\"status\";i:0;s:4:\"name\";s:5:\"Admin\";s:6:\"avatar\";s:0:\"\";s:8:\"messages\";a:11:{s:2:\"01\";s:129:\"Привет! Меня зовут {bot_name}, я владелец этого сайта и буду рад помочь тебе.\";s:2:\"02\";s:57:\"У меня есть несколько вопросов.\";s:2:\"03\";s:27:\"Как тебя зовут?\";s:2:\"04\";s:38:\"Nice to meet you here, {visitor_name}!\";s:2:\"05\";s:83:\"Как видите, наш сайт будет открыт очень скоро!\";s:2:\"06\";s:198:\"Понимаю, что Вы очень хотели бы видеть всё уже прямо сейчас, но нам нужно ещё несколько дней, чтобы закончить.\";s:2:\"07\";s:63:\"Хотите быть первым, кто это увидит?\";s:4:\"08_1\";s:183:\"Здорово! Пожалуйста, оставьте свой e-mail здесь и я отправлю Вам сообщение, как только всё будет готово.\";s:4:\"08_2\";s:92:\"Очень жаль слышать это,  {visitor_name} :( Всего хорошего...\";s:2:\"09\";s:63:\"Понял! Спасибо и до скорой встречи!\";i:10;s:24:\"Хорошего дня!\";}s:9:\"responses\";a:4:{s:2:\"01\";s:44:\"Введите здесь Ваше имя...\";s:4:\"02_1\";s:42:\"Расскажи мне подробнее\";s:4:\"02_2\";s:12:\"Надоел\";s:2:\"03\";s:42:\"Укажите здесь Ваш e-mail...\";}s:10:\"custom_css\";a:0:{}}s:4:\"gdpr\";a:6:{s:6:\"status\";i:0;s:17:\"policy_page_label\";s:53:\"Политика конфиденциальности\";s:16:\"policy_page_link\";s:0:\"\";s:18:\"policy_page_target\";i:0;s:17:\"contact_form_tail\";s:475:\"Эта форма собирает Ваши имя и адрес электронной почты, чтобы у нас была возможность связаться с Вами. Пожалуйста, ознакомьтесь с нашей <a href=\"#\">Политикой конфиденциальности</a>, чтобы полностью понимать, как мы защищаем и управляем предоставленными Вами данными.\";s:19:\"subscribe_form_tail\";s:496:\"Эта форма собирает Ваш адрес электронной почты, чтобы у нас была возможность добавить его в наш список рассылки. Пожалуйста, ознакомьтесь с нашей <a href=\"#\">Политикой конфиденциальности</a>, чтобы полностью понимать, как мы защищаем и управляем предоставленными Вами данными.\";}}', 'yes'),
(162, 'wpmm_version', '2.4.0', 'yes'),
(164, 'category_children', 'a:0:{}', 'yes'),
(1450, 'theme_mods_simplified', 'a:4:{i:0;b:0;s:18:\"nav_menu_locations\";a:0:{}s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1560185446;s:4:\"data\";a:1:{s:19:\"wp_inactive_widgets\";a:6:{i:0;s:10:\"archives-2\";i:1;s:6:\"meta-2\";i:2;s:8:\"search-2\";i:3;s:12:\"categories-2\";i:4;s:14:\"recent-posts-2\";i:5;s:17:\"recent-comments-2\";}}}s:18:\"custom_css_post_id\";i:-1;}', 'yes'),
(2587, 'wp_page_for_privacy_policy', '0', 'yes'),
(2588, 'show_comments_cookies_opt_in', '1', 'yes'),
(2589, 'db_upgraded', '', 'yes'),
(2593, 'recovery_keys', 'a:0:{}', 'yes'),
(2615, 'activity_log_db_version', '1.0', 'yes'),
(2616, 'activity-log-settings', 'a:1:{s:13:\"logs_lifespan\";s:2:\"30\";}', 'yes'),
(2672, 'dismissed_season_notices_until', '1594310854', 'yes'),
(8592, 'widget_block', 'a:1:{s:12:\"_multiwidget\";i:1;}', 'yes'),
(8593, 'admin_email_lifespan', '0', 'yes'),
(8594, 'disallowed_keys', '', 'no'),
(8595, 'comment_previously_approved', '1', 'yes'),
(8596, 'auto_plugin_theme_update_emails', 'a:0:{}', 'no'),
(8597, 'auto_update_core_dev', 'enabled', 'yes'),
(8598, 'auto_update_core_minor', 'enabled', 'yes'),
(8599, 'auto_update_core_major', 'unset', 'yes'),
(8600, 'wp_force_deactivated_plugins', 'a:0:{}', 'yes'),
(8601, 'finished_updating_comment_type', '1', 'yes'),
(8606, 'https_detection_errors', 'a:1:{s:19:\"bad_response_source\";a:1:{i:0;s:55:\"It looks like the response did not come from this site.\";}}', 'yes'),
(8608, 'can_compress_scripts', '1', 'no'),
(9074, '_transient_doing_cron', '1686811768.1310930252075195312500', 'yes');

-- --------------------------------------------------------

--
-- Структура таблицы `wp_postmeta`
--

CREATE TABLE `wp_postmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wp_postmeta`
--

INSERT INTO `wp_postmeta` (`meta_id`, `post_id`, `meta_key`, `meta_value`) VALUES
(1, 2, '_wp_page_template', 'page-templates/main.php'),
(28, 11, '_wp_attached_file', '2017/12/maintenance.jpg'),
(29, 11, '_wp_attachment_metadata', 'a:5:{s:5:\"width\";i:1920;s:6:\"height\";i:1080;s:4:\"file\";s:23:\"2017/12/maintenance.jpg\";s:5:\"sizes\";a:5:{s:9:\"thumbnail\";a:4:{s:4:\"file\";s:23:\"maintenance-150x150.jpg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:6:\"medium\";a:4:{s:4:\"file\";s:23:\"maintenance-300x169.jpg\";s:5:\"width\";i:300;s:6:\"height\";i:169;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:12:\"medium_large\";a:4:{s:4:\"file\";s:23:\"maintenance-768x432.jpg\";s:5:\"width\";i:768;s:6:\"height\";i:432;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:5:\"large\";a:4:{s:4:\"file\";s:24:\"maintenance-1024x576.jpg\";s:5:\"width\";i:1024;s:6:\"height\";i:576;s:9:\"mime-type\";s:10:\"image/jpeg\";}s:21:\"cactus-featured-image\";a:4:{s:4:\"file\";s:23:\"maintenance-960x720.jpg\";s:5:\"width\";i:960;s:6:\"height\";i:720;s:9:\"mime-type\";s:10:\"image/jpeg\";}}s:10:\"image_meta\";a:12:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"1\";s:8:\"keywords\";a:0:{}}}'),
(30, 2, '_edit_last', '1'),
(31, 2, '_edit_lock', '1561362100:1'),
(34, 18, '_edit_last', '1'),
(35, 18, '_edit_lock', '1558618092:1'),
(36, 18, '_wp_page_template', 'page-templates/articles_wizard.php'),
(309, 99, '_edit_last', '1'),
(310, 99, '_edit_lock', '1559663603:1'),
(311, 99, '_wp_page_template', 'page-templates/articles.php'),
(326, 112, '_edit_last', '1'),
(327, 112, '_edit_lock', '1559765140:1'),
(328, 112, '_wp_page_template', 'page-templates/articles_view.php'),
(393, 128, '_edit_lock', '1560883371:1'),
(394, 128, '_edit_last', '1'),
(395, 128, '_wp_page_template', 'page-templates/articles_wizard.php'),
(396, 130, '_edit_lock', '1560983513:1'),
(397, 130, '_edit_last', '1'),
(398, 130, '_wp_page_template', 'page-templates/programs.php'),
(399, 132, '_edit_lock', '1560986033:1'),
(400, 132, '_edit_last', '1'),
(401, 132, '_wp_page_template', 'page-templates/programs_create.php'),
(402, 134, '_edit_lock', '1560986148:1'),
(403, 134, '_edit_last', '1'),
(404, 134, '_wp_page_template', 'page-templates/programs_view.php'),
(405, 136, '_edit_lock', '1561043239:1'),
(406, 136, '_edit_last', '1'),
(407, 136, '_wp_page_template', 'page-templates/programs_create.php'),
(410, 140, '_edit_lock', '1561122491:1'),
(411, 140, '_edit_last', '1'),
(412, 140, '_wp_page_template', 'page-templates/conferences.php'),
(413, 142, '_edit_lock', '1561289202:1'),
(414, 142, '_edit_last', '1'),
(415, 142, '_wp_page_template', 'page-templates/main-redirect.php'),
(416, 144, '_edit_lock', '1561361915:1'),
(417, 144, '_edit_last', '1'),
(418, 144, '_wp_page_template', 'page-templates/authors_view.php'),
(419, 146, '_edit_lock', '1561361916:1'),
(420, 146, '_edit_last', '1'),
(421, 146, '_wp_page_template', 'page-templates/authors_create.php'),
(422, 148, '_edit_lock', '1561361918:1'),
(423, 148, '_edit_last', '1'),
(424, 148, '_wp_page_template', 'page-templates/export.php'),
(428, 150, '_edit_lock', '1561483433:1'),
(429, 150, '_edit_last', '1'),
(430, 150, '_wp_page_template', 'page-templates/service.php'),
(431, 153, '_edit_lock', '1562407606:1'),
(432, 153, '_edit_last', '1'),
(433, 153, '_wp_page_template', 'page-templates/download.php');

-- --------------------------------------------------------

--
-- Структура таблицы `wp_posts`
--

CREATE TABLE `wp_posts` (
  `ID` bigint UNSIGNED NOT NULL,
  `post_author` bigint UNSIGNED NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int NOT NULL DEFAULT '0',
  `post_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wp_posts`
--

INSERT INTO `wp_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(2, 1, '2017-12-30 12:58:52', '2017-12-30 09:58:52', '', 'Главная', '', 'publish', 'closed', 'closed', '', 'index', '', '', '2019-06-24 10:41:40', '2019-06-24 07:41:40', '', 0, '', 1, 'page', '', 0),
(11, 1, '2017-12-30 13:09:53', '2017-12-30 10:09:53', '', 'maintenance', '', 'inherit', 'open', 'closed', '', 'maintenance', '', '', '2017-12-30 13:09:53', '2017-12-30 10:09:53', '', 0, '', 0, 'attachment', 'image/jpeg', 0),
(18, 1, '2018-01-02 22:21:14', '2018-01-02 19:21:14', '', 'Добавить публикацию', '', 'publish', 'closed', 'closed', '', 'wizard', '', '', '2019-05-23 16:28:12', '2019-05-23 13:28:12', '', 99, '', 1, 'page', '', 0),
(99, 1, '2018-01-09 22:33:18', '2018-01-09 19:33:18', '', 'Публикации', '', 'publish', 'closed', 'closed', '', 'articles', '', '', '2019-06-04 18:53:23', '2019-06-04 15:53:23', '', 0, '', 2, 'page', '', 0),
(112, 1, '2018-02-23 20:57:13', '2018-02-23 17:57:13', '', 'Детальные сведения', '', 'publish', 'closed', 'closed', '', 'view', '', '', '2019-06-05 23:05:40', '2019-06-05 20:05:40', '', 99, '', 2, 'page', '', 0),
(128, 1, '2019-06-18 21:45:11', '2019-06-18 18:45:11', '', 'Редактировать', '', 'publish', 'closed', 'closed', '', 'edit', '', '', '2019-06-18 21:45:11', '2019-06-18 18:45:11', '', 99, '', 3, 'page', '', 0),
(130, 1, '2019-06-20 01:00:38', '2019-06-19 22:00:38', '', 'Свидетельства', '', 'publish', 'closed', 'closed', '', 'programs', '', '', '2019-06-20 01:00:55', '2019-06-19 22:00:55', '', 0, '', 3, 'page', '', 0),
(132, 1, '2019-06-20 01:34:55', '2019-06-19 22:34:55', '', 'Добавить свидетельство', '', 'publish', 'closed', 'closed', '', 'create', '', '', '2019-06-20 01:34:55', '2019-06-19 22:34:55', '', 130, '', 1, 'page', '', 0),
(134, 1, '2019-06-20 02:16:52', '2019-06-19 23:16:52', '', 'Детальные сведения', '', 'publish', 'closed', 'closed', '', 'view', '', '', '2019-06-20 02:16:52', '2019-06-19 23:16:52', '', 130, '', 2, 'page', '', 0),
(136, 1, '2019-06-20 02:18:36', '2019-06-19 23:18:36', '', 'Редактировать', '', 'publish', 'closed', 'closed', '', 'edit', '', '', '2019-06-20 02:18:36', '2019-06-19 23:18:36', '', 130, '', 3, 'page', '', 0),
(140, 1, '2019-06-20 20:16:46', '2019-06-20 17:16:46', '', 'Конференции', '', 'publish', 'closed', 'closed', '', 'conferences', '', '', '2019-06-20 20:16:46', '2019-06-20 17:16:46', '', 0, '', 4, 'page', '', 0),
(142, 1, '2019-06-23 14:08:34', '2019-06-23 11:08:34', '', 'Авторы', '', 'publish', 'closed', 'closed', '', 'authors', '', '', '2019-06-23 14:09:45', '2019-06-23 11:09:45', '', 0, '', 5, 'page', '', 0),
(144, 1, '2019-06-23 14:29:46', '2019-06-23 11:29:46', '', 'Детальные сведения', '', 'publish', 'closed', 'closed', '', 'view', '', '', '2019-06-23 14:29:46', '2019-06-23 11:29:46', '', 142, '', 1, 'page', '', 0),
(146, 1, '2019-06-23 16:58:47', '2019-06-23 13:58:47', '', 'Редактировать', '', 'publish', 'closed', 'closed', '', 'edit', '', '', '2019-06-23 16:58:47', '2019-06-23 13:58:47', '', 142, '', 2, 'page', '', 0),
(148, 1, '2019-06-23 23:25:11', '2019-06-23 20:25:11', '', 'Экспорт', '', 'publish', 'closed', 'closed', '', 'export', '', '', '2019-06-23 23:25:11', '2019-06-23 20:25:11', '', 0, '', 6, 'page', '', 0),
(150, 1, '2019-06-25 20:26:14', '2019-06-25 17:26:14', '', 'Service', '', 'publish', 'closed', 'closed', '', 'service', '', '', '2019-06-25 20:26:14', '2019-06-25 17:26:14', '', 0, '', 7, 'page', '', 0),
(153, 1, '2019-07-06 12:18:20', '2019-07-06 09:18:20', '', 'Загрузка', '', 'publish', 'closed', 'closed', '', 'download', '', '', '2019-07-06 12:18:28', '2019-07-06 09:18:28', '', 0, '', 7, 'page', '', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `wp_termmeta`
--

CREATE TABLE `wp_termmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `term_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_terms`
--

CREATE TABLE `wp_terms` (
  `term_id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `term_group` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wp_terms`
--

INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(1, 'Без рубрики', '%d0%b1%d0%b5%d0%b7-%d1%80%d1%83%d0%b1%d1%80%d0%b8%d0%ba%d0%b8', 0),
(2, 'Main Menu', 'main-menu', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `wp_term_relationships`
--

CREATE TABLE `wp_term_relationships` (
  `object_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `term_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wp_term_relationships`
--

INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES
(1, 1, 0),
(6, 2, 0),
(102, 2, 0),
(119, 1, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `wp_term_taxonomy`
--

CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint UNSIGNED NOT NULL,
  `term_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `count` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wp_term_taxonomy`
--

INSERT INTO `wp_term_taxonomy` (`term_taxonomy_id`, `term_id`, `taxonomy`, `description`, `parent`, `count`) VALUES
(1, 1, 'category', '', 0, 1),
(2, 2, 'nav_menu', '', 0, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `wp_usermeta`
--

CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_users`
--

CREATE TABLE `wp_users` (
  `ID` bigint UNSIGNED NOT NULL,
  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `user_status` int NOT NULL DEFAULT '0',
  `display_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `wp_wpmm_subscribers`
--

CREATE TABLE `wp_wpmm_subscribers` (
  `id_subscriber` bigint NOT NULL,
  `email` varchar(50) NOT NULL,
  `insert_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `wp_ab_articles`
--
ALTER TABLE `wp_ab_articles`
  ADD PRIMARY KEY (`ID_Article`),
  ADD KEY `Relation_1` (`ID_Issue`) USING BTREE,
  ADD KEY `Articles_2` (`ID_Conference`);

--
-- Индексы таблицы `wp_ab_articles_links`
--
ALTER TABLE `wp_ab_articles_links`
  ADD PRIMARY KEY (`ID_Link`),
  ADD KEY `Relation_4` (`ID_Author`),
  ADD KEY `Relation_3` (`ID_Article`);

--
-- Индексы таблицы `wp_ab_authors`
--
ALTER TABLE `wp_ab_authors`
  ADD PRIMARY KEY (`ID_Author`);

--
-- Индексы таблицы `wp_ab_conferences`
--
ALTER TABLE `wp_ab_conferences`
  ADD PRIMARY KEY (`ID_Conference`);

--
-- Индексы таблицы `wp_ab_gratz`
--
ALTER TABLE `wp_ab_gratz`
  ADD PRIMARY KEY (`ID_Gratz`);

--
-- Индексы таблицы `wp_ab_hides`
--
ALTER TABLE `wp_ab_hides`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `wp_ab_issues`
--
ALTER TABLE `wp_ab_issues`
  ADD PRIMARY KEY (`ID_Issue`),
  ADD KEY `Relation_2` (`ID_Journal`);

--
-- Индексы таблицы `wp_ab_journals`
--
ALTER TABLE `wp_ab_journals`
  ADD PRIMARY KEY (`ID_Journal`);

--
-- Индексы таблицы `wp_ab_programs`
--
ALTER TABLE `wp_ab_programs`
  ADD PRIMARY KEY (`ID_Program`);

--
-- Индексы таблицы `wp_ab_programs_links`
--
ALTER TABLE `wp_ab_programs_links`
  ADD PRIMARY KEY (`ID_Link`),
  ADD KEY `PLinks_1` (`ID_Author`),
  ADD KEY `PLinks_2` (`ID_Program`);

--
-- Индексы таблицы `wp_ab_templates`
--
ALTER TABLE `wp_ab_templates`
  ADD PRIMARY KEY (`ID_Template`);

--
-- Индексы таблицы `wp_ab_twins`
--
ALTER TABLE `wp_ab_twins`
  ADD PRIMARY KEY (`ID_Twin`),
  ADD KEY `Twins_1` (`ID_Main`),
  ADD KEY `Twins_2` (`ID_Alias`);

--
-- Индексы таблицы `wp_aryo_activity_log`
--
ALTER TABLE `wp_aryo_activity_log`
  ADD PRIMARY KEY (`histid`);

--
-- Индексы таблицы `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `wp_comments`
--
ALTER TABLE `wp_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10));

--
-- Индексы таблицы `wp_links`
--
ALTER TABLE `wp_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Индексы таблицы `wp_options`
--
ALTER TABLE `wp_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `autoload` (`autoload`);

--
-- Индексы таблицы `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `wp_posts`
--
ALTER TABLE `wp_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- Индексы таблицы `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `wp_terms`
--
ALTER TABLE `wp_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Индексы таблицы `wp_term_relationships`
--
ALTER TABLE `wp_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Индексы таблицы `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Индексы таблицы `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Индексы таблицы `wp_users`
--
ALTER TABLE `wp_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Индексы таблицы `wp_wpmm_subscribers`
--
ALTER TABLE `wp_wpmm_subscribers`
  ADD PRIMARY KEY (`id_subscriber`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `wp_ab_articles`
--
ALTER TABLE `wp_ab_articles`
  MODIFY `ID_Article` smallint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_articles_links`
--
ALTER TABLE `wp_ab_articles_links`
  MODIFY `ID_Link` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_authors`
--
ALTER TABLE `wp_ab_authors`
  MODIFY `ID_Author` smallint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_conferences`
--
ALTER TABLE `wp_ab_conferences`
  MODIFY `ID_Conference` smallint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_gratz`
--
ALTER TABLE `wp_ab_gratz`
  MODIFY `ID_Gratz` smallint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_hides`
--
ALTER TABLE `wp_ab_hides`
  MODIFY `ID` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_issues`
--
ALTER TABLE `wp_ab_issues`
  MODIFY `ID_Issue` smallint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_journals`
--
ALTER TABLE `wp_ab_journals`
  MODIFY `ID_Journal` smallint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_programs`
--
ALTER TABLE `wp_ab_programs`
  MODIFY `ID_Program` smallint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_programs_links`
--
ALTER TABLE `wp_ab_programs_links`
  MODIFY `ID_Link` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_templates`
--
ALTER TABLE `wp_ab_templates`
  MODIFY `ID_Template` tinyint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_ab_twins`
--
ALTER TABLE `wp_ab_twins`
  MODIFY `ID_Twin` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_aryo_activity_log`
--
ALTER TABLE `wp_aryo_activity_log`
  MODIFY `histid` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_comments`
--
ALTER TABLE `wp_comments`
  MODIFY `comment_ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `wp_links`
--
ALTER TABLE `wp_links`
  MODIFY `link_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_options`
--
ALTER TABLE `wp_options`
  MODIFY `option_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9075;

--
-- AUTO_INCREMENT для таблицы `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=436;

--
-- AUTO_INCREMENT для таблицы `wp_posts`
--
ALTER TABLE `wp_posts`
  MODIFY `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT для таблицы `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_terms`
--
ALTER TABLE `wp_terms`
  MODIFY `term_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  MODIFY `umeta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_users`
--
ALTER TABLE `wp_users`
  MODIFY `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wp_wpmm_subscribers`
--
ALTER TABLE `wp_wpmm_subscribers`
  MODIFY `id_subscriber` bigint NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `wp_ab_articles`
--
ALTER TABLE `wp_ab_articles`
  ADD CONSTRAINT `Articles_2` FOREIGN KEY (`ID_Conference`) REFERENCES `wp_ab_conferences` (`ID_Conference`),
  ADD CONSTRAINT `Relation_1` FOREIGN KEY (`ID_Issue`) REFERENCES `wp_ab_issues` (`ID_Issue`);

--
-- Ограничения внешнего ключа таблицы `wp_ab_articles_links`
--
ALTER TABLE `wp_ab_articles_links`
  ADD CONSTRAINT `Relation_3` FOREIGN KEY (`ID_Article`) REFERENCES `wp_ab_articles` (`ID_Article`) ON DELETE CASCADE,
  ADD CONSTRAINT `Relation_4` FOREIGN KEY (`ID_Author`) REFERENCES `wp_ab_authors` (`ID_Author`);

--
-- Ограничения внешнего ключа таблицы `wp_ab_issues`
--
ALTER TABLE `wp_ab_issues`
  ADD CONSTRAINT `Relation_2` FOREIGN KEY (`ID_Journal`) REFERENCES `wp_ab_journals` (`ID_Journal`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `wp_ab_programs_links`
--
ALTER TABLE `wp_ab_programs_links`
  ADD CONSTRAINT `PLinks_1` FOREIGN KEY (`ID_Author`) REFERENCES `wp_ab_authors` (`ID_Author`),
  ADD CONSTRAINT `PLinks_2` FOREIGN KEY (`ID_Program`) REFERENCES `wp_ab_programs` (`ID_Program`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `wp_ab_twins`
--
ALTER TABLE `wp_ab_twins`
  ADD CONSTRAINT `Twins_1` FOREIGN KEY (`ID_Main`) REFERENCES `wp_ab_authors` (`ID_Author`),
  ADD CONSTRAINT `Twins_2` FOREIGN KEY (`ID_Alias`) REFERENCES `wp_ab_authors` (`ID_Author`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
