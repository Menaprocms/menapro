/**
 * Created by Eros on 26/06/2016.
 */

    $.extend(true, JSBlocks.blocks, {
        contactdata: {
            group: 'other',
            contentClass: 'eContactdata',
            icon: 'fa fa-envelope-o',
            name: 'contactdata',
            configurable: true,
            dataStructure: {
                address:{},
                openinghours:{},
                telephone:{},
                email:{},
                mobile:{},
                webname:{},
                showtitle:{},
                facebook:{},
                twitter:{},
                instagram:{},
                pinterest:{}
            },
            data: null,
            events: {
                keyup: [
                    {
                        el: "#image_src",
                        ck: function (e) {
                            $("#image_previewImage").css({
                                backgroundImage: "url('" + JSBlocks.utils.realToRelative($(e.currentTarget).val()) + "')"
                            });
                        }
                    }
                ]
            },
            ready: function () {

            },
            getProbox:function(){
                cHtml.clearProBox('proBox-' + this.name);
                cHtml.openProBox(this.name);
            },
            getPreview: function (content) {
                return '<p>Contact data</p>'
            },
            afterOpen: function () {

                return true;
            },
            beforeClose: function () {

                return true;
            }
        }
});

