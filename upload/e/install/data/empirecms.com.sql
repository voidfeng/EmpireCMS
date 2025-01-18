# --------------------------------------------------------
# 
# 帝国网站管理系统 
# 
# http://www.PHome.Net
# 
# EmpireCMS Version 8.0
# 
# --------------------------------------------------------


# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsad;
CREATE TABLE `phome_enewsad` (
  `adid` int(10) unsigned NOT NULL auto_increment,
  `picurl` varchar(255) NOT NULL default '',
  `url` text NOT NULL,
  `pic_width` int(10) unsigned NOT NULL default '0',
  `pic_height` int(10) unsigned NOT NULL default '0',
  `onclick` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `adtype` tinyint(3) unsigned NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `target` varchar(10) NOT NULL default '',
  `alt` varchar(120) NOT NULL default '',
  `starttime` date NOT NULL default '0000-00-00',
  `endtime` date NOT NULL default '0000-00-00',
  `adsay` varchar(255) NOT NULL default '',
  `titlefont` varchar(14) NOT NULL default '',
  `titlecolor` varchar(10) NOT NULL default '',
  `htmlcode` text NOT NULL,
  `t` tinyint(3) unsigned NOT NULL default '0',
  `ylink` tinyint(1) NOT NULL default '0',
  `reptext` text NOT NULL,
  PRIMARY KEY  (`adid`),
  KEY `classid` (`classid`),
  KEY `t` (`t`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsadclass;
CREATE TABLE `phome_enewsadclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` char(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsadminstyle;
CREATE TABLE `phome_enewsadminstyle` (
  `styleid` smallint(5) unsigned NOT NULL auto_increment,
  `stylename` char(30) NOT NULL default '',
  `path` smallint(5) unsigned NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`styleid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsag;
CREATE TABLE `phome_enewsag` (
  `agid` int(10) unsigned NOT NULL auto_increment,
  `agname` varchar(60) NOT NULL default '',
  `isadmin` tinyint(1) NOT NULL default '0',
  `auids` text NOT NULL,
  PRIMARY KEY  (`agid`),
  KEY `isadmin` (`isadmin`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsautodo;
CREATE TABLE `phome_enewsautodo` (
  `doid` bigint(20) unsigned NOT NULL auto_increment,
  `dotype` char(50) NOT NULL default '',
  `classid` int(11) NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `tid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `dotime` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `pid` int(11) NOT NULL default '0',
  `ckstr` char(32) NOT NULL default '',
  `fname` char(255) NOT NULL default '',
  `ecmspno` char(32) NOT NULL default '',
  `ckpass` char(32) NOT NULL default '',
  PRIMARY KEY  (`doid`),
  KEY `userid` (`userid`),
  KEY `pid` (`pid`),
  KEY `dotime` (`dotime`),
  KEY `ckstr` (`ckstr`),
  KEY `ecmspno` (`ecmspno`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsbefrom;
CREATE TABLE `phome_enewsbefrom` (
  `befromid` smallint(5) unsigned NOT NULL auto_increment,
  `sitename` char(60) NOT NULL default '',
  `siteurl` char(200) NOT NULL default '',
  PRIMARY KEY  (`befromid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsbq;
CREATE TABLE `phome_enewsbq` (
  `bqid` smallint(5) unsigned NOT NULL auto_increment,
  `bqname` varchar(60) NOT NULL default '',
  `bqsay` text NOT NULL,
  `funname` varchar(60) NOT NULL default '',
  `bq` varchar(60) NOT NULL default '',
  `issys` tinyint(1) NOT NULL default '0',
  `bqgs` text NOT NULL,
  `isclose` tinyint(1) NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bqid`),
  KEY `classid` (`classid`),
  KEY `isclose` (`isclose`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsbqclass;
CREATE TABLE `phome_enewsbqclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` char(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsbuybak;
CREATE TABLE `phome_enewsbuybak` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` char(25) NOT NULL default '',
  `card_no` char(120) NOT NULL default '',
  `buytime` datetime NOT NULL default '0000-00-00 00:00:00',
  `cardfen` int(10) unsigned NOT NULL default '0',
  `money` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `userdate` int(10) unsigned NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `type` (`type`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsbuygroup;
CREATE TABLE `phome_enewsbuygroup` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `gname` varchar(255) NOT NULL default '',
  `gmoney` int(10) unsigned NOT NULL default '0',
  `gfen` int(10) unsigned NOT NULL default '0',
  `gdate` int(10) unsigned NOT NULL default '0',
  `ggroupid` smallint(5) unsigned NOT NULL default '0',
  `gzgroupid` smallint(5) unsigned NOT NULL default '0',
  `buygroupid` smallint(5) unsigned NOT NULL default '0',
  `gsay` text NOT NULL,
  `myorder` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewscard;
CREATE TABLE `phome_enewscard` (
  `cardid` int(10) unsigned NOT NULL auto_increment,
  `card_no` char(30) NOT NULL default '',
  `password` char(20) NOT NULL default '',
  `money` int(10) unsigned NOT NULL default '0',
  `cardfen` int(10) unsigned NOT NULL default '0',
  `endtime` date NOT NULL default '0000-00-00',
  `cardtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `carddate` int(10) unsigned NOT NULL default '0',
  `cdgroupid` smallint(5) unsigned NOT NULL default '0',
  `cdzgroupid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cardid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclass;
CREATE TABLE `phome_enewsclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `bclassid` smallint(5) unsigned NOT NULL default '0',
  `classname` varchar(50) NOT NULL default '',
  `sonclass` text NOT NULL,
  `is_zt` tinyint(1) NOT NULL default '0',
  `lencord` smallint(6) NOT NULL default '0',
  `link_num` tinyint(4) NOT NULL default '0',
  `newstempid` smallint(6) NOT NULL default '0',
  `onclick` int(11) NOT NULL default '0',
  `listtempid` smallint(6) NOT NULL default '0',
  `featherclass` text NOT NULL,
  `islast` tinyint(1) NOT NULL default '0',
  `classpath` text NOT NULL,
  `classtype` varchar(10) NOT NULL default '',
  `newspath` varchar(20) NOT NULL default '',
  `filename` tinyint(1) NOT NULL default '0',
  `filetype` varchar(10) NOT NULL default '',
  `openpl` tinyint(1) NOT NULL default '0',
  `openadd` tinyint(1) NOT NULL default '0',
  `newline` int(11) NOT NULL default '0',
  `hotline` int(11) NOT NULL default '0',
  `goodline` int(11) NOT NULL default '0',
  `classurl` varchar(200) NOT NULL default '',
  `groupid` int(11) NOT NULL default '0',
  `myorder` smallint(6) NOT NULL default '0',
  `filename_qz` varchar(20) NOT NULL default '',
  `hotplline` tinyint(4) NOT NULL default '0',
  `modid` smallint(6) NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `firstline` tinyint(4) NOT NULL default '0',
  `bname` varchar(50) NOT NULL default '',
  `islist` tinyint(1) NOT NULL default '0',
  `searchtempid` smallint(6) NOT NULL default '0',
  `tid` smallint(6) NOT NULL default '0',
  `tbname` varchar(60) NOT NULL default '',
  `maxnum` int(11) NOT NULL default '0',
  `checkpl` tinyint(1) NOT NULL default '0',
  `down_num` tinyint(4) NOT NULL default '0',
  `online_num` tinyint(4) NOT NULL default '0',
  `listorder` varchar(50) NOT NULL default '',
  `reorder` varchar(50) NOT NULL default '',
  `intro` text NOT NULL,
  `classimg` varchar(255) NOT NULL default '',
  `jstempid` smallint(6) NOT NULL default '0',
  `addinfofen` int(11) NOT NULL default '0',
  `listdt` tinyint(1) NOT NULL default '0',
  `showclass` tinyint(1) NOT NULL default '0',
  `showdt` tinyint(1) NOT NULL default '0',
  `checkqadd` tinyint(1) NOT NULL default '0',
  `qaddlist` tinyint(1) NOT NULL default '0',
  `qaddgroupid` int(11) NOT NULL default '0',
  `qaddshowkey` tinyint(1) NOT NULL default '0',
  `adminqinfo` tinyint(1) NOT NULL default '0',
  `doctime` smallint(6) NOT NULL default '0',
  `classpagekey` varchar(255) NOT NULL default '',
  `dtlisttempid` smallint(6) NOT NULL default '0',
  `classtempid` smallint(6) NOT NULL default '0',
  `nreclass` tinyint(1) NOT NULL default '0',
  `nreinfo` tinyint(1) NOT NULL default '0',
  `nrejs` tinyint(1) NOT NULL default '0',
  `nottobq` tinyint(1) NOT NULL default '0',
  `ipath` varchar(255) NOT NULL default '',
  `addreinfo` tinyint(1) NOT NULL default '0',
  `haddlist` tinyint(4) NOT NULL default '0',
  `sametitle` tinyint(1) NOT NULL default '0',
  `definfovoteid` smallint(6) NOT NULL default '0',
  `wburl` varchar(255) NOT NULL default '',
  `qeditchecked` tinyint(1) NOT NULL default '0',
  `wapstyleid` smallint(6) NOT NULL default '0',
  `repreinfo` tinyint(1) NOT NULL default '0',
  `pltempid` smallint(6) NOT NULL default '0',
  `cgroupid` int(11) NOT NULL default '0',
  `yhid` smallint(6) NOT NULL default '0',
  `wfid` smallint(6) NOT NULL default '0',
  `cgtoinfo` tinyint(1) NOT NULL default '0',
  `bdinfoid` varchar(25) NOT NULL default '',
  `repagenum` smallint(5) unsigned NOT NULL default '0',
  `keycid` smallint(6) NOT NULL default '0',
  `allinfos` int(10) unsigned NOT NULL default '0',
  `infos` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `oneinfo` smallint(6) NOT NULL default '0',
  `addsql` varchar(255) NOT NULL default '',
  `wapislist` tinyint(1) NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  `ecmsvpf` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`classid`),
  KEY `bclassid` (`bclassid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclass_stats;
CREATE TABLE `phome_enewsclass_stats` (
  `classid` smallint(5) unsigned NOT NULL default '0',
  `uptime` int(10) unsigned NOT NULL default '0',
  `pvall` int(10) unsigned NOT NULL default '0',
  `pvyear` int(10) unsigned NOT NULL default '0',
  `pvhalfyear` int(10) unsigned NOT NULL default '0',
  `pvquarter` int(10) unsigned NOT NULL default '0',
  `pvmonth` int(10) unsigned NOT NULL default '0',
  `pvweek` int(10) unsigned NOT NULL default '0',
  `pvday` int(10) unsigned NOT NULL default '0',
  `pvyesterday` int(10) unsigned NOT NULL default '0',
  `ipall` int(10) unsigned NOT NULL default '0',
  `ipyear` int(10) unsigned NOT NULL default '0',
  `iphalfyear` int(10) unsigned NOT NULL default '0',
  `ipquarter` int(10) unsigned NOT NULL default '0',
  `ipmonth` int(10) unsigned NOT NULL default '0',
  `ipweek` int(10) unsigned NOT NULL default '0',
  `ipday` int(10) unsigned NOT NULL default '0',
  `ipyesterday` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclass_stats_ip;
CREATE TABLE `phome_enewsclass_stats_ip` (
  `ip` char(50) NOT NULL default '',
  PRIMARY KEY  (`ip`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclass_stats_set;
CREATE TABLE `phome_enewsclass_stats_set` (
  `openstats` tinyint(1) NOT NULL default '0',
  `pvtime` int(10) unsigned NOT NULL default '0',
  `statsdate` int(10) unsigned NOT NULL default '0',
  `changedate` int(10) unsigned NOT NULL default '0'
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclassadd;
CREATE TABLE `phome_enewsclassadd` (
  `classid` smallint(5) unsigned NOT NULL default '0',
  `classtext` mediumtext NOT NULL,
  `ttids` text NOT NULL,
  `eclasspagetext` mediumtext NOT NULL,
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclassf;
CREATE TABLE `phome_enewsclassf` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `f` varchar(30) NOT NULL default '',
  `fname` varchar(30) NOT NULL default '',
  `fform` varchar(20) NOT NULL default '',
  `fhtml` mediumtext NOT NULL,
  `fzs` varchar(255) NOT NULL default '',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `ftype` varchar(30) NOT NULL default '',
  `flen` varchar(20) NOT NULL default '',
  `fvalue` text NOT NULL,
  `fformsize` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclassnavcache;
CREATE TABLE `phome_enewsclassnavcache` (
  `navtype` char(16) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  `modid` smallint(5) unsigned NOT NULL default '0',
  KEY `navtype` (`navtype`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdiggips;
CREATE TABLE `phome_enewsdiggips` (
  `classid` smallint(5) unsigned NOT NULL default '0',
  `id` int(11) NOT NULL default '0',
  `ips` mediumtext NOT NULL,
  KEY `classid` (`classid`,`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdo;
CREATE TABLE `phome_enewsdo` (
  `doid` smallint(5) unsigned NOT NULL auto_increment,
  `doname` varchar(60) NOT NULL default '',
  `dotime` smallint(6) NOT NULL default '0',
  `isopen` tinyint(1) NOT NULL default '0',
  `doing` tinyint(4) NOT NULL default '0',
  `classid` text NOT NULL,
  `lasttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`doid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdolog;
CREATE TABLE `phome_enewsdolog` (
  `logid` bigint(20) NOT NULL auto_increment,
  `logip` varchar(50) NOT NULL default '',
  `logtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `username` varchar(30) NOT NULL default '',
  `enews` varchar(30) NOT NULL default '',
  `doing` varchar(255) NOT NULL default '',
  `pubid` bigint(16) unsigned NOT NULL default '0',
  `ipport` varchar(6) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`logid`),
  KEY `pubid` (`pubid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdownerror;
CREATE TABLE `phome_enewsdownerror` (
  `errorid` int(10) unsigned NOT NULL auto_increment,
  `id` int(10) unsigned NOT NULL default '0',
  `errortext` varchar(255) NOT NULL default '',
  `errortime` datetime NOT NULL default '0000-00-00 00:00:00',
  `errorip` varchar(50) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `cid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`errorid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdownrecord;
CREATE TABLE `phome_enewsdownrecord` (
  `id` int(11) NOT NULL default '0',
  `pathid` int(11) NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `title` varchar(120) NOT NULL default '',
  `cardfen` int(11) NOT NULL default '0',
  `truetime` int(11) NOT NULL default '0',
  `classid` smallint(6) NOT NULL default '0',
  `online` tinyint(1) NOT NULL default '0',
  KEY `userid` (`userid`),
  KEY `truetime` (`truetime`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdownurlqz;
CREATE TABLE `phome_enewsdownurlqz` (
  `urlid` smallint(5) unsigned NOT NULL auto_increment,
  `urlname` varchar(30) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `downtype` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`urlid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdtuserpage;
CREATE TABLE `phome_enewsdtuserpage` (
  `aid` int(10) unsigned NOT NULL auto_increment,
  `aname` varchar(50) NOT NULL default '',
  `cid` int(10) unsigned NOT NULL default '0',
  `atype` tinyint(4) NOT NULL default '0',
  `isopen` tinyint(1) NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `edittime` int(10) unsigned NOT NULL default '0',
  `onclick` int(10) unsigned NOT NULL default '0',
  `avar` varchar(60) NOT NULL default '',
  `avarid` varchar(30) NOT NULL default '',
  `apass` varchar(255) NOT NULL default '',
  `rtype` tinyint(4) NOT NULL default '0',
  `actime` int(10) unsigned NOT NULL default '0',
  `aclast` int(10) unsigned NOT NULL default '0',
  `maxpage` int(11) NOT NULL default '0',
  `addcs` varchar(255) NOT NULL default '',
  `atemptext` mediumtext NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `cid` (`cid`),
  KEY `atype` (`atype`),
  KEY `avar` (`avar`),
  KEY `avarid` (`avarid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsdtuserpageclass;
CREATE TABLE `phome_enewsdtuserpageclass` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `cname` char(50) NOT NULL default '',
  `myorder` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewserrorclass;
CREATE TABLE `phome_enewserrorclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsf;
CREATE TABLE `phome_enewsf` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `f` varchar(30) NOT NULL default '',
  `fname` varchar(30) NOT NULL default '',
  `fform` varchar(20) NOT NULL default '',
  `fhtml` mediumtext NOT NULL,
  `fzs` varchar(255) NOT NULL default '',
  `isadd` tinyint(1) NOT NULL default '0',
  `isshow` tinyint(1) NOT NULL default '0',
  `iscj` tinyint(1) NOT NULL default '0',
  `cjhtml` mediumtext NOT NULL,
  `myorder` smallint(6) NOT NULL default '0',
  `ftype` varchar(30) NOT NULL default '',
  `flen` varchar(20) NOT NULL default '',
  `dotemp` tinyint(1) NOT NULL default '0',
  `tid` smallint(6) NOT NULL default '0',
  `tbname` varchar(60) NOT NULL default '',
  `savetxt` tinyint(1) NOT NULL default '0',
  `fvalue` text NOT NULL,
  `iskey` tinyint(1) NOT NULL default '0',
  `tobr` tinyint(1) NOT NULL default '0',
  `dohtml` tinyint(1) NOT NULL default '0',
  `qfhtml` mediumtext NOT NULL,
  `isonly` tinyint(1) NOT NULL default '0',
  `linkfieldval` varchar(30) NOT NULL default '',
  `samedata` tinyint(1) NOT NULL default '0',
  `fformsize` varchar(12) NOT NULL default '',
  `tbdataf` tinyint(1) NOT NULL default '0',
  `ispage` tinyint(1) NOT NULL default '0',
  `adddofun` varchar(255) NOT NULL default '',
  `editdofun` varchar(255) NOT NULL default '',
  `qadddofun` varchar(255) NOT NULL default '',
  `qeditdofun` varchar(255) NOT NULL default '',
  `linkfieldtb` varchar(60) NOT NULL default '',
  `linkfieldshow` varchar(30) NOT NULL default '',
  `editorys` tinyint(1) NOT NULL default '0',
  `issmalltext` tinyint(1) NOT NULL default '0',
  `fmvnum` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `tid` (`tid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfava;
CREATE TABLE `phome_enewsfava` (
  `favaid` bigint(20) NOT NULL auto_increment,
  `id` int(11) NOT NULL default '0',
  `favatime` datetime NOT NULL default '0000-00-00 00:00:00',
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `classid` smallint(6) NOT NULL default '0',
  `cid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`favaid`),
  KEY `userid` (`userid`),
  KEY `cid` (`cid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfavaclass;
CREATE TABLE `phome_enewsfavaclass` (
  `cid` int(11) NOT NULL auto_increment,
  `cname` varchar(30) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfeedback;
CREATE TABLE `phome_enewsfeedback` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `bid` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(120) NOT NULL default '',
  `saytext` text NOT NULL,
  `name` varchar(30) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `mycall` varchar(30) NOT NULL default '',
  `homepage` varchar(160) NOT NULL default '',
  `company` varchar(80) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `saytime` datetime NOT NULL default '0000-00-00 00:00:00',
  `job` varchar(36) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `filepath` varchar(20) NOT NULL default '',
  `filename` text NOT NULL,
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `haveread` tinyint(1) NOT NULL default '0',
  `eipport` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `bid` (`bid`),
  KEY `haveread` (`haveread`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfeedbackclass;
CREATE TABLE `phome_enewsfeedbackclass` (
  `bid` smallint(5) unsigned NOT NULL auto_increment,
  `bname` varchar(60) NOT NULL default '',
  `btemp` mediumtext NOT NULL,
  `bzs` varchar(255) NOT NULL default '',
  `enter` text NOT NULL,
  `mustenter` text NOT NULL,
  `filef` varchar(255) NOT NULL default '',
  `groupid` smallint(6) NOT NULL default '0',
  `checkboxf` text NOT NULL,
  `usernames` text NOT NULL,
  PRIMARY KEY  (`bid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfeedbackf;
CREATE TABLE `phome_enewsfeedbackf` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `f` varchar(30) NOT NULL default '',
  `fname` varchar(30) NOT NULL default '',
  `fform` varchar(20) NOT NULL default '',
  `fzs` varchar(255) NOT NULL default '',
  `myorder` smallint(6) NOT NULL default '0',
  `ftype` varchar(30) NOT NULL default '',
  `flen` varchar(20) NOT NULL default '',
  `fformsize` varchar(12) NOT NULL default '',
  `fvalue` text NOT NULL,
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfile_1;
CREATE TABLE `phome_enewsfile_1` (
  `fileid` int(10) unsigned NOT NULL auto_increment,
  `pubid` bigint(16) unsigned NOT NULL default '0',
  `filename` char(60) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `path` char(20) NOT NULL default '',
  `adduser` char(30) NOT NULL default '',
  `filetime` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `no` char(60) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `onclick` mediumint(8) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `cjid` int(10) unsigned NOT NULL default '0',
  `fpath` tinyint(1) NOT NULL default '0',
  `modtype` tinyint(1) NOT NULL default '0',
  `cid` smallint(5) unsigned NOT NULL default '0',
  `cid2` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fileid`),
  KEY `id` (`id`),
  KEY `type` (`type`),
  KEY `classid` (`classid`),
  KEY `pubid` (`pubid`),
  KEY `cid` (`cid`),
  KEY `cid2` (`cid2`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfile_member;
CREATE TABLE `phome_enewsfile_member` (
  `fileid` int(10) unsigned NOT NULL auto_increment,
  `pubid` tinyint(1) NOT NULL default '0',
  `filename` char(60) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `path` char(20) NOT NULL default '',
  `adduser` char(30) NOT NULL default '',
  `filetime` int(10) unsigned NOT NULL default '0',
  `classid` tinyint(1) NOT NULL default '0',
  `no` char(60) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `onclick` mediumint(8) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `cjid` int(10) unsigned NOT NULL default '0',
  `fpath` tinyint(1) NOT NULL default '0',
  `modtype` tinyint(1) NOT NULL default '0',
  `cid` smallint(5) unsigned NOT NULL default '0',
  `cid2` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fileid`),
  KEY `id` (`id`),
  KEY `type` (`type`),
  KEY `cid` (`cid`),
  KEY `cid2` (`cid2`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfile_other;
CREATE TABLE `phome_enewsfile_other` (
  `fileid` int(10) unsigned NOT NULL auto_increment,
  `pubid` tinyint(1) NOT NULL default '0',
  `filename` char(60) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `path` char(20) NOT NULL default '',
  `adduser` char(30) NOT NULL default '',
  `filetime` int(10) unsigned NOT NULL default '0',
  `classid` tinyint(1) NOT NULL default '0',
  `no` char(60) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `onclick` mediumint(8) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `cjid` int(10) unsigned NOT NULL default '0',
  `fpath` tinyint(1) NOT NULL default '0',
  `modtype` tinyint(3) unsigned NOT NULL default '0',
  `cid` smallint(5) unsigned NOT NULL default '0',
  `cid2` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fileid`),
  KEY `id` (`id`),
  KEY `type` (`type`),
  KEY `modtype` (`modtype`),
  KEY `cid` (`cid`),
  KEY `cid2` (`cid2`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfile_public;
CREATE TABLE `phome_enewsfile_public` (
  `fileid` int(10) unsigned NOT NULL auto_increment,
  `pubid` tinyint(1) NOT NULL default '0',
  `filename` char(60) NOT NULL default '',
  `filesize` int(10) unsigned NOT NULL default '0',
  `path` char(20) NOT NULL default '',
  `adduser` char(30) NOT NULL default '',
  `filetime` int(10) unsigned NOT NULL default '0',
  `classid` tinyint(1) NOT NULL default '0',
  `no` char(60) NOT NULL default '',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `onclick` mediumint(8) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `cjid` int(10) unsigned NOT NULL default '0',
  `fpath` tinyint(1) NOT NULL default '0',
  `modtype` tinyint(3) unsigned NOT NULL default '0',
  `cid` smallint(5) unsigned NOT NULL default '0',
  `cid2` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fileid`),
  KEY `id` (`id`),
  KEY `type` (`type`),
  KEY `modtype` (`modtype`),
  KEY `cid` (`cid`),
  KEY `cid2` (`cid2`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfileclass;
CREATE TABLE `phome_enewsfileclass` (
  `cid` smallint(5) unsigned NOT NULL auto_increment,
  `cname` char(50) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `myorder` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `classid` (`classid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfileclasst;
CREATE TABLE `phome_enewsfileclasst` (
  `cid` smallint(5) unsigned NOT NULL auto_increment,
  `cname` char(50) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `myorder` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `classid` (`classid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsformhash;
CREATE TABLE `phome_enewsformhash` (
  `ecms` char(50) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  `ckname` char(20) NOT NULL default '',
  `ckval` char(32) NOT NULL default '',
  `dotime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `ecms` (`ecms`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfz_class;
CREATE TABLE `phome_enewsfz_class` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `pubid` bigint(16) unsigned NOT NULL default '0',
  `bcid` int(10) unsigned NOT NULL default '0',
  `cname` char(50) NOT NULL default '',
  `islast` tinyint(1) NOT NULL default '0',
  `myorder` smallint(6) NOT NULL default '0',
  `islist` tinyint(1) NOT NULL default '0',
  `lencord` smallint(5) unsigned NOT NULL default '0',
  `classtempid` smallint(5) unsigned NOT NULL default '0',
  `listtempid` smallint(5) unsigned NOT NULL default '0',
  `bdinfoid` char(20) NOT NULL default '',
  `isopen` tinyint(1) NOT NULL default '0',
  `reorder` char(50) NOT NULL default '',
  `cdiy` char(255) NOT NULL default '',
  `qadd` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `pubid` (`pubid`),
  KEY `bcid` (`bcid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfz_data_1;
CREATE TABLE `phome_enewsfz_data_1` (
  `bpubid` bigint(16) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `bcid` int(10) unsigned NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL default '0',
  `newstime` int(10) unsigned NOT NULL default '0',
  `tid` smallint(5) unsigned NOT NULL default '0',
  `isgood` tinyint(1) NOT NULL default '0',
  `firsttitle` tinyint(1) NOT NULL default '0',
  KEY `bpubid` (`bpubid`),
  KEY `id` (`id`),
  KEY `classid` (`classid`),
  KEY `mid` (`mid`),
  KEY `bcid` (`bcid`),
  KEY `cid` (`cid`),
  KEY `newstime` (`newstime`),
  KEY `tid` (`tid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfz_data_check;
CREATE TABLE `phome_enewsfz_data_check` (
  `bpubid` bigint(16) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `bcid` int(10) unsigned NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL default '0',
  `newstime` int(10) unsigned NOT NULL default '0',
  `tid` smallint(5) unsigned NOT NULL default '0',
  `isgood` tinyint(1) NOT NULL default '0',
  `firsttitle` tinyint(1) NOT NULL default '0',
  KEY `bpubid` (`bpubid`),
  KEY `id` (`id`),
  KEY `classid` (`classid`),
  KEY `mid` (`mid`),
  KEY `bcid` (`bcid`),
  KEY `cid` (`cid`),
  KEY `newstime` (`newstime`),
  KEY `tid` (`tid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfz_info;
CREATE TABLE `phome_enewsfz_info` (
  `pubid` bigint(16) NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `fzstb` smallint(5) unsigned NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL default '0',
  `usefz` tinyint(1) NOT NULL default '0',
  `sclassid` smallint(5) unsigned NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  `tid` smallint(5) unsigned NOT NULL default '0',
  `qadd` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pubid`),
  KEY `id` (`id`),
  KEY `classid` (`classid`),
  KEY `mid` (`mid`),
  KEY `cid` (`cid`),
  KEY `usefz` (`usefz`),
  KEY `sclassid` (`sclassid`),
  KEY `tid` (`tid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfz_infoclass;
CREATE TABLE `phome_enewsfz_infoclass` (
  `classid` int(10) unsigned NOT NULL auto_increment,
  `classname` char(60) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsfz_set;
CREATE TABLE `phome_enewsfz_set` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `openfz` tinyint(1) NOT NULL default '0',
  `openfzpage` tinyint(1) NOT NULL default '0',
  `fzdatatbs` text NOT NULL,
  `fzdeftb` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsgbook;
CREATE TABLE `phome_enewsgbook` (
  `lyid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `mycall` varchar(30) NOT NULL default '',
  `lytime` datetime NOT NULL default '0000-00-00 00:00:00',
  `lytext` text NOT NULL,
  `retext` text NOT NULL,
  `bid` smallint(5) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `checked` tinyint(1) NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `eipport` varchar(6) NOT NULL default '',
  `eipf` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`lyid`),
  KEY `bid` (`bid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsgbookclass;
CREATE TABLE `phome_enewsgbookclass` (
  `bid` smallint(5) unsigned NOT NULL auto_increment,
  `bname` varchar(60) NOT NULL default '',
  `checked` tinyint(1) NOT NULL default '0',
  `groupid` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`bid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsgfenip;
CREATE TABLE `phome_enewsgfenip` (
  `ip` varchar(50) NOT NULL default '',
  `addtime` int(11) NOT NULL default '0',
  UNIQUE KEY `ip` (`ip`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsgoodtype;
CREATE TABLE `phome_enewsgoodtype` (
  `tid` smallint(5) unsigned NOT NULL auto_increment,
  `tname` varchar(60) NOT NULL default '',
  `ttype` tinyint(1) NOT NULL default '0',
  `levelid` tinyint(3) unsigned NOT NULL default '0',
  `myorder` smallint(6) NOT NULL default '0',
  `groupid` varchar(255) NOT NULL default '',
  `showall` tinyint(1) NOT NULL default '0',
  `showcid` text NOT NULL,
  `hiddencid` text NOT NULL,
  PRIMARY KEY  (`tid`),
  KEY `ttype` (`ttype`),
  KEY `levelid` (`levelid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsgroup;
CREATE TABLE `phome_enewsgroup` (
  `groupid` smallint(6) NOT NULL auto_increment,
  `groupname` varchar(50) NOT NULL default '',
  `dopublic` tinyint(1) NOT NULL default '0',
  `doclass` tinyint(1) NOT NULL default '0',
  `dotemplate` tinyint(1) NOT NULL default '0',
  `dopicnews` tinyint(1) NOT NULL default '0',
  `dofile` tinyint(1) NOT NULL default '0',
  `douser` tinyint(1) NOT NULL default '0',
  `dolog` tinyint(1) NOT NULL default '0',
  `domember` tinyint(1) NOT NULL default '0',
  `dobefrom` tinyint(1) NOT NULL default '0',
  `doword` tinyint(1) NOT NULL default '0',
  `dokey` tinyint(1) NOT NULL default '0',
  `doad` tinyint(1) NOT NULL default '0',
  `dovote` tinyint(1) NOT NULL default '0',
  `dogroup` tinyint(1) NOT NULL default '0',
  `doall` tinyint(1) NOT NULL default '0',
  `docj` tinyint(1) NOT NULL default '0',
  `dobq` tinyint(1) NOT NULL default '0',
  `domovenews` tinyint(1) NOT NULL default '0',
  `dopostdata` tinyint(1) NOT NULL default '0',
  `dochangedata` tinyint(1) NOT NULL default '0',
  `dopl` tinyint(1) NOT NULL default '0',
  `dof` tinyint(1) NOT NULL default '0',
  `dom` tinyint(1) NOT NULL default '0',
  `dodo` tinyint(1) NOT NULL default '0',
  `dodbdata` tinyint(1) NOT NULL default '0',
  `dorepnewstext` tinyint(1) NOT NULL default '0',
  `dotempvar` tinyint(1) NOT NULL default '0',
  `dostats` tinyint(1) NOT NULL default '0',
  `dowriter` tinyint(1) NOT NULL default '0',
  `dototaldata` tinyint(1) NOT NULL default '0',
  `dosearchkey` tinyint(1) NOT NULL default '0',
  `dozt` tinyint(1) NOT NULL default '0',
  `docard` tinyint(1) NOT NULL default '0',
  `dolink` tinyint(1) NOT NULL default '0',
  `doselfinfo` tinyint(1) NOT NULL default '0',
  `doexecsql` tinyint(1) NOT NULL default '0',
  `dotable` tinyint(1) NOT NULL default '0',
  `dodownurl` tinyint(1) NOT NULL default '0',
  `dodeldownrecord` tinyint(1) NOT NULL default '0',
  `doshoppayfs` tinyint(1) NOT NULL default '0',
  `doshopps` tinyint(1) NOT NULL default '0',
  `doshopdd` tinyint(1) NOT NULL default '0',
  `dogbook` tinyint(1) NOT NULL default '0',
  `dofeedback` tinyint(1) NOT NULL default '0',
  `douserpage` tinyint(1) NOT NULL default '0',
  `donotcj` tinyint(1) NOT NULL default '0',
  `dodownerror` tinyint(1) NOT NULL default '0',
  `dodelinfodata` tinyint(1) NOT NULL default '0',
  `doaddinfo` tinyint(1) NOT NULL default '0',
  `doeditinfo` tinyint(1) NOT NULL default '0',
  `dodelinfo` tinyint(1) NOT NULL default '0',
  `doadminstyle` tinyint(1) NOT NULL default '0',
  `dorepdownpath` tinyint(1) NOT NULL default '0',
  `douserjs` tinyint(1) NOT NULL default '0',
  `douserlist` tinyint(1) NOT NULL default '0',
  `domsg` tinyint(1) NOT NULL default '0',
  `dosendemail` tinyint(1) NOT NULL default '0',
  `dosetmclass` tinyint(1) NOT NULL default '0',
  `doinfodoc` tinyint(1) NOT NULL default '0',
  `dotempgroup` tinyint(1) NOT NULL default '0',
  `dofeedbackf` tinyint(1) NOT NULL default '0',
  `dotask` tinyint(1) NOT NULL default '0',
  `domemberf` tinyint(1) NOT NULL default '0',
  `dospacestyle` tinyint(1) NOT NULL default '0',
  `dospacedata` tinyint(1) NOT NULL default '0',
  `dovotemod` tinyint(1) NOT NULL default '0',
  `doplayer` tinyint(1) NOT NULL default '0',
  `dowap` tinyint(1) NOT NULL default '0',
  `dopay` tinyint(1) NOT NULL default '0',
  `dobuygroup` tinyint(1) NOT NULL default '0',
  `dosearchall` tinyint(1) NOT NULL default '0',
  `doinfotype` tinyint(1) NOT NULL default '0',
  `doplf` tinyint(1) NOT NULL default '0',
  `dopltable` tinyint(1) NOT NULL default '0',
  `dochadminstyle` tinyint(1) NOT NULL default '0',
  `dotags` tinyint(1) NOT NULL default '0',
  `dosp` tinyint(1) NOT NULL default '0',
  `doyh` tinyint(1) NOT NULL default '0',
  `dofirewall` tinyint(1) NOT NULL default '0',
  `dosetsafe` tinyint(1) NOT NULL default '0',
  `douserclass` tinyint(1) NOT NULL default '0',
  `doworkflow` tinyint(1) NOT NULL default '0',
  `domenu` tinyint(1) NOT NULL default '0',
  `dopubvar` tinyint(1) NOT NULL default '0',
  `doclassf` tinyint(1) NOT NULL default '0',
  `doztf` tinyint(1) NOT NULL default '0',
  `dofiletable` tinyint(1) NOT NULL default '0',
  `docheckinfo` tinyint(1) NOT NULL default '0',
  `dogoodinfo` tinyint(1) NOT NULL default '0',
  `dodocinfo` tinyint(1) NOT NULL default '0',
  `domoveinfo` tinyint(1) NOT NULL default '0',
  `dodttemp` tinyint(1) NOT NULL default '0',
  `doloadcj` tinyint(1) NOT NULL default '0',
  `domustcheck` tinyint(1) NOT NULL default '0',
  `docheckedit` tinyint(1) NOT NULL default '0',
  `domemberconnect` tinyint(1) NOT NULL default '0',
  `doprecode` tinyint(1) NOT NULL default '0',
  `domoreport` tinyint(1) NOT NULL default '0',
  `docanhtml` tinyint(1) NOT NULL default '0',
  `dodelclass` tinyint(1) NOT NULL default '0',
  `doinfofile` tinyint(1) NOT NULL default '0',
  `doingroup` tinyint(1) NOT NULL default '0',
  `domembergroup` tinyint(1) NOT NULL default '0',
  `doviewgroup` tinyint(1) NOT NULL default '0',
  `domadmingroup` tinyint(1) NOT NULL default '0',
  `dochmoreport` tinyint(1) NOT NULL default '0',
  `doisqf` tinyint(1) NOT NULL default '0',
  `doeditqf` tinyint(1) NOT NULL default '0',
  `dofzinfo` tinyint(1) NOT NULL default '0',
  `dofztable` tinyint(1) NOT NULL default '0',
  `dofzdata` tinyint(1) NOT NULL default '0',
  `dofzdatac` tinyint(1) NOT NULL default '0',
  `dofzinfocl` tinyint(1) NOT NULL default '0',
  `dosearchurl` tinyint(1) NOT NULL default '0',
  `dofileclass` tinyint(1) NOT NULL default '0',
  `dodeltable` tinyint(1) NOT NULL default '0',
  `doapi` tinyint(1) NOT NULL default '0',
  `dodtuserpage` tinyint(1) NOT NULL default '0',
  `dolgac` tinyint(1) NOT NULL default '0',
  `docklgac` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`groupid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewshmsg;
CREATE TABLE `phome_enewshmsg` (
  `mid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL default '',
  `msgtext` text NOT NULL,
  `haveread` tinyint(1) NOT NULL default '0',
  `msgtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `to_username` varchar(30) NOT NULL default '',
  `from_userid` int(10) unsigned NOT NULL default '0',
  `from_username` varchar(30) NOT NULL default '',
  `isadmin` tinyint(1) NOT NULL default '0',
  `issys` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`mid`),
  KEY `to_username` (`to_username`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewshnotice;
CREATE TABLE `phome_enewshnotice` (
  `mid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL default '',
  `msgtext` text NOT NULL,
  `haveread` tinyint(1) NOT NULL default '0',
  `msgtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `to_username` varchar(30) NOT NULL default '',
  `from_userid` int(10) unsigned NOT NULL default '0',
  `from_username` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`mid`),
  KEY `to_username` (`to_username`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewshy;
CREATE TABLE `phome_enewshy` (
  `fid` bigint(20) NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `fname` varchar(25) NOT NULL default '',
  `cid` int(11) NOT NULL default '0',
  `fsay` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `userid` (`userid`),
  KEY `cid` (`cid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewshyclass;
CREATE TABLE `phome_enewshyclass` (
  `cid` int(11) NOT NULL auto_increment,
  `cname` varchar(30) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsinfoclass;
CREATE TABLE `phome_enewsinfoclass` (
  `classid` int(11) NOT NULL auto_increment,
  `bclassid` int(11) NOT NULL default '0',
  `classname` varchar(100) NOT NULL default '',
  `infourl` mediumtext NOT NULL,
  `newsclassid` smallint(6) NOT NULL default '0',
  `startday` date NOT NULL default '0000-00-00',
  `endday` date NOT NULL default '0000-00-00',
  `bz` text NOT NULL,
  `num` smallint(6) NOT NULL default '0',
  `copyimg` tinyint(1) NOT NULL default '0',
  `renum` smallint(6) NOT NULL default '0',
  `keyboard` text NOT NULL,
  `oldword` text NOT NULL,
  `newword` text NOT NULL,
  `titlelen` smallint(6) NOT NULL default '0',
  `retitlewriter` tinyint(1) NOT NULL default '0',
  `smalltextlen` smallint(6) NOT NULL default '0',
  `zz_smallurl` text NOT NULL,
  `zz_newsurl` text NOT NULL,
  `httpurl` varchar(255) NOT NULL default '',
  `repad` text NOT NULL,
  `imgurl` varchar(255) NOT NULL default '',
  `relistnum` smallint(6) NOT NULL default '0',
  `zz_titlepicl` text NOT NULL,
  `z_titlepicl` varchar(255) NOT NULL default '',
  `qz_titlepicl` varchar(255) NOT NULL default '',
  `save_titlepicl` varchar(10) NOT NULL default '',
  `keynum` tinyint(4) NOT NULL default '0',
  `insertnum` smallint(6) NOT NULL default '0',
  `copyflash` tinyint(1) NOT NULL default '0',
  `tid` smallint(6) NOT NULL default '0',
  `tbname` varchar(60) NOT NULL default '',
  `pagetype` tinyint(1) NOT NULL default '0',
  `smallpagezz` text NOT NULL,
  `pagezz` text NOT NULL,
  `smallpageallzz` text NOT NULL,
  `pageallzz` text NOT NULL,
  `mark` tinyint(1) NOT NULL default '0',
  `enpagecode` tinyint(1) NOT NULL default '0',
  `recjtheurl` tinyint(1) NOT NULL default '0',
  `hiddenload` tinyint(1) NOT NULL default '0',
  `justloadin` tinyint(1) NOT NULL default '0',
  `justloadcheck` tinyint(1) NOT NULL default '0',
  `delloadinfo` tinyint(1) NOT NULL default '0',
  `pagerepad` mediumtext NOT NULL,
  `newsztid` text NOT NULL,
  `getfirstpic` tinyint(4) NOT NULL default '0',
  `oldpagerep` text NOT NULL,
  `newpagerep` text NOT NULL,
  `keeptime` smallint(6) NOT NULL default '0',
  `lasttime` int(11) NOT NULL default '0',
  `newstextisnull` tinyint(1) NOT NULL default '0',
  `getfirstspic` tinyint(1) NOT NULL default '0',
  `getfirstspicw` smallint(6) NOT NULL default '0',
  `getfirstspich` smallint(6) NOT NULL default '0',
  `doaddtextpage` tinyint(1) NOT NULL default '0',
  `infourlispage` tinyint(1) NOT NULL default '0',
  `repf` varchar(255) NOT NULL default '',
  `repadf` varchar(255) NOT NULL default '',
  `loadkeeptime` smallint(6) NOT NULL default '0',
  `isnullf` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`classid`),
  KEY `newsclassid` (`newsclassid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsinfotype;
CREATE TABLE `phome_enewsinfotype` (
  `typeid` smallint(5) unsigned NOT NULL auto_increment,
  `tname` varchar(30) NOT NULL default '',
  `mid` smallint(6) NOT NULL default '0',
  `myorder` smallint(6) NOT NULL default '0',
  `yhid` smallint(6) NOT NULL default '0',
  `tnum` tinyint(3) unsigned NOT NULL default '0',
  `listtempid` smallint(5) unsigned NOT NULL default '0',
  `tpath` varchar(100) NOT NULL default '',
  `ttype` varchar(10) NOT NULL default '',
  `maxnum` int(10) unsigned NOT NULL default '0',
  `reorder` varchar(50) NOT NULL default '',
  `tid` smallint(5) unsigned NOT NULL default '0',
  `tbname` varchar(60) NOT NULL default '',
  `timg` varchar(200) NOT NULL default '',
  `intro` varchar(255) NOT NULL default '',
  `pagekey` varchar(255) NOT NULL default '',
  `newline` tinyint(3) unsigned NOT NULL default '0',
  `hotline` tinyint(3) unsigned NOT NULL default '0',
  `goodline` tinyint(3) unsigned NOT NULL default '0',
  `hotplline` tinyint(3) unsigned NOT NULL default '0',
  `firstline` tinyint(3) unsigned NOT NULL default '0',
  `jstempid` smallint(5) unsigned NOT NULL default '0',
  `nrejs` tinyint(1) NOT NULL default '0',
  `listdt` tinyint(1) NOT NULL default '0',
  `repagenum` smallint(5) unsigned NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  `ecmsvpf` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`typeid`),
  KEY `mid` (`mid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsinfovote;
CREATE TABLE `phome_enewsinfovote` (
  `pubid` bigint(16) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(120) NOT NULL default '',
  `votenum` int(10) unsigned NOT NULL default '0',
  `voteip` mediumtext NOT NULL,
  `votetext` text NOT NULL,
  `voteclass` tinyint(1) NOT NULL default '0',
  `doip` tinyint(1) NOT NULL default '0',
  `dotime` date NOT NULL default '0000-00-00',
  `tempid` smallint(5) unsigned NOT NULL default '0',
  `width` int(10) unsigned NOT NULL default '0',
  `height` int(10) unsigned NOT NULL default '0',
  `diyotherlink` tinyint(1) NOT NULL default '0',
  `infouptime` int(10) unsigned NOT NULL default '0',
  `infodowntime` int(10) unsigned NOT NULL default '0',
  `copyids` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`pubid`),
  KEY `id` (`id`,`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsingroup;
CREATE TABLE `phome_enewsingroup` (
  `gid` smallint(5) unsigned NOT NULL auto_increment,
  `gname` char(60) NOT NULL default '',
  `myorder` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`gid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewskey;
CREATE TABLE `phome_enewskey` (
  `keyid` smallint(5) unsigned NOT NULL auto_increment,
  `keyname` char(50) NOT NULL default '',
  `keyurl` char(200) NOT NULL default '',
  `cid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`keyid`),
  KEY `cid` (`cid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewskeyclass;
CREATE TABLE `phome_enewskeyclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` char(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewslink;
CREATE TABLE `phome_enewslink` (
  `lid` smallint(5) unsigned NOT NULL auto_increment,
  `lname` varchar(100) NOT NULL default '',
  `lpic` varchar(255) NOT NULL default '',
  `lurl` varchar(255) NOT NULL default '',
  `ltime` datetime NOT NULL default '0000-00-00 00:00:00',
  `onclick` int(11) NOT NULL default '0',
  `width` varchar(10) NOT NULL default '',
  `height` varchar(10) NOT NULL default '',
  `target` varchar(10) NOT NULL default '',
  `myorder` tinyint(4) NOT NULL default '0',
  `email` varchar(60) NOT NULL default '',
  `lsay` text NOT NULL,
  `checked` tinyint(1) NOT NULL default '0',
  `ltype` smallint(6) NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewslinkclass;
CREATE TABLE `phome_enewslinkclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewslinktmp;
CREATE TABLE `phome_enewslinktmp` (
  `newsurl` varchar(255) NOT NULL default '',
  `checkrnd` varchar(50) NOT NULL default '',
  `linkid` bigint(20) NOT NULL auto_increment,
  `titlepic` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`linkid`),
  KEY `checkrnd` (`checkrnd`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewslog;
CREATE TABLE `phome_enewslog` (
  `loginid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `logintime` datetime NOT NULL default '0000-00-00 00:00:00',
  `loginip` varchar(50) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  `password` varchar(30) NOT NULL default '',
  `loginauth` tinyint(1) NOT NULL default '0',
  `ipport` varchar(6) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  `onepass` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`loginid`),
  KEY `status` (`status`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsloginfail;
CREATE TABLE `phome_enewsloginfail` (
  `ip` varchar(50) NOT NULL default '',
  `num` tinyint(4) NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ip`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsloginfail_u;
CREATE TABLE `phome_enewsloginfail_u` (
  `uname` varchar(110) NOT NULL default '',
  `num` tinyint(4) NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `uname` (`uname`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmember;
CREATE TABLE `phome_enewsmember` (
  `userid` int(10) unsigned NOT NULL auto_increment,
  `username` char(25) NOT NULL default '',
  `password` char(32) NOT NULL default '',
  `rnd` char(36) NOT NULL default '',
  `email` char(50) NOT NULL default '',
  `registertime` int(10) unsigned NOT NULL default '0',
  `groupid` smallint(5) unsigned NOT NULL default '0',
  `userfen` int(11) NOT NULL default '0',
  `userdate` int(10) unsigned NOT NULL default '0',
  `money` float(11,2) NOT NULL default '0.00',
  `zgroupid` smallint(5) unsigned NOT NULL default '0',
  `havemsg` tinyint(1) NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `salt` char(12) NOT NULL default '',
  `userkey` char(20) NOT NULL default '',
  `ingid` smallint(5) unsigned NOT NULL default '0',
  `agid` smallint(5) unsigned NOT NULL default '0',
  `isern` tinyint(1) NOT NULL default '0',
  `isot` tinyint(1) NOT NULL default '0',
  `phno` char(20) NOT NULL default '',
  `upic` tinyint(1) NOT NULL default '0',
  `mustdo` tinyint(1) NOT NULL default '0',
  `rndt` char(36) NOT NULL default '',
  `userjyz` int(11) NOT NULL default '0',
  `usertitle` char(20) NOT NULL default '',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `username` (`username`),
  KEY `groupid` (`groupid`),
  KEY `ingid` (`ingid`),
  KEY `email` (`email`),
  KEY `phno` (`phno`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmember_connect;
CREATE TABLE `phome_enewsmember_connect` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `apptype` char(20) NOT NULL default '',
  `bindkey` char(32) NOT NULL default '',
  `bindtime` int(10) unsigned NOT NULL default '0',
  `loginnum` int(10) unsigned NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `bindkey` (`bindkey`),
  KEY `apptype` (`apptype`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmember_connect_app;
CREATE TABLE `phome_enewsmember_connect_app` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `apptype` char(20) NOT NULL default '',
  `appname` char(30) NOT NULL default '',
  `appid` char(60) NOT NULL default '',
  `appkey` char(120) NOT NULL default '',
  `isclose` tinyint(1) NOT NULL default '0',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `qappname` char(30) NOT NULL default '',
  `appsay` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `apptype` (`apptype`),
  KEY `isclose` (`isclose`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmemberadd;
CREATE TABLE `phome_enewsmemberadd` (
  `userid` int(10) unsigned NOT NULL auto_increment,
  `truename` varchar(20) NOT NULL default '',
  `oicq` varchar(25) NOT NULL default '',
  `msn` varchar(120) NOT NULL default '',
  `mycall` varchar(30) NOT NULL default '',
  `phone` varchar(30) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `zip` varchar(8) NOT NULL default '',
  `spacestyleid` smallint(6) NOT NULL default '0',
  `homepage` varchar(200) NOT NULL default '',
  `saytext` text NOT NULL,
  `company` varchar(255) NOT NULL default '',
  `fax` varchar(30) NOT NULL default '',
  `spacename` varchar(255) NOT NULL default '',
  `spacegg` text NOT NULL,
  `viewstats` int(11) NOT NULL default '0',
  `regip` varchar(50) NOT NULL default '',
  `lasttime` int(10) unsigned NOT NULL default '0',
  `lastip` varchar(50) NOT NULL default '',
  `loginnum` int(10) unsigned NOT NULL default '0',
  `regipport` varchar(6) NOT NULL default '',
  `lastipport` varchar(6) NOT NULL default '',
  `eipf` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmemberf;
CREATE TABLE `phome_enewsmemberf` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `f` varchar(30) NOT NULL default '',
  `fname` varchar(30) NOT NULL default '',
  `fform` varchar(20) NOT NULL default '',
  `fhtml` mediumtext NOT NULL,
  `fzs` varchar(255) NOT NULL default '',
  `myorder` smallint(6) NOT NULL default '0',
  `ftype` varchar(30) NOT NULL default '',
  `flen` varchar(20) NOT NULL default '',
  `fvalue` text NOT NULL,
  `fformsize` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmemberfeedback;
CREATE TABLE `phome_enewsmemberfeedback` (
  `fid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(12) NOT NULL default '',
  `company` varchar(80) NOT NULL default '',
  `phone` varchar(30) NOT NULL default '',
  `fax` varchar(20) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `zip` varchar(8) NOT NULL default '',
  `title` varchar(120) NOT NULL default '',
  `ftext` text NOT NULL,
  `userid` int(10) unsigned NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `uid` int(10) unsigned NOT NULL default '0',
  `uname` varchar(25) NOT NULL default '',
  `addtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `eipport` varchar(6) NOT NULL default '',
  `eipf` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmemberform;
CREATE TABLE `phome_enewsmemberform` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `fname` varchar(60) NOT NULL default '',
  `ftemp` mediumtext NOT NULL,
  `fzs` varchar(255) NOT NULL default '',
  `enter` text NOT NULL,
  `mustenter` text NOT NULL,
  `filef` varchar(255) NOT NULL default '',
  `imgf` varchar(255) NOT NULL default '0',
  `tobrf` text NOT NULL,
  `viewenter` text NOT NULL,
  `searchvar` varchar(255) NOT NULL default '',
  `canaddf` text NOT NULL,
  `caneditf` text NOT NULL,
  `checkboxf` text NOT NULL,
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmembergbook;
CREATE TABLE `phome_enewsmembergbook` (
  `gid` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `isprivate` tinyint(1) NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  `uname` varchar(25) NOT NULL default '',
  `ip` varchar(50) NOT NULL default '',
  `addtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `gbtext` text NOT NULL,
  `retext` text NOT NULL,
  `checked` tinyint(1) NOT NULL default '0',
  `eipport` varchar(6) NOT NULL default '',
  `eipf` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`gid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmembergroup;
CREATE TABLE `phome_enewsmembergroup` (
  `groupid` smallint(6) NOT NULL auto_increment,
  `groupname` varchar(60) NOT NULL default '',
  `level` smallint(6) NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `favanum` smallint(6) NOT NULL default '0',
  `daydown` int(11) NOT NULL default '0',
  `msglen` int(11) NOT NULL default '0',
  `msgnum` int(11) NOT NULL default '0',
  `canreg` tinyint(1) NOT NULL default '0',
  `formid` smallint(6) NOT NULL default '0',
  `regchecked` tinyint(1) NOT NULL default '0',
  `spacestyleid` smallint(6) NOT NULL default '0',
  `dayaddinfo` smallint(6) NOT NULL default '0',
  `infochecked` tinyint(1) NOT NULL default '0',
  `plchecked` tinyint(1) NOT NULL default '0',
  `regps` varchar(30) NOT NULL default '',
  `loginps` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`groupid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmemberphno;
CREATE TABLE `phome_enewsmemberphno` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `uname` char(25) NOT NULL default '',
  `phno` char(20) NOT NULL default '',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `uname` (`uname`),
  UNIQUE KEY `phno` (`phno`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmemberpub;
CREATE TABLE `phome_enewsmemberpub` (
  `userid` int(10) unsigned NOT NULL auto_increment,
  `todayinfodate` char(10) NOT NULL default '',
  `todayaddinfo` mediumint(8) unsigned NOT NULL default '0',
  `todaydate` char(10) NOT NULL default '',
  `todaydown` mediumint(8) unsigned NOT NULL default '0',
  `authstr` char(30) NOT NULL default '',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmembertitle;
CREATE TABLE `phome_enewsmembertitle` (
  `tid` smallint(6) NOT NULL auto_increment,
  `ttitle` char(20) NOT NULL default '',
  `tjyz` int(11) NOT NULL default '0',
  `tstar` tinyint(4) NOT NULL default '0',
  `tcolor` char(10) NOT NULL default '',
  PRIMARY KEY  (`tid`),
  KEY `tjyz` (`tjyz`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmenu;
CREATE TABLE `phome_enewsmenu` (
  `menuid` int(10) unsigned NOT NULL auto_increment,
  `menuname` varchar(60) NOT NULL default '',
  `menuurl` varchar(255) NOT NULL default '',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `addhash` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`menuid`),
  KEY `myorder` (`myorder`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmenuclass;
CREATE TABLE `phome_enewsmenuclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(60) NOT NULL default '',
  `issys` tinyint(1) NOT NULL default '0',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `classtype` tinyint(1) NOT NULL default '0',
  `groupids` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`classid`),
  KEY `myorder` (`myorder`),
  KEY `classtype` (`classtype`)
) TYPE=MyISAM;

# --------------------------------------------------------


DROP TABLE IF EXISTS phome_enewsmod;
CREATE TABLE `phome_enewsmod` (
  `mid` smallint(5) unsigned NOT NULL auto_increment,
  `mname` varchar(100) NOT NULL default '',
  `mtemp` mediumtext NOT NULL,
  `mzs` varchar(255) NOT NULL default '',
  `cj` mediumtext NOT NULL,
  `enter` mediumtext NOT NULL,
  `tempvar` mediumtext NOT NULL,
  `sonclass` text NOT NULL,
  `searchvar` varchar(255) NOT NULL default '',
  `tid` smallint(5) unsigned NOT NULL default '0',
  `tbname` varchar(60) NOT NULL default '',
  `qenter` mediumtext NOT NULL,
  `mustqenterf` text NOT NULL,
  `qmtemp` mediumtext NOT NULL,
  `listandf` text NOT NULL,
  `setandf` tinyint(1) NOT NULL default '0',
  `listtempvar` mediumtext NOT NULL,
  `qmname` varchar(30) NOT NULL default '',
  `canaddf` text NOT NULL,
  `caneditf` text NOT NULL,
  `definfovoteid` smallint(6) NOT NULL default '0',
  `showmod` tinyint(1) NOT NULL default '0',
  `usemod` tinyint(1) NOT NULL default '0',
  `myorder` smallint(6) NOT NULL default '0',
  `orderf` text NOT NULL,
  `isdefault` tinyint(1) NOT NULL default '0',
  `listfile` varchar(30) NOT NULL default '',
  `printtempid` smallint(6) NOT NULL default '0',
  `maddfun` varchar(255) NOT NULL default '',
  `meditfun` varchar(255) NOT NULL default '',
  `qmaddfun` varchar(255) NOT NULL default '',
  `qmeditfun` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`mid`),
  KEY `tid` (`tid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmodlist;
CREATE TABLE `phome_enewsmodlist` (
  `lid` int(11) NOT NULL auto_increment,
  `lname` varchar(60) NOT NULL default '',
  `lpath` int(11) NOT NULL default '0',
  `lsay` varchar(255) NOT NULL default '',
  `ldoup` tinyint(1) NOT NULL default '0',
  `qafall` varchar(255) NOT NULL default '',
  `qafclass` varchar(255) NOT NULL default '',
  `qafqinfo` varchar(255) NOT NULL default '',
  `qafdoc` varchar(255) NOT NULL default '',
  `tempall` mediumtext NOT NULL,
  `tempclass` mediumtext NOT NULL,
  `tempqinfo` mediumtext NOT NULL,
  `tempdoc` mediumtext NOT NULL,
  PRIMARY KEY  (`lid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsmoreport;
CREATE TABLE `phome_enewsmoreport` (
  `pid` int(11) NOT NULL auto_increment,
  `pname` char(60) NOT NULL default '',
  `purl` char(255) NOT NULL default '',
  `ppath` char(255) NOT NULL default '',
  `postpass` char(120) NOT NULL default '',
  `postfile` char(255) NOT NULL default '',
  `tempgid` smallint(5) unsigned NOT NULL default '0',
  `mustdt` tinyint(1) NOT NULL default '0',
  `isclose` tinyint(1) NOT NULL default '0',
  `closeadd` tinyint(1) NOT NULL default '0',
  `headersign` char(255) NOT NULL default '',
  `openadmin` tinyint(1) NOT NULL default '0',
  `rehtml` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`pid`),
  KEY `isclose` (`isclose`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsnotcj;
CREATE TABLE `phome_enewsnotcj` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `word` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsnotice;
CREATE TABLE `phome_enewsnotice` (
  `mid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL default '',
  `msgtext` text NOT NULL,
  `haveread` tinyint(1) NOT NULL default '0',
  `msgtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `to_username` varchar(30) NOT NULL default '',
  `from_userid` int(10) unsigned NOT NULL default '0',
  `from_username` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`mid`),
  KEY `to_username` (`to_username`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspage;
CREATE TABLE `phome_enewspage` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(120) NOT NULL default '',
  `pagetext` mediumtext NOT NULL,
  `path` varchar(255) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `pagetitle` varchar(120) NOT NULL default '',
  `pagekeywords` varchar(255) NOT NULL default '',
  `pagedescription` varchar(255) NOT NULL default '',
  `tempid` smallint(5) unsigned NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  `infocid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `classid` (`classid`),
  KEY `infocid` (`infocid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspageclass;
CREATE TABLE `phome_enewspageclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspayapi;
CREATE TABLE `phome_enewspayapi` (
  `payid` smallint(5) unsigned NOT NULL auto_increment,
  `paytype` varchar(20) NOT NULL default '',
  `myorder` tinyint(4) NOT NULL default '0',
  `payfee` varchar(10) NOT NULL default '',
  `payuser` varchar(60) NOT NULL default '',
  `partner` varchar(60) NOT NULL default '',
  `paykey` varchar(120) NOT NULL default '',
  `paylogo` varchar(200) NOT NULL default '',
  `paysay` text NOT NULL,
  `payname` varchar(120) NOT NULL default '',
  `isclose` tinyint(1) NOT NULL default '0',
  `payemail` varchar(120) NOT NULL default '',
  `paymethod` tinyint(1) NOT NULL default '0',
  `payappid` varchar(100) NOT NULL default '',
  `payopenid` varchar(100) NOT NULL default '',
  `paymchid` varchar(100) NOT NULL default '',
  `opennturl` tinyint(1) NOT NULL default '0',
  `mpids` varchar(255) NOT NULL default '',
  `diyset1` varchar(200) NOT NULL default '',
  `diyset2` varchar(200) NOT NULL default '',
  `diyset3` varchar(200) NOT NULL default '',
  `diyset4` varchar(200) NOT NULL default '',
  `diyset5` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`payid`),
  UNIQUE KEY `paytype` (`paytype`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspayrecord;
CREATE TABLE `phome_enewspayrecord` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `orderid` varchar(50) NOT NULL default '',
  `money` varchar(20) NOT NULL default '',
  `posttime` datetime NOT NULL default '0000-00-00 00:00:00',
  `paybz` varchar(160) NOT NULL default '',
  `paytype` varchar(20) NOT NULL default '',
  `payip` varchar(50) NOT NULL default '',
  `ispay` tinyint(1) NOT NULL default '0',
  `paydo` varchar(20) NOT NULL default '',
  `payfor` varchar(120) NOT NULL default '',
  `payckcode` varchar(20) NOT NULL default '',
  `payddno` varchar(50) NOT NULL default '',
  `pname` varchar(100) NOT NULL default '',
  `psay` varchar(160) NOT NULL default '',
  `endtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `mpid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `orderid` (`orderid`),
  KEY `payddno` (`payddno`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspayrecordst;
CREATE TABLE `phome_enewspayrecordst` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `orderid` varchar(50) NOT NULL default '',
  `money` varchar(20) NOT NULL default '',
  `posttime` datetime NOT NULL default '0000-00-00 00:00:00',
  `paybz` varchar(160) NOT NULL default '',
  `paytype` varchar(20) NOT NULL default '',
  `payip` varchar(50) NOT NULL default '',
  `ispay` tinyint(1) NOT NULL default '0',
  `paydo` varchar(20) NOT NULL default '',
  `payfor` varchar(120) NOT NULL default '',
  `payckcode` varchar(20) NOT NULL default '',
  `payddno` varchar(50) NOT NULL default '',
  `pname` varchar(100) NOT NULL default '',
  `psay` varchar(160) NOT NULL default '',
  `endtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `mpid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `orderid` (`orderid`),
  KEY `payddno` (`payddno`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsphmsg;
CREATE TABLE `phome_enewsphmsg` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `ckkey` char(50) NOT NULL default '',
  `phno` char(20) NOT NULL default '',
  `mtype` tinyint(1) NOT NULL default '0',
  `uid` int(10) unsigned NOT NULL default '0',
  `uname` char(25) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `outtime` int(10) unsigned NOT NULL default '0',
  `sendst` tinyint(1) NOT NULL default '0',
  `addip` char(50) NOT NULL default '',
  `sno` char(32) NOT NULL default '',
  `isuse` tinyint(1) NOT NULL default '0',
  `usetime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ckkey` (`ckkey`),
  KEY `phno` (`phno`),
  KEY `addtime` (`addtime`),
  KEY `uid` (`uid`),
  KEY `uname` (`uname`),
  KEY `addip` (`addip`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspic;
CREATE TABLE `phome_enewspic` (
  `picid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(120) NOT NULL default '',
  `pic_url` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `pic_width` varchar(20) NOT NULL default '',
  `pic_height` varchar(20) NOT NULL default '',
  `open_pic` varchar(20) NOT NULL default '',
  `border` tinyint(1) NOT NULL default '0',
  `pictext` text NOT NULL,
  `classid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`picid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspicclass;
CREATE TABLE `phome_enewspicclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspl_1;
CREATE TABLE `phome_enewspl_1` (
  `plid` int(10) unsigned NOT NULL auto_increment,
  `pubid` bigint(16) NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `sayip` varchar(50) NOT NULL default '',
  `saytime` int(10) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `zcnum` int(10) unsigned NOT NULL default '0',
  `fdnum` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `isgood` tinyint(1) NOT NULL default '0',
  `saytext` text NOT NULL,
  `eipport` varchar(6) NOT NULL default '',
  PRIMARY KEY  (`plid`),
  KEY `id` (`id`),
  KEY `classid` (`classid`),
  KEY `pubid` (`pubid`,`checked`,`plid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspl_set;
CREATE TABLE `phome_enewspl_set` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `pltime` smallint(5) unsigned NOT NULL default '0',
  `plsize` smallint(5) unsigned NOT NULL default '0',
  `plincludesize` smallint(5) unsigned NOT NULL default '0',
  `plkey_ok` tinyint(1) NOT NULL default '0',
  `plface` text NOT NULL,
  `plfacenum` tinyint(3) unsigned NOT NULL default '0',
  `plgroupid` int(11) NOT NULL default '0',
  `plclosewords` text NOT NULL,
  `plf` text NOT NULL,
  `plmustf` text NOT NULL,
  `pldatatbs` text NOT NULL,
  `defpltempid` smallint(5) unsigned NOT NULL default '0',
  `pl_num` smallint(5) unsigned NOT NULL default '0',
  `pldeftb` smallint(5) unsigned NOT NULL default '0',
  `plurl` varchar(200) NOT NULL default '',
  `plmaxfloor` smallint(6) NOT NULL default '0',
  `plquotetemp` text NOT NULL,
  `plcanlogin` tinyint(1) NOT NULL default '0',
  `plnewaddtime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsplayer;
CREATE TABLE `phome_enewsplayer` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `player` varchar(30) NOT NULL default '',
  `filename` varchar(20) NOT NULL default '',
  `bz` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsplf;
CREATE TABLE `phome_enewsplf` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `f` varchar(30) NOT NULL default '',
  `fname` varchar(30) NOT NULL default '',
  `fzs` varchar(255) NOT NULL default '',
  `ftype` varchar(30) NOT NULL default '',
  `flen` varchar(20) NOT NULL default '',
  `ismust` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspostdata;
CREATE TABLE `phome_enewspostdata` (
  `postid` bigint(20) unsigned NOT NULL auto_increment,
  `rnd` varchar(32) NOT NULL default '',
  `postdata` varchar(255) NOT NULL default '',
  `ispath` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`postid`),
  KEY `rnd` (`rnd`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspostserver;
CREATE TABLE `phome_enewspostserver` (
  `pid` smallint(5) unsigned NOT NULL auto_increment,
  `pname` varchar(60) NOT NULL default '',
  `purl` varchar(255) NOT NULL default '',
  `ptype` tinyint(1) NOT NULL default '0',
  `ftphost` varchar(255) NOT NULL default '',
  `ftpport` varchar(20) NOT NULL default '',
  `ftpusername` varchar(120) NOT NULL default '',
  `ftppassword` varchar(120) NOT NULL default '',
  `ftppath` varchar(255) NOT NULL default '',
  `ftpmode` tinyint(1) NOT NULL default '0',
  `ftpssl` tinyint(1) NOT NULL default '0',
  `ftppasv` tinyint(1) NOT NULL default '0',
  `ftpouttime` smallint(5) unsigned NOT NULL default '0',
  `lastposttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pid`),
  KEY `ptype` (`ptype`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspublic;
CREATE TABLE `phome_enewspublic` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `newsurl` varchar(120) NOT NULL default '',
  `sitename` varchar(60) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `filetype` text NOT NULL,
  `filesize` int(11) NOT NULL default '0',
  `hotnum` tinyint(4) NOT NULL default '0',
  `newnum` tinyint(4) NOT NULL default '0',
  `relistnum` int(11) NOT NULL default '0',
  `renewsnum` int(11) NOT NULL default '0',
  `min_keyboard` tinyint(4) NOT NULL default '0',
  `max_keyboard` tinyint(4) NOT NULL default '0',
  `search_num` tinyint(4) NOT NULL default '0',
  `search_pagenum` tinyint(4) NOT NULL default '0',
  `newslink` tinyint(4) NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `searchtime` int(11) NOT NULL default '0',
  `loginnum` smallint(6) NOT NULL default '0',
  `logintime` int(11) NOT NULL default '0',
  `addnews_ok` tinyint(1) NOT NULL default '0',
  `register_ok` tinyint(1) NOT NULL default '0',
  `indextype` varchar(10) NOT NULL default '',
  `goodlencord` tinyint(4) NOT NULL default '0',
  `goodtype` varchar(10) NOT NULL default '',
  `goodnum` tinyint(4) NOT NULL default '0',
  `searchtype` varchar(10) NOT NULL default '',
  `exittime` smallint(6) NOT NULL default '0',
  `smalltextlen` smallint(6) NOT NULL default '0',
  `defaultgroupid` smallint(6) NOT NULL default '0',
  `fileurl` varchar(255) NOT NULL default '',
  `phpmode` tinyint(1) NOT NULL default '0',
  `ftphost` varchar(255) NOT NULL default '',
  `ftpport` varchar(20) NOT NULL default '21',
  `ftpusername` varchar(120) NOT NULL default '',
  `ftppassword` varchar(120) NOT NULL default '',
  `ftppath` varchar(255) NOT NULL default '',
  `ftpmode` tinyint(1) NOT NULL default '0',
  `install` tinyint(1) NOT NULL default '0',
  `hotplnum` tinyint(4) NOT NULL default '0',
  `softversion` varchar(100) NOT NULL default '0',
  `lctime` int(11) NOT NULL default '0',
  `dorepnum` smallint(6) NOT NULL default '0',
  `loadtempnum` smallint(6) NOT NULL default '0',
  `firstnum` tinyint(4) NOT NULL default '0',
  `bakdbpath` varchar(50) NOT NULL default '',
  `bakdbzip` varchar(50) NOT NULL default '',
  `downpass` varchar(32) NOT NULL default '',
  `min_userlen` tinyint(4) NOT NULL default '0',
  `max_userlen` tinyint(4) NOT NULL default '0',
  `min_passlen` tinyint(4) NOT NULL default '0',
  `max_passlen` tinyint(4) NOT NULL default '0',
  `filechmod` tinyint(1) NOT NULL default '0',
  `tid` smallint(6) NOT NULL default '0',
  `tbname` varchar(60) NOT NULL default '',
  `loginkey_ok` tinyint(1) NOT NULL default '0',
  `limittype` tinyint(1) NOT NULL default '0',
  `redodown` smallint(6) NOT NULL default '0',
  `candocode` tinyint(1) NOT NULL default '0',
  `opennotcj` tinyint(1) NOT NULL default '0',
  `reuserpagenum` int(11) NOT NULL default '0',
  `revotejsnum` int(11) NOT NULL default '0',
  `readjsnum` int(11) NOT NULL default '0',
  `qaddtran` tinyint(1) NOT NULL default '0',
  `qaddtransize` int(11) NOT NULL default '0',
  `ebakthisdb` tinyint(1) NOT NULL default '0',
  `delnewsnum` int(11) NOT NULL default '0',
  `markpos` tinyint(4) NOT NULL default '0',
  `markimg` varchar(80) NOT NULL default '',
  `marktext` varchar(80) NOT NULL default '',
  `markfontsize` varchar(12) NOT NULL default '',
  `markfontcolor` varchar(12) NOT NULL default '',
  `markfont` varchar(80) NOT NULL default '',
  `adminloginkey` tinyint(1) NOT NULL default '0',
  `php_outtime` int(11) NOT NULL default '0',
  `listpagefun` varchar(36) NOT NULL default '',
  `textpagefun` varchar(36) NOT NULL default '',
  `adfile` varchar(30) NOT NULL default '',
  `notsaveurl` text NOT NULL,
  `jstempid` smallint(6) NOT NULL default '0',
  `rssnum` smallint(6) NOT NULL default '0',
  `rsssub` smallint(6) NOT NULL default '0',
  `savetxtf` text NOT NULL,
  `dorepdlevelnum` int(11) NOT NULL default '0',
  `listpagelistfun` varchar(36) NOT NULL default '',
  `listpagelistnum` smallint(6) NOT NULL default '0',
  `infolinknum` int(11) NOT NULL default '0',
  `searchgroupid` int(11) NOT NULL default '0',
  `opencopytext` tinyint(1) NOT NULL default '0',
  `reuserjsnum` int(11) NOT NULL default '0',
  `reuserlistnum` int(11) NOT NULL default '0',
  `opentitleurl` tinyint(1) NOT NULL default '0',
  `qaddtranimgtype` text NOT NULL,
  `qaddtranfile` tinyint(1) NOT NULL default '0',
  `qaddtranfilesize` int(11) NOT NULL default '0',
  `qaddtranfiletype` text NOT NULL,
  `sendmailtype` tinyint(1) NOT NULL default '0',
  `smtphost` varchar(255) NOT NULL default '',
  `fromemail` varchar(120) NOT NULL default '',
  `loginemail` tinyint(1) NOT NULL default '0',
  `emailusername` varchar(60) NOT NULL default '',
  `emailpassword` varchar(60) NOT NULL default '',
  `smtpport` varchar(20) NOT NULL default '',
  `emailname` varchar(120) NOT NULL default '',
  `deftempid` smallint(6) NOT NULL default '0',
  `feedbacktfile` tinyint(1) NOT NULL default '0',
  `feedbackfilesize` int(11) NOT NULL default '0',
  `feedbackfiletype` text NOT NULL,
  `searchtempvar` tinyint(1) NOT NULL default '0',
  `showinfolevel` int(11) NOT NULL default '0',
  `navfh` varchar(20) NOT NULL default '',
  `spicwidth` smallint(6) NOT NULL default '0',
  `spicheight` smallint(6) NOT NULL default '0',
  `spickill` tinyint(1) NOT NULL default '0',
  `jpgquality` tinyint(4) NOT NULL default '0',
  `markpct` tinyint(4) NOT NULL default '0',
  `redoview` smallint(6) NOT NULL default '0',
  `reggetfen` smallint(6) NOT NULL default '0',
  `regbooktime` smallint(6) NOT NULL default '0',
  `revotetime` smallint(6) NOT NULL default '0',
  `nreclass` text NOT NULL,
  `nreinfo` text NOT NULL,
  `nrejs` text NOT NULL,
  `fpath` tinyint(1) NOT NULL default '0',
  `filepath` varchar(30) NOT NULL default '',
  `openmembertranimg` tinyint(1) NOT NULL default '0',
  `memberimgsize` int(11) NOT NULL default '0',
  `memberimgtype` text NOT NULL,
  `openmembertranfile` tinyint(1) NOT NULL default '0',
  `memberfilesize` int(11) NOT NULL default '0',
  `memberfiletype` text NOT NULL,
  `nottobq` text NOT NULL,
  `defspacestyleid` smallint(6) NOT NULL default '0',
  `canposturl` text NOT NULL,
  `openspace` tinyint(1) NOT NULL default '0',
  `defadminstyle` smallint(6) NOT NULL default '0',
  `realltime` smallint(6) NOT NULL default '0',
  `closeip` text NOT NULL,
  `openip` text NOT NULL,
  `hopenip` text NOT NULL,
  `closewords` text NOT NULL,
  `closewordsf` text NOT NULL,
  `textpagelistnum` smallint(6) NOT NULL default '0',
  `memberlistlevel` int(11) NOT NULL default '0',
  `wapopen` tinyint(1) NOT NULL default '0',
  `wapdefstyle` smallint(6) NOT NULL default '0',
  `wapshowmid` varchar(255) NOT NULL default '',
  `waplistnum` tinyint(4) NOT NULL default '0',
  `wapsubtitle` tinyint(4) NOT NULL default '0',
  `wapshowdate` varchar(50) NOT NULL default '',
  `wapchar` tinyint(1) NOT NULL default '0',
  `ebakcanlistdb` tinyint(1) NOT NULL default '0',
  `paymoneytofen` int(11) NOT NULL default '0',
  `payminmoney` int(11) NOT NULL default '0',
  `keytog` tinyint(1) NOT NULL default '0',
  `keyrnd` varchar(60) NOT NULL default '',
  `keytime` int(11) NOT NULL default '0',
  `regkey_ok` tinyint(1) NOT NULL default '0',
  `opengetdown` tinyint(1) NOT NULL default '0',
  `gbkey_ok` tinyint(1) NOT NULL default '0',
  `fbkey_ok` tinyint(1) NOT NULL default '0',
  `newaddinfotime` smallint(6) NOT NULL default '0',
  `classnavline` smallint(6) NOT NULL default '0',
  `classnavfh` varchar(120) NOT NULL default '',
  `adminstyle` text NOT NULL,
  `sitekey` varchar(255) NOT NULL default '',
  `siteintro` text NOT NULL,
  `docnewsnum` int(11) NOT NULL default '0',
  `openschall` tinyint(1) NOT NULL default '0',
  `schallfield` tinyint(1) NOT NULL default '0',
  `schallminlen` tinyint(4) NOT NULL default '0',
  `schallmaxlen` tinyint(4) NOT NULL default '0',
  `schallnotcid` text NOT NULL,
  `schallnum` smallint(6) NOT NULL default '0',
  `schallpagenum` smallint(6) NOT NULL default '0',
  `dtcanbq` tinyint(1) NOT NULL default '0',
  `dtcachetime` int(11) NOT NULL default '0',
  `regretime` smallint(6) NOT NULL default '0',
  `regclosewords` text NOT NULL,
  `regemailonly` tinyint(4) NOT NULL default '0',
  `repkeynum` smallint(6) NOT NULL default '0',
  `getpasstime` int(11) NOT NULL default '0',
  `acttime` int(11) NOT NULL default '0',
  `regacttype` tinyint(1) NOT NULL default '0',
  `acttext` text NOT NULL,
  `getpasstext` text NOT NULL,
  `acttitle` varchar(120) NOT NULL default '',
  `getpasstitle` varchar(120) NOT NULL default '',
  `opengetpass` tinyint(1) NOT NULL default '0',
  `hlistinfonum` int(11) NOT NULL default '0',
  `qlistinfonum` int(11) NOT NULL default '0',
  `dtncanbq` tinyint(1) NOT NULL default '0',
  `dtncachetime` int(11) NOT NULL default '0',
  `readdinfotime` smallint(6) NOT NULL default '0',
  `qeditinfotime` int(11) NOT NULL default '0',
  `ftpssl` tinyint(1) NOT NULL default '0',
  `ftppasv` tinyint(1) NOT NULL default '0',
  `ftpouttime` smallint(6) NOT NULL default '0',
  `onclicktype` tinyint(1) NOT NULL default '0',
  `onclickfilesize` int(11) NOT NULL default '0',
  `onclickfiletime` int(11) NOT NULL default '0',
  `schalltime` smallint(6) NOT NULL default '0',
  `defprinttempid` smallint(6) NOT NULL default '0',
  `opentags` tinyint(1) NOT NULL default '0',
  `tagstempid` smallint(6) NOT NULL default '0',
  `usetags` text NOT NULL,
  `chtags` text NOT NULL,
  `tagslistnum` smallint(6) NOT NULL default '0',
  `closeqdt` tinyint(1) NOT NULL default '0',
  `settop` tinyint(1) NOT NULL default '0',
  `qlistinfomod` tinyint(1) NOT NULL default '0',
  `gb_num` tinyint(4) NOT NULL default '0',
  `member_num` tinyint(4) NOT NULL default '0',
  `space_num` tinyint(4) NOT NULL default '0',
  `opendoip` text NOT NULL,
  `closedoip` text NOT NULL,
  `doiptype` varchar(255) NOT NULL default '',
  `filelday` int(11) NOT NULL default '0',
  `infolday` int(11) NOT NULL default '0',
  `baktempnum` tinyint(4) NOT NULL default '0',
  `dorepkey` tinyint(1) NOT NULL default '0',
  `dorepword` tinyint(1) NOT NULL default '0',
  `onclickrnd` varchar(20) NOT NULL default '',
  `indexpagedt` tinyint(1) NOT NULL default '0',
  `keybgcolor` varchar(8) NOT NULL default '',
  `keyfontcolor` varchar(8) NOT NULL default '',
  `keydistcolor` varchar(8) NOT NULL default '',
  `indexpageid` smallint(6) NOT NULL default '0',
  `closeqdtmsg` varchar(255) NOT NULL default '',
  `openfileserver` tinyint(1) NOT NULL default '0',
  `closemods` varchar(255) NOT NULL default '',
  `fieldandtop` tinyint(1) NOT NULL default '0',
  `fieldandclosetb` text NOT NULL,
  `filedatatbs` text NOT NULL,
  `filedeftb` smallint(5) unsigned NOT NULL default '0',
  `closelisttemp` varchar(255) NOT NULL default '',
  `chclasscolor` varchar(8) NOT NULL default '',
  `timeclose` varchar(255) NOT NULL default '',
  `timeclosedo` varchar(255) NOT NULL default '',
  `ipaddinfonum` int(10) unsigned NOT NULL default '0',
  `ipaddinfotime` smallint(5) unsigned NOT NULL default '0',
  `rewriteinfo` varchar(120) NOT NULL default '',
  `rewriteclass` varchar(120) NOT NULL default '',
  `rewriteinfotype` varchar(120) NOT NULL default '',
  `rewritetags` varchar(120) NOT NULL default '',
  `closehmenu` varchar(80) NOT NULL default '',
  `indexaddpage` tinyint(1) NOT NULL default '0',
  `rewritepl` varchar(150) NOT NULL default '',
  `modmemberedittran` tinyint(1) NOT NULL default '0',
  `modinfoedittran` tinyint(1) NOT NULL default '0',
  `php_adminouttime` int(11) NOT NULL default '0',
  `httptype` tinyint(1) NOT NULL default '0',
  `qinfoaddfen` tinyint(1) NOT NULL default '0',
  `bakescapetype` tinyint(1) NOT NULL default '0',
  `hkeytime` int(11) NOT NULL default '0',
  `hkeyrnd` varchar(60) NOT NULL default '',
  `mhavedatedo` tinyint(1) NOT NULL default '0',
  `reportkey` tinyint(1) NOT NULL default '0',
  `wapchstyle` tinyint(1) NOT NULL default '0',
  `usetotalnum` tinyint(1) NOT NULL default '0',
  `spacegids` int(11) NOT NULL default '0',
  `candocodetag` tinyint(1) NOT NULL default '0',
  `openern` varchar(255) NOT NULL default '',
  `ernurl` varchar(200) NOT NULL default '',
  `smtpssl` tinyint(1) NOT NULL default '0',
  `sitedm` varchar(120) NOT NULL default '',
  `qmaxpage` int(11) NOT NULL default '0',
  `regphonly` tinyint(4) NOT NULL default '0',
  `regmustef` tinyint(1) NOT NULL default '0',
  `upicsize` int(11) NOT NULL default '0',
  `upictype` varchar(50) NOT NULL default '',
  `upicdef` varchar(60) NOT NULL default '',
  `tfilechmod` tinyint(1) NOT NULL default '0',
  `fpnpub` varchar(100) NOT NULL default '',
  `rewritefz` varchar(120) NOT NULL default '',
  `searchuptime` int(11) NOT NULL default '0',
  `searchupnum` int(11) NOT NULL default '0',
  `searchfmax` smallint(6) NOT NULL default '0',
  `schalltype` tinyint(1) NOT NULL default '0',
  `schallfmax` smallint(6) NOT NULL default '0',
  `schallupnum` int(11) NOT NULL default '0',
  `schalluptime` int(11) NOT NULL default '0',
  `fntype` tinyint(1) NOT NULL default '0',
  `loginckt` tinyint(1) NOT NULL default '0',
  `lenids` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspublic_fc;
CREATE TABLE `phome_enewspublic_fc` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `fclastindex` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspublic_up;
CREATE TABLE `phome_enewspublic_up` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `lasttimeinfo` int(10) unsigned NOT NULL default '0',
  `lasttimepl` int(10) unsigned NOT NULL default '0',
  `lastnuminfo` int(10) unsigned NOT NULL default '0',
  `lastnumpl` int(10) unsigned NOT NULL default '0',
  `lastnuminfotb` text NOT NULL,
  `lastnumpltb` text NOT NULL,
  `todaytimeinfo` int(10) unsigned NOT NULL default '0',
  `todaytimepl` int(10) unsigned NOT NULL default '0',
  `todaynuminfo` int(10) unsigned NOT NULL default '0',
  `yesterdaynuminfo` int(10) unsigned NOT NULL default '0',
  `todaynumpl` int(10) unsigned NOT NULL default '0',
  `yesterdaynumpl` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspublicadd;
CREATE TABLE `phome_enewspublicadd` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `ctimeopen` tinyint(1) NOT NULL default '0',
  `ctimelast` int(10) unsigned NOT NULL default '0',
  `ctimeindex` int(11) NOT NULL default '0',
  `ctimeclass` int(11) NOT NULL default '0',
  `ctimelist` int(11) NOT NULL default '0',
  `ctimetext` int(11) NOT NULL default '0',
  `ctimett` int(11) NOT NULL default '0',
  `ctimetags` int(11) NOT NULL default '0',
  `ctimegids` varchar(255) NOT NULL default '',
  `ctimecids` varchar(255) NOT NULL default '',
  `ctimernd` varchar(60) NOT NULL default '',
  `ctimeaddre` tinyint(4) NOT NULL default '0',
  `ctimeqaddre` tinyint(4) NOT NULL default '0',
  `autodoopen` tinyint(1) NOT NULL default '0',
  `autodouser` varchar(30) NOT NULL default '',
  `autodopass` varchar(32) NOT NULL default '',
  `autodosalt` varchar(20) NOT NULL default '',
  `autodoshowkey` tinyint(1) NOT NULL default '0',
  `autodotime` int(11) NOT NULL default '0',
  `autodoline` int(11) NOT NULL default '0',
  `autodovar` varchar(20) NOT NULL default '',
  `autodoval` varchar(60) NOT NULL default '',
  `autodoshow` tinyint(1) NOT NULL default '0',
  `autodofile` tinyint(1) NOT NULL default '0',
  `autodopostpass` varchar(120) NOT NULL default '',
  `autodoss` tinyint(1) NOT NULL default '0',
  `autodoaction` varchar(200) NOT NULL default '',
  `autodock` tinyint(1) NOT NULL default '0',
  `digglevel` int(11) NOT NULL default '0',
  `diggcmids` varchar(255) NOT NULL default '',
  `toqjf` text NOT NULL,
  `qtoqjf` text NOT NULL,
  `eaopenpage` tinyint(1) NOT NULL default '0',
  `eackrnd` varchar(120) NOT NULL default '',
  `eacktime` int(11) NOT NULL default '0',
  `drretype` tinyint(1) NOT NULL default '0',
  `drrepage` varchar(120) NOT NULL default '',
  `qedtext` tinyint(1) NOT NULL default '0',
  `qedmpids` varchar(255) NOT NULL default '',
  `qedmids` varchar(255) NOT NULL default '',
  `qedtemp` mediumtext NOT NULL,
  `chlisttemp` tinyint(1) NOT NULL default '0',
  `nchlisttemp` varchar(255) NOT NULL default '',
  `chnewstemp` tinyint(1) NOT NULL default '0',
  `nchnewstemp` varchar(255) NOT NULL default '',
  `chclasstemp` tinyint(1) NOT NULL default '0',
  `nchclasstemp` varchar(255) NOT NULL default '',
  `phmmax` int(11) NOT NULL default '0',
  `phmdaymax` int(11) NOT NULL default '0',
  `phmonemax` int(11) NOT NULL default '0',
  `phmretime` int(11) NOT NULL default '0',
  `phmlen` tinyint(4) NOT NULL default '0',
  `phmouttime` int(11) NOT NULL default '0',
  `phmtog` tinyint(1) NOT NULL default '0',
  `phmopen` tinyint(1) NOT NULL default '0',
  `phmern` tinyint(1) NOT NULL default '0',
  `phmmust` tinyint(1) NOT NULL default '0',
  `phmreg` tinyint(1) NOT NULL default '0',
  `phmckst` smallint(6) NOT NULL default '0',
  `phmdo` varchar(50) NOT NULL default '',
  `phmckrnd` varchar(50) NOT NULL default '',
  `phmlgtoreg` tinyint(1) NOT NULL default '0',
  `openipf` tinyint(1) NOT NULL default '0',
  `openbqr` tinyint(1) NOT NULL default '0',
  `ctimefz` int(11) NOT NULL default '0',
  `sametgids` varchar(255) NOT NULL default '',
  `sametgdo` varchar(50) NOT NULL default '',
  `canusedm` text NOT NULL,
  `usavelast` smallint(6) NOT NULL default '0',
  `ckedpasst` int(11) NOT NULL default '0',
  `ckedpassc` tinyint(1) NOT NULL default '0',
  `canusecj` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspubvar;
CREATE TABLE `phome_enewspubvar` (
  `varid` smallint(5) unsigned NOT NULL auto_increment,
  `myvar` varchar(60) NOT NULL default '',
  `varname` varchar(60) NOT NULL default '',
  `varvalue` text NOT NULL,
  `varsay` varchar(255) NOT NULL default '',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `tocache` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`varid`),
  UNIQUE KEY `varname` (`varname`),
  KEY `classid` (`classid`),
  KEY `tocache` (`tocache`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspubvarclass;
CREATE TABLE `phome_enewspubvarclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  `classsay` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsqmsg;
CREATE TABLE `phome_enewsqmsg` (
  `mid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(80) NOT NULL default '',
  `msgtext` text NOT NULL,
  `haveread` tinyint(1) NOT NULL default '0',
  `msgtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `to_username` varchar(30) NOT NULL default '',
  `from_userid` int(10) unsigned NOT NULL default '0',
  `from_username` varchar(30) NOT NULL default '',
  `isadmin` tinyint(1) NOT NULL default '0',
  `issys` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`mid`),
  KEY `to_username` (`to_username`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssearch;
CREATE TABLE `phome_enewssearch` (
  `searchid` bigint(20) unsigned NOT NULL auto_increment,
  `keyboard` varchar(255) NOT NULL default '',
  `searchtime` int(10) unsigned NOT NULL default '0',
  `searchclass` varchar(255) NOT NULL default '',
  `result_num` int(10) unsigned NOT NULL default '0',
  `searchip` varchar(50) NOT NULL default '',
  `classid` varchar(255) NOT NULL default '',
  `onclick` int(10) unsigned NOT NULL default '0',
  `orderby` varchar(30) NOT NULL default '0',
  `myorder` tinyint(1) NOT NULL default '0',
  `checkpass` varchar(32) NOT NULL default '',
  `tbname` varchar(60) NOT NULL default '',
  `tempid` smallint(5) unsigned NOT NULL default '0',
  `iskey` tinyint(1) NOT NULL default '0',
  `andsql` text NOT NULL,
  `trueclassid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`searchid`),
  KEY `checkpass` (`checkpass`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssearchall;
CREATE TABLE `phome_enewssearchall` (
  `sid` int(10) unsigned NOT NULL auto_increment,
  `id` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `title` text NOT NULL,
  `infotime` int(10) unsigned NOT NULL default '0',
  `infotext` mediumtext NOT NULL,
  PRIMARY KEY  (`sid`),
  KEY `id` (`id`,`classid`,`infotime`),
  FULLTEXT KEY `title` (`title`,`infotext`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssearchall_load;
CREATE TABLE `phome_enewssearchall_load` (
  `lid` smallint(5) unsigned NOT NULL auto_increment,
  `tbname` varchar(60) NOT NULL default '',
  `titlefield` varchar(30) NOT NULL default '',
  `infotextfield` varchar(30) NOT NULL default '',
  `smalltextfield` varchar(30) NOT NULL default '',
  `loadnum` smallint(5) unsigned NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  `lastid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssearchurl;
CREATE TABLE `phome_enewssearchurl` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` char(120) NOT NULL default '',
  `url` char(200) NOT NULL default '',
  `onclick` int(10) unsigned NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshop_address;
CREATE TABLE `phome_enewsshop_address` (
  `addressid` int(10) unsigned NOT NULL auto_increment,
  `addressname` char(50) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  `truename` char(20) NOT NULL default '',
  `oicq` char(20) NOT NULL default '',
  `msn` char(60) NOT NULL default '',
  `email` char(60) NOT NULL default '',
  `address` char(255) NOT NULL default '',
  `zip` char(8) NOT NULL default '',
  `mycall` char(30) NOT NULL default '',
  `phone` char(30) NOT NULL default '',
  `signbuild` char(100) NOT NULL default '',
  `besttime` char(120) NOT NULL default '',
  `isdefault` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`addressid`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshop_ddlog;
CREATE TABLE `phome_enewsshop_ddlog` (
  `logid` int(10) unsigned NOT NULL auto_increment,
  `ddid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ecms` varchar(30) NOT NULL default '',
  `bz` varchar(255) NOT NULL default '',
  `addbz` varchar(255) NOT NULL default '',
  `logtime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`logid`),
  KEY `ddid` (`ddid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshop_precode;
CREATE TABLE `phome_enewsshop_precode` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `prename` varchar(30) NOT NULL default '',
  `precode` varchar(36) NOT NULL default '',
  `premoney` int(10) unsigned NOT NULL default '0',
  `pretype` tinyint(1) NOT NULL default '0',
  `reuse` tinyint(1) NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `groupid` varchar(255) NOT NULL default '',
  `classid` text NOT NULL,
  `musttotal` int(10) unsigned NOT NULL default '0',
  `usenum` int(11) NOT NULL default '0',
  `haveusenum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `precode` (`precode`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshop_set;
CREATE TABLE `phome_enewsshop_set` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `shopddgroupid` smallint(5) unsigned NOT NULL default '0',
  `buycarnum` smallint(5) unsigned NOT NULL default '0',
  `havefp` tinyint(1) NOT NULL default '0',
  `fpnum` smallint(5) unsigned NOT NULL default '0',
  `fpname` text NOT NULL,
  `ddmust` text NOT NULL,
  `haveatt` tinyint(1) NOT NULL default '0',
  `shoptbs` varchar(255) NOT NULL default '',
  `buystep` tinyint(3) unsigned NOT NULL default '0',
  `shoppsmust` tinyint(1) NOT NULL default '0',
  `shoppayfsmust` tinyint(1) NOT NULL default '0',
  `dddeltime` smallint(5) unsigned NOT NULL default '0',
  `cutnumtype` tinyint(1) NOT NULL default '0',
  `cutnumtime` int(10) unsigned NOT NULL default '0',
  `freepstotal` int(10) unsigned NOT NULL default '0',
  `singlenum` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshopdd;
CREATE TABLE `phome_enewsshopdd` (
  `ddid` int(10) unsigned NOT NULL auto_increment,
  `ddno` varchar(30) NOT NULL default '',
  `ddtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `userid` int(10) unsigned NOT NULL default '0',
  `username` varchar(25) NOT NULL default '',
  `outproduct` tinyint(1) NOT NULL default '0',
  `haveprice` tinyint(1) NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `truename` varchar(20) NOT NULL default '',
  `oicq` varchar(25) NOT NULL default '',
  `msn` varchar(120) NOT NULL default '',
  `email` varchar(120) NOT NULL default '',
  `mycall` varchar(30) NOT NULL default '',
  `phone` varchar(30) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `zip` varchar(8) NOT NULL default '',
  `psid` smallint(6) NOT NULL default '0',
  `psname` varchar(60) NOT NULL default '',
  `pstotal` float(11,2) NOT NULL default '0.00',
  `alltotal` float(11,2) NOT NULL default '0.00',
  `payfsid` smallint(6) NOT NULL default '0',
  `payfsname` varchar(60) NOT NULL default '',
  `payby` tinyint(4) NOT NULL default '0',
  `alltotalfen` float(11,2) NOT NULL default '0.00',
  `fp` tinyint(1) NOT NULL default '0',
  `fptt` varchar(255) NOT NULL default '',
  `fptotal` float(11,2) NOT NULL default '0.00',
  `fpname` varchar(50) NOT NULL default '',
  `userip` varchar(50) NOT NULL default '',
  `signbuild` varchar(100) NOT NULL default '',
  `besttime` varchar(120) NOT NULL default '',
  `pretotal` float(11,2) NOT NULL default '0.00',
  `ddtruetime` int(10) unsigned NOT NULL default '0',
  `havecutnum` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ddid`),
  KEY `ddno` (`ddno`),
  KEY `userid` (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshopdd_add;
CREATE TABLE `phome_enewsshopdd_add` (
  `ddid` int(10) unsigned NOT NULL default '0',
  `buycar` mediumtext NOT NULL,
  `bz` text NOT NULL,
  `retext` text NOT NULL,
  PRIMARY KEY  (`ddid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshoppayfs;
CREATE TABLE `phome_enewsshoppayfs` (
  `payid` smallint(5) unsigned NOT NULL auto_increment,
  `payname` varchar(60) NOT NULL default '',
  `payurl` varchar(255) NOT NULL default '',
  `paysay` text NOT NULL,
  `userpay` tinyint(1) NOT NULL default '0',
  `userfen` tinyint(1) NOT NULL default '0',
  `isclose` tinyint(1) NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`payid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsshopps;
CREATE TABLE `phome_enewsshopps` (
  `pid` smallint(5) unsigned NOT NULL auto_increment,
  `pname` varchar(60) NOT NULL default '',
  `price` float(11,2) NOT NULL default '0.00',
  `otherprice` text NOT NULL,
  `psay` text NOT NULL,
  `isclose` tinyint(1) NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssp;
CREATE TABLE `phome_enewssp` (
  `spid` int(10) unsigned NOT NULL auto_increment,
  `spname` varchar(30) NOT NULL default '',
  `varname` varchar(30) NOT NULL default '',
  `sppic` varchar(255) NOT NULL default '',
  `spsay` varchar(255) NOT NULL default '',
  `sptype` tinyint(1) NOT NULL default '0',
  `cid` smallint(5) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `tempid` smallint(5) unsigned NOT NULL default '0',
  `maxnum` int(11) NOT NULL default '0',
  `sptime` int(10) unsigned NOT NULL default '0',
  `groupid` text NOT NULL,
  `userclass` text NOT NULL,
  `username` text NOT NULL,
  `isclose` tinyint(1) NOT NULL default '0',
  `cladd` tinyint(1) NOT NULL default '0',
  `refile` tinyint(1) NOT NULL default '0',
  `spfile` varchar(255) NOT NULL default '',
  `spfileline` smallint(6) NOT NULL default '0',
  `spfilesub` smallint(6) NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`spid`),
  UNIQUE KEY `varname` (`varname`),
  KEY `refile` (`refile`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssp_1;
CREATE TABLE `phome_enewssp_1` (
  `sid` int(10) unsigned NOT NULL auto_increment,
  `spid` int(10) unsigned NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `titlepic` varchar(200) NOT NULL default '',
  `bigpic` varchar(200) NOT NULL default '',
  `titleurl` varchar(200) NOT NULL default '',
  `smalltext` varchar(255) NOT NULL default '',
  `titlefont` varchar(20) NOT NULL default '',
  `newstime` int(10) unsigned NOT NULL default '0',
  `titlepre` varchar(30) NOT NULL default '',
  `titlenext` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`sid`),
  KEY `spid` (`spid`),
  KEY `newstime` (`newstime`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssp_2;
CREATE TABLE `phome_enewssp_2` (
  `sid` int(10) unsigned NOT NULL auto_increment,
  `spid` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `newstime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`sid`),
  KEY `spid` (`spid`),
  KEY `newstime` (`newstime`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssp_3;
CREATE TABLE `phome_enewssp_3` (
  `sid` int(10) unsigned NOT NULL auto_increment,
  `spid` int(10) unsigned NOT NULL default '0',
  `sptext` mediumtext NOT NULL,
  PRIMARY KEY  (`sid`),
  UNIQUE KEY `spid` (`spid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssp_3_bak;
CREATE TABLE `phome_enewssp_3_bak` (
  `bid` int(10) unsigned NOT NULL auto_increment,
  `sid` int(10) unsigned NOT NULL default '0',
  `spid` int(10) unsigned NOT NULL default '0',
  `sptext` mediumtext NOT NULL,
  `lastuser` varchar(30) NOT NULL default '',
  `lasttime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bid`),
  KEY `sid` (`sid`),
  KEY `spid` (`spid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsspacestyle;
CREATE TABLE `phome_enewsspacestyle` (
  `styleid` smallint(5) unsigned NOT NULL auto_increment,
  `stylename` varchar(30) NOT NULL default '',
  `stylepic` varchar(255) NOT NULL default '',
  `stylesay` varchar(255) NOT NULL default '',
  `stylepath` varchar(30) NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  `membergroup` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`styleid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsspclass;
CREATE TABLE `phome_enewsspclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  `classsay` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssql;
CREATE TABLE `phome_enewssql` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `sqlname` varchar(60) NOT NULL default '',
  `sqltext` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstable;
CREATE TABLE `phome_enewstable` (
  `tid` smallint(5) unsigned NOT NULL auto_increment,
  `tbname` varchar(60) NOT NULL default '',
  `tname` varchar(60) NOT NULL default '',
  `tsay` text NOT NULL,
  `isdefault` tinyint(1) NOT NULL default '0',
  `datatbs` text NOT NULL,
  `deftb` varchar(6) NOT NULL default '',
  `yhid` smallint(5) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `intb` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`tid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstags;
CREATE TABLE `phome_enewstags` (
  `tagid` int(10) unsigned NOT NULL auto_increment,
  `tagname` char(20) NOT NULL default '',
  `num` int(10) unsigned NOT NULL default '0',
  `isgood` tinyint(1) NOT NULL default '0',
  `cid` smallint(5) unsigned NOT NULL default '0',
  `tagtitle` char(60) NOT NULL default '',
  `tagkey` char(100) NOT NULL default '',
  `tagdes` char(255) NOT NULL default '',
  `fclast` int(10) unsigned NOT NULL default '0',
  `cnum` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tagid`),
  UNIQUE KEY `tagname` (`tagname`),
  KEY `isgood` (`isgood`),
  KEY `cid` (`cid`),
  KEY `num` (`num`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstagsclass;
CREATE TABLE `phome_enewstagsclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstagsdata;
CREATE TABLE `phome_enewstagsdata` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `tagid` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `newstime` int(10) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tid`),
  KEY `tagid` (`tagid`),
  KEY `classid` (`classid`),
  KEY `id` (`id`),
  KEY `newstime` (`newstime`),
  KEY `mid` (`mid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstagsdata_check;
CREATE TABLE `phome_enewstagsdata_check` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `tagid` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `newstime` int(10) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tid`),
  KEY `tagid` (`tagid`),
  KEY `classid` (`classid`),
  KEY `id` (`id`),
  KEY `newstime` (`newstime`),
  KEY `mid` (`mid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstask;
CREATE TABLE `phome_enewstask` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `taskname` varchar(60) NOT NULL default '',
  `userid` int(10) unsigned NOT NULL default '0',
  `isopen` tinyint(1) NOT NULL default '0',
  `filename` varchar(60) NOT NULL default '',
  `lastdo` int(10) unsigned NOT NULL default '0',
  `doweek` char(1) NOT NULL default '0',
  `doday` char(2) NOT NULL default '0',
  `dohour` char(2) NOT NULL default '0',
  `dominute` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstogzts;
CREATE TABLE `phome_enewstogzts` (
  `togid` int(10) unsigned NOT NULL auto_increment,
  `keyboard` varchar(255) NOT NULL default '',
  `searchf` varchar(255) NOT NULL default '',
  `query` text NOT NULL,
  `specialsearch` varchar(255) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `retype` tinyint(1) NOT NULL default '0',
  `startday` varchar(20) NOT NULL default '',
  `endday` varchar(20) NOT NULL default '',
  `startid` int(10) unsigned NOT NULL default '0',
  `endid` int(10) unsigned NOT NULL default '0',
  `pline` int(11) NOT NULL default '0',
  `doecmszt` tinyint(1) NOT NULL default '0',
  `togztname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`togid`),
  KEY `togztname` (`togztname`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuser;
CREATE TABLE `phome_enewsuser` (
  `userid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `rnd` varchar(30) NOT NULL default '',
  `adminclass` mediumtext NOT NULL,
  `groupid` smallint(5) unsigned NOT NULL default '0',
  `checked` tinyint(1) NOT NULL default '0',
  `styleid` smallint(5) unsigned NOT NULL default '0',
  `filelevel` tinyint(1) NOT NULL default '0',
  `salt` varchar(8) NOT NULL default '',
  `loginnum` int(10) unsigned NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  `lastip` varchar(50) NOT NULL default '',
  `truename` varchar(20) NOT NULL default '',
  `email` varchar(120) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `pretime` int(10) unsigned NOT NULL default '0',
  `preip` varchar(50) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `addip` varchar(50) NOT NULL default '',
  `userprikey` varchar(50) NOT NULL default '',
  `salt2` varchar(20) NOT NULL default '',
  `lastipport` varchar(6) NOT NULL default '',
  `preipport` varchar(6) NOT NULL default '',
  `addipport` varchar(6) NOT NULL default '',
  `uprnd` varchar(32) NOT NULL default '',
  `wname` varchar(60) NOT NULL default '',
  `tel` varchar(20) NOT NULL default '',
  `wxno` varchar(80) NOT NULL default '',
  `qq` varchar(20) NOT NULL default '',
  `isot` tinyint(1) NOT NULL default '0',
  `tuser` varchar(100) NOT NULL default '',
  `rnds` varchar(255) NOT NULL default '',
  `ckinfos` text NOT NULL,
  `onepassnum` int(10) unsigned NOT NULL default '0',
  `lgac` tinyint(4) NOT NULL default '0',
  `goac` tinyint(4) NOT NULL default '0',
  `lgactime` int(10) unsigned NOT NULL default '0',
  `edpasstime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `tuser` (`tuser`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuser_save;
CREATE TABLE `phome_enewsuser_save` (
  `userid` int(11) NOT NULL default '0',
  `lasttitle` varchar(255) NOT NULL default '',
  `lasttext` mediumtext NOT NULL,
  `lasttime` int(10) unsigned NOT NULL default '0',
  `haveup` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuseradd;
CREATE TABLE `phome_enewsuseradd` (
  `userid` int(10) unsigned NOT NULL auto_increment,
  `equestion` tinyint(4) NOT NULL default '0',
  `eanswer` varchar(32) NOT NULL default '',
  `openip` text NOT NULL,
  `certkey` varchar(60) NOT NULL default '',
  `certtime` int(10) unsigned NOT NULL default '0',
  `ckffname` varchar(60) NOT NULL default '',
  `ckftime` int(10) unsigned NOT NULL default '0',
  `certkeyrnd` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuserclass;
CREATE TABLE `phome_enewsuserclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuserjs;
CREATE TABLE `phome_enewsuserjs` (
  `jsid` int(10) unsigned NOT NULL auto_increment,
  `jsname` varchar(60) NOT NULL default '',
  `jssql` text NOT NULL,
  `jstempid` smallint(5) unsigned NOT NULL default '0',
  `jsfilename` varchar(120) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`jsid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuserjsclass;
CREATE TABLE `phome_enewsuserjsclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuserlist;
CREATE TABLE `phome_enewsuserlist` (
  `listid` int(10) unsigned NOT NULL auto_increment,
  `listname` varchar(60) NOT NULL default '',
  `pagetitle` varchar(120) NOT NULL default '',
  `filepath` varchar(120) NOT NULL default '',
  `filetype` varchar(12) NOT NULL default '',
  `totalsql` text NOT NULL,
  `listsql` text NOT NULL,
  `maxnum` int(11) NOT NULL default '0',
  `lencord` int(11) NOT NULL default '0',
  `listtempid` smallint(5) unsigned NOT NULL default '0',
  `pagekeywords` varchar(255) NOT NULL default '',
  `pagedescription` varchar(255) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`listid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuserlistclass;
CREATE TABLE `phome_enewsuserlistclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------


DROP TABLE IF EXISTS phome_enewsuserloginck;
CREATE TABLE `phome_enewsuserloginck` (
  `userid` int(10) unsigned NOT NULL auto_increment,
  `andauth` varchar(32) NOT NULL default '',
  `addauthvn` varchar(20) NOT NULL default '',
  `addauth` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsuseronepass;
CREATE TABLE `phome_enewsuseronepass` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `pno` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `salt` varchar(20) NOT NULL default '',
  `salt2` varchar(20) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adduserid` int(10) unsigned NOT NULL default '0',
  `edittime` int(10) unsigned NOT NULL default '0',
  `edituserid` int(10) unsigned NOT NULL default '0',
  `isopen` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`),
  KEY `pno` (`pno`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsvg;
CREATE TABLE `phome_enewsvg` (
  `vgid` smallint(5) unsigned NOT NULL auto_increment,
  `gname` char(60) NOT NULL default '',
  `gids` char(255) NOT NULL default '',
  `ingids` char(255) NOT NULL default '',
  `agids` char(255) NOT NULL default '',
  `mlist` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`vgid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsvglist;
CREATE TABLE `phome_enewsvglist` (
  `vgid` smallint(5) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  `outtime` int(10) unsigned NOT NULL default '0',
  KEY `vgid` (`vgid`),
  KEY `userid` (`userid`),
  KEY `addtime` (`addtime`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsvote;
CREATE TABLE `phome_enewsvote` (
  `voteid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(120) NOT NULL default '',
  `votenum` int(10) unsigned NOT NULL default '0',
  `voteip` mediumtext NOT NULL,
  `votetext` text NOT NULL,
  `voteclass` tinyint(1) NOT NULL default '0',
  `doip` tinyint(1) NOT NULL default '0',
  `votetime` int(10) unsigned NOT NULL default '0',
  `dotime` date NOT NULL default '0000-00-00',
  `width` int(11) NOT NULL default '0',
  `height` int(11) NOT NULL default '0',
  `addtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `tempid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`voteid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsvotemod;
CREATE TABLE `phome_enewsvotemod` (
  `voteid` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(120) NOT NULL default '',
  `votetext` text NOT NULL,
  `voteclass` tinyint(1) NOT NULL default '0',
  `doip` tinyint(1) NOT NULL default '0',
  `dotime` date NOT NULL default '0000-00-00',
  `tempid` smallint(5) unsigned NOT NULL default '0',
  `width` int(11) NOT NULL default '0',
  `height` int(11) NOT NULL default '0',
  `votenum` int(10) unsigned NOT NULL default '0',
  `ysvotename` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`voteid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewswapstyle;
CREATE TABLE `phome_enewswapstyle` (
  `styleid` smallint(5) unsigned NOT NULL auto_increment,
  `stylename` varchar(60) NOT NULL default '',
  `path` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`styleid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewswfinfo;
CREATE TABLE `phome_enewswfinfo` (
  `id` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `wfid` smallint(5) unsigned NOT NULL default '0',
  `tid` int(10) unsigned NOT NULL default '0',
  `groupid` text NOT NULL,
  `userclass` text NOT NULL,
  `username` text NOT NULL,
  `checknum` tinyint(4) NOT NULL default '0',
  `tstatus` varchar(30) NOT NULL default '0',
  `checktno` tinyint(4) NOT NULL default '0',
  KEY `id` (`id`,`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewswfinfolog;
CREATE TABLE `phome_enewswfinfolog` (
  `logid` int(10) unsigned NOT NULL auto_increment,
  `id` int(10) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `wfid` smallint(5) unsigned NOT NULL default '0',
  `tid` int(10) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `checktime` int(10) unsigned NOT NULL default '0',
  `checktext` text NOT NULL,
  `checknum` tinyint(4) NOT NULL default '0',
  `checktype` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`logid`),
  KEY `id` (`id`,`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewswords;
CREATE TABLE `phome_enewswords` (
  `wordid` smallint(5) unsigned NOT NULL auto_increment,
  `oldword` varchar(255) NOT NULL default '',
  `newword` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`wordid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsworkflow;
CREATE TABLE `phome_enewsworkflow` (
  `wfid` smallint(5) unsigned NOT NULL auto_increment,
  `wfname` varchar(60) NOT NULL default '',
  `wftext` varchar(255) NOT NULL default '',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `adduser` varchar(30) NOT NULL default '',
  `canedit` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`wfid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsworkflowitem;
CREATE TABLE `phome_enewsworkflowitem` (
  `tid` int(10) unsigned NOT NULL auto_increment,
  `wfid` smallint(5) unsigned NOT NULL default '0',
  `tname` varchar(60) NOT NULL default '',
  `tno` int(11) NOT NULL default '0',
  `ttext` varchar(255) NOT NULL default '',
  `groupid` text NOT NULL,
  `userclass` text NOT NULL,
  `username` text NOT NULL,
  `lztype` tinyint(1) NOT NULL default '0',
  `tbdo` int(10) unsigned NOT NULL default '0',
  `tddo` int(10) unsigned NOT NULL default '0',
  `tstatus` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`tid`),
  KEY `wfid` (`wfid`),
  KEY `tno` (`tno`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewswriter;
CREATE TABLE `phome_enewswriter` (
  `wid` smallint(5) unsigned NOT NULL auto_increment,
  `writer` varchar(30) NOT NULL default '',
  `email` varchar(120) NOT NULL default '',
  PRIMARY KEY  (`wid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsyh;
CREATE TABLE `phome_enewsyh` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `yhname` varchar(30) NOT NULL default '',
  `yhtext` varchar(255) NOT NULL default '',
  `hlist` int(11) NOT NULL default '0',
  `qlist` int(11) NOT NULL default '0',
  `bqnew` int(11) NOT NULL default '0',
  `bqhot` int(11) NOT NULL default '0',
  `bqpl` int(11) NOT NULL default '0',
  `bqgood` int(11) NOT NULL default '0',
  `bqfirst` int(11) NOT NULL default '0',
  `bqdown` int(11) NOT NULL default '0',
  `otherlink` int(11) NOT NULL default '0',
  `qmlist` int(11) NOT NULL default '0',
  `dobq` tinyint(1) NOT NULL default '0',
  `dojs` tinyint(1) NOT NULL default '0',
  `dosbq` tinyint(1) NOT NULL default '0',
  `rehtml` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewszt;
CREATE TABLE `phome_enewszt` (
  `ztid` mediumint(8) unsigned NOT NULL auto_increment,
  `ztname` varchar(60) NOT NULL default '',
  `onclick` int(10) unsigned NOT NULL default '0',
  `ztnum` tinyint(3) unsigned NOT NULL default '0',
  `listtempid` smallint(5) unsigned NOT NULL default '0',
  `ztpath` varchar(255) NOT NULL default '',
  `zttype` varchar(10) NOT NULL default '',
  `zturl` varchar(200) NOT NULL default '',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `islist` tinyint(1) NOT NULL default '0',
  `maxnum` int(11) NOT NULL default '0',
  `reorder` varchar(50) NOT NULL default '',
  `intro` text NOT NULL,
  `ztimg` varchar(255) NOT NULL default '',
  `zcid` smallint(5) unsigned NOT NULL default '0',
  `showzt` tinyint(1) NOT NULL default '0',
  `ztpagekey` varchar(255) NOT NULL default '',
  `classtempid` smallint(5) unsigned NOT NULL default '0',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `usezt` tinyint(1) NOT NULL default '0',
  `yhid` smallint(5) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `closepl` tinyint(1) NOT NULL default '0',
  `checkpl` tinyint(1) NOT NULL default '0',
  `restb` tinyint(3) unsigned NOT NULL default '0',
  `usernames` varchar(255) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `pltempid` smallint(5) unsigned NOT NULL default '0',
  `fclast` int(10) unsigned NOT NULL default '0',
  `ecmsvpf` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ztid`),
  KEY `classid` (`classid`),
  KEY `zcid` (`zcid`),
  KEY `usezt` (`usezt`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsztadd;
CREATE TABLE `phome_enewsztadd` (
  `ztid` mediumint(8) unsigned NOT NULL default '0',
  `classtext` mediumtext NOT NULL,
  PRIMARY KEY  (`ztid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsztclass;
CREATE TABLE `phome_enewsztclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsztf;
CREATE TABLE `phome_enewsztf` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,
  `f` varchar(30) NOT NULL default '',
  `fname` varchar(30) NOT NULL default '',
  `fform` varchar(20) NOT NULL default '',
  `fhtml` mediumtext NOT NULL,
  `fzs` varchar(255) NOT NULL default '',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `ftype` varchar(30) NOT NULL default '',
  `flen` varchar(20) NOT NULL default '',
  `fvalue` text NOT NULL,
  `fformsize` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsztinfo;
CREATE TABLE `phome_enewsztinfo` (
  `zid` int(10) unsigned NOT NULL auto_increment,
  `ztid` mediumint(8) unsigned NOT NULL default '0',
  `cid` mediumint(8) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `newstime` int(10) unsigned NOT NULL default '0',
  `mid` smallint(5) unsigned NOT NULL default '0',
  `isgood` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`zid`),
  KEY `ztid` (`ztid`),
  KEY `cid` (`cid`),
  KEY `classid` (`classid`),
  KEY `id` (`id`),
  KEY `newstime` (`newstime`),
  KEY `mid` (`mid`),
  KEY `isgood` (`isgood`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewszttype;
CREATE TABLE `phome_enewszttype` (
  `cid` mediumint(8) unsigned NOT NULL auto_increment,
  `ztid` mediumint(8) unsigned NOT NULL default '0',
  `cname` varchar(20) NOT NULL default '',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `islist` tinyint(1) NOT NULL default '0',
  `listtempid` smallint(5) unsigned NOT NULL default '0',
  `maxnum` int(10) unsigned NOT NULL default '0',
  `tnum` tinyint(3) unsigned NOT NULL default '0',
  `reorder` varchar(50) NOT NULL default '',
  `ttype` varchar(10) NOT NULL default '',
  `endtime` int(10) unsigned NOT NULL default '0',
  `tfile` varchar(60) NOT NULL default '',
  `fclast` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `ztid` (`ztid`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewszttypeadd;
CREATE TABLE `phome_enewszttypeadd` (
  `cid` mediumint(8) unsigned NOT NULL default '0',
  `classtext` mediumtext NOT NULL,
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM;


