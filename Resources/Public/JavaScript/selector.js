$(function() {
    var ajaxUrl = $("input#tx_egovapi_ajaxUrl").val();
    var defaultLanguage = $("input#tx_egovapi_defaultLanguage").val();
    var eID = "egovapi_pi2";

    // Reset form fields
    $("select#tx_egovapi_organization").html("");
    $("select#tx_egovapi_service").html("");
    $("input#tx_egovapi_version").val("");
    $("select#tx_egovapi_language").val(defaultLanguage);

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
            function (response) {
                if (response.success) {
                    var data = response.data;
                    var options = '<option value=""></option>';
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }
                    $("select#tx_egovapi_organization").html(options);
                    $("select#tx_egovapi_service").html('');
                    $("input#tx_egovapi_version").val("");
                }
            }
        )
    });

    // Update the list of services
    $("select#tx_egovapi_organization").change(function() {
        var community = $("select#tx_egovapi_community").val();
        var language = $("select#tx_egovapi_language").val();

        $.getJSON(
            ajaxUrl,
            {
                eID: eID,
                action: "services",
                language: language,
                community: community,
                organization: $(this).val()
            },
            function (response) {
                if (response.success) {
                    var data = response.data;
                    var options = '<option value=""></option>';
                    for (var i = 0; i < data.length; i++) {
                        options += '<option value="' + data[i].id + '-' + data[i].version + '">' + data[i].name + '</option>';
                    }
                    $("select#tx_egovapi_service").html(options);
                    $("input#tx_egovapi_version").val("");
                }
            }
        )
    });

    // Update the select service's version
    $("select#tx_egovapi_service").change(function() {
        var info = $(this).val().split("-");
        var version = info[1];
        $("input#tx_egovapi_version").val(version);
    });

    // Toggle select blocks
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

    // Update fields when language is changed
    $("select#tx_egovapi_language").change(function() {
        $("select#tx_egovapi_organization").trigger("change");
        $("input#tx_egovapi_version").val("");
    });

    // Generation of the links
    $("input#tx_egovapi_selectorForm_submit").click(function() {
        var community = $("select#tx_egovapi_community").val();
        var organization = $("select#tx_egovapi_organization").val();
        var service = $("select#tx_egovapi_service").val();
        var version = $("input#tx_egovapi_version").val();
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
            ajaxUrl,
            {
                eID: eID,
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
                    for (var i = 0; i < data.length; i++) {
                        items += "<li>" + data[i].url + "</li>";
                    }
                    result = "<ul>" + items + "</ul>";
                }
                $("#tx_egovapi_result").html(result);
            }
        );
    })
});
