<?php 
require('class_db.php');
$ext_path = "/typo3conf/ext/".$_EXTKEY;
$staticDataQ = $db->query("SELECT COUNT(*) FROM forecast_setup WHERE conf_item = 'installed'");
$staticData = $staticDataQ->fetchColumn();
if (!$staticData){
	$icons = $db->query("INSERT INTO `forecast_icons` (`id`, `name`, `image`) VALUES ('', 'Sunny', '1-sunny.png'), ('', 'Sunny Intervals', '3-sunny-intervals.png'), ('', 'Mist', '5-mist.png'), ('', 'Fog', '6-fog.png'), ('', 'Medium level cloud', '7-medium-level-cloud.png'), ('', 'Low level cloud', '8-low-level-cloud.png'), ('', 'Light rain shower', '10-light-rain-shower-day.png'), ('', 'Drizzle', '11-drizzle.png'), ('', 'Light Rain', '12-light-rain.png'), ('', 'Heavy rain shower', '14-heavy-rain-shower-day.png'), ('', 'Heavy rain', '15-heavy-rain.png'), ('', 'Sleet shower', '17-sleet-shower-day.png'), ('', 'Sleet', '18-sleet.png'), ('', 'Hail shower', '20-hail-shower-day.png'), ('', 'Hail', '21-hail.png'), ('', 'Thundery shower', '29-thundery-shower-day.png'), ('', 'Thunderstorm', '30-thunderstorm.png'), ('', 'Tropical storm', '31-tropical-storm.png'), ('', 'Haze', '33-haze.png')");
	$icons->execute();
	$forecast_periods = $db->query("INSERT INTO `forecast_periods` (`id`, `name`) VALUES ('', 'morning'), ('', 'afternoon')");
	$forecast_periods->execute();
	$forecast_locations = $db->query("INSERT INTO `forecast_locations` (`id`, `name`, `lat`, `lon`, `deleted`) VALUES ('', 'Kigali', '-1.95', '30.059', 0), ('', 'Rubavu', '-1.698', '29.371', 0), ('', 'Kiburara', '-1.614', '30.39', 0), ('', 'Kinyami', '-1.668', '30.117', 0), ('', 'Ruhango', '-2.23', '29.78', 0), ('', 'Kinigi', ' -1.453', '29.586', 0)");
	$forecast_locations->execute();
	$setup = $db->query("INSERT INTO `forecast_setup` VALUES ('installed', '1')");
	$setup->execute();
}
?>
<script src="<?php echo $ext_path; ?>/js/jquery-1.9.1.js"></script>
<script src="<?php echo $ext_path; ?>/js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="<?php echo $ext_path; ?>/js/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo $ext_path; ?>/js/main.js"></script>
<link rel="stylesheet" href="<?php echo $ext_path; ?>/css/jquery-ui-1.10.2.min.css" />
<link rel="stylesheet" href="<?php echo $ext_path; ?>/css/main.css" />
<link rel="stylesheet" href="<?php echo $ext_path; ?>/css/locations.css" />
<div id="screen"><!-- --></div>
<div id="message"><!-- --></div>
<div id="container">
	<h1>Forecast</h1>
	<div id="tabs">
		<ul>
			<li><a href="#" id="forecastTab" class="tab active">Forecast</a></li>
			<li><a href="#" id="locationTab" class="tab">Manage Locations</a></li>
			<li><a href="#" id="warningsTab" class="tab">Manage Warnings</a></li>
		</ul>
	</div>
	<div id="contentArea">
		<div id="forecast">
			<h2>Forecast Management</h2>
			<div id="forecastDefault">
				<p>What would you like to do?</p>
				<p><button id="forecastBtnNew">Issue a new forecast</button> <button id="forecastBtnUpdate">Update an existing forecast</button></p>
			</div>
			<div id="forecastNew" style="display:none;">
				<h3>Issue a new forecast</h3>
				<div id="forecastNewFormContainer"></div>
				<button class="forecastCancel">Cancel</button>
			</div>
			<div id="forecastUpdate" style="display:none;">
				<div id="forecastCurrent">
					<h3>Currently issued forecasts</h3>
					<div id="forecastsTable"><!-- --></div>
					<button class="forecastCancel">Cancel</button>
				</div>
				<div id="forecastEdit" style="display:none;">
					<h3>Edit forecast</h3>
				<div id="forecastEditFormContainer"></div>
					<button id="forecastCancelEdit">Cancel</button>
				</div>
			</div>
		</div>
		<div id="locationsManagement" style="display:none;">
			<h2>Manage Locations</h2>
			<div id="locMain">
				<h3>Add a new location</h3>
				<form action="" id="locFormAdd">
				<table>
					<tr>
						<td>Name</td>
						<td><input type="text" name="locName" /></td>
					</tr>
					<tr>
						<td>Latitude</td>
						<td><input type="text" name="locLat" /></td>
					</tr>
					<tr>
						<td>longitude</td>
						<td><input type="text" name="locLon" /></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Add" /></td>
					</tr>
				</table>
				</form>
				<h3>Current Locations</h3>
				<div id="locationsTable">loading...</div>
			</div>
			<div id="locEdit" style="display:none;">
				<h3>Edit Location</h3>
				<form action="" id="locFormEdit">
				<table>
					<tr>
						<td>Name</td>
						<td><input type="text" name="locName" /></td>
					</tr>
					<tr>
						<td>Latitude</td>
						<td><input type="text" name="locLat" /></td>
					</tr>
					<tr>
						<td>longitude</td>
						<td><input type="text" name="locLon" /></td>
					</tr>
					<tr>
						<td><input type="hidden" name="locId" /></td>
						<td><input type="submit" value="Update" /><button id="locCancelEdit">Cancel</button></td>
					</tr>
				</table>
				</form>
			</div>
		</div>
		<div id="warningsManagement" style="display:none;">
			<h2>Warnings Management</h2>
			<div id="warnMain">
				<h3>Add a new warning</h3>
				<form action="" id="warnFormAdd">
				<table>
					<tr>
						<td>Warning headline:</td>
						<td><input type="text" name="warnTitle" /></td>
					</tr>
					<tr>
						<td>Content</td>
						<td><textarea name="warnDescription"></textarea></td>
					</tr>
					<tr>
						<td>Valid from:</td>
						<td><input type="text" name="warnValidFrom" class="dateTimeInput" /></td>
					</tr>
					<tr>
						<td>Valid to:</td>
						<td><input type="text" name="warnValidTo" class="dateTimeInput" /></td>
					</tr>
					<tr>
						<td>Type:</td>
						<td>
							<select name="warnType">
								<option value="yellow">Yellow</option>								
								<option value="orange">Orange</option>								
								<option value="red">Red</option>								
							</select>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Add" /></td>
					</tr>
				</table>
				</form>
				<h3>Current Warnings</h3>
				<div id="warningsTable">loading...</div>
			</div>
			<div id="warnEdit" style="display:none;">
				<h3>Edit Warnings</h3>
				<form action="" id="warnFormEdit">
				<table>
					<tr>
						<td>Warning headline:</td>
						<td><input type="text" name="warnTitle" /></td>
					</tr>
					<tr>
						<td>Content</td>
						<td><textarea name="warnDescription"></textarea></td>
					</tr>
					<tr>
						<td>Valid from:</td>
						<td><input type="text" name="warnValidFrom" class="dateTimeInput"  /></td>
					</tr>
					<tr>
						<td>Valid to:</td>
						<td><input type="text" name="warnValidTo" class="dateTimeInput"  /></td>
					</tr>
					<tr>
						<td>Type:</td>
						<td>
							<select name="warnType">
								<option value="yellow">Yellow</option>								
								<option value="orange">Orange</option>								
								<option value="red">Red</option>								
							</select>
						</td>
					</tr>
					<tr>
						<td><input type="hidden" name="warnId" /></td>
						<td><input type="submit" value="Update" /><button id="warnCancelEdit">Cancel</button></td>
					</tr>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>