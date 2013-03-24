/*jshint forin:true, noarg:true, noempty:true, eqeqeq:true, bitwise:true, undef:true, unused:true, curly:true, browser:true, jquery:true, indent:4, maxerr:50 */

// global location of extension (useful for ajax requests)
var ext_loc = "/typo3conf/ext/forecastBackend/mod/",
	ext_root = "/typo3conf/ext/forecastBackend/";
//stuff to run on pageload
$(document).ready(function () {
	if (typeof firstRun !== 'undefined') { // need to install some static data
		$('#install').click(function() {
			$.ajax({
				url: ext_loc + 'insert_static.php',
				async: false,
				success: function (data) {
					$('#firstContent').html('The data has been installed successfully, please now refresh the page & re-open the xtension to start using it!');
					return false;
				}
			});
		});
	}
	f.defaultClickEvents(); // register the global click events
});
// global to contain all other functions (short name to save typing "#lazycoder")
var f = {
	defaultClickEvents: function () {
		f.forecast.init();
		$('#forecastTab').click(function () {
			$('.tab').removeClass('active'); // if we add another tab, it'll mae sense to move this line & the one below, outside 
			$(this).addClass('active'); //      as it's the same actions for both, (it's the same number of lines with only 2 tabs)
			$('#forecast').show();
			$('#locationsManagement, #warningsManagement').hide();
			f.forecast.init();
		});
		$('#locationTab').click(function () {
			$('.tab').removeClass('active');
			$(this).addClass('active');
			$('#locationsManagement').show();
			$('#forecast, #warningsManagement').hide();
			f.warnings.hideEdit();
			f.locations.init();
		});
		$('#warningsTab').click(function () {
			$('.tab').removeClass('active');
			$(this).addClass('active');
			$('#warningsManagement').show();
			$('#forecast, #locationsManagement').hide();
			f.locations.hideEdit();
			f.warnings.init();
		});
	},
	makeElement: function (element, content, attr, attrValue) {
		// creates an element which can then be attached to the dom
		// this the the proper way of doing it! rather than "output += '<td>something</td>'" etc
		var el = document.createElement(element);
		// JsLint moans about typeof, but it's correct, jsLint is wrong, typeof is the right way until 'undefined' becomes a special word in all browsers (read:when IE8 dies..)
		if (typeof (content) !== 'undefined' && content !== '') {
			el.innerHTML = content;
		}
		if (typeof (attr) !== 'undefined' && typeof (attrValue) !== 'undefined') {
			el.setAttribute(attr, attrValue);
		}
		return el;
	},
	showScreen: function () {
		$('#screen').stop().show().fadeTo("fast", 0.6, function() {
			$('#message').stop().html('Working...').removeClass('pass').removeClass('fail').addClass('working').show();
		});
	},
	showMessage: function (type, message) {
		$('#screen').clearQueue().stop().show().css('opacity',0.6);
		$('#message').clearQueue().stop().removeClass('working').addClass(type).html(message).fadeIn('slow').delay(1000).fadeToggle('slow', function(){
			$('#screen').fadeTo("fast", 0).hide();
		});
	},
	forecast: { //container for all functions relating to the forecast tab - f.forecast.[function]()
		init: function () {
			this.registerEvents();
		},
		registerEvents: function () {
			$('#forecastBtnNew').unbind().click(function () {
				$('#forecastDefault').fadeOut(500);
				$('#forecastNew').delay(500).fadeIn('slow');
				f.forecast.getFormInfo();
				f.forecast.buildForm.init('forecastNewFormContainer');
				return false;
			});
			$('#forecastBtnUpdate').unbind().click(function () {
				$('#forecastDefault').fadeOut(500);
				$('#forecastUpdate').delay(500).fadeIn('slow');
				f.forecast.getForecastList();
				f.forecast.registerEvents();
				return false;
			});
			$('#forecastCancelEdit').unbind().click( function () {
				f.forecast.hideEdit();
				return false;
			});
			$('#forecastsTable .edit').unbind().click( function () {
				var id = $(this).parent().attr('data-id');
				f.forecast.getFormInfo(true, id);
				f.forecast.buildForm.init('forecastEditFormContainer');
				f.forecast.showEdit();
				return false;
			});
			$('.forecastCancel').click(function () {
				f.forecast.showInitalScreen();
				return false;
			});
		},
		showInitalScreen: function () {
			$('#forecastNew, #forecastUpdate').fadeOut(500);
			$('#forecastDefault').delay(500).fadeIn('slow');
		},
		showEdit: function () {
			$('#forecastCurrent').fadeOut('fast');
			$('#forecastEdit').fadeIn('slow');
		},
		hideEdit: function () {
			$('#forecastEdit').fadeOut('fast');
			$('#forecastCurrent').fadeIn('slow');
		},
		getForecastList: function () {
			$.ajax({
				url: ext_loc + 'controller_forecast.php',
				data: 'action=list',
				async: false,
				success: function (data) {
					var table, tr, th, td, div, i, forecast;
					if (data.forecasts.length > 0) {
						table = f.makeElement('table');
						tr = f.makeElement('tr');
						table.appendChild(tr);
						th = f.makeElement('th', 'Date');
						tr.appendChild(th);
						th = f.makeElement('th', 'Headline');
						//tr.appendChild(th);
						//th = f.makeElement('th', 'Severe Weather');
						tr.appendChild(th);
						th = f.makeElement('th', 'Edit');
						tr.appendChild(th);
						for (i = 0; i < data.forecasts.length; i++) {
							forecast = data.forecasts[i];
							if (i % 2 === 0) {
								tr = f.makeElement('tr', '', 'class', 'row');
							} else {
								tr = f.makeElement('tr');
							}
							table.appendChild(tr);
							td = f.makeElement('td', forecast.date);
							tr.appendChild(td);
							td = f.makeElement('td', forecast.headline);
							tr.appendChild(td);
							//td = f.makeElement('td', forecast.severeWeather);
							//tr.appendChild(td);
							td = f.makeElement('td', '', 'data-id', forecast.id);
							tr.appendChild(td);
							div = f.makeElement('div', '', 'class', 'icon edit');
							td.appendChild(div);
						}
						$('#forecastsTable').html('').append(table);
					} else {
						$('#forecastsTable').html('There are no forecasts currently, please issue one!');
					}
				}
			});
			return false;
		},
		formInfo: [],
		getFormInfo: function (edit, id) {
			var data = 'action=form';
			data += edit ? '&id=' + id : '';
			$.ajax({
				url: ext_loc + 'controller_forecast.php',
				data: data,
				async: false,
				success: function (data) {
					f.forecast.formInfo = data;
					return false;
				}
			});
			return false;
		},
		buildForm: {
			init: function (formDiv) {
				var form, input, table, tr, td, th, data = f.forecast.formInfo.info;
				$('#' + formDiv).html('');
				form = f.makeElement('form', '', 'id', formDiv.substring(0, formDiv.length - 9));
				table = f.makeElement('table');
				//--------------------------------------
				tr = f.makeElement('tr');
				table.appendChild(tr);
				td = f.makeElement('td', 'Date :');
				tr.appendChild(td);
				// --------------------
				td = f.makeElement('td');
				input = f.makeElement('input', '', 'name', 'issue_time');
				input.setAttribute('type', 'text');
				input.setAttribute('class', 'dateInput');
				if (data.issueTime !== null){
					input.setAttribute('value', data.issueTime);
				}
				td.appendChild(input);
				if (data.id !== null){
					input = f.makeElement('input', '', 'name', 'id');
					input.setAttribute('type', 'hidden');
					input.setAttribute('value', data.id);
					td.appendChild(input);
				}
				tr.appendChild(td);
				//-------------------------------------
				tr = f.makeElement('tr');
				table.appendChild(tr);
				td = f.makeElement('td', 'Headline :');
				tr.appendChild(td);
				// --------------------
				td = f.makeElement('td');
				input = f.makeElement('input', '', 'name', 'headline');
				input.setAttribute('type', 'text');
				if (data.headline !== null){
					input.setAttribute('value', data.headline);
				}
				td.appendChild(input);
				tr.appendChild(td);
				//-------------------------------------
				tr = f.makeElement('tr');
				table.appendChild(tr);
				td = f.makeElement('td', '&nbsp;');
				tr.appendChild(td);
				// --------------------
				td = f.makeElement('td');
				input = f.makeElement('input', '', 'name', 'severe_weather');
				input.setAttribute('type', 'hidden');
				if (data.severeWeather !== null){
					input.setAttribute('value', data.severeWeather);
				}
				td.appendChild(input);
				tr.appendChild(td);
				form.appendChild(table);

				table = f.makeElement('table');
				tr = f.makeElement('tr');
				table.appendChild(tr);
				th = f.makeElement('th', 'Location');
				tr.appendChild(th);
				th = f.makeElement('th', 'Periods');
				tr.appendChild(th);
				th = f.makeElement('th', 'Icon', 'colspan', '2');
				th.setAttribute('width', '15%');
				tr.appendChild(th);
				th = f.makeElement('th', 'Text');
				tr.appendChild(th);
				th = f.makeElement('th', 'Max Temp');
				tr.appendChild(th);
				th = f.makeElement('th', 'Min Temp');
				tr.appendChild(th);
				th = f.makeElement('th', 'Wind Speed');
				tr.appendChild(th);
				th = f.makeElement('th', 'Wind Direction');
				tr.appendChild(th);
				f.forecast.buildForm.rows(table);
				form.appendChild(table);

				input = f.makeElement('input', '', 'type', 'submit');
				input.setAttribute('value', 'Issue forecast');
				form.appendChild(input);
				$('#' + formDiv).append(form);
				f.forecast.buildForm.registerEvents(formDiv);
				return true;
			},
			rows: function (table) {
				var i, j, location, period, tr, td, data = f.forecast.formInfo;
				for (i = 0; i < data.locations.length; i++) {
					location = data.locations[i];
					if (i % 2 === 0) {
						tr = f.makeElement('tr');
					} else {
						tr = f.makeElement('tr', '', 'class', 'row');
					}
					table.appendChild(tr);
					td = f.makeElement('td', location.name);
					td.setAttribute('rowspan', location.periods.length);
					tr.appendChild(td);
					for (j = 0; j < location.periods.length; j++) {
						period = location.periods[j];
						td = f.makeElement('td', period.name);
						tr.appendChild(td);
						f.forecast.buildForm.columns(tr, period.id, location.id, period.detail[0]);
						if (i % 2 === 0) {
							tr = f.makeElement('tr');
						} else {
							tr = f.makeElement('tr', '', 'class', 'row');
						}
						table.appendChild(tr);
					}
				}
			},
			columns: function (tr, period, location, detail) {
				var td, textarea, input, div;
				detail = (typeof detail !== 'undefined') ? detail : "";
				// ---------------------------------------
				td = f.makeElement('td');
				tr.appendChild(td);
				div = f.makeElement('div', '', 'id', period + '_' + location + 'preview');
				td.appendChild(div);
				// ---------------------------------------
				td = f.makeElement('td');
				f.forecast.buildForm.icons(td, div, period, location, detail);
				tr.appendChild(td);
				// ---------------------------------------
				td = f.makeElement('td');
				tr.appendChild(td);
				textarea = f.makeElement('textarea', '', 'name', period + '_' + location + 'text');
				if (typeof (detail.text) !== 'undefined') {
					textarea.innerHTML = detail.text;
				}
				td.appendChild(textarea);
				// ---------------------------------------
				td = f.makeElement('td');
				tr.appendChild(td);
				input = f.makeElement('input', '', 'name', period + '_' + location + 'maxTemp');
				input.setAttribute('type', 'text');
				input.setAttribute('size', '3');
				if (typeof (detail.maxTemp) !== 'undefined') {
					input.setAttribute('value', detail.maxTemp);
				}
				td.appendChild(input);
				// ---------------------------------------
				td = f.makeElement('td');
				tr.appendChild(td);
				input = f.makeElement('input', '', 'name', period + '_' + location + 'minTemp');
				input.setAttribute('type', 'text');
				input.setAttribute('size', '3');
				if (typeof (detail.minTemp) !== 'undefined') {
					input.setAttribute('value', detail.minTemp);
				}
				td.appendChild(input);
				// ---------------------------------------
				td = f.makeElement('td');
				tr.appendChild(td);
				input = f.makeElement('input', '', 'name', period + '_' + location + 'windSpeed');
				input.setAttribute('type', 'text');
				input.setAttribute('size', '3');
				if (typeof (detail.windSpeed) !== 'undefined') {
					input.setAttribute('value', detail.windSpeed);
				}
				td.appendChild(input);
				// ---------------------------------------
				td = f.makeElement('td');
				tr.appendChild(td);
				input = f.makeElement('input', '', 'name', period + '_' + location + 'windDir');
				input.setAttribute('type', 'text');
				input.setAttribute('size', '3');
				if (typeof (detail.windDirection) !== 'undefined') {
					input.setAttribute('value', detail.windDirection);
				}
				td.appendChild(input);
			},
			icons: function (td, div, period, location, detail) {
				var i, select, option, icon, image, data = f.forecast.formInfo;
				select = f.makeElement('select', '', 'name', period + '_' + location + 'icon');
				select.setAttribute('data-previewDiv', period + '_' + location + 'preview');
				select.setAttribute('class', 'iconSelect');
				td.appendChild(select);
				for (i = 0; i < data.icons.length; i++) {
					icon = data.icons[i];
					option = f.makeElement('option', icon.name, 'value', icon.id);
					if (detail.iconId === icon.id){
						option.setAttribute('selected', 'selected');
					}
					select.appendChild(option);
				}
				if (typeof (detail.icon) !== 'undefined') {
					image = f.makeElement('img', '', 'src', ext_root + 'img/' + detail.icon);
				} else {
					image = f.makeElement('img', '', 'src', ext_root + 'img/' + data.icons[0].image);
				}
				div.appendChild(image);
			},
			registerEvents: function (formDiv) {
				var i, icon, iconId, image, previewDiv, data = f.forecast.formInfo;
				$('#' + formDiv + ' .iconSelect').unbind().change(function () {
					iconId = $(this).val();
					previewDiv = $(this).attr('data-previewdiv');
					for (i = 0; i < data.icons.length; i++) {
						icon = data.icons[i];
						if (iconId === icon.id) {
							image = f.makeElement('img', '', 'src', ext_root + 'img/' + icon.image);
							$('#' + previewDiv).html('');
							$('#' + previewDiv).append(image);
						}
					}
				});
				$('#' + formDiv + ' .dateInput').datepicker({dateFormat:'yy-mm-dd'});
				$('#' + formDiv.substring(0, formDiv.length - 9)).unbind().submit(function () {
					f.forecast.issue($(this));
					return false;
				});
			}
		},
		issue: function (form) {
			f.showScreen();
			$.ajax({
				type: 'POST',
				url: ext_loc + 'controller_forecast.php?action=issue',
				data: form.serialize(),
				success: function (data) {
					if (data.result) {
						f.showMessage('pass', 'Forecast Issued!');
						f.forecast.showInitalScreen();
					} else {
						f.showMessage('fail', 'Error issuing forecast');
					}
				}
			});
		}
	},
	// ##############-------[Locations]-----------##############
	locations: { // container for all functions relating to the locations tab - f.locations.[function]()
		init: function () {
			this.registerEvents();
			this.getLocationList();
		},
		showEdit: function (id) {
			var i, location;
			$('#locMain').fadeOut('fast');
			$('#locEdit').fadeIn('slow');
			for (i = 0; i < f.locations.list.length; i++) {
				location = f.locations.list[i];
				if (location.id === id) {
					$('#locFormEdit input[name=locId]').val(location.id);
					$('#locFormEdit input[name=locName]').val(location.name);
					$('#locFormEdit input[name=locLat]').val(location.lat);
					$('#locFormEdit input[name=locLon]').val(location.lon);
				}
			}
		},
		hideEdit: function () {
			$('#locEdit').fadeOut('fast');
			$('#locMain').fadeIn('slow');
			$('#locFormEdit input[type=text], #locFormEdit input[type=hidden]').val('');
		},
		list: [],
		getLocationList: function () {
			var i, location, table, tr, th, td, div;
			$.ajax({
				url: ext_loc + 'controller_location.php?action=list',
				success: function (data) {
					if (data.locations.length > 0) {
						table = f.makeElement('table');
						tr = f.makeElement('tr');
						table.appendChild(tr);
						th = f.makeElement('th', 'Name');
						tr.appendChild(th);
						th = f.makeElement('th', 'Latitude');
						tr.appendChild(th);
						th = f.makeElement('th', 'longitude');
						tr.appendChild(th);
						th = f.makeElement('th', 'Edit');
						tr.appendChild(th);
						th = f.makeElement('th', 'Delete');
						tr.appendChild(th);
						for (i = 0; i < data.locations.length; i++) {
							location = data.locations[i];
							if (i % 2 === 0) {
								tr = f.makeElement('tr', '', 'class', 'row');
							} else {
								tr = f.makeElement('tr');
							}
							table.appendChild(tr);
							td = f.makeElement('td', location.name);
							tr.appendChild(td);
							td = f.makeElement('td', location.lat);
							tr.appendChild(td);
							td = f.makeElement('td', location.lon);
							tr.appendChild(td);
							td = f.makeElement('td', '', 'data-id', location.id);
							tr.appendChild(td);
							div = f.makeElement('div', '', 'class', 'icon edit');
							td.appendChild(div);
							td = f.makeElement('td', '', 'data-id', location.id);
							tr.appendChild(td);
							div = f.makeElement('div', '', 'class', 'icon delete');
							td.appendChild(div);
						}
						$('#locationsTable').html('').append(table);
					} else {
						$('#locationsTable').html('There are no locations currently, please add some!');
					}
					f.locations.list = data.locations;
					f.locations.registerEvents();
				}
			});
		},
		registerEvents: function () {
			$('#locFormAdd').unbind().submit(function () {
				f.locations.processAdd();
				return false; // return false to stop the page reloading
			});
			$('#locFormEdit').unbind().submit(function () {
				f.locations.processEdit();
				return false;
			});
			$('#locationsTable .edit').unbind().click(function () {
				var id = $(this).parent().attr('data-id');
				f.locations.showEdit(id);
				return false;
			});
			$('#locationsTable .delete').unbind().click(function () {
				var id = $(this).parent().attr('data-id'),
					name = $(this).parent().prev().prev().prev().prev().html(); //this feels messy...
				if (confirm('Are you sure you want to delete \'' + name + '\'?')) {
					f.locations.processDelete(id);
					return false;
				}
				return false;
			});
			$('#locCancelEdit').unbind().click(function () {
				f.locations.hideEdit();
				return false;
			});
		},
		processAdd: function () {
			var form = $('#locFormAdd'), valid = true;
			f.showScreen();
			valid = ($('#locFormAdd input[name=locName]').val() !== "") ? valid : false;
			valid = ($('#locFormAdd input[name=locLat]').val() !== "") ? valid : false;
			valid = ($('#locFormAdd input[name=locLon]').val() !== "") ? valid : false;
			if (valid){
				$.ajax({
					url: ext_loc + 'controller_location.php',
					data: form.serialize() + '&action=add',
					success: function (data) {
						if (data.result) {
							f.showMessage('pass', 'Location added!');
							f.locations.getLocationList();
						} else {
							f.showMessage('fail', 'Error adding location');
						}
						$('#locFormAdd input[type=text]').val('');
					}
				});
			} else {
				f.showMessage('fail', 'Please fill out all fields on the form.');
			}
		},
		processEdit: function () {
			var form = $('#locFormEdit'), valid = true;
			f.showScreen();
			valid = ($('#locFormEdit input[name=locName]').val() !== "") ? valid : false;
			valid = ($('#locFormEdit input[name=locLat]').val() !== "") ? valid : false;
			valid = ($('#locFormEdit input[name=locLon]').val() !== "") ? valid : false;
			if (valid){
				$.ajax({
					url: ext_loc + 'controller_location.php',
					data: form.serialize() + '&action=update',
					success: function (data) {
						if (data.result) {
							f.showMessage('pass', 'Location updated!');
							f.locations.getLocationList();
							f.locations.hideEdit();
							$('#locFormEdit input[type=text]').val('');
						} else {
							f.showMessage('fail', 'Error updating location');
						}
					}
				});
			} else {
				f.showMessage('fail', 'Please fill out all fields on the form.');
			}
		},
		processDelete: function (id) {
			f.showScreen();
			$.ajax({
				url: ext_loc + 'controller_location.php',
				data: 'locId=' + id + '&action=delete',
				success: function (data) {
					if (data.result) {
						f.showMessage('pass', 'Location deleted!');
						f.locations.getLocationList();
					} else {
						f.showMessage('fail', 'Error deleting location');
					}
				}
			});
		}
	},
	// ##############-------[Warnings]-----------##############
	warnings: { // container for all functions relating to the warnings tab - f.warnings.[function]()
		init: function () {
			this.registerEvents();
			this.getWarningList();
		},
		showEdit: function (id) {
			var i, warning;
			$('#warnMain').fadeOut('fast');
			$('#warnEdit').fadeIn('slow');
			for (i = 0; i < f.warnings.list.length; i++) {
				warning = f.warnings.list[i];
				if (warning.id === id) {
					$('#warnFormEdit input[name=warnId]').val(warning.id);
					$('#warnFormEdit input[name=warnTitle]').val(warning.title);
					$('#warnFormEdit textarea').html(warning.description);
					$('#warnFormEdit input[name=warnValidFrom]').val(warning.validFrom);
					$('#warnFormEdit input[name=warnValidTo]').val(warning.validTo);
					$('#warnFormEdit select').children().each(function () {
						if ($(this).val() === warning.type){
							$(this).attr('selected', 'selected');
						}
					});
				}
			}
		},
		hideEdit: function () {
			$('#warnEdit').fadeOut('fast');
			$('#warnMain').fadeIn('slow');
			$('#warnFormEdit input[type=text], #warnFormEdit input[type=hidden]').val('');
			$('#warnFormEdit textarea').html('');
		},
		list: [],
		getWarningList: function () {
			var i, warning, table, tr, th, td, div;
			$.ajax({
				url: ext_loc + 'controller_warning.php?action=list',
				success: function (data) {
					if (data.warnings.length > 0) {
						table = f.makeElement('table');
						tr = f.makeElement('tr');
						table.appendChild(tr);
						th = f.makeElement('th', 'Headline');
						tr.appendChild(th);
						th = f.makeElement('th', 'Content');
						tr.appendChild(th);
						th = f.makeElement('th', 'Valid From');
						tr.appendChild(th);
						th = f.makeElement('th', 'Valid To');
						tr.appendChild(th);
						th = f.makeElement('th', 'Type');
						tr.appendChild(th);
						th = f.makeElement('th', 'Edit');
						tr.appendChild(th);
						th = f.makeElement('th', 'Delete');
						tr.appendChild(th);
						for (i = 0; i < data.warnings.length; i++) {
							warning = data.warnings[i];
							if (i % 2 === 0) {
								tr = f.makeElement('tr', '', 'class', 'row');
							} else {
								tr = f.makeElement('tr');
							}
							table.appendChild(tr);
							td = f.makeElement('td', warning.title);
							tr.appendChild(td);
							td = f.makeElement('td', warning.description);
							tr.appendChild(td);
							td = f.makeElement('td', warning.validFrom);
							tr.appendChild(td);
							td = f.makeElement('td', warning.validTo);
							tr.appendChild(td);
							td = f.makeElement('td', warning.type);
							tr.appendChild(td);
							td = f.makeElement('td', '', 'data-id', warning.id);
							tr.appendChild(td);
							div = f.makeElement('div', '', 'class', 'icon edit');
							td.appendChild(div);
							td = f.makeElement('td', '', 'data-id', warning.id);
							tr.appendChild(td);
							div = f.makeElement('div', '', 'class', 'icon delete');
							td.appendChild(div);
						}
						$('#warningsTable').html('').append(table);
					} else {
						$('#warningsTable').html('There are no warnings currently.');
					}
					f.warnings.list = data.warnings;
					f.warnings.registerEvents();
				}
			});
		},
		registerEvents: function () {
			$('#warnFormAdd').unbind().submit(function () {
				f.warnings.processAdd();
				return false; // return false to stop the page reloading
			});
			$('#warnFormEdit').unbind().submit(function () {
				f.warnings.processEdit();
				return false;
			});
			$('#warnFormAdd .dateTimeInput, #warnFormEdit .dateTimeInput').datetimepicker({
				timeFormat: 'HH:mm:ss',
				dateFormat: 'yy-mm-dd'
			});
			$('#warningsTable .edit').unbind().click(function () {
				var id = $(this).parent().attr('data-id');
				f.warnings.showEdit(id);
				return false;
			});
			$('#warningsTable .delete').unbind().click(function () {
				var id = $(this).parent().attr('data-id'),
					name = $(this).parent().prev().prev().prev().prev().prev().prev().html(); //this feels messy...
				if (confirm('Are you sure you want to delete the warning: \'' + name + '\'?')) {
					f.warnings.processDelete(id);
					return false;
				}
				return false;
			});
			$('#warnCancelEdit').unbind().click(function () {
				f.warnings.hideEdit();
				return false;
			});
		},
		processAdd: function () {
			var form = $('#warnFormAdd'), valid = true;
			f.showScreen();
			valid = ($('#warnFormAdd input[name=warnTitle]').val() !== "") ? valid : false;
			valid = ($('#warnFormAdd input[name=warnValidFrom]').val() !== "") ? valid : false;
			valid = ($('#warnFormAdd input[name=warnValidTo]').val() !== "") ? valid : false;
			valid = ($('#warnFormAdd textarea').html() !== "") ? valid : false;
			if (valid){
				$.ajax({
					url: ext_loc + 'controller_warning.php',
					data: form.serialize() + '&action=add',
					success: function (data) {
						if (data.result) {
							f.showMessage('pass', 'Warning added!');
							f.warnings.getWarningList();
						} else {
							f.showMessage('fail', 'Error adding warning');
						}
						$('#warnFormAdd input[type=text]').val('');
						$('#warnFormAdd textarea').html('');
					}
				});
			} else {
				f.showMessage('fail', 'Please fill out all fields on the form.');
			}
		},
		processEdit: function () {
			var form = $('#warnFormEdit'), valid = true;
			f.showScreen();
			valid = ($('#warnFormEdit input[name=warnTitle]').val() !== "") ? valid : false;
			valid = ($('#warnFormEdit input[name=warnValidFrom]').val() !== "") ? valid : false;
			valid = ($('#warnFormEdit input[name=warnValidTo]').val() !== "") ? valid : false;
			valid = ($('#warnFormEdit textarea').html() !== "") ? valid : false;
			if (valid){
				$.ajax({
					url: ext_loc + 'controller_warning.php',
					data: form.serialize() + '&action=update',
					success: function (data) {
						if (data.result) {
							f.showMessage('pass', 'Warning updated!');
							f.warnings.getWarningList();
							f.warnings.hideEdit();
							$('#warnFormEdit input[type=text]').val('');
							$('#warnFormEdit textarea').html('');
						} else {
							f.showMessage('fail', 'Error updating warning');
						}
					}
				});
			} else {
				f.showMessage('fail', 'Please fill out all fields on the form.');
			}
		},
		processDelete: function (id) {
			f.showScreen();
			$.ajax({
				url: ext_loc + 'controller_warning.php',
				data: 'warnId=' + id + '&action=delete',
				success: function (data) {
					if (data.result) {
						f.showMessage('pass', 'Warning deleted!');
						f.warnings.getWarningList();
					} else {
						f.showMessage('fail', 'Error deleting warning');
					}
				}
			});
		}
	}
};