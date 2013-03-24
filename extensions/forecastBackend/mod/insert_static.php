<?php
require('class_db.php');
$icons = $db->query("INSERT INTO `forecast_icons` (`id`, `name`, `image`) VALUES ('', 'Sunny', '1-sunny.png'), ('', 'Sunny Intervals', '3-sunny-intervals.png'), ('', 'Mist', '5-mist.png'), ('', 'Fog', '6-fog.png'), ('', 'Medium level cloud', '7-medium-level-cloud.png'), ('', 'Low level cloud', '8-low-level-cloud.png'), ('', 'Light rain shower', '10-light-rain-shower-day.png'), ('', 'Drizzle', '11-drizzle.png'), ('', 'Light Rain', '12-light-rain.png'), ('', 'Heavy rain shower', '14-heavy-rain-shower-day.png'), ('', 'Heavy rain', '15-heavy-rain.png'), ('', 'Sleet shower', '17-sleet-shower-day.png'), ('', 'Sleet', '18-sleet.png'), ('', 'Hail shower', '20-hail-shower-day.png'), ('', 'Hail', '21-hail.png'), ('', 'Thundery shower', '29-thundery-shower-day.png'), ('', 'Thunderstorm', '30-thunderstorm.png'), ('', 'Tropical storm', '31-tropical-storm.png'), ('', 'Haze', '33-haze.png')");
//$icons->execute();
$forecast_periods = $db->query("INSERT INTO `forecast_periods` VALUES ('', 'morning'), ('', 'afternoon')");
//$forecast_periods->execute();
$forecast_locations = $db->query("INSERT INTO `forecast_locations` (`id`, `name`, `lat`, `lon`, `deleted`) VALUES ('', 'Kigali', '-1.95', '30.059', 0), ('', 'Rubavu', '-1.698', '29.371', 0), ('', 'Kiburara', '-1.614', '30.39', 0), ('', 'Kinyami', '-1.668', '30.117', 0), ('', 'Ruhango', '-2.23', '29.78', 0), ('', 'Kinigi', ' -1.453', '29.586', 0)");
//$forecast_locations->execute();
$setup = $db->query("INSERT INTO `forecast_setup` VALUES ('installed', '1')");
//$setup->execute();
?>