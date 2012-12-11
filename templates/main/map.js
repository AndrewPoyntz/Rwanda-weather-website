$(document).ready(function () {
	var mapOptions = {
		theme: null, // Prevent /theme/style.css from being requested
		projection: new OpenLayers.Projection("EPSG:900913"),
		displayProjection: new OpenLayers.Projection("EPSG:4326"),
		//units: "m",
		//maxResolution: 156543.0339,
		//maxExtent: new OpenLayers.Bounds(-20037508, -20037508, 20037508, 20037508.34),
		 controls: [
			new OpenLayers.Control.Navigation({
				dragPanOptions: {
					enableKinetic: true
				}
			}),
			new OpenLayers.Control.Attribution(),
			new OpenLayers.Control.Zoom({
				zoomInId: "customZoomIn",
				zoomOutId: "customZoomOut"
			})
		]
	},
	constant = {
		DEFAULT_LNG: 29.873888,
		DEFAULT_LAT: -1.940278,
		// MIN_LAT: 6411819.9396445,
		// MIN_LON: -891534.32014409,
		// MAX_LAT: 8427311.5011069,
		// MAX_LON: 1065253.6036058,
		OVERALL_MAX_ZOOM: 9,
		DEFAULT_ZOOM: 8,
		MAP_TILES: new OpenLayers.Layer.OSM()		
	},
	control = new OpenLayers.Control()
	map = new OpenLayers.Map("map", mapOptions);
	map.addLayers([constant.MAP_TILES]);
	map.zoomToMaxExtent();
	map.setCenter(new OpenLayers.LonLat(constant.DEFAULT_LNG, constant.DEFAULT_LAT).transform(map.displayProjection, map.projection), constant.DEFAULT_ZOOM);
});    