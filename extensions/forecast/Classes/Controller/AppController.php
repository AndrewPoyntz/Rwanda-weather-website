<?php
 
class Tx_Forecast_Controller_AppController
extends Tx_Extbase_MVC_Controller_ActionController {
	public function initializeAction() {
	$headers = "<script src=\"//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js\"></script>
<script src=\"/fileadmin/templates/main/OpenLayers.js\"></script> 
<script src=\"/fileadmin/templates/main/map.js\"></script>";
		$GLOBALS['TSFE']->additionalHeaderData['custom headers'] = $headers;
	}
	/**
	* @return string The rendered view
	*/
	private function getMapData($today){
		//$today = "2012-12-10";
		$current_forecast_Q = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, "SELECT max(id) as id, headline, severe_weather, issue_time from forecasts where issue_time = '".$today."'");
		while ($current_forecast = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($current_forecast_Q)){
			$forecast_id = $current_forecast['id'];
			$headline = $current_forecast['headline'];
			$severe_weather = $current_forecast['severe_weather'];
			$issue_time = $current_forecast['issue_time'];
		}
		$locations_array = array();
		$locations = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, "SELECT distinct name, forecast_locations.id, lat, lon FROM forecast_locations, forecast_detail WHERE forecast_id = " . $forecast_id . " AND forecast_detail.location_id = forecast_locations.id");
		while ($location = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($locations)){
			$location_id = $location['id'];
			$location_name = $location['name'];
			$location_lat = $location['lat'];
			$location_lon = $location['lon'];
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
						//'icon_id' => $forecast_detail['icon_id'],
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
					'id' => $period_id,
					'name' => $period_name,
					'detail' => $detail_array
				);
			}
			$locations_array[] = array(
				//'id' => $location_id,
				'name' => $location_name,
				'lat' => $location_lat,
				'lon' => $location_lon,		
				'periods' => $period_array
			);
		};
		$data = array(
			//'forecast_id' => $forecast_id,
			'date' => date('Y-m-d', strtotime($issue_time)),
			'headline' => $headline,
			'severeWeather' => $severe_weather,
			'locations' => $locations_array	
		);
		return json_encode($data);
	}
	private function getCurrentForecast($date){
		$current_forecast_Q = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'forecasts', "issue_time='".$date."'" , '', 'published');
		return $current_forecast_Q;
	}
	private function getCurrentWarnings($date){
		$warnings_array = array();
		$current_warnings_Q = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'warnings', "valid_from < '".$date."' AND valid_to > '".$date."'", '', 'type');
		$num_warnings = $GLOBALS['TYPO3_DB']->sql_num_rows($current_warnings_Q);
		if ($num_warnings > 0){
			$this->view->assign('warningExists', true);
		} else {
			$this->view->assign('warningExists', false);
		}
		while ($current_warning = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($current_warnings_Q)){
			$warningTime = date('jS M H:i', strtotime($current_warning['valid_from'])) . " - " . date('jS M H:i', strtotime($current_warning['valid_to']));
			$warnings_array[] = array(
				'title' => stripslashes($current_warning['title']),
				'content' => stripslashes($current_warning['description']),
				'time' => $warningTime,
				'type' => $current_warning['type']
			);
		}
		return $warnings_array;
	}
	private function getLocations($forecast_id){		
		
		//echo "forecast id: ". $forecast_id ."<br>";
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
	public function indexAction() {
//		$today = "2012-12-10";
		$today = date('Y-m-d');
		$todayTime = date('Y-m-d H:i:s');
		$current_forecast_Q = $this->getCurrentForecast($today);
		while ($current_forecast = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($current_forecast_Q)){
			$forecast_id = $current_forecast['id'];
			$headline = $current_forecast['headline'];
			$severe_weather = $current_forecast['severe_weather'];
		}
		
		$rows = $this->getLocations($forecast_id);
		$this->view->assign('forecastIssued', (count($rows) > 0 ? true : false));
		$this->view->assign('locations', $rows);
		$this->view->assign('date', date('D jS F Y', strtotime($today)));
		$this->view->assign('headline', $headline);
		$this->view->assign('severe_weather', $severe_weather);
		
		$current_warnings = $this->getCurrentWarnings($todayTime);
		$this->view->assign('warnings', $current_warnings);
		
		$this->view->assign('mapData', $this->getMapData($today));
	}
}