// Set up name space
if (typeof TX_EGOVAPI == 'undefined') TX_EGOVAPI = {};

TX_EGOVAPI.selector = {
	eID: "egovapi_pi2",
	ajaxUrl: '',
	defaultLanguage: '',

	/**
	 * Initialize this class.
	 */
	init: function() {
		this.ajaxUrl = $("input#tx_egovapi_ajaxUrl").val();
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
		var language = $("select#tx_egovapi_language").val();

		$.getJSON(
			this.ajaxUrl,
			{
				eID: this.eID,
				action: "organizations",
				language: language,
				community: community
			},
			function (response) {
				if (response.success) {
					var data = response.data;
					var options = '<option value=""></option>';
					for (var i = 0; i < data.length; i++) {
						options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
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
		var community = $("select#tx_egovapi_community").val();
		var language = $("select#tx_egovapi_language").val();

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
		var community = $("select#tx_egovapi_community").val();
		var organization = $("select#tx_egovapi_organization").val();
		var language = $("select#tx_egovapi_language").val();

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
	}
}

$(document).ready(function() {
	TX_EGOVAPI.selector.init();
});
