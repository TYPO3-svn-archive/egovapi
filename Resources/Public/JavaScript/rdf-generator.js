// Set up name space
if (typeof TX_EGOVAPI == 'undefined') TX_EGOVAPI = {};

TX_EGOVAPI.generator = {
	eID: "egovapi_pi2",
	ajaxUrl: '',
	processing: false,

	/**
	 * Initialize this class.
	 */
	init: function() {
		this.ajaxUrl = $("input#tx_egovapi_ajaxUrl").val();
		var self = this;

		this.reset();

		// Register handler for updating the list of organizations
		$("select#tx_egovapi_community").change(function() {
			if (self.processing) return;
			self.processing = true;
			self.updateOrganizations($(this).val());
		});

		// Register handler for updating the list of services
		$("select#tx_egovapi_organization").change(function() {
			self.updateServices($(this).val());
		});
	},

	/**
	 * Reset the form fields.
	 */
	reset: function() {
		$("select#tx_egovapi_organization").html("");
	},

	/**
	 * Update the list of organizations.
	 * @param community
	 */
	updateOrganizations: function(community) {
		var self = this;
		var language = $("input#tx_egovapi_generatorForm_language").val();

		self.showLoading("tx_egovapi_generatorForm_organization_loader");
		$.getJSON(
			this.ajaxUrl,
			{
				eID: this.eID,
				action: "organizations",
				language: language,
				community: community
			},
			function (response) {
				self.processing = false;
				self.hideLoading("tx_egovapi_generatorForm_organization_loader");
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
				}
			}
		)
	},

	showLoading: function(id) {
		var element = $("#" + id);
		element.show();
	},

	hideLoading: function(id) {
		$("#" + id).hide();
	}
}

$(document).ready(function() {
	TX_EGOVAPI.generator.init();
});
