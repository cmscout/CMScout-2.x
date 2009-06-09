#@#INSERT INTO `!#prefix#!auth` (`id`, `authname`, `dynamic`, `permission`, `static`, `subsites`, `type`) VALUES 
(1, '2', 'a:19:{i:11;s:1:"1";i:34;s:1:"1";i:10;s:1:"1";i:9;s:1:"1";i:31;s:1:"1";i:29;s:1:"1";i:19;s:1:"1";i:37;s:1:"1";i:4;s:1:"1";i:45;s:1:"1";i:32;s:1:"1";i:2;s:1:"1";i:16;s:1:"1";i:12;s:1:"1";i:49;s:1:"1";i:14;s:1:"1";i:53;s:1:"1";i:15;s:1:"1";i:48;s:1:"1";}', 'a:8:{i:21;s:1:"1";i:23;s:1:"1";i:18;s:1:"1";i:24;s:1:"1";i:25;s:1:"1";i:20;s:1:"1";i:38;s:1:"1";i:17;s:1:"1";}', 'a:2:{i:1;s:1:"1";i:2;s:1:"1";}', 'N;', 2),
(2, '-1', 'a:19:{i:11;s:1:"1";i:34;s:1:"1";i:10;s:1:"1";i:9;s:1:"1";i:31;s:1:"1";i:29;s:1:"1";i:19;s:1:"0";i:37;s:1:"1";i:4;s:1:"1";i:45;s:1:"1";i:32;s:1:"0";i:2;s:1:"1";i:16;s:1:"0";i:12;s:1:"1";i:49;s:1:"1";i:14;s:1:"0";i:53;s:1:"0";i:15;s:1:"0";i:48;s:1:"1";}', 'a:8:{i:21;s:1:"0";i:23;s:1:"0";i:18;s:1:"0";i:24;s:1:"0";i:25;s:1:"0";i:20;s:1:"0";i:38;s:1:"0";i:17;s:1:"1";}', 'a:2:{i:1;s:1:"1";i:2;s:1:"1";}', 'N;', 1);

#@#INSERT INTO `!#prefix#!forumauths` (`forum_id`, `new_topic`, `reply_topic`, `edit_post`, `delete_post`, `view_forum`, `read_topics`, `sticky`, `announce`, `poll`) VALUES 
(1, 'a:2:{i:1;s:1:"1";i:2;s:1:"1";}', 'a:2:{i:1;s:1:"1";i:2;s:1:"1";}', 'a:1:{i:1;s:1:"1";}', 'a:1:{i:1;s:1:"1";}', 'a:4:{i:-1;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";}', 'a:4:{i:-1;s:1:"1";i:1;s:1:"1";i:2;s:1:"1";i:3;s:1:"1";}', 'a:1:{i:1;s:1:"1";}', 'a:1:{i:1;s:1:"1";}', 'a:1:{i:1;s:1:"1";}');

#@#INSERT INTO `!#prefix#!forummods` (`id`, `fid`, `mid`, `type`) VALUES 
(1, 1, 1, 1);

#@#INSERT INTO `!#prefix#!forumposts` (`id`, `subject`, `posttext`, `userposted`, `dateposted`, `topic`, `edittime`, `edituser`, `attachment`) VALUES 
(1, 'Welcome', '<p>Welcome to CMScout 2.</p><p>If you have any questions or issues please don''t hesitate to post on the official CMScout forums at <a href="http://www.cmscout.za.net">www.cmscout.za.net</a>&nbsp;</p>', 1, 1193827547, 1, 0, 0, '0'); 

#@#INSERT INTO `!#prefix#!forums` (`id`, `name`, `desc`, `lasttopic`, `lastpost`, `lastdate`, `cat`, `pos`, `parent`, `limit`) VALUES 
(1, 'Test Forum', 'This is a test forum', 1, '1', 1193827547, 1, 1, 0, 0);

#@#INSERT INTO `!#prefix#!forumscats` (`id`, `name`, `pos`) VALUES 
(1, 'Test Category', 1);

#@#INSERT INTO `!#prefix#!forumtopics` (`id`, `subject`, `desc`, `numviews`, `userposted`, `dateposted`, `lastpost`, `lastdate`, `type`, `forum`, `locked`) VALUES 
(1, 'Welcome', 'Welcome to the CMScout 2 forums', 4, 1, 1193827547, 1, 1193827547, 0, 1, 1);

#@#INSERT INTO `!#prefix#!frontpage` (`id`, `item`, `type`, `pos`) VALUES 
(1, 1, 0, 1);

#@#INSERT INTO `!#prefix#!static_content` (`id`, `name`, `content`, `friendly`, `type`, `frontpage`, `pid`, `special`, `trash`) VALUES 
(1, 'welcome', '<h1>Welcome to CMScout 2</h1><p><strong>CMScout is a free, open source content management and publishing system designed to give non-profit organisations (such as Scouts) an easy, quick way to get a presence on the internet.</strong></p><p>CMScout provides an easy to use, user friendly graphical interface for managing your website. You never have to see a single line of code, and yet you can still have a professional website with advanced features such as forums, downloads, photo albums, calendars and much more.&nbsp;</p>', 'Welcome to CMScout 2', 0, 0, 0, 0, 0),
(2, 'whatnew', '<p>CMScout 2.00 represents a new phase in the development of CMScout. It was decided early on in the development of CMScout 2.00 to focus on usability and stability.</p><p>If you have used CMScout 1.x previously, the first thing that you will notice in version 2 is the new administration panel. Many hours have been spent in developing and testing the new administration panel in order to make it as user friendly and easy to use as possible. </p><p>Some other new or improved features in CMScout 2.00:</p><ul><li>Rewritten calendar that includes the ability for members to sign up for events</li><li>&nbsp;Article Topics</li><li>New Photo Album</li><li>Sub forums</li><li>Improved group management, users can now belong to more then one group</li><li>Customisable administration panel access (on a per-group basis)</li><li>Membership management, allows you to manage members of your organisations, even if they are not registered on the website</li><li>Custom profile fields</li></ul>', 'What''s new in 2.00', 0, 0, 0, 0, 0);

#@#INSERT INTO `!#prefix#!menu_cats` (`id`, `name`, `numitems`, `position`, `side`, `showhead`, `showwhen`, `expanded`, `published`, `groups`) VALUES 
(1, 'Main Menu', 5, 1, 'left', 1, 0, 0, 1, 'N;'),
(2, 'Personal', 1, 2, 'left', 1, 1, 0, 1, 'a:2:{i:1;s:1:"1";i:2;s:1:"1";}'),
(3, 'Login', 1, 1, 'right', 1, 0, 0, 1, 'N;');

#@#INSERT INTO `!#prefix#!menu_items` (`id`, `name`, `cat`, `item`, `pos`, `type`, `parent`, `target`) VALUES 
(1, 'login', 3, '1', 1, 3, 0, NULL),
(2, 'Home', 1, '4', 1, 2, 0, NULL),
(3, 'Forums', 1, '31', 3, 2, 0, NULL),
(4, 'What''s new', 1, '2', 2, 1, 0, NULL),
(5, 'CMScout', 1, 'www.cmscout.za.net', 4, 5, 0, NULL),
(6, 'Manual', 1, 'manual.cmscout.za.net', 5, 5, 0, NULL),
(7, 'User Control Panel', 2, '53', 1, 2, 0, NULL);
