<?phpheader('Content-type: application/json');include 'conf.php';include $BACK_PATH."/init.php";$id = $_GET['locId'];$name = $_GET['locName'];$lat = $_GET['locLat'];$lon = $_GET['locLon'];$updateArray = array(	'name' => $name,	'lat' => $lat,	'lon' => $lon);$res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('forecast_locations', 'id='.$id, $updateArray);if ($res){	$result["result"] = true;} else {	$result["result"] = false;}echo json_encode($result);?>