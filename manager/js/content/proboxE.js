/**
 *
 * ++++++++++ proBox v0.1 ++++++++++
 * Developed by Xenon
 *
 */




$(document).ready(function () {

    proB.init();


});


var proB = {

    uid: "#proBox",
    open: false,
    locked: false,
    init: function () {
        setTimeout(function () {
//            proB._resize(true);
        }, 500);
        proB._triggerOutClick();
        $(window).resize(function () {
            proB._resize()
        });
    },
    hide: function (callback) {
        $("body").removeClass("probox-scroll-lock");

        if (typeof(callback) == 'undefined') {
            callback = function () {
            };
        }
        $(proB.uid).hide(0, callback());
        proB.open = false;
    },
    show: function () {
        $("body").addClass("probox-scroll-lock");
        this.locked = true;
        setTimeout(function () {
            proB.locked = false;
            proB._resize(true);
        }, 50);
        $(proB.uid).show(0);
        proB.open = true;
    },
    _resize: function (forced) {

        if (typeof(forced) == "undefined") {
            forced = false;
        }
        var proBox = $(proB.uid);
        if (proBox.is(":visible") || forced) {
            var s = $(window);
            var sH = s.height();
            var pH = proBox.outerHeight();
            if (forced) {
                //pH = sH * 0.8;
            }
            proBox.css({
                top: ((sH - pH) / 2) + "px"
            })
        }
    },
    _triggerOutClick: function () {
        $(document).click(function (event) {

            if ($(proB.uid).is(":visible") && !proB.locked) {
                if (!$(event.target).closest(proB.uid).length
                    && event.target != $('div[class*="mce"]')
                    && !$(event.target).closest('div[class*="mce"]').length
                    && !$(event.target).closest('div[id="banana"]').length
                    && !$(event.target).closest('.gmap_autocomplete').length
                    && !$(event.target).closest('.gmapmulti_autocomplete').length
                    && !$(event.target).find('.sp-container').length//for colorpicker box

                //&& !$(event.target).closest('#bestsByCategory').length
                    //&& !$(event.target).closest('.tt-suggestion').length
                    //&& !$(event.target).closest('#product-search-results').length
                ) {
                    proB.hide();
                    JSBlocks.clear();
                }
            } else {
                return true
            }
        })
    }
};