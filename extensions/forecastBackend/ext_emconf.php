<?php

########################################################################
# Extension Manager/Repository config file for ext "forecastBackend".
#
# Auto generated 23-03-2013 18:38
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Forecast',
	'description' => 'Allows updating of forecast info, note to work. note: This extension requires the accompanying forecast frontend plugin.',
	'category' => 'module',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod',
	'doNotLoadInFE' => 1,
	'state' => 'beta',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Andrew Poyntz',
	'author_email' => 'andrew.poyntz@metoffice.gov.uk',
	'author_company' => 'Met Office',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '1.0.0',
	'_md5_values_when_last_written' => 'a:65:{s:12:"ext_icon.gif";s:4:"96df";s:17:"ext_localconf.php";s:4:"88b7";s:14:"ext_tables.php";s:4:"0128";s:14:"ext_tables.sql";s:4:"9f85";s:28:"css/jquery-ui-1.10.2.min.css";s:4:"dffc";s:17:"css/locations.css";s:4:"8409";s:12:"css/main.css";s:4:"4807";s:25:"img/0-clear-sky-night.png";s:4:"8b66";s:15:"img/1-sunny.png";s:4:"d64c";s:32:"img/10-light-rain-shower-day.png";s:4:"e612";s:18:"img/11-drizzle.png";s:4:"d161";s:21:"img/12-light-rain.png";s:4:"13cd";s:34:"img/13-heavy-rain-shower-night.png";s:4:"b4db";s:32:"img/14-heavy-rain-shower-day.png";s:4:"a5a0";s:21:"img/15-heavy-rain.png";s:4:"08e9";s:29:"img/16-sleet-shower-night.png";s:4:"33fc";s:27:"img/17-sleet-shower-day.png";s:4:"a125";s:16:"img/18-sleet.png";s:4:"393d";s:28:"img/19-hail-shower-night.png";s:4:"56f7";s:29:"img/2-partly-cloudy-night.png";s:4:"f61c";s:26:"img/20-hail-shower-day.png";s:4:"8031";s:15:"img/21-hail.png";s:4:"5cdd";s:32:"img/28-thundery-shower-night.png";s:4:"ce58";s:30:"img/29-thundery-shower-day.png";s:4:"3f9c";s:25:"img/3-sunny-intervals.png";s:4:"b9aa";s:23:"img/30-thunderstorm.png";s:4:"6842";s:25:"img/31-tropical-storm.png";s:4:"4f1a";s:15:"img/33-haze.png";s:4:"56de";s:14:"img/4-dust.png";s:4:"b322";s:14:"img/5-mist.png";s:4:"7efa";s:13:"img/6-fog.png";s:4:"fe65";s:28:"img/7-medium-level-cloud.png";s:4:"290f";s:25:"img/8-low-level-cloud.png";s:4:"bce1";s:33:"img/9-light-rain-shower-night.png";s:4:"0427";s:13:"img/Thumbs.db";s:4:"9c3c";s:19:"img/icon_sprite.png";s:4:"17b0";s:27:"img/UI/animated-overlay.gif";s:4:"2b91";s:48:"img/UI/ui-bg_diagonals-thick_18_b81900_40x40.png";s:4:"c884";s:48:"img/UI/ui-bg_diagonals-thick_20_666666_40x40.png";s:4:"7d37";s:38:"img/UI/ui-bg_flat_10_000000_40x100.png";s:4:"6e8d";s:39:"img/UI/ui-bg_glass_100_f6f6f6_1x400.png";s:4:"8419";s:39:"img/UI/ui-bg_glass_100_fdf5ce_1x400.png";s:4:"2d33";s:38:"img/UI/ui-bg_glass_65_ffffff_1x400.png";s:4:"63d0";s:45:"img/UI/ui-bg_gloss-wave_35_f6a828_500x100.png";s:4:"781c";s:48:"img/UI/ui-bg_highlight-soft_100_eeeeee_1x100.png";s:4:"a51b";s:47:"img/UI/ui-bg_highlight-soft_75_ffe45c_1x100.png";s:4:"1f57";s:34:"img/UI/ui-icons_222222_256x240.png";s:4:"a1b3";s:34:"img/UI/ui-icons_228ef1_256x240.png";s:4:"7304";s:34:"img/UI/ui-icons_ef8c08_256x240.png";s:4:"1eec";s:34:"img/UI/ui-icons_ffd27a_256x240.png";s:4:"c1a7";s:34:"img/UI/ui-icons_ffffff_256x240.png";s:4:"e3f4";s:18:"js/jquery-1.9.1.js";s:4:"08c2";s:33:"js/jquery-ui-1.10.2.custom.min.js";s:4:"2761";s:32:"js/jquery-ui-timepicker-addon.js";s:4:"4f23";s:10:"js/main.js";s:4:"00f5";s:13:"mod/about.gif";s:4:"24ff";s:16:"mod/class_db.php";s:4:"436b";s:17:"mod/class_lib.php";s:4:"8a96";s:12:"mod/conf.php";s:4:"8743";s:27:"mod/controller_forecast.php";s:4:"d0c9";s:27:"mod/controller_location.php";s:4:"f332";s:26:"mod/controller_warning.php";s:4:"d2eb";s:13:"mod/index.php";s:4:"d751";s:21:"mod/locallang_mod.xml";s:4:"9104";s:32:"mod/processor_CreateJSONdata.php";s:4:"c698";}',
	'constraints' => array(
		'depends' => array(
			'php' => '5.1.0-0.0.0',
			'typo3' => '4.4.4-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>