/**
 * Created by Eros on 26/06/2016.
 */

$.extend(true,JSBlocks.blocks, {
    gallery: {
        group: 'image',
        contentClass: 'eGallery',
        icon: 'fa fa-picture-o',
        name: 'gallery',
        configurable: true,
        dataStructure: {
            images: {
                src: {},
                alt: {}
            }
        },
        data: null,
        events: {},
        bananaImageCallback:function(items){
            var self = this;
            var cont=$('#gallery_images li').length;
            $.each(items, function(k,v){
                var fullDir= v.fullDir;
                var url=JSBlocks.utils.relativeToReal(fullDir);
                var li=$('<li>',{
                    class:'list-group-item '+self.name+'_images'
                }).appendTo('#gallery_images');
                var html=self.getimageslicontent(cont,{src:url},li);
                li.html(html);
                var src=li.find('.gallery_src').val(url);
                cont++;
            });
        },
        ready: function () {
            $("#gallery_browse").banana(JSBlocks.getImageBananaSettings(true));
            $("#gallery_images").sortable({
                axis: 'y',
                handle:'.eHandler'
            });
        },
        getPreview: function (content) {
            if (content && content.images) {
                var images=[];
                $.each(content.images,function(k,v){
                    images.push($("<li>",{
                        class:"gallery_preview",
                        css:{
                            backgroundImage: "url('" + JSBlocks.utils.getThumbUrl(JSBlocks.utils.realToRelative(v.src)) + "')"
                        }
                    }));

                });
                return $("<ul>",{
                    html:images,
                    class:"images_preview "+(content.images.length>6?"half":"")
                }).outerHTML();
            }
        },
        afterOpen: function () {
            return true;
        },
        beforeClose: function () {
            this.data=null;
            return true;
        },
        clearimages: function () {
            $("#gallery_images").html("");
            console.info("Cleaning images");
        },
        getimageslicontent:function(key,content,container){
            var self=this;
            return $("<div>", {

                class: "row",
                html: [
                    //    eHandler
                    $("<div>", {
                        class: "col-xs-1 eHandler",
                        html: "<span></span><span></span><span></span>"
                    }),
                    //    preview
                    $("<div>", {
                        class: "col-xs-3",
                        html: $("<div>", {
                            class: "gallery_slide",
                            css: {
                                backgroundImage: "url('" + JSBlocks.utils.getThumbUrl(JSBlocks.utils.realToRelative(content.src)) + "')"
                            }
                        })
                    }),
                    //    Actions
                    $("<div>", {
                        class: "col-xs-6 gallery_actions",
                        html:$("<input>", {
                            type: "text",
                            placeholder: "ALT",
                            value:content.alt,
                            class:"form-control gallery_images_alt",
                            id: "gallery_images_alt_" + key

                        })
                    }),
                    $("<input>",{
                        type:'hidden',
                        class:'gallery_images_src',
                        id: "gallery_images_src_" + key,
                        value:content.src
                    }),
                    //    Delete button
                    $("<div>",{
                        class:"buttons",
                        html:$("<span>",{
                            class:"delete btn btn-danger",
                            html:$("<i>",{
                                class:"fa fa-trash"
                            }),
                            click:function(e){

                                e.stopPropagation();
                                self.data.images.splice(key,1);
                                $(this).closest('.gallery_images').remove();
                                proB._resize();
                            }
                        })
                    })
                ]
            });
        }
    }
});


