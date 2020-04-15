define(['jquery', 'mage/url'], function($, urlBuilder) {
    "use strict";

    function getQueryParam(q) {
        return (window.location.search.match(new RegExp('[?&]' + q + '=([^&]+)')) || [, null])[1];
    }

    var clickId = getQueryParam('belboon');

    if (null !== clickId) {
        var storeClickIdUrl = urlBuilder.build('belboon/storeclickid');
        $.ajax({
            method: 'POST',
            url: storeClickIdUrl,
            data: {
                clickId: clickId
            }
        });
    }
});
