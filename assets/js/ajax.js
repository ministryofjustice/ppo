/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function update_tiles(queryParams, clearData) {
    $.ajax({
        type: 'GET',
        url: PPOAjax.ajaxurl,
        async: true,
        cache: false,
        data: {
            action: 'update_tiles',
            queryParams: queryParams
        },
        success: function(results) {
            if (clearData === true) {
                jQuery(".live-results").html(results);
                curPage=1;
            } else {
                jQuery(".live-results").append(results);
            }
            $contentLoadTriggered = false;
            $("#loading-spinner").hide();
        },
        error: function(error) {
            console.log(error);
            $("#loading-spinner").hide();
        }
    });
}

function update_spreadsheet(queryParams, paged) {
    $.ajax({
        type: 'POST',
        url: PPOAjax.ajaxurl,
        async: true,
        cache: false,
        data: {
            action: 'update_spreadsheet',
            queryParams: queryParams,
            paged: paged
        },
        success: function(results) {
            $contentLoadTriggered = false;
            $("#loading-spinner").hide();
            jQuery(".spreadsheet").html(results);
        },
        error: function(error) {
            $("#loading-spinner").hide();
        }
    });
}

jQuery(document).ready(function($) {
    if ($('.live-results').length > 0) {
        $("#loading-spinner").show();
        update_tiles(PPOAjax.queryParams);
    }
});
