$(function(){
    var ajaxUrl = $("input#tx_egovapi_ajaxUrl").val();
    var eID = "egovapi_pi2";

    // Reset form fields
    $("select#tx_egovapi_organization").html("");
    $("select#tx_egovapi_service").html("");
    $("input#tx_egovapi_version").html("");

    // Update the list of organizations
    $("select#tx_egovapi_community").change(function() {
        var language = $("select#tx_egovapi_language").val();

        $.getJSON(
            ajaxUrl,
            {
                eID: eID,
                action: "organizations",
                language: language,
                community: $(this).val()
            },
            function(response) {
                if (response.success) {
                    var data = response.data;
                    var options = '<option value=""></option>';
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    $("select#tx_egovapi_organization").html(options);
                }
            }
        )
    });

    // Update the list of services
    $("select#tx_egovapi_organization").change(function() {
        var language = $("select#tx_egovapi_language").val();
        var community = $("select#tx_egovapi_community").val();

        $.getJSON(
            ajaxUrl,
            {
                eID: eID,
                action: "services",
                language: language,
                community: community,
                organization: $(this).val()
            },
            function(response) {
                if (response.success) {
                    var data = response.data;
                    var options = '<option value=""></option>';
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '-' + data[i].version + '">' + data[i].name + '</option>';
                    }
                    $("select#tx_egovapi_service").html(options);
                }
            }
        )
    });

    // Update the select service's version
    $("select#tx_egovapi_service").change(function() {
        var info = $(this).val().split("-");
        var version = info[1];
        $("input#tx_egovapi_version").val(version);
    })
});
