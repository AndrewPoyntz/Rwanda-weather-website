--
-- Table structure for table `forecasts`
--

DROP TABLE IF EXISTS `forecasts`;
CREATE TABLE `forecasts` (
  `id` int(11) NOT NULL auto_increment,
  `issue_time` datetime,
  `published` datetime,
  `headline` text,
  `severe_weather` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forecast_detail`
--

DROP TABLE IF EXISTS `forecast_detail`;
CREATE TABLE `forecast_detail` (
  `id` int(15) unsigned NOT NULL auto_increment,
  `text` text,
  `min_temp` int(3) default NULL,
  `max_temp` int(3) default NULL,
  `wind_speed` int(3) default NULL,
  `wind_direction` varchar(3) NOT NULL,
  `icon_id` int(11) default NULL,
  `forecast_id` int(11) default NULL,
  `location_id` int(11) NOT NULL,
  `period_id` int(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forecast_icons`
--

DROP TABLE IF EXISTS `forecast_icons`;
CREATE TABLE `forecast_icons` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


-- --------------------------------------------------------

--
-- Table structure for table `forecast_locations`
--

DROP TABLE IF EXISTS `forecast_locations`;
CREATE TABLE `forecast_locations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `lat` varchar(255) NOT NULL default '',
  `lon` varchar(255) NOT NULL default '',
  `deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


-- --------------------------------------------------------

--
-- Table structure for table `forecast_periods`
--

DROP TABLE IF EXISTS `forecast_periods`;
CREATE TABLE `forecast_periods` (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

DROP TABLE IF EXISTS `warnings`;
CREATE TABLE `warnings` (
  `id` int(255) NOT NULL auto_increment,
  `title` varchar(200),
  `description` text,
  `valid_from` datetime,
  `valid_to` datetime,
  `type` varchar(10),
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;


DROP TABLE IF EXISTS `forecast_setup`;
CREATE TABLE `forecast_setup` (
  `conf_item` varchar(200) NOT NULL default '',
  `conf_value` varchar(200) default '',
  PRIMARY KEY (`conf_item`)
);