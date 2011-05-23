$(function(){
    $("select#tx_egovapi_community").change(function() {
        var url = '/?id=45&eID=egovapi_pi2&action=organizations&language=FR&community=' + $(this).val();
        $.getJSON(url, function(response){
            if (response.success) {
                var data = response.data;
                var options = '';
                for (var i = 0; i < data.length; i++) {
                    options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                }
                $("select#tx_egovapi_organization").html(options);
            }
        })
    })
});
