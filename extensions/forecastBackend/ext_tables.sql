--
-- Table structure for table `forecasts`
--

DROP TABLE IF EXISTS `forecasts`;
CREATE TABLE  `forecasts` (
  `id` int(11) NOT NULL auto_increment,
  `issue_time` datetime default '',
  `published` datetime default '',
  `headline` text,
  `severe_weather` text,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forecast_detail`
--

DROP TABLE IF EXISTS `forecast_detail`;
CREATE TABLE  `forecast_detail` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `text` text,
  `min_temp` int(3) default '',
  `max_temp` int(3) default '',
  `wind_speed` int(3) default '',
  `wind_direction` varchar(3) default '',
  `icon_id` int(11) default '',
  `forecast_id` int(11) default '',
  `location_id` int(11) default '',
  `period_id` int(2) default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forecast_icons`
--

DROP TABLE IF EXISTS `forecast_icons`;
CREATE TABLE  `forecast_icons` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forecast_icons`
--

INSERT INTO `forecast_icons` (`id`, `name`, `image`) VALUES
(1, 'Sunny', '1-sunny.png'),
(2, 'Sunny Intervals', '3-sunny-intervals.png'),
(3, 'Mist', '5-mist.png'),
(4, 'Fog', '6-fog.png'),
(5, 'Medium level cloud', '7-medium-level-cloud.png'),
(6, 'Low level cloud', '8-low-level-cloud.png'),
(7, 'Light rain shower', '10-light-rain-shower-day.png'),
(8, 'Drizzle', '11-drizzle.png'),
(9, 'Light Rain', '12-light-rain.png'),
(10, 'Heavy rain shower', '14-heavy-rain-shower-day.png'),
(11, 'Heavy rain', '15-heavy-rain.png'),
(12, 'Sleet shower', '17-sleet-shower-day.png'),
(13, 'Sleet', '18-sleet.png'),
(14, 'Hail shower', '20-hail-shower-day.png'),
(15, 'Hail', '21-hail.png'),
(16, 'Thundery shower', '29-thundery-shower-day.png'),
(17, 'Thunderstorm', '30-thunderstorm.png'),
(18, 'Tropical storm', '31-tropical-storm.png'),
(19, 'Haze', '33-haze.png');


-- --------------------------------------------------------

--
-- Table structure for table `forecast_locations`
--

DROP TABLE IF EXISTS `forecast_locations`;
CREATE TABLE  `forecast_locations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `lat` varchar(255) NOT NULL default '',
  `lon` varchar(255) NOT NULL default '',
  `deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

--
-- Dumping data for table `forecast_periods`
--

INSERT INTO `forecast_periods` (`id`, `name`) VALUES
(1, 'morning'),
(2, 'afternoon');


-- --------------------------------------------------------

--
-- Table structure for table `forecast_periods`
--

DROP TABLE IF EXISTS `forecast_periods`;
CREATE TABLE  `forecast_periods` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
