$(function(){
    var ajaxUrl = $("input#tx_egovapi_ajaxUrl").val();
    var eID = "egovapi_pi2";

    // Reset drop down fields
    $("select#tx_egovapi_organization").html("");
    $("select#tx_egovapi_service").html("");

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
    })
});
