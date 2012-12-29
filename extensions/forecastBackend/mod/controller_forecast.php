<?php
header('Content-type: application/json');
require_once("class_lib.php");
$action = $_GET["action"];
$forecast = new forecast();
$out = array();
switch ($action) {
	case "list":
		$out["forecasts"] = $forecast->list_all();
	break;
	case "form":
		$forecast_id = (isset($_GET["forecast_id"]) ? $_GET["forecast_id"] : "");
		$out["locations"] = $forecast->details($forecast_id);
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