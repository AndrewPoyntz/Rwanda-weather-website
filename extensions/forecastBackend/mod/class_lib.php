<?php
require('class_db.php');

class location{
	var $ID;
	var $name;
	var $lat;
	var $lon;
	function __construct($location_data) {
		global $db;
		if (!empty($location_data)) {
			if (!is_array($location_data)) {
				$locations = $db->prepare("SELECT * FROM forecast_locations WHERE id = ':id' LIMIT 1");
				$locations->execute(array(':id' => $location_data));
				$location_data = $locations->fetch(); 
			}
			$this->ID = stripslashes($location_data['id']);
			$this->name = stripslashes($location_data['name']);
			$this->lat = stripslashes($location_data['lat']);
			$this->lon = stripslashes($location_data['lon']);
		}
	}
	
	function add ($name, $lat, $lon) {
		global $db;
		$query = $db->prepare("INSERT INTO forecast_locations VALUES ('', :name, :lat, :lon, 0)");
		$query->bindParam(':name', $name);
		$query->bindParam(':lat', $lat);
		$query->bindParam(':lon', $lon);
		$query->execute();
		if ($query) {
			$this->id = $db->lastInsertId();
			$this->name = $name;
			$this->lat = $lat;
			$this->lon = $lon;
			return true;
		} else {
			return false;
		};
	}
	function update ($id, $name, $lat, $lon) {
		global $db;
		$query = $db->prepare("UPDATE forecast_locations SET name = :name, lat = :lat, lon = :lon WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->bindParam(':name', $name);
		$query->bindParam(':lat', $lat);
		$query->bindParam(':lon', $lon);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	function delete ($id) {
		global $db;
		$query = $db->prepare("UPDATE forecast_locations SET deleted = 1 WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->execute();
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}
class forecast {
	var $locations;
	var $periods;
	var $icons;
	function __construct() {
		$this->locations = $this->locations();
		$this->periods = $this->periods();
		$this->icons = $this->icons();
	}
	function list_all () {
		global $db;
		$forecasts = $db->query("SELECT * FROM forecasts ORDER BY issue_time");
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
	function info ($forecast_id) {
		global $db;
		$info = array();
		$query = $db->prepare("SELECT * FROM forecasts WHERE id = :id");
		$query->bindParam(':id', $forecast_id);
		$query->execute();
		$forecast = $query->fetch();
		$info["id"] = $forecast['id'];
		if (!empty($forecast['issue_time'])) {
			$info["issueTime"] = date('Y-m-d', strtotime($forecast['issue_time']));
		} else {
			$info["issueTime"] = date('Y-m-d');
		}
		$info["headline"] = $forecast['headline'];
		$info["severeWeather"] = $forecast['severe_weather'];
		return $info;
	}
	function details ($forecast_id) {
		global $db;
		$i = 0;
		$locations_array = array();
		$locations			= $db->prepare("SELECT distinct name, forecast_locations.id FROM forecast_locations, forecast_detail WHERE forecast_detail.location_id = forecast_locations.id");
		$periods 			= $db->prepare("SELECT distinct name, forecast_periods.id FROM forecast_periods, forecast_detail WHERE forecast_detail.period_id = forecast_periods.id");
		$forecast_details	= $db->prepare("SELECT * FROM forecast_detail WHERE forecast_id = :forecastID AND period_id = :periodID AND location_id = :locationID LIMIT 1");
		$icons				= $db->prepare("SELECT * FROM forecast_icons WHERE id = :iconID LIMIT 1");
		$forecast_details->bindParam(':forecastID', $forecast_id);
		$locations->execute();
		while ($location = $locations->fetch()) {
			$periods->execute();
			$location_id = $location['id'];
			$location_name = $location['name'];
			$forecast_details->bindParam(':locationID', $location_id);
			//echo "location: ". $location_name ." <br>";
			$period_array = array();
			while ($period = $periods->fetch()) {
				$period_id = $period['id'];
				$period_name = $period['name'];
				$forecast_details->bindValue(':periodID', $period_id);
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
					while ($icon = $icons->fetch()) {
						$icon_id = $icon['id'];
						$icon_image = $icon['image'];
						$icon_name = $icon['name'];
					}
					//echo "   detail : ".$icon_image. " " . $text . " " . $min_temp . " " . $max_temp . " " . $wind_speed . " " . $wind_direction . "<hr>";
					$detail_array[] = array(
						'icon' => $icon_image,
						'iconName' => $icon_name,
						'iconId' => $icon_id,
						'text' => $text,
						'minTemp' => $min_temp,
						'maxTemp' => $max_temp,
						'windSpeed' => $wind_speed,
						'windDirection' => $wind_direction,
						'windSpeed' => $wind_speed
					);
				}
				$period_array[] = array(
					'id' => $period_id,
					'name' => $period_name,
					'detail' => $detail_array
				);
			}
			$i = $i + 1;
			$class = ($i % 2 === 0 ? " class='row'" : "");
			$locations_array[] = array(
				'id' => $location_id,
				'name' => $location_name,
				'rowclass' => $class,
				'periods' => $period_array
			);
		}
		return $locations_array;
	}
	function locations () {
		global $db;
		$locations = $db->query("SELECT * FROM forecast_locations");
		$location_list = array();
		while ($location = $locations->fetch()){
			$location_list[] = array(
				'id' => $location["id"],
				'name' => $location["name"],
				'lon' => $location["lat"],
				'lon' => $location["lon"]
			);
		}
		return $location_list;
	}
	function periods () {
		global $db;
		$periods = $db->query("SELECT * FROM forecast_periods");
		$period_list = array();
		while ($period = $periods->fetch()){
			$period_list[] = array(
				'id' => $period["id"],
				'name' => $period["name"]
			);
		}
		return $period_list;
	}
	function icons () {
		global $db;
		$icons = $db->query('SELECT * FROM forecast_icons');
		$icon_list = array();
		while ($icon = $icons->fetch()) {
			$icon_list[] = array(
				'id' => $icon['id'],
				'name' => $icon['name'],
				'image' => $icon['image']
			);
		}
		return $icon_list;
	}
	function issue ($issue_time, $headline, $severe_weather) {
		global $db;
		$query = $db->prepare("INSERT INTO forecasts VALUES ('', :issue_time, :published, :headline, :severe_weather)");
		$query->bindParam(':issue_time', date('Y-m-d', strtotime($issue_time)));
		$query->bindParam(':published', date('Y-m-d H:i:s'));
		$query->bindParam(':headline', $headline);
		$query->bindParam(':severe_weather', $severe_weather);
		$query->execute();
		if ($query){
			return $db->lastInsertId();
		} else {
			return false;
		}
	}
	function issue_detail ($text, $min_temp, $max_temp, $wind_speed, $wind_direction, $icon_id,	$forecast_id, $location_id, $period_id){
		global $db;
		$query = $db->prepare("INSERT INTO forecast_detail VALUES ('', :text, :min_temp, :max_temp, :wind_speed, :wind_direction, :icon_id, :forecast_id, :location_id, :period_id)");
		$query->bindParam(':text', $text);
		$query->bindParam(':min_temp', $min_temp);
		$query->bindParam(':max_temp', $max_temp);
		$query->bindParam(':wind_speed', $wind_speed);
		$query->bindParam(':wind_direction', $wind_direction);
		$query->bindParam(':icon_id', $icon_id);
		$query->bindParam(':forecast_id', $forecast_id);
		$query->bindParam(':location_id', $location_id);
		$query->bindParam(':period_id', $period_id);
		$query->execute();
		if ($query){
			return true;
		} else {
			return false;
		}
	}
	function delete ($id){
		global $db;
		$detail_query = $db->prepare("DELETE FROM forecast_detail WHERE forecast_id = :id");
		$detail_query->bindParam(':id', $id);
		$detail_query->execute();
		$forecast_query = $db->prepare("DELETE FROM forecasts WHERE id = :id");
		$forecast_query->bindParam(':id', $id);
		$forecast_query->execute();
		return true;
	}
}
class warning{
	var $ID;
	var $title;
	var $description;
	var $validFrom;
	var $validTo;
	var $type;
	function __construct($warning_data) {
		global $db;
		if (!empty($warning_data)) {
			if (!is_array($warning_data)) {
				$warnings = $db->prepare("SELECT * FROM warnings WHERE id = :id LIMIT 1");
				$warnings->execute(array(':id' => $warning_data));
				$warning_data = $warnings->fetch(); 
			}
			$this->ID = stripslashes($warning_data['id']);
			$this->title = stripslashes($warning_data['title']);
			$this->description = stripslashes($warning_data['description']);
			$this->validFrom = stripslashes($warning_data['valid_from']);
			$this->validTo = stripslashes($warning_data['valid_to']);
			$this->type = stripslashes($warning_data['type']);
		}
	}
	
	function add ($title, $description, $validfrom, $validto, $type) {
		global $db;
		$query = $db->prepare("INSERT INTO warnings VALUES ('', :title, :description, :validfrom, :validto, :type)");
		$query->bindParam(':title', $title);
		$query->bindParam(':description', $description);
		$query->bindParam(':validfrom', $validfrom);
		$query->bindParam(':validto', $validto);
		$query->bindParam(':type', $type);
		$query->execute();
		if ($query) {
			$this->ID = $db->lastInsertId();
			$this->title = $title;
			$this->description = $description;
			$this->validfrom = $validfrom;
			$this->validto = $validto;
			$this->type = $type;
			return true;
		} else {
			return false;
		};
	}
	function update ($id, $title, $description, $validfrom, $validto, $type) {
		global $db;
		$query = $db->prepare("UPDATE warnings SET title = :title, description = :description, valid_from = :validfrom, valid_to = :validto, type = :type WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->bindParam(':title', $title);
		$query->bindParam(':description', $description);
		$query->bindParam(':validfrom', $validfrom);
		$query->bindParam(':validto', $validto);
		$query->bindParam(':type', $type);
		$query->execute();
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	function delete ($id) {
		global $db;
		$query = $db->prepare("DELETE FROM warnings WHERE id = :id");
		$query->bindParam(':id', $id);
		$query->execute();
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
}

?>