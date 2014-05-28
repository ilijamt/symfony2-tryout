$(document).ready(function() {

    var timeoutId = null;
    var timeoutInSec = 5;

    var scheduleCall = function() {
        window.clearTimeout(timeoutId);
        timeoutId = setTimeout(getLatestTweets, timeoutInSec * 1000);
    };

    var getLatestTweets = function() {

        var latestTimestamp = $(".tweet-entry:first").data('timestamp');
        var $target = $(".tweet-entry:first");
        var append = false;

        if (!($target.length > 0)) {
            $target = $("#tweet_container");
            latestTimestamp = 0;
            append = true;
        }

        jQuery.ajax({
            'url': "/api/latests/" + latestTimestamp + '/html',
            'type': "GET",
            'async': true,
            success: function(returnData, textStatus, jqXHR) {
                if (returnData.length > 0) {

                    if (!($target.length > 0)) {
                        $("#no-tweets").hide();
                    }

                    if (append) {
                        $target.append($(returnData));
                    } else {
                        $target.before($(returnData));
                    }
                }
                scheduleCall();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                scheduleCall();
            }});
    };

    scheduleCall();

    window.refreshTweets = getLatestTweets;

});