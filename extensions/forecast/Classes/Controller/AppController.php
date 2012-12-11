<?php
 
class Tx_Forecast_Controller_AppController
extends Tx_Extbase_MVC_Controller_ActionController {
	public function initializeAction() {

	}
	/**
	* @return string The rendered view
	*/
	private function getCurrentForecast($date){
		$current_forecast_Q = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'forecasts', "issue_time='".$date."'" , '', 'published');
		return $current_forecast_Q;
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
		//$today = date('Y-m-d');
		$today = "2012-12-10";
		$current_forecast_Q = $this->getCurrentForecast($today);
		while ($current_forecast = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($current_forecast_Q)){
			$forecast_id = $current_forecast['id'];
			$headline = $current_forecast['headline'];
			$severe_weather = $current_forecast['severe_weather'];
		}
		$rows = $this->getLocations($forecast_id);
		$this->view->assign('locations', $rows);
		$this->view->assign('date', date('D jS F Y', strtotime($today)));
		$this->view->assign('headline', $headline);
		$this->view->assign('severe_weather', $severe_weather);
	}
}