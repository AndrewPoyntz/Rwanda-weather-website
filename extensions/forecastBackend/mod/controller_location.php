<?php
header('Content-type: application/json');
require_once("class_lib.php");
$id = $_GET['locId'];
if (isset($id)){
	$location = new location($id);
} else {
	$location = new location();
}
$action = $_GET["action"];
$out = array();
switch ($action) {
	case "list":
		$locationList = array();
		$locations = $db->query("SELECT * FROM forecast_locations WHERE deleted = 0");
		while ($location = $locations->fetch()) {
			$locationList[] = array(
				'id' => $location['id'],
				'name' => $location['name'],
				'lat' => $location['lat'],
				'lon' => $location['lon']
			);
		}
		$out["locations"] = $locationList;
	break;
	case "add":
		$name = $_GET['locName'];
		$lat = $_GET['locLat'];
		$lon = $_GET['locLon'];
		$result = $location->add($name, $lat, $lon);
		if ($result){
			$out["result"] = true;
		} else {
			$out["result"] = false;
		}
	break;
	case "update":
		$id = $_GET['locId'];
		$name = $_GET['locName'];
		$lat = $_GET['locLat'];
		$lon = $_GET['locLon'];
		$result = $location->update($id, $name, $lat, $lon);
		if ($result){
			$out["result"] = true;
		} else {
			$out["result"] = false;
		}
	break;
	case "delete":
		$out["result"] = ($location->delete($_GET['id']) ? true : false);		
	break;
	default:
		$out["result"] = false;
}
echo json_encode($out);	
?>