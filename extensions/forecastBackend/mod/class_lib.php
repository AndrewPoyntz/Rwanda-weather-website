<?php
require('./class_db.php');

class location{
	var $ID;
	var $name;
	var $lat;
	var $lon;
	function __construct($location_data){
		global $db;
		if (!empty($location_data)){
			if (!is_array($location_data)){
				$locations = $db->prepare("SELECT * FROM forecast_locations WHERE id = :id LIMIT 1");
				$loactions->bindParam(':id', $location_data);
				$locations->execute();
				$location_data = $locations->fetch(); 
			}
			$this->ID = stripslashes($location_data['id']);
			$this->name = stripslashes($location_data['name']);
			$this->lat = stripslashes($location_data['lat']);
			$this->lon = stripslashes($location_data['lon']);
		}
	}
	
	function add ($name, $lat, $lon){
		global $db;
		$query = $db->prepare("INSERT INTO forecast_locations VALUES ('', :name, :lat, :lon, 0)");
		$query->bindParam(':name', $name);
		$query->bindParam(':lat', $lat);
		$query->bindParam(':lon', $lon);
		$query->execute();
		if ($query){
			$this->id = $db->lastInsertId();
			$this->name = $name;
			$this->lat = $lat;
			$this->lon = $lon;
			return true;
		} else {
			return false;
		};
	}
	function update ($id, $name, $lat, $lon){
		global $db;
		$query = $db->prepare("UPDATE forecast_locations SET name = :name, lat = :lat, lon = :lon WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->bindParam(':name', $name);
		$query->bindParam(':lat', $lat);
		$query->bindParam(':lon', $lon);
		if ($query){
			return true;
		} else {
			return false;
		}
	}
	function delete ($id){
		global $db;
		$query = $db->prepare("UPDATE forecast_locations SET deleted = 1 WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->execute();
		if ($query){
			return true;
		} else {
			return false;
		}
	}
}
class forecast {
	function __construct(){
		
	}
	function list_all (){
		global $db;
		$forecasts = $db->query("SELECT * FROM forecasts");
		$forecastList = array();
		while ($forecast = $forecasts->fetch()) {
			$forecastList[] = array(
				'id' => $forecast['id'],
				'rawIssueTime' => $forecast['issue_time'],
				'date' => date('D jS F Y', strtotime($forecast['issue_time'])),
				'headline' => $forecast['headline'],
				'severeWeather' => $forecast['severe_weather']
			);
		}
		return $forecastList;
	}
	function details ($forecast_id){
		global $db;
		$i = 0;
		$locations_array = array();
		$locations			= $db->prepare("SELECT distinct name, forecast_locations.id FROM forecast_locations, forecast_detail WHERE forecast_detail.location_id = forecast_locations.id");
		$periods 			= $db->prepare("SELECT distinct name, forecast_periods.id FROM forecast_periods, forecast_detail WHERE forecast_detail.period_id = forecast_periods.id");
		$forecast_details	= $db->prepare("SELECT * FROM forecast_detail WHERE forecast_id = :forecastID AND period_id = :periodID AND location_id = :locationID LIMIT 1");
		$icons				= $db->prepare("SELECT * FROM forecast_icons WHERE id = :iconID LIMIT 1");
		$forecast_details->bindParam(':forecastID', $forecast_id);
		$locations->execute();
		while ($location = $locations->fetch()){
			$periods->execute();
			$forecast_details->bindParam(':locationID', $location['id']);
			$location_name = $location['name'];
			//echo "location: ". $location_name ." <br>";
			$period_array = array();
			while ($period = $periods->fetch()){
				$forecast_details->bindValue(':periodID', $period['id']);
				$period_name = $period['name'];
				//echo "period: ". $period_name ." <br>";
				$detail_array = array();
				$forecast_details->execute();
				//echo $forecast_details->debugDumpParams();
				while ($forecast_detail = $forecast_details->fetch()) {
					$text           = $forecast_detail['text'];
					$min_temp       = $forecast_detail['min_temp'];
					$max_temp       = $forecast_detail['max_temp'];
					$wind_speed     = $forecast_detail['wind_speed'];
					$wind_direction = $forecast_detail['wind_direction'];
					$icons->bindParam(':iconID', $forecast_detail['icon_id']);
					$icons->execute();
					while ($icon = $icons->fetch()){
						$icon_image = $icon['image'];
						$icon_name = $icon['name'];
					}
					//echo "   detail : ".$icon_image. " " . $text . " " . $min_temp . " " . $max_temp . " " . $wind_speed . " " . $wind_direction . "<hr>";
					$detail_array[] = array(
						'icon' => $icon_image,
						'icon_name' => $icon_name,
						'text' => $text,
						'minTemp' => $min_temp,
						'maxTemp' => $max_temp,
						'windSpeed' => $wind_speed,
						'windDirection' => $wind_direction,
						'windSpeed' => $wind_speed
					);
				}
				$period_array[] = array(
					'name' => $period_name,
					'detail' => $detail_array
				);
			}
			$i = $i + 1;
			$class = ($i % 2 === 0 ? " class='row'" : "");
			$locations_array[] = array(
				'name' => $location_name,
				'rowclass' => $class,
				'periods' => $period_array
			);
		}
		return $locations_array;
	}
}
?>