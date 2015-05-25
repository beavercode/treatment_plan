(function () {
    'use strict';

    /**
     * Add my effect & close function as global within JQuery object
     *
     * @param speed
     */
    jQuery.fn.removeEffect = function (speed, maxStage) {
        $(this).fadeOut(speed, function () {
            $(this).remove();
            showHideButtons(maxStage);
        });
    };

    $(document).ready(function () {
        //Form's stages handling
        myAjaxContainer('', "#stages");
        //Hide #notify-msg when changing content in form
        //http://stackoverflow.com/questions/12797700/jquery-detect-change-in-input-field/12797759#12797759
        $(".form").change(function () {
            $('#notify-msg').hide();
        });
    });
})();

/**
 * Container for ajax actions
 *
 * @param url Form action parameter, current page by default
 * @param container
 */
function myAjaxContainer(url, container) {
    var requestData = {"stage": "init"};
    //init stage
    myAjax(url, requestData, function (data) {
        //show stage html with proper #number
        $(data.html).appendTo(container);
        //fix TW Select
        loadTWSelect('.selectpicker', {size: 7});
        //fix TW file input for each input[type=file], doing this at once breaks TB Input plugin markup
        for (var i = 1; i <= data.stage; i++) {
            $("#file" + i).bootstrapFileInput();
        }
        //todo hide 'show' button if 'min === max'
        showHideButtons(data.maxStage);

        //debug:
        console.log('Init... stages#' + data.stage);
    });

    //add stage
    $('#add-stage').on('click', function () {
        var requestData = {"stage": "add"};

        myAjax(url, requestData, function (data) {
            if (data.limit) {
                return;
            }
            //show stage html with proper #number
            $(data.html).appendTo(container);
            //fix TW Select
            loadTWSelect('.selectpicker', {size: 7});
            //fix TW file input
            $("#file" + data.stage).bootstrapFileInput();
            //todo hide 'show' button if 'min === max'
            showHideButtons(data.maxStage);
            // hide notify-msg
            $('#notify-msg').hide();

            //debug:
            console.log('Add stage#' + data.stage);
        });
    });

    //delete stage
    $('#remove-stage').on('click', function () {
        var requestData = {"stage": "delete"};

        myAjax(url, requestData, function (data) {
            if (data.limit) {
                return;
            }
            $("#stage" + data.stage).removeEffect(350, data.maxStage);
            // hide notify-msg
            $('#notify-msg').hide();

            //debug:
            console.log('Remove stage#' + data.stage);
        });
    });
}

/**
 * Ajax wrapper uses post method by default
 *
 * @param url
 * @param action
 * @param okFunc
 */
function myAjax(url, action, okFunc) {
    $.ajax({
        type: "post",
        dataType: "json",
        url: url,
        data: action,
        success: okFunc,
        error: function (jqxhr, textStatus, error) {
            var err = textStatus + ", " + error;
            //debug:
            console.log("Request Failed: " + err);
        }
    });
}

/**
 * TB Select setup
 *
 * @param container
 * @param params
 */
function loadTWSelect(container, params) {
    $(container).selectpicker(params);
}

/**
 * Show or hide stage control buttons
 *
 * @param maxStages
 */
function showHideButtons(maxStages) {
    var numStages = $('#stages').children().length,
        addButton = '#add-stage',
        delButton = '#remove-stage';

    if (numStages > 1) {
        $(delButton).show();
    } else {
        $(delButton).hide();
    }

    if (numStages < maxStages) {
        $(addButton).show();
    } else {
        $(addButton).hide();
    }
}
