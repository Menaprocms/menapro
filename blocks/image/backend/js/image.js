/**
 * Created by Eros on 26/06/2016.
 */

    $.extend(true, JSBlocks.blocks, {
        image: {
            group: 'image',
            contentClass: 'eImage',
            icon: 'fa fa-camera',
            name: 'image',
            configurable: true,
            dataStructure: {
                elink:{},
                src: {
                    required:true
                },
                alt: {
                    validator: 'string'
                   }
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
            bananaImageCallback:function(url){
                $("#image_previewImage").css({
                    backgroundImage: "url('" + url + "')"
                });
                $("#image_src").val(JSBlocks.utils.relativeToReal(url))
            },
            ready: function () {
                $("#image_browse,#image_previewImage,#image_elink_file_browser").banana(JSBlocks.getImageBananaSettings(false));
                var cont=$('#image_elink');
                JSBlocks.linkButton(this.name,"elink",'','',cont,true);
            },
            getProbox:function(){
                cHtml.clearProBox('proBox-' + this.name);
                var cont=$('#image_elink');
                JSBlocks.setLinkData(this.name,"elink",this.data,cont);
                cHtml.openProBox(this.name);
            },
            getPreview: function (content) {
                if (content && content.src) {
                    var imgUrl = "";
                    imgUrl = JSBlocks.utils.realToRelative(content.src);
                    return $("<img>", {
                        src: imgUrl,
                        class: "img-responsive img-thumbnail"
                    }).outerHTML();
                }
            },
            afterOpen: function () {
                $("#" + this.name + "_previewImage").css({
                    backgroundImage: this.data.src ? "url('" + JSBlocks.utils.realToRelative(this.data.src) + "')" : ""
                });
                return true;
            },
            beforeClose: function () {
                $("#" + this.name + "_previewImage").css({
                    backgroundImage: ""
                });
                return true;
            }
        }
});

