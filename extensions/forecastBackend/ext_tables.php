<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

if (TYPO3_MODE=='BE') {
	t3lib_extMgm::addModulePath('web_forecastBackend',t3lib_extMgm::extPath ($_EXTKEY).'mod/');
	t3lib_extMgm::addModule('web','forecastBackend','',t3lib_extMgm::extPath($_EXTKEY).'mod/');
}
?>