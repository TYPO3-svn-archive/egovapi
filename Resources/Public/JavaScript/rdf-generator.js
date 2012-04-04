// Set up name space
if (typeof TX_EGOVAPI == 'undefined') TX_EGOVAPI = {};

TX_EGOVAPI.generator = {
	eID: "egovapi_pi2",
	ajaxUrl: '',
	defaultLanguage: '',
	processing: false,

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
			if (self.processing) return;
			self.processing = true;
			self.updateOrganizations($(this).val());
		});

		$("select#tx_egovapi_organization").change(function() {
			self.toggleNextButton();
		});

		$("input#tx_egovapi_website").keypress(function() {
			self.toggleNextButton();
		});

		if ($("select#tx_egovapi_community").val()) {
			$("select#tx_egovapi_community").trigger('change');
		}
	},

	toggleNextButton: function() {
		var buttonNext = $("input#tx_egovapi_generatorForm_next");
		var organization = $("select#tx_egovapi_organization").val();
		var website = $("input#tx_egovapi_website").val();

		if (organization.length > 0 && website.length > 0) {
			buttonNext.removeAttr("disabled");
		} else {
			buttonNext.attr("disabled", true);
		}
	},

	/**
	 * Reset the form fields.
	 */
	reset: function() {
		$("select#tx_egovapi_language").val(this.defaultLanguage);
		$("select#tx_egovapi_organization").html("");
	},

	/**
	 * Update the list of organizations.
	 * @param community
	 */
	updateOrganizations: function(community) {
		var self = this;
		var language = $("select#tx_egovapi_language").val();

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

					var defaultOrganization = $("input#tx_egovapi_defaultOrganization").val();
					$("select#tx_egovapi_organization").val(defaultOrganization);
					self.toggleNextButton();
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
