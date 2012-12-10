<?php 
include "conf.php";
include $BACK_PATH."/init.php";
$ext_path = "/typo3conf/ext/".$_EXTKEY;
?>
<script src="<?php echo $ext_path; ?>/js/jquery-1.8.3.min.js"></script>
<script src="<?php echo $ext_path; ?>/js/main.js"></script>
<link rel="stylesheet" href="<?php echo $ext_path; ?>/css/main.css" />
<link rel="stylesheet" href="<?php echo $ext_path; ?>/css/locations.css" />
<div id="message"><!-- --></div>
<div id="container">
	<h1>Forecast</h1>
	<div id="tabs">
		<ul>
			<li><a href="#" id="forecastTab" class="tab active">Forecast</a></li>
			<li><a href="#" id="locationTab" class="tab">Manage Locations</a></li>
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
				<div id="forecastNewForm"></div>
				<button class="forecastCancel">Cancel</button>
			</div>
			<div id="forecastUpdate" style="display:none;">
				<div id="forecastCurrent">
					<h3>Currently issued forecasts</h3>
					<button class="forecastCancel">Cancel</button>
				</div>
				<div id="forecastEdit" style="display:none;">
					<h3>Edit forecast</h3>
				<div id="forecastEditForm"></div>
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
						<td><input type="submit" value="Update" /><button id="cancelEdit">Cancel</button></td>
					</tr>
				</table>
				</form>
			</div>
		</div>
	</div>
</div>