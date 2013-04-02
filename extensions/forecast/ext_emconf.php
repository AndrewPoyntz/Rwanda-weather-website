<?php

########################################################################
# Extension Manager/Repository config file for ext "forecast".
#
# Auto generated 24-03-2013 23:31
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Forecast',
	'description' => 'Fetch and display forecast info',
	'category' => 'plugin',
	'author' => 'Andrew Poyntz',
	'author_company' => 'Met Office',
	'author_email' => 'andrew.poyntz@metoffice.gov.uk',
	'dependencies' => 'extbase,fluid',
	'state' => 'beta',
	'clearCacheOnLoad' => 1,
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.4.4-0.0.0',
			'extbase' => '0.0.0-0.0.0',
			'fluid' => '0.0.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:6:{s:12:"ext_icon.gif";s:4:"96df";s:17:"ext_localconf.php";s:4:"27e8";s:14:"ext_tables.php";s:4:"3088";s:14:"ext_tables.sql";s:4:"9f85";s:36:"Classes/Controller/AppController.php";s:4:"9d58";s:42:"Resources/Private/Templates/App/index.html";s:4:"1339";}',
	'suggests' => array(
	),
	'conflicts' => '',
);

?>