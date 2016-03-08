
--
-- 表的结构 `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `passwd` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `openid` varchar(100) NOT NULL ,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;