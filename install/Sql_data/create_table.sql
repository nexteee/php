--
-- 数据库: `pinphp`
--

-- --------------------------------------------------------

--
-- 表的结构 `pp_access`
--

DROP TABLE IF EXISTS `pp_access`;
CREATE TABLE `pp_access` (
  `role_id` tinyint(5) NOT NULL,
  `node_id` tinyint(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_ad`
--

DROP TABLE IF EXISTS `pp_ad`;
CREATE TABLE `pp_ad` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `board_id` smallint(5) NOT NULL,
  `type` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `code` text NOT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL,
  `clicks` int(10) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  `ordid` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_adboard`
--

DROP TABLE IF EXISTS `pp_adboard`;
CREATE TABLE `pp_adboard` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `width` smallint(5) NOT NULL,
  `height` smallint(5) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_admin`
--

DROP TABLE IF EXISTS `pp_admin`;
CREATE TABLE `pp_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `add_time` int(10) DEFAULT NULL,
  `last_time` int(10) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `role_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_album`
--

DROP TABLE IF EXISTS `pp_album`;
CREATE TABLE `pp_album` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '默认专辑',
  `img` varchar(255) DEFAULT NULL,
  `uid` int(10) NOT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `cid` int(10) NOT NULL DEFAULT '0',
  `sort_order` int(10) DEFAULT NULL,
  `recommend` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `add_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_album_cate`
--

DROP TABLE IF EXISTS `pp_album_cate`;
CREATE TABLE `pp_album_cate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '我的专辑',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `album_num` int(10) NOT NULL DEFAULT '0',
  `sort_order` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_album_items`
--

DROP TABLE IF EXISTS `pp_album_items`;
CREATE TABLE `pp_album_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `items_id` int(10) NOT NULL DEFAULT '0',
  `pid` int(10) NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `add_time` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_article`
--

DROP TABLE IF EXISTS `pp_article`;
CREATE TABLE `pp_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` tinyint(4) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `orig` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `abst` varchar(255) NOT NULL,
  `info` text NOT NULL,
  `add_time` datetime NOT NULL,
  `ordid` tinyint(4) NOT NULL,
  `is_hot` tinyint(1) NOT NULL DEFAULT '0',
  `is_best` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-待审核 1-已审核',
  `seo_title` varchar(255) NOT NULL,
  `seo_keys` varchar(255) NOT NULL,
  `seo_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_article_cate`
--

DROP TABLE IF EXISTS `pp_article_cate`;
CREATE TABLE `pp_article_cate` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `pid` smallint(4) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `article_nums` int(10) unsigned NOT NULL,
  `sort_order` smallint(4) unsigned NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_keys` varchar(255) NOT NULL,
  `seo_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_attatch`
--

DROP TABLE IF EXISTS `pp_attatch`;
CREATE TABLE `pp_attatch` (
  `aid` int(10) NOT NULL AUTO_INCREMENT,
  `res_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `filetype` varchar(20) NOT NULL,
  `filesize` float NOT NULL,
  `uptime` datetime NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `res_id` (`res_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_collect_taobao`
--

DROP TABLE IF EXISTS `pp_collect_taobao`;
CREATE TABLE `pp_collect_taobao` (
  `cate_id` smallint(4) NOT NULL,
  `collect_time` int(10) NOT NULL DEFAULT '0',
  UNIQUE KEY `cate_id` (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_flink`
--

DROP TABLE IF EXISTS `pp_flink`;
CREATE TABLE `pp_flink` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` smallint(4) NOT NULL DEFAULT '1',
  `img` varchar(255) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ordid` smallint(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_flink_cate`
--

DROP TABLE IF EXISTS `pp_flink_cate`;
CREATE TABLE `pp_flink_cate` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_focus`
--

DROP TABLE IF EXISTS `pp_focus`;
CREATE TABLE `pp_focus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `abst` text NOT NULL,
  `clicks` int(10) NOT NULL DEFAULT '0',
  `ordid` smallint(4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_focus_cate`
--

DROP TABLE IF EXISTS `pp_focus_cate`;
CREATE TABLE `pp_focus_cate` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `width` smallint(6) NOT NULL DEFAULT '0',
  `height` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_group`
--

DROP TABLE IF EXISTS `pp_group`;
CREATE TABLE `pp_group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_items`
--

DROP TABLE IF EXISTS `pp_items`;
CREATE TABLE `pp_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cid` smallint(4) DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '3',
  `item_key` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `simg` varchar(255) DEFAULT NULL,
  `bimg` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `info` text NOT NULL,
  `sid` smallint(4) NOT NULL DEFAULT '0',
  `hits` int(10) NOT NULL DEFAULT '0',
  `dlikes` int(11) NOT NULL,
  `likes` int(10) NOT NULL DEFAULT '0' COMMENT '喜欢数',
  `haves` int(10) NOT NULL DEFAULT '0' COMMENT '库存数',
  `comments` int(10) NOT NULL DEFAULT '0' COMMENT '评论数',
  `comments_last` text COMMENT '最近的N条评论',
  `is_index` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `last_time` int(10) NOT NULL,
  `uid` int(10) NOT NULL DEFAULT '0',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keys` varchar(255) DEFAULT NULL,
  `sort_order` int(10) DEFAULT '0',
  `seo_desc` text,
  `remark` varchar(50) DEFAULT NULL,
  `remark_status` tinyint(6) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `is_index` (`is_index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_items_cate`
--

DROP TABLE IF EXISTS `pp_items_cate`;
CREATE TABLE `pp_items_cate` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `pid` smallint(4) NOT NULL DEFAULT '0',
  `item_nums` int(10) NOT NULL DEFAULT '0',
  `item_likes` int(11) NOT NULL,
  `collect_time` int(10) NOT NULL COMMENT '最后采集时间',
  `ordid` smallint(4) NOT NULL DEFAULT '0',
  `tags` varchar(50) NOT NULL,
  `is_hots` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `seo_title` varchar(255) NOT NULL,
  `seo_keys` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `seo_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_items_likes`
--

DROP TABLE IF EXISTS `pp_items_likes`;
CREATE TABLE `pp_items_likes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `items_id` int(10) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_items_pics`
--

DROP TABLE IF EXISTS `pp_items_pics`;
CREATE TABLE `pp_items_pics` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item_id` int(10) NOT NULL,
  `url` varchar(255) NOT NULL,
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_items_site`
--

DROP TABLE IF EXISTS `pp_items_site`;
CREATE TABLE `pp_items_site` (
  `id` smallint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `site_domain` varchar(255) NOT NULL,
  `site_logo` varchar(255) NOT NULL,
  `collect_url` varchar(255) NOT NULL,
  `collect_time` int(10) NOT NULL DEFAULT '0',
  `item_nums` int(10) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `is_dels` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_items_tags`
--

DROP TABLE IF EXISTS `pp_items_tags`;
CREATE TABLE `pp_items_tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `item_nums` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `seo_title` varchar(255) NOT NULL,
  `seo_keys` varchar(255) NOT NULL,
  `seo_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_items_tags_item`
--

DROP TABLE IF EXISTS `pp_items_tags_item`;
CREATE TABLE `pp_items_tags_item` (
  `item_id` int(10) NOT NULL,
  `tag_id` int(10) NOT NULL,
  KEY `item_id` (`item_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_nav`
--

DROP TABLE IF EXISTS `pp_nav`;
CREATE TABLE `pp_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` smallint(4) NOT NULL,
  `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-系统 0-自定义',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '导航位置1-顶部 2-底部',
  `in_site` tinyint(1) NOT NULL COMMENT '1-本站内 0-站外',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `seo_title` varchar(255) NOT NULL,
  `seo_keys` text NOT NULL,
  `seo_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_node`
--

DROP TABLE IF EXISTS `pp_node`;
CREATE TABLE `pp_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `module_name` varchar(50) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_name` varchar(50) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned NOT NULL DEFAULT '0',
  `auth_type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  `often` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-不常用 1-常用',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0-不常用 1-常用',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `name` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_role`
--

DROP TABLE IF EXISTS `pp_role`;
CREATE TABLE `pp_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_setting`
--

DROP TABLE IF EXISTS `pp_setting`;
CREATE TABLE `pp_setting` (
  `name` varchar(100) NOT NULL,
  `data` text NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_user`
--

DROP TABLE IF EXISTS `pp_user`;
CREATE TABLE `pp_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `passwd` varchar(50) NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL DEFAULT '@',
  `sex` tinyint(4) NOT NULL DEFAULT '0',
  `brithday` date NOT NULL,
  `address` varchar(50) NOT NULL,
  `province` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `area` varchar(20) NOT NULL,
  `blog` varchar(200) NOT NULL,
  `info` varchar(500) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `add_time` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `last_time` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(15) DEFAULT '0',
  `share_num` int(11) DEFAULT '0',
  `like_num` int(11) DEFAULT '0',
  `follow_num` int(10) DEFAULT '0',
  `fans_num` int(10) DEFAULT '0',
  `album_num` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_user_comments`
--

DROP TABLE IF EXISTS `pp_user_comments`;
CREATE TABLE `pp_user_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0',
  `pid` int(10) NOT NULL DEFAULT '0',
  `info` text,
  `type` varchar(20) DEFAULT NULL,
  `orig` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `add_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_user_follow`
--

DROP TABLE IF EXISTS `pp_user_follow`;
CREATE TABLE `pp_user_follow` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fans_id` int(10) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_user_history`
--

DROP TABLE IF EXISTS `pp_user_history`;
CREATE TABLE `pp_user_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0',
  `add_time` int(10) NOT NULL DEFAULT '0',
  `info` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp_user_openid`
--

DROP TABLE IF EXISTS `pp_user_openid`;
CREATE TABLE `pp_user_openid` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '0',
  `info` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
