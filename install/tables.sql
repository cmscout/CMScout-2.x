-- 
-- Table structure for table `!#prefix#!advancements`
-- 

CREATE TABLE `!#prefix#!advancements` (
  `ID` int(11) NOT NULL auto_increment,
  `advancement` varchar(30) default NULL,
  `position` mediumint(9) NOT NULL default '0',
  `scheme` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!album_track`
-- 

CREATE TABLE `!#prefix#!album_track` (
  `ID` int(11) NOT NULL auto_increment,
  `album_name` varchar(80) default NULL,
  `patrol` int(11) default NULL,
  `allowed` tinyint(4) NOT NULL default '0',
  `trash` tinyint(4) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!articletopics`
-- 

CREATE TABLE `!#prefix#!articletopics` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `sort` varchar(50) NOT NULL,
  `order` varchar(6) NOT NULL,
  `groups` longtext NOT NULL,
  `display` tinyint(4) NOT NULL,
  `perpage` tinyint(4) NOT NULL default '10',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!attendies`
-- 

CREATE TABLE `!#prefix#!attendies` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `option` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!auth`
-- 

CREATE TABLE `!#prefix#!auth` (
  `id` int(11) NOT NULL auto_increment,
  `authname` varchar(50) NOT NULL default '0',
  `dynamic` longtext NOT NULL,
  `permission` longtext NOT NULL,
  `static` longtext NOT NULL,
  `subsites` longtext NOT NULL,
  `type` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!awardschemes`
-- 

CREATE TABLE `!#prefix#!awardschemes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!badges`
-- 

CREATE TABLE `!#prefix#!badges` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `scheme` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!calendar_downloads`
-- 

CREATE TABLE `!#prefix#!calendar_downloads` (
  `id` int(11) NOT NULL auto_increment,
  `eid` int(11) NOT NULL,
  `did` int(11) NOT NULL,
  `permission` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!calendar_items`
-- 

CREATE TABLE `!#prefix#!calendar_items` (
  `id` int(11) NOT NULL auto_increment,
  `summary` varchar(50) NOT NULL default '',
  `startdate` int(11) NOT NULL default '0',
  `enddate` int(11) NOT NULL default '0',
  `detail` longtext,
  `allowed` tinyint(4) NOT NULL default '0',
  `groups` longtext NOT NULL,
  `date_post` int(11) NOT NULL,
  `colour` varchar(7) NOT NULL,
  `signup` tinyint(4) NOT NULL,
  `signupusers` tinyint(4) NOT NULL,
  `patrols` longtext NOT NULL,
  `trash` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `detail` (`summary`,`detail`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!censorwords`
-- 

CREATE TABLE `!#prefix#!censorwords` (
  `id` int(11) NOT NULL auto_increment,
  `word` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!comments`
-- 

CREATE TABLE `!#prefix#!comments` (
  `id` int(11) NOT NULL auto_increment,
  `item_id` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `comment` mediumtext NOT NULL,
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!config`
-- 

CREATE TABLE `!#prefix#!config` (
  `name` varchar(30) NOT NULL default '',
  `value` longtext,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!downloads`
-- 

CREATE TABLE `!#prefix#!downloads` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `descs` longtext NOT NULL,
  `cat` int(11) NOT NULL default '0',
  `file` varchar(50) NOT NULL default '',
  `saved_file` varchar(32) NOT NULL,
  `thumbnail` int(11) default NULL,
  `numdownloads` int(11) NOT NULL default '0',
  `size` int(11) NOT NULL default '0',
  `allowed` tinyint(4) NOT NULL default '0',
  `trash` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!download_cats`
-- 

CREATE TABLE `!#prefix#!download_cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `upauth` longtext NOT NULL,
  `downauth` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!emails`
-- 

CREATE TABLE `!#prefix#!emails` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(15) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` longtext NOT NULL,
  `subject` varchar(255) NOT NULL,
  `customtags` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumauths`
-- 

CREATE TABLE `!#prefix#!forumauths` (
  `forum_id` int(11) NOT NULL default '0',
  `new_topic` longtext,
  `reply_topic` longtext,
  `edit_post` longtext,
  `delete_post` longtext,
  `view_forum` longtext,
  `read_topics` longtext,
  `sticky` longtext NOT NULL,
  `announce` longtext NOT NULL,
  `poll` longtext NOT NULL,
  PRIMARY KEY  (`forum_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forummods`
-- 

CREATE TABLE `!#prefix#!forummods` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL default '0',
  `mid` int(11) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumnew`
-- 

CREATE TABLE `!#prefix#!forumnew` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `topic` int(11) NOT NULL default '0',
  `forum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumpollitems`
-- 

CREATE TABLE `!#prefix#!forumpollitems` (
  `id` int(11) NOT NULL auto_increment,
  `poll_id` int(11) NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  `pos` tinyint(4) NOT NULL default '0',
  `results` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumpolls`
-- 

CREATE TABLE `!#prefix#!forumpolls` (
  `id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL default '0',
  `question` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumpollvoters`
-- 

CREATE TABLE `!#prefix#!forumpollvoters` (
  `poll_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumposts`
-- 

CREATE TABLE `!#prefix#!forumposts` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) default NULL,
  `posttext` longtext NOT NULL,
  `userposted` int(11) NOT NULL,
  `dateposted` int(12) default NULL,
  `topic` int(11) NOT NULL default '0',
  `edittime` int(11) NOT NULL default '0',
  `edituser` int(11) NOT NULL,
  `attachment` varchar(20) default NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `subject` (`subject`,`posttext`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forums`
-- 

CREATE TABLE `!#prefix#!forums` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `desc` varchar(255) default NULL,
  `lasttopic` int(11) default NULL,
  `lastpost` int(11) default NULL,
  `lastdate` int(11) default NULL,
  `cat` int(11) NOT NULL default '0',
  `pos` int(11) NOT NULL default '1',
  `parent` int(11) NOT NULL default '0',
  `limit` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumscats`
-- 

CREATE TABLE `!#prefix#!forumscats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumstopicwatch`
-- 

CREATE TABLE `!#prefix#!forumstopicwatch` (
  `topic_id` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL,
  `notify` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!forumtopics`
-- 

CREATE TABLE `!#prefix#!forumtopics` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL default '',
  `desc` varchar(255) default NULL,
  `numviews` int(11) default '0',
  `userposted` int(11) NOT NULL,
  `dateposted` int(11) NOT NULL default '0',
  `lastpost` int(11) NOT NULL,
  `lastdate` int(11) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  `forum` int(11) NOT NULL default '0',
  `locked` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!frontpage`
-- 

CREATE TABLE `!#prefix#!frontpage` (
  `id` int(11) NOT NULL auto_increment,
  `item` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!functions`
-- 

CREATE TABLE `!#prefix#!functions` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `code` longtext NOT NULL,
  `type` tinyint(4) NOT NULL default '0',
  `filetouse` varchar(50) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '1',
  `mainmodule` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!groups`
-- 

CREATE TABLE `!#prefix#!groups` (
  `id` int(4) NOT NULL auto_increment,
  `teamname` varchar(50) NOT NULL default '',
  `ispatrol` tinyint(4) NOT NULL default '0',
  `ispublic` tinyint(4) NOT NULL default '0',
  `getpoints` tinyint(4) NOT NULL default '0',
  `points` int(11) NOT NULL default '0',
  `normaladmin` longtext,
  `agladmin` longtext,
  `gladmin` longtext,
  PRIMARY KEY  (`id`),
  KEY `teamname` (`teamname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!ical_items`
-- 

CREATE TABLE `!#prefix#!ical_items` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL,
  `groups` longtext NOT NULL,
  `colour` varchar(7) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!links`
-- 

CREATE TABLE `!#prefix#!links` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `url` mediumtext NOT NULL,
  `desc` longtext,
  `cat` int(11) NOT NULL default '0',
  `position` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!links_cats`
-- 

CREATE TABLE `!#prefix#!links_cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `position` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!members`
-- 

CREATE TABLE `!#prefix#!members` (
  `id` int(11) NOT NULL auto_increment,
  `firstName` varchar(100) NOT NULL,
  `middleName` varchar(100) default NULL,
  `lastName` varchar(100) NOT NULL,
  `dob` int(11) default NULL,
  `sex` tinyint(4) NOT NULL,
  `address` varchar(255) default NULL,
  `cell` varchar(50) default NULL,
  `home` varchar(50) default NULL,
  `work` varchar(50) default NULL,
  `email` varchar(255) default NULL,
  `aidName` varchar(100) default NULL,
  `aidNumber` varchar(100) default NULL,
  `docName` varchar(100) default NULL,
  `docNumber` varchar(50) default NULL,
  `medicalDetails` varchar(255) default NULL,
  `section` int(11) NOT NULL,
  `patrol` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `userId` int(11) NOT NULL,
  `fatherId` int(11) NOT NULL,
  `motherId` int(11) NOT NULL,
  `primaryGuard` tinyint(4) NOT NULL,
  `awardScheme` int(11) NOT NULL,
  `custom` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!menu_cats`
-- 

CREATE TABLE `!#prefix#!menu_cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `numitems` int(11) NOT NULL default '0',
  `position` mediumint(9) NOT NULL default '0',
  `side` varchar(5) NOT NULL default '',
  `showhead` tinyint(4) NOT NULL default '1',
  `showwhen` int(11) NOT NULL default '0',
  `expanded` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL default '0',
  `groups` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!menu_items`
-- 

CREATE TABLE `!#prefix#!menu_items` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `cat` int(11) NOT NULL default '0',
  `item` varchar(50) default NULL,
  `pos` mediumint(9) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  `parent` int(11) NOT NULL default '0',
  `target` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!messages`
-- 

CREATE TABLE `!#prefix#!messages` (
  `id` int(11) NOT NULL auto_increment,
  `uid` varchar(32) NOT NULL,
  `message` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `post` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!newscontent`
-- 

CREATE TABLE `!#prefix#!newscontent` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `news` longtext NOT NULL,
  `event` int(11) NOT NULL default '0',
  `attachment` varchar(20) NOT NULL,
  `allowed` tinyint(4) NOT NULL default '0',
  `trash` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`,`news`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!onlineusers`
-- 

CREATE TABLE `!#prefix#!onlineusers` (
  `uid` varchar(32) NOT NULL default '',
  `uname` varchar(50) NOT NULL default '',
  `logon` int(11) NOT NULL default '0',
  `lastupdate` int(11) NOT NULL default '0',
  `isactive` tinyint(1) NOT NULL default '0',
  `pages` int(11) NOT NULL default '0',
  `location` varchar(30) NOT NULL default '',
  `locchange` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL,
  `isguest` tinyint(4) NOT NULL,
  `isbot` tinyint(4) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!owners`
-- 

CREATE TABLE `!#prefix#!owners` (
  `id` int(11) NOT NULL auto_increment,
  `item_id` int(11) NOT NULL,
  `item_type` varchar(20) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` tinyint(4) NOT NULL,
  `type_owner` tinyint(4) NOT NULL,
  `expire` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!pagecontent`
-- 

CREATE TABLE `!#prefix#!pagecontent` (
  `pageid` int(11) NOT NULL default '0',
  `pagenum` int(11) NOT NULL default '0',
  `content` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!pagetracking`
-- 

CREATE TABLE `!#prefix#!pagetracking` (
  `id` int(11) NOT NULL auto_increment,
  `pagename` varchar(20) NOT NULL default '',
  `numpages` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!patrollog`
-- 

CREATE TABLE `!#prefix#!patrollog` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `dateposted` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `itemdetails` longtext NOT NULL,
  `group` varchar(50) NOT NULL default '',
  `private` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `title` (`title`,`itemdetails`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!patrolmenu`
-- 

CREATE TABLE `!#prefix#!patrolmenu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `item` varchar(255) default NULL,
  `type` tinyint(4) NOT NULL,
  `patrol` int(11) NOT NULL,
  `pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!patrol_articles`
-- 

CREATE TABLE `!#prefix#!patrol_articles` (
  `ID` int(11) NOT NULL auto_increment,
  `patrol` int(11) default '0',
  `pic` int(11) default NULL,
  `title` varchar(255) NOT NULL default '',
  `detail` longtext NOT NULL,
  `date_post` int(11) default NULL,
  `album_id` int(3) default NULL,
  `event_id` int(11) default NULL,
  `author` varchar(50) NOT NULL default '',
  `allowed` tinyint(4) NOT NULL default '0',
  `topics` longtext,
  `order` tinyint(4) default NULL,
  `summary` mediumtext,
  `related` longtext,
  `trash` tinyint(4) NOT NULL,
  PRIMARY KEY  (`ID`),
  FULLTEXT KEY `title` (`title`,`detail`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!photos`
-- 

CREATE TABLE `!#prefix#!photos` (
  `ID` int(11) NOT NULL auto_increment,
  `filename` varchar(80) default NULL,
  `caption` longtext,
  `album_id` int(10) default NULL,
  `date` int(11) NOT NULL default '0',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!pms`
-- 

CREATE TABLE `!#prefix#!pms` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL default '',
  `text` longtext NOT NULL,
  `date` int(11) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  `readpm` tinyint(4) NOT NULL default '0',
  `newpm` tinyint(4) NOT NULL default '0',
  `fromuser` varchar(50) NOT NULL default '',
  `touser` longtext,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `subject` (`subject`,`text`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!polls`
-- 

CREATE TABLE `!#prefix#!polls` (
  `id` int(11) NOT NULL auto_increment,
  `question` varchar(255) NOT NULL default '',
  `sidebox` tinyint(4) NOT NULL default '0',
  `date_start` int(11) NOT NULL default '0',
  `date_stop` int(11) default '0',
  `options` longtext NOT NULL,
  `results` longtext NOT NULL,
  `allowed` tinyint(4) NOT NULL default '0',
  `trash` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!pollvoters`
-- 

CREATE TABLE `!#prefix#!pollvoters` (
  `poll_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `ip` varchar(20) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!profilefields`
-- 

CREATE TABLE `!#prefix#!profilefields` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `query` varchar(255) NOT NULL,
  `options` longtext NOT NULL,
  `hint` varchar(255) default NULL,
  `type` tinyint(4) NOT NULL,
  `required` tinyint(4) NOT NULL,
  `register` tinyint(4) NOT NULL,
  `profileview` tinyint(4) NOT NULL,
  `pos` int(11) NOT NULL,
  `place` tinyint(4) NOT NULL,
  `eventid` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!review`
-- 

CREATE TABLE `!#prefix#!review` (
  `item_id` int(11) NOT NULL auto_increment,
  `type` varchar(20) NOT NULL,
  PRIMARY KEY  (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!requirements`
-- 

CREATE TABLE `!#prefix#!requirements` (
  `ID` int(11) NOT NULL auto_increment,
  `item` varchar(255) default NULL,
  `description` longtext NOT NULL,
  `advancement` int(11) default NULL,
  `position` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `award_id` (`advancement`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!rssfeeds`
-- 

CREATE TABLE `!#prefix#!rssfeeds` (
  `id` int(11) NOT NULL auto_increment,
  `itemid` varchar(50) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  `uname` varchar(32) NOT NULL default '',
  `userid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!scoutlanguage`
-- 

CREATE TABLE `!#prefix#!scoutlanguage` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!scoutrecord`
-- 

CREATE TABLE `!#prefix#!scoutrecord` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `requirements` longtext NOT NULL,
  `comment` longtext,
  `scheme` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!sections`
-- 

CREATE TABLE `!#prefix#!sections` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!static_content`
-- 

CREATE TABLE `!#prefix#!static_content` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `content` longtext NOT NULL,
  `friendly` varchar(255) NOT NULL default '',
  `type` tinyint(4) NOT NULL,
  `frontpage` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `special` tinyint(4) NOT NULL,
  `trash` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `content` (`content`,`friendly`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!submenu`
-- 

CREATE TABLE `!#prefix#!submenu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `item` varchar(255) default NULL,
  `type` tinyint(4) NOT NULL,
  `site` varchar(50) NOT NULL default '',
  `pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!subsites`
-- 

CREATE TABLE `!#prefix#!subsites` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!themes`
-- 

CREATE TABLE `!#prefix#!themes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `dir` varchar(50) NOT NULL default '',
  `configfile` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!timezones`
-- 

CREATE TABLE IF NOT EXISTS `!#prefix#!timezones` (
  `id` mediumint(9) NOT NULL auto_increment,
  `offset` decimal(3,1) NOT NULL default '0.0',
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!userbadges`
-- 

CREATE TABLE `!#prefix#!userbadges` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `badgeid` int(11) NOT NULL,
  `comment` varchar(255) default NULL,
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!usergroups`
-- 

CREATE TABLE `!#prefix#!usergroups` (
  `groupid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `utype` tinyint(4) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `!#prefix#!users`
-- 

CREATE TABLE `!#prefix#!users` (
  `id` int(11) NOT NULL auto_increment,
  `uid` varchar(32) default '',
  `uname` varchar(50) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `status` tinyint(2) NOT NULL default '1',
  `joined` int(11) NOT NULL default '0',
  `lastlogin` int(11) default NULL,
  `prevlogin` int(11) default NULL,
  `logincount` int(11) default NULL,
  `theme_id` int(11) NOT NULL default '0',
  `timezone` tinyint(4) NOT NULL default '0',
  `activationcode` varchar(32) default '',
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `avyfile` varchar(50) default NULL,
  `sig` varchar(255) default NULL,
  `newtopic` tinyint(4) NOT NULL default '0',
  `allowemail` tinyint(4) NOT NULL default '1',
  `newpm` tinyint(4) NOT NULL default '1',
  `numposts` int(11) NOT NULL default '0',
  `publicprofile` tinyint(4) NOT NULL default '0',
  `numtopics` int(11) NOT NULL default '0',
  `numalbums` int(11) NOT NULL default '0',
  `numphotos` int(11) NOT NULL default '0',
  `numarticles` int(11) NOT NULL default '0',
  `numnews` int(11) NOT NULL default '0',
  `numdown` int(11) NOT NULL default '0',
  `numevent` int(11) NOT NULL default '0',
  `showemail` tinyint(4) NOT NULL default '0',
  `showname` tinyint(4) NOT NULL default '0',
  `showrecord` tinyint(4) NOT NULL default '0',
  `replytopic` tinyint(4) NOT NULL,
  `newarticle` tinyint(4) NOT NULL,
  `newevent` tinyint(4) NOT NULL,
  `newalbum` tinyint(4) NOT NULL,
  `newnews` tinyint(4) NOT NULL,
  `newdownload` tinyint(4) NOT NULL,
  `newpoll` tinyint(4) NOT NULL,
  `custom` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
