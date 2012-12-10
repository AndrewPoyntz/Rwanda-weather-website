<?php

########################################################################
# Extension Manager/Repository config file for ext "forecastBackend".
#
# Auto generated 10-12-2012 21:29
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
	'state' => 'alpha',
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
	'version' => '0.0.1',
	'_md5_values_when_last_written' => 'a:46:{s:12:"ext_icon.gif";s:4:"96df";s:17:"ext_localconf.php";s:4:"88b7";s:14:"ext_tables.php";s:4:"0128";s:14:"ext_tables.sql";s:4:"b7cf";s:17:"css/locations.css";s:4:"8409";s:12:"css/main.css";s:4:"41f0";s:25:"img/0-clear-sky-night.png";s:4:"8b66";s:15:"img/1-sunny.png";s:4:"d64c";s:32:"img/10-light-rain-shower-day.png";s:4:"e612";s:18:"img/11-drizzle.png";s:4:"d161";s:21:"img/12-light-rain.png";s:4:"13cd";s:34:"img/13-heavy-rain-shower-night.png";s:4:"b4db";s:32:"img/14-heavy-rain-shower-day.png";s:4:"a5a0";s:21:"img/15-heavy-rain.png";s:4:"08e9";s:29:"img/16-sleet-shower-night.png";s:4:"33fc";s:27:"img/17-sleet-shower-day.png";s:4:"a125";s:16:"img/18-sleet.png";s:4:"393d";s:28:"img/19-hail-shower-night.png";s:4:"56f7";s:29:"img/2-partly-cloudy-night.png";s:4:"f61c";s:26:"img/20-hail-shower-day.png";s:4:"8031";s:15:"img/21-hail.png";s:4:"5cdd";s:32:"img/28-thundery-shower-night.png";s:4:"ce58";s:30:"img/29-thundery-shower-day.png";s:4:"3f9c";s:25:"img/3-sunny-intervals.png";s:4:"b9aa";s:23:"img/30-thunderstorm.png";s:4:"6842";s:25:"img/31-tropical-storm.png";s:4:"4f1a";s:15:"img/33-haze.png";s:4:"56de";s:14:"img/4-dust.png";s:4:"b322";s:14:"img/5-mist.png";s:4:"7efa";s:13:"img/6-fog.png";s:4:"fe65";s:28:"img/7-medium-level-cloud.png";s:4:"290f";s:25:"img/8-low-level-cloud.png";s:4:"bce1";s:33:"img/9-light-rain-shower-night.png";s:4:"0427";s:19:"img/icon_sprite.png";s:4:"17b0";s:22:"js/jquery-1.8.3.min.js";s:4:"e128";s:10:"js/main.js";s:4:"6108";s:13:"mod/about.gif";s:4:"24ff";s:12:"mod/conf.php";s:4:"8743";s:13:"mod/index.php";s:4:"e956";s:21:"mod/locallang_mod.xml";s:4:"9104";s:30:"mod/processor_forecastForm.php";s:4:"3b29";s:31:"mod/processor_forecastIssue.php";s:4:"8cab";s:29:"mod/processor_locationAdd.php";s:4:"9a32";s:32:"mod/processor_locationDelete.php";s:4:"6d2b";s:30:"mod/processor_locationEdit.php";s:4:"2b8d";s:30:"mod/processor_locationList.php";s:4:"5820";}',
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