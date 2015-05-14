(function () {
    'use strict';

    /**
     * Add my effect & close function as global within JQuery object
     *
     * @param speed
     */
    jQuery.fn.removeEffect = function (speed) {
        $(this).fadeOut(speed, function () {
            $(this).remove();
        });
    };

    $(document).ready(function () {
        //Form's stages handling
        myAjaxContainer('', "#stages");
    });
})();

/**
 * Container for ajax actions
 *
 * @param url
 * @param container
 */
function myAjaxContainer(url, container) {
    //init stage
    myAjax(url, "init", function (data) {
        //show stage html with proper #number
        $(data.html).appendTo(container);
        //fix TW Select
        loadTWSelect('.selectpicker', {size: 7});

        //debug:
        console.log('Init..., num stages#' + data.stages);
    });

    //add stage
    $('#add-stage').on('click', function () {
        myAjax(url, "add", function (data) {
            if (data.limit) {
                return;
            }
            //show stage html with proper #number
            $(data.html).appendTo(container);
            //fix TW Select
            loadTWSelect('.selectpicker', {size: 7});
            //Add / Remove buttons handling
            showHideButton('#remove-stage', '#add-stage', data.maxStages, data.stage);

            //debug:
            console.log('Add stage#' + data.stage);
        });
    });

    //delete stage
    $('#remove-stage').on('click', function () {
        myAjax(url, "delete", function (data) {
            if (data.limit) {
                return;
            }
            $("#stage" + data.stage).removeEffect(400);
            //Add / Remove buttons handling
            // data.minStages + 1 because data.stage = min + 1
            showHideButton('#add-stage', '#remove-stage', data.minStages + 1, data.stage);

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
        data: {"stage": action},
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
 * Show / hide by selector
 * Use null to hide parameter if no need to hide
 *
 * @param show
 * @param hide
 * @param minMax
 * @param current
 */
function showHideButton(show, hide, minMax, current) {
    $(show).show();
    if (hide && minMax === current) {
        $(hide).hide();
    }
}
