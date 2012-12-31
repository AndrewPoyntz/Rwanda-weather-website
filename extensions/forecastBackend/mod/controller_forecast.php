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
		$forecast_id = (isset($_GET["id"]) ? $_GET["id"] : "");
		$out["info"] = $forecast->info($forecast_id);
		$out["locations"] = $forecast->details($forecast_id);
		$out["icons"] = $forecast->icons();
	break;
	case "issue":
		$issue_time = $_POST['issue_time'];
		$headline = $_POST['headline'];
		$severe_weather = $_POST['severe_weather'];
		$forecast_id = $forecast->issue($issue_time, $headline, $severe_weather);
		$result = true;
		if ($forecast_id !== false){
			foreach ($forecast->periods as $period) {;
				$period_id = $period['id'];
				foreach($forecast->locations as $location){
					$location_id = $location['id'];
					$beginning = $period_id . '_' . $location_id;
					$text = $_POST[$beginning . 'text'];
					$min_temp = $_POST[$beginning . 'minTemp'];
					$max_temp = $_POST[$beginning . 'maxTemp'];
					$wind_speed = $_POST[$beginning . 'windSpeed'];
					$wind_direction = $_POST[$beginning . 'windDir'];
					$icon_id = $_POST[$beginning . 'icon'];
					$query = $forecast->issue_detail($text, $min_temp, $max_temp, $wind_speed, $wind_direction, $icon_id,	$forecast_id, $location_id, $period_id);
					if (!$query){
						$result = false;
					}
				}
			}
			if (isset($_POST['id'])){
				$forecast->delete($_POST['id']);
			}
			$out["result"] = $result;
		} else {
			$out["result"] = $forecast_id;
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