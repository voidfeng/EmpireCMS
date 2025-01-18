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

DROP TABLE IF EXISTS phome_enewsbqtemp;
CREATE TABLE `phome_enewsbqtemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(60) NOT NULL default '',
  `modid` smallint(5) unsigned NOT NULL default '0',
  `temptext` text NOT NULL,
  `showdate` varchar(50) NOT NULL default '',
  `listvar` text NOT NULL,
  `subnews` smallint(5) unsigned NOT NULL default '0',
  `rownum` smallint(5) unsigned NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `docode` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`tempid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsbqtempclass;
CREATE TABLE `phome_enewsbqtempclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` char(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclasstemp;
CREATE TABLE `phome_enewsclasstemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(30) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  `classid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tempid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsclasstempclass;
CREATE TABLE `phome_enewsclasstempclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` char(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsindexpage;
CREATE TABLE `phome_enewsindexpage` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(30) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  PRIMARY KEY  (`tempid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsjstemp;
CREATE TABLE `phome_enewsjstemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(30) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  `classid` smallint(5) unsigned NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  `showdate` varchar(20) NOT NULL default '',
  `modid` smallint(6) NOT NULL default '0',
  `subnews` smallint(6) NOT NULL default '0',
  `subtitle` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`tempid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsjstempclass;
CREATE TABLE `phome_enewsjstempclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewslisttemp;
CREATE TABLE `phome_enewslisttemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(60) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  `subnews` smallint(6) NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  `listvar` text NOT NULL,
  `rownum` smallint(6) NOT NULL default '0',
  `modid` smallint(6) NOT NULL default '0',
  `showdate` varchar(50) NOT NULL default '',
  `subtitle` smallint(6) NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `docode` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`tempid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewslisttempclass;
CREATE TABLE `phome_enewslisttempclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsnewstemp;
CREATE TABLE `phome_enewsnewstemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(60) NOT NULL default '',
  `isdefault` tinyint(1) NOT NULL default '0',
  `temptext` mediumtext NOT NULL,
  `showdate` varchar(50) NOT NULL default '',
  `modid` smallint(6) NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tempid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsnewstempclass;
CREATE TABLE `phome_enewsnewstempclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspagetemp;
CREATE TABLE `phome_enewspagetemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(30) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  PRIMARY KEY  (`tempid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspltemp;
CREATE TABLE `phome_enewspltemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(60) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  `isdefault` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`tempid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsprinttemp;
CREATE TABLE `phome_enewsprinttemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(60) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  `isdefault` tinyint(1) NOT NULL default '0',
  `showdate` varchar(50) NOT NULL default '',
  `modid` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`tempid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewspubtemp;
CREATE TABLE `phome_enewspubtemp` (
  `id` smallint(6) NOT NULL auto_increment,
  `indextemp` mediumtext NOT NULL,
  `cptemp` mediumtext NOT NULL,
  `searchtemp` mediumtext NOT NULL,
  `searchjstemp` mediumtext NOT NULL,
  `searchjstemp1` mediumtext NOT NULL,
  `otherlinktemp` mediumtext NOT NULL,
  `downsofttemp` text NOT NULL,
  `onlinemovietemp` text NOT NULL,
  `listpagetemp` text NOT NULL,
  `gbooktemp` mediumtext NOT NULL,
  `loginiframe` mediumtext NOT NULL,
  `otherlinktempsub` tinyint(4) NOT NULL default '0',
  `otherlinktempdate` varchar(20) NOT NULL default '',
  `loginjstemp` mediumtext NOT NULL,
  `downpagetemp` mediumtext NOT NULL,
  `pljstemp` mediumtext NOT NULL,
  `schalltemp` mediumtext NOT NULL,
  `schallsubnum` smallint(6) NOT NULL default '0',
  `schalldate` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssearchtemp;
CREATE TABLE `phome_enewssearchtemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(60) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  `subnews` smallint(6) NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  `listvar` text NOT NULL,
  `rownum` smallint(6) NOT NULL default '0',
  `modid` smallint(6) NOT NULL default '0',
  `showdate` varchar(50) NOT NULL default '',
  `subtitle` smallint(6) NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `docode` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`tempid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewssearchtempclass;
CREATE TABLE `phome_enewssearchtempclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstempbak;
CREATE TABLE `phome_enewstempbak` (
  `bid` int(10) unsigned NOT NULL auto_increment,
  `tempid` smallint(5) unsigned NOT NULL default '0',
  `tempname` varchar(60) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  `subnews` smallint(6) NOT NULL default '0',
  `isdefault` tinyint(1) NOT NULL default '0',
  `listvar` text NOT NULL,
  `rownum` smallint(6) NOT NULL default '0',
  `modid` smallint(5) unsigned NOT NULL default '0',
  `showdate` varchar(50) NOT NULL default '',
  `subtitle` smallint(6) NOT NULL default '0',
  `classid` smallint(5) unsigned NOT NULL default '0',
  `docode` tinyint(1) NOT NULL default '0',
  `baktime` int(10) unsigned NOT NULL default '0',
  `temptype` varchar(30) NOT NULL default '',
  `gid` smallint(5) unsigned NOT NULL default '0',
  `lastuser` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`bid`),
  KEY `tempid` (`tempid`),
  KEY `temptype` (`temptype`),
  KEY `gid` (`gid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstempdt;
CREATE TABLE `phome_enewstempdt` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempvar` char(30) NOT NULL default '',
  `tempname` char(30) NOT NULL default '',
  `tempsay` char(255) NOT NULL default '',
  `tempfile` char(200) NOT NULL default '',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  `temptype` char(20) NOT NULL default '',
  PRIMARY KEY  (`tempid`),
  UNIQUE KEY `tempvar` (`tempvar`),
  KEY `temptype` (`temptype`),
  KEY `myorder` (`myorder`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstempgroup;
CREATE TABLE `phome_enewstempgroup` (
  `gid` smallint(5) unsigned NOT NULL auto_increment,
  `gname` varchar(60) NOT NULL default '',
  `isdefault` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`gid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstempvar;
CREATE TABLE `phome_enewstempvar` (
  `varid` smallint(5) unsigned NOT NULL auto_increment,
  `myvar` varchar(60) NOT NULL default '',
  `varname` varchar(60) NOT NULL default '',
  `varvalue` mediumtext NOT NULL,
  `classid` smallint(5) unsigned NOT NULL default '0',
  `isclose` tinyint(1) NOT NULL default '0',
  `myorder` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`varid`),
  KEY `classid` (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewstempvarclass;
CREATE TABLE `phome_enewstempvarclass` (
  `classid` smallint(5) unsigned NOT NULL auto_increment,
  `classname` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`classid`)
) TYPE=MyISAM;

# --------------------------------------------------------

DROP TABLE IF EXISTS phome_enewsvotetemp;
CREATE TABLE `phome_enewsvotetemp` (
  `tempid` smallint(5) unsigned NOT NULL auto_increment,
  `tempname` varchar(60) NOT NULL default '',
  `temptext` mediumtext NOT NULL,
  PRIMARY KEY  (`tempid`)
) TYPE=MyISAM;



