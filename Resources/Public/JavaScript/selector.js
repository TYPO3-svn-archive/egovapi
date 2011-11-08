// Set up name space
if (typeof TX_EGOVAPI == 'undefined') TX_EGOVAPI = {};

TX_EGOVAPI.selector = {
	eID: "egovapi_pi2",
	ajaxUrl: '',
	showGoogleMap: '',
	defaultLanguage: '',
	coordinates: {},
	initialLocation: '',
	organizationBern: '',
	browserSupportFlag: new Boolean(),

	/**
	 * Initialize this class.
	 */
	init: function() {
		this.ajaxUrl = $("input#tx_egovapi_ajaxUrl").val();
		this.showGoogleMap = $("input#tx_egovapi_showGoogleMap").val();
		this.defaultLanguage = $("input#tx_egovapi_defaultLanguage").val();
		var self = this;

		this.reset();

		// Register handler for updating the list of organizations
		$("select#tx_egovapi_community").change(function() {
			self.updateOrganizations($(this).val());
		});

		// Register handler for updating the list of services
		$("select#tx_egovapi_organization").change(function() {
			self.updateServices($(this).val());
		});

		// Register handler for updating the list of service versions
		$("select#tx_egovapi_service").change(function() {
			var info = $(this).val().split("-");
			self.updateVersions(info[0], info[1]);
		});

		// Register handler for updating form fields when language is changed
		$("select#tx_egovapi_language").change(function() {
			$("select#tx_egovapi_organization").trigger("change");
			$("select#tx_egovapi_version").html("");
		});

		// Register handler for updating the service links
		$("input#tx_egovapi_selectorForm_submit").click(function() {
			self.generateLinks();
		});

		// Register handler for toggling select blocks
		var toggleState = "ALL";
		$.each($(".toggleBlocks a"), function() {
			$(this).click(function() {
				var newState = toggleState == "ALL" ? "NONE" : "ALL";
				$.each($("input[name='tx_egovapi_selectorForm_block[]']"), function() {
					$(this).attr("checked", newState == "ALL");
				});
				toggleState = newState;
				return false;
			});
		});

		if (this.showGoogleMap) {
			this.organizationBern = new google.maps.LatLng(46.94792, 7.44461);

			// Try W3C Geolocation (Preferred)
			if (navigator.geolocation) {
				self.browserSupportFlag = true;
				navigator.geolocation.getCurrentPosition(function(position) {
					self.initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
					self.findNearestOrganization(self.initialLocation);
				}, function() {
					self.handleNoGeolocation(self.browserSupportFlag);
				},
				{
					timeout: 10000
				});
				// Try Google Gears Geolocation
			} else if (google.gears) {
				self.browserSupportFlag = true;
				var geo = google.gears.factory.create("beta.geolocation");
				geo.getCurrentPosition(function(position) {
					self.initialLocation = new google.maps.LatLng(position.latitude, position.longitude);
					self.findNearestOrganization(self.initialLocation);
				}, function() {
					self.handleNoGeoLocation(self.browserSupportFlag);
				},
				{
					timeout: 10000
				});
			// Browser doesn't support Geolocation
			} else {
				self.browserSupportFlag = false;
				self.handleNoGeolocation(self.browserSupportFlag);
			}
		}
	},

	// When geolocation is not possible
	handleNoGeolocation: function(errorFlag) {
		if (errorFlag == true) {
			alert("Geolocation service failed.");
			this.initialLocation = this.organizationBern;
		} else {
			alert("Your browser doesn't support geolocation. We've placed you in Bern.");
			this.initialLocation = this.organizationBern;
		}
		this.findNearestOrganization(this.initialLocation);
	},

    // Find the nearest organization
    findNearestOrganization: function(position) {
		var language = $("select#tx_egovapi_language").val();

		showMap(position);
		$.getJSON(
			this.ajaxUrl,
			{
				eID: this.eID,
				action: "nearest",
				language: language,
				lat: position.lat(),
				lng: position.lng()
			},
			function (response) {
				if (response.success) {
					var data = response.data;
					$("select#tx_egovapi_community").val(data.community.id);
					$("select#tx_egovapi_community").change();
					setTimeout('setOrganization("' + data.organization.id + '")', 200);
				}
			}
		);
	},

	/**
	 * Reset the form fields.
	 */
	reset: function() {
		$("select#tx_egovapi_organization").html("");
		$("select#tx_egovapi_service").html("");
		$("select#tx_egovapi_version").html("");
		$("select#tx_egovapi_language").val(this.defaultLanguage);
	},

	/**
	 * Update the list of organizations.
	 * @param community
	 */
	updateOrganizations: function(community) {
		var self = this;
		var language = $("select#tx_egovapi_language").val();

		self.showLoading();
		$.getJSON(
			this.ajaxUrl,
			{
				eID: this.eID,
				action: "organizations",
				language: language,
				community: community
			},
			function (response) {
				self.hideLoading();
				if (response.success) {
					var data = response.data;
					var options = '<option value=""></option>';
					self.coordinates = {};
					for (var i = 0; i < data.length; i++) {
						options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
						self.coordinates[data[i].id] = {
							lat: data[i].latitude,
							lng: data[i].longitude
						}
					}
					$("select#tx_egovapi_organization").html(options);
					$("select#tx_egovapi_service").html('');
					$("select#tx_egovapi_version").html("");
				}
			}
		)
	},

	/**
	 * Update the list of services.
	 * @param organization
	 */
	updateServices: function(organization) {
		var self = this;
		var community = $("select#tx_egovapi_community").val();
		var language = $("select#tx_egovapi_language").val();

		if (self.showGoogleMap) {
			var position = self.coordinates[organization];
			if (!(position.lat && position.lng)) {
				alert('No geolocation available for the organization. Possible misconfiguration!');
			} else {
				var origin = new google.maps.LatLng(position.lat, position.lng);
				showMap(origin);
			}
		}

		self.showLoading();
		$.getJSON(
			this.ajaxUrl,
			{
				eID: this.eID,
				action: "services",
				language: language,
				community: community,
				organization: organization
			},
			function (response) {
				self.hideLoading();
				if (response.success) {
					var data = response.data;
					var options = '<option value=""></option>';
					var provider = '';
					for (var i = 0; i < data.length; i++) {
						if (provider != data[i].provider) {
							if (provider != '') options += '</optgroup>';
							options += '<optgroup label="' + data[i].provider + '">';
						}
						options += '<option value="' + data[i].id + '-' + data[i].version + '">' + data[i].name + '</option>';
						provider = data[i].provider;
					}
					options += '</optgroup>';

					$("select#tx_egovapi_service").html(options);
					$("select#tx_egovapi_version").html("");
				}
			}
		)
	},

	/**
	 * Update the list of service versions.
	 * @param service
	 */
	updateVersions: function(service, defaultVersion) {
		var self = this;
		var community = $("select#tx_egovapi_community").val();
		var organization = $("select#tx_egovapi_organization").val();
		var language = $("select#tx_egovapi_language").val();

		self.showLoading();
		$.getJSON(
			this.ajaxUrl,
			{
				eID: this.eID,
				action: "versions",
				language: language,
				community: community,
				organization: organization,
				service: service
			},
			function (response) {
				self.hideLoading();
				if (response.success) {
					var data = response.data;
					var options = '<option value="' + defaultVersion + '"></option>';
					for (var i = 0; i < data.length; i++) {
						var cls = data[i].is_default == "1" ? "status_default" : "";
						var status;
						switch (data[i].status) {
							case 1:
								cls += " status_draft";
								status = "D";
								break;
							case 2:
								cls += " status_published";
								status = "P";
								break;
							case 3:
								cls += " status_archived";
								status = "A";
								break;
						}
						status = data[i].is_default == "1" ? "•" : status;
						var label = data[i].id + ' - ' + data[i].name + " (" + status + ")";
						options += '<option value="' + data[i].id + '" class="' + cls + '">' + label + '</option>';
					}

					$("select#tx_egovapi_version").html(options);
				}
			}
		);
	},

	/**
	 * Generate the service links.
	 */
	generateLinks: function() {
		var self = this;
		var community = $("select#tx_egovapi_community").val();
		var organization = $("select#tx_egovapi_organization").val();
		var service = $("select#tx_egovapi_service").val();
		var version = $("select#tx_egovapi_version").val();
		var language = $("select#tx_egovapi_language").val();

		var selectedBlocks = new Array();
		$.each($("input[name='tx_egovapi_selectorForm_block[]']:checked"), function() {
			selectedBlocks.push($(this).val());
		});

		var ok = (community ? true : false)
			&& (organization ? true : false)
			&& (language ? true : false)
			&& selectedBlocks.length > 0;
		if (!ok) return;

		if (service) {
			var info = service.split("-");
			service = info[0];
		}

		self.showLoading();
		$.getJSON(
			this.ajaxUrl,
			{
				eID: this.eID,
				action: "url",
				language: language,
				community: community,
				organization: organization,
				service: service,
				version: version,
				blocks: selectedBlocks.join(",")
			},
			function (response) {
				self.hideLoading();
				var result = "";
				if (response.success) {
					var data = response.data;

					var items = '';
					var provider = '';
					for (var i = 0; i < data.length; i++) {
						if (provider != data[i].provider) {
							if (provider != '') items += '</ul>';
							items += '<li>' + data[i].provider + '<ul>';
						}
						items += "<li>" + data[i].url + "</li>";
						provider = data[i].provider;
					}
					result = "<ul>" + items + "</ul></ul>";
				}
				$("#tx_egovapi_result").html(result);
			}
		);
	},

	showLoading: function() {
		var overlay = $("#tx_egovapi_resultoverlay");
		overlay.css('top', $("#tx_egovapi_result").position().top);
		overlay.css('height', Math.max(200, $("#tx_egovapi_result").height()) + 20);
		overlay.css('left', $("#tx_egovapi_result").position().left);
		overlay.css('width', $("#tx_egovapi_result").width());
		overlay.show();
	},

	hideLoading: function() {
		$("#tx_egovapi_resultoverlay").hide();
	}
}

// These methods MUST be global
function setOrganization(id) {
    if (!$("select#tx_egovapi_organization").text()) {
        setTimeout('setOrganization("' + id + '")', 200);
    }
    $("select#tx_egovapi_organization").val(id);
    $("select#tx_egovapi_organization").change();
}

function showMap(origin) {
	var mapOptions = {
		zoom: 12,
		center: origin,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		zoomControl: false,
		mapTypeControl: false,
		streetViewControl: false
	};
	var map = new google.maps.Map(document.getElementById("tx_egovapi_map"), mapOptions);
	var marker = new google.maps.Marker({
		map: map,
		position: origin,
		animation: google.maps.Animation.DROP
	});
}

$(document).ready(function() {
	TX_EGOVAPI.selector.init();
});
