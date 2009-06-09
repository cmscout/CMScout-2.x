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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


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

DROP TABLE `!#prefix#!badges`;

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
-- Table structure for table `!#prefix#!forummods`
-- 

CREATE TABLE `!#prefix#!forummods` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL default '0',
  `mid` int(11) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


DROP TABLE `!#prefix#!frontpage`;
-- --------------------------------------------------------

-- 
-- Table structure for table !#prefix#!frontpage
-- 

CREATE TABLE !#prefix#!frontpage (
  id int(11) NOT NULL auto_increment,
  item int(11) NOT NULL,
  type tinyint(4) NOT NULL,
  pos int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
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
  `normaladmin` longtext ,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
-- Table structure for table `!#prefix#!onlineusers`
-- 

DROP TABLE  `!#prefix#!onlineusers`;

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
-- Table structure for table `!#prefix#!rssfeeds`
-- 
DROP TABLE `!#prefix#!rssfeeds`;

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
DROP TABLE `!#prefix#!scoutrecord`;
CREATE TABLE `!#prefix#!scoutrecord` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `requirements` longtext NOT NULL,
  `comment` longtext,
  `scheme` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

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
