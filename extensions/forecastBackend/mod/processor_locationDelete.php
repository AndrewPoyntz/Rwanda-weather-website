<?phpheader('Content-type: application/json');include 'conf.php';include $BACK_PATH."/init.php";$id = $_GET['id'];$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery('forecast_locations', 'id='.$id);if ($res){	$result["result"] = true;} else {	$result["result"] = false;}echo json_encode($result);?>