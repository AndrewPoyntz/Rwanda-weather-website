<?phpheader('Content-type: application/json');include 'conf.php';include $BACK_PATH."/init.php";$result = array();// get locations$locations = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'forecast_locations');$locationList = array();$result["numLocations"] = $GLOBALS['TYPO3_DB']->sql_num_rows($locations);while ($location = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($locations)) {	$locationList[] = array(		'id' => $location['id'],		'name' => $location['name'],		'lat' => $location['lat'],		'lon' => $location['lon']	);}$result["locations"] = $locationList;// get periods$periods = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'forecast_periods');$periodList = array();$result["numPeriods"] = $GLOBALS['TYPO3_DB']->sql_num_rows($periods);while ($period = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($periods)) {	$periodList[] = array(		'id' => $period['id'],		'name' => $period['name']	);}$result["periods"] = $periodList;// get icons$icons = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'forecast_icons');$iconList = array();$result["numIcons"] = $GLOBALS['TYPO3_DB']->sql_num_rows($icons);while ($icon = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($icons)) {	$iconList[] = array(		'id' => $icon['id'],		'name' => $icon['name'],		'image' => $icon['image']	);}$result["icons"] = $iconList;echo json_encode($result);?>