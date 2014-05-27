$(document).ready(function() {

    $("#tweet_container").on('click', '.btn-delete-tweet', function(evt) {

        var data = $(this).data();

        if (confirm("Are you sure you want to remove this tweet? ")) {
            var settings = {
                'contentType': 'application/json; charset=utf-8',
                'url': "/api/tweets/" + data.id,
                'type': "DELETE",
                'data': JSON.stringify(data),
                'async': true,
                success: function(returnData, textStatus, jqXHR) {
                    var $target = $("#" + data.target);
                    if ($target.length) {
                        $target.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Couldn't delete the tweet");
                }};

            jQuery.ajax(settings);

        }

    });

    $("#tweet_container").on('click', '.btn-edit-tweet', function(evt) {
        console.log("Edit tweet");
    });

    $("#new-tweet").on('click', function(evt) {
        console.log("New tweet");
    });

});