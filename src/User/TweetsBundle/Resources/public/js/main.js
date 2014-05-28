BootstrapDialog.yesno = function(message, callback) {
    new BootstrapDialog({
        title: 'Confirmation',
        message: message,
        closable: false,
        data: {
            'callback': callback
        },
        buttons: [{
                label: 'No',
                action: function(dialog) {
                    typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(false);
                    dialog.close();
                }
            }, {
                label: 'Yes',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    typeof dialog.getData('callback') === 'function' && dialog.getData('callback')(true);
                    dialog.close();
                }
            }]
    }).open();
};

$(document).ready(function() {

    $("#refresh-tweet").on('click', function() {
        try {
            refreshTweets();
        } catch (err) {

        }
    });

    $("#tweet_container").on('click', '.btn-delete-tweet', function(evt) {

        var data = $(this).data();

        BootstrapDialog.yesno('Are you sure you want to remove this tweet?', function(result) {
            if (result) {
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
                        BootstrapDialog.alert("Couldn't delete the tweet");
                    }};

                jQuery.ajax(settings);
            } else {

            }
        });

    });

    $("#tweet_container").on('click', '.btn-edit-tweet', function(evt) {

        var data = $(this).data();

        jQuery.ajax({
            'contentType': 'application/json; charset=utf-8',
            'url': "/api/tweets/" + data.id + "/entry",
            'type': "GET",
            'async': true,
            success: function(returnData, textStatus, jqXHR) {

                var $flash = jQuery('<div/>', {
                    class: 'alert alert-danger'
                });
                $flash.text("The text field is required, it must not be empty.");
                $flash.hide();

                var $char_count = jQuery('<div/>', {
                    class: 'alert alert-info'
                });

                var $textarea = jQuery('<textarea/>', {
                    id: 'tweet-entry',
                    class: 'form-control',
                    rows: 5
                });

                $textarea.keyup(function(evt) {
                    var maxLength = 140;
                    var text = $(this).val();
                    var textLength = text.length;
                    if (textLength > maxLength) {
                        $(this).val(text.substring(0, (maxLength)));
                    }
                    $char_count.text("You have " + (maxLength - textLength) + " characters remaining.");
                    if (textLength > 0) {
                        $flash.hide();
                    } else {
                        $flash.show();
                    }
                });

                $textarea.val(returnData.entry);
                $textarea.trigger('keyup');

                var $container = jQuery('<div/>');
                $container.append($textarea);
                $container.append($char_count);
                $container.append($flash);

                BootstrapDialog.show({
                    title: 'Edit tweet',
                    message: $container,
                    buttons: [
                        {
                            icon: 'glyphicon glyphicon-edit',
                            label: 'Update',
                            cssClass: 'btn-primary',
                            action: function(dialogRef) {

                                $button = $(this);
                                dialogRef.enableButtons(false);
                                dialogRef.setClosable(false);

                                var validText = $textarea.val().length > 0;

                                if (validText) {

                                    jQuery.ajax({
                                        'url': "/api/tweets/" + data.id + "/html",
                                        'type': "PATCH",
                                        'data': $textarea.val(),
                                        'async': true,
                                        success: function(returnData, textStatus, jqXHR) {
                                            var $target = $("#" + data.target);
                                            if ($target.length) {

                                                var $parent = $(".tweet-entry:first");
                                                $target.fadeOut(300, function() {
                                                    $(this).remove();
                                                    $parent.before($(returnData));
                                                    $ntarget = $("#" + data.target);
                                                    $ntarget.slideDown(400);
                                                });

                                            }

                                            dialogRef.close();
                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            BootstrapDialog.alert("Couldn't delete the tweet");
                                            dialogRef.close();
                                        }});

                                } else {

                                    // we have an error not enough characters 
                                    $flash.show();
                                    dialogRef.enableButtons(true);
                                    dialogRef.setClosable(true);

                                }
                            }
                        }, {
                            label: 'Close',
                            action: function(dialogRef) {
                                dialogRef.close();
                            }
                        }]
                });


            },
            error: function(jqXHR, textStatus, errorThrown) {
                BootstrapDialog.alert("Couldn't edit the tweet");
            }});

    });

    $("#new-tweet").on('click', function(evt) {

        var $flash = jQuery('<div/>', {
            class: 'alert alert-danger'
        });
        $flash.text("The text field is required, it must not be empty.");
        $flash.hide();

        var $char_count = jQuery('<div/>', {
            class: 'alert alert-info'
        });

        var $textarea = jQuery('<textarea/>', {
            id: 'tweet-entry',
            class: 'form-control',
            rows: 5
        });

        $textarea.keyup(function(evt) {
            var state = true;
            var maxLength = 140;
            var text = $(this).val();
            var textLength = text.length;
            if (textLength > maxLength) {
                $(this).val(text.substring(0, (maxLength)));
                state = false;
            }
            $char_count.text("You have " + (maxLength - textLength) + " characters remaining.");
            if (textLength > 0) {
                $flash.hide();
            } else {
                $flash.show();
            }
            return state;
        });

        $textarea.trigger('keyup');

        var $container = jQuery('<div/>');
        $container.append($textarea);
        $container.append($char_count);
        $container.append($flash);

        BootstrapDialog.show({
            title: 'New tweet',
            message: $container,
            buttons: [
                {
                    icon: 'glyphicon glyphicon-envelope',
                    label: 'Create',
                    cssClass: 'btn-primary',
                    action: function(dialogRef) {

                        dialogRef.enableButtons(false);
                        dialogRef.setClosable(false);

                        var validText = $textarea.val().length > 0;

                        if (validText) {

                            jQuery.ajax({
                                'url': "/api/tweets",
                                'type': "POST",
                                'data': $textarea.val(),
                                'async': true,
                                success: function(returnData, textStatus, jqXHR) {
                                    jQuery.ajax({
                                        'url': "/api/tweets/" + returnData.id + "/entry/html",
                                        'type': "GET",
                                        'data': $textarea.val(),
                                        'async': true,
                                        success: function(returnData, textStatus, jqXHR) {

                                            var $target = $(".tweet-entry:first");
                                            $target.before($(returnData));

                                        },
                                        error: function(jqXHR, textStatus, errorThrown) {
                                            BootstrapDialog.alert("Couldn't add a new tweet");
                                        }});
                                    dialogRef.close();
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    BootstrapDialog.alert("Couldn't add a new tweet");
                                    dialogRef.close();
                                }});

                        } else {

                            // we have an error not enough characters 
                            $flash.show();
                            dialogRef.enableButtons(true);
                            dialogRef.setClosable(true);

                        }

                    }
                }, {
                    label: 'Close',
                    action: function(dialogRef) {
                        dialogRef.close();
                    }
                }]
        });


    });

});