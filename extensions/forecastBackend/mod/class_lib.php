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
	function getDetails ($forecast_id){
		$i = 0;
		$locations_array = array();
		$locations = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, "SELECT distinct name, forecast_locations.id FROM forecast_locations, forecast_detail WHERE forecast_id = " . $forecast_id . " AND forecast_detail.location_id = forecast_locations.id");
		while ($location = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($locations)){
			$location_id = $location['id'];
			$location_name = $location['name'];
			//echo "location: ". $location_name ." <br>";
			$period_array = array();
			$periods = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, "SELECT distinct name, forecast_periods.id FROM forecast_periods, forecast_detail WHERE forecast_id = " . $forecast_id . " AND forecast_detail.period_id = forecast_periods.id");
			while ($period = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($periods)){
				$period_id = $period['id'];
				$period_name = $period['name'];
				//echo "period: ". $period_name ." <br>";
				$detail_array = array();
				$forecast_details = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, "SELECT * FROM forecast_detail WHERE forecast_id=" . $forecast_id . " AND period_id=".$period_id." AND location_id=".$location_id."");
				while ($forecast_detail = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($forecast_details)) {
					$text           = $forecast_detail['text'];
					$min_temp       = $forecast_detail['min_temp'];
					$max_temp       = $forecast_detail['max_temp'];
					$wind_speed     = $forecast_detail['wind_speed'];
					$wind_direction = $forecast_detail['wind_direction'];
					$icon_Q         = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'forecast_icons', "id='" . $forecast_detail['icon_id'] . "'");
					while ($icon = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($icon_Q)){
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