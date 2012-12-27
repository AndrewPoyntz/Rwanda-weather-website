<?php
header('Content-type: application/json');
include 'conf.php';
include $BACK_PATH."/init.php";
require 'class_db.php';
if (isset($_GET['id'])){
	$edit = true;
	$editId = $_GET['id'];
} else {
	$edit = false;
}
$result = array();
// get locations
if ($edit){
	//$result["query"] = "SELECT distinct name, forecast_locations.id, lat, lon, forecast_id as used FROM forecast_locations left JOIN forecast_detail on forecast_locations.id = forecast_detail.location_id and forecast_id = '".$editId."' and deleted <> 1";
	//$locations = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, "SELECT distinct name, forecast_locations.id, lat, lon, forecast_id as used FROM forecast_locations left JOIN forecast_detail on forecast_locations.id = forecast_detail.location_id and forecast_id = '".$editId."' and deleted <> 1");
	$locations = $db->prepare("SELECT distinct name, forecast_locations.id, lat, lon, forecast_id as used FROM forecast_locations left JOIN forecast_detail on forecast_locations.id = forecast_detail.location_id and forecast_id = :editId  and deleted <> 1");
    $locations->bindParam(':editId', $editId);
    $locations->execute();
	$locations->setFetchMode(PDO::FETCH_ASSOC); // this only needs to be done on prepared statements?
	//$locations = $db->selectLJ("distinct name, forecast_locations.id, lat, lon, forecast_id as used", "forecast_locations", "forecast_detail", "id", "=", "location_id", "forecast_id = '".$editId."' and deleted <> 1");
} else {
	$locations = $db->query('SELECT * FROM forecast_locations');
}
$locationList = array();
while ($location = $locations->fetch()) {
	if (array_key_exists('used', $location)){
		$used = ($location['used'] !== null ? true : false);
	} else {
		$used = false;
	}
	$locationList[] = array(
		'id' => $location['id'],
		'name' => $location['name'],
		'lat' => $location['lat'],
		'lon' => $location['lon'],
		'used' => $used
	);
}
$result["locations"] = $locationList;
// get periods
$periods = $db->query('SELECT * FROM forecast_periods');

$periodList = array();
if ($periods === false){
    $echo = $db->errorInfo();
    //log the error or take some other smart action
} else {
	while ($period = $periods->fetch()) {
		$periodList[] = array(
			'id' => $period['id'],
			'name' => $period['name']
		);
	}
}
$result["periods"] = $periodList;

// get icons
$icons = $db->query('SELECT * FROM forecast_icons');
$iconList = array();
while ($icon = $icons->fetch()) {
	$iconList[] = array(
		'id' => $icon['id'],
		'name' => $icon['name'],
		'image' => $icon['image']
	);
}
$result["icons"] = $iconList;
echo json_encode($result);
?>