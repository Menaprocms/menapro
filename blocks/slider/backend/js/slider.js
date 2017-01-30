/**
 * Created by Eros on 26/06/2016.
 */

$.extend(true,JSBlocks.blocks, {
    slider: {
        group: 'image',
        contentClass: 'eslider',
        icon: 'fa fa-play',
        name: 'slider',
        configurable: true,
        dataStructure: {
            slides: {
                src:{},
                alt:{},
                elink:{}
            }
        },
        data: null,
        events: {},
        bananaImageCallback:function(items){
            var self = this;

            var cont=$('#slider_slides li').length;
            $.each(items, function(k,v){
                var fullDir= v.fullDir;
                var url=JSBlocks.utils.relativeToReal(fullDir);
                var li=$('<li>',{
                    class:'list-group-item '+self.name+'_slides'
                }).appendTo('#slider_slides');
                var html=self.getslideslicontent(cont,{src:url},li);
                li.html(html);
                var src=li.find('.slider_src').val(url);
                cont++;
            });
        },
        ready: function () {
            $("#slider_browse").banana(JSBlocks.getImageBananaSettings(true));
            $("#slider_slides").sortable({
                axis: 'y',
                handle:'.eHandler'
            })
        },
        getPreview: function (content) {
            if (content && content.slides) {
                var slides=[];
                $.each(content.slides,function(k,v){
                    slides.push($("<li>",{
                        class:"slide_preview",
                        css:{
                            backgroundImage: "url('" + JSBlocks.utils.getThumbUrl(JSBlocks.utils.realToRelative(v.src)) + "')"
                        },
                        html:$("<i>",{
                            class:"slide_next fa fa-angle-right"

                        })
                    }));
                });
                return $("<ul>",{
                    html:slides,
                    class:"slides_preview"
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
        clearslides: function () {
            $("#slider_slides").html("");
        },
        getslideslicontent:function(key, content,container){
            var self = this;

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
                            class: "col-xs-2",
                            html: $("<div>", {
                                class: "slider_slide",
                                css: {
                                    backgroundImage: "url('" + JSBlocks.utils.getThumbUrl(JSBlocks.utils.realToRelative(content.src)) + "')"
                                }
                            })
                        }),

                        //    Actions
                        $("<div>", {
                            class: "col-xs-6 slider_actions",
                            html:[$("<input>", {
                                type: "text",
                                placeholder: "ALT / TITLE",
                                value:content.alt,
                                class:"form-control slider_slides_alt",
                                id: "slider_slides_alt_" + key
                            }),$("<textarea>", {
                                placeholder: "Description",
                                value:content.alt,
                                class:"form-control slider_slides_description",
                                id: "slider_slides_description_" + key
                            }),
                                $("<div>",{
                                id:"slider_slides_elink_" + key,
                                class:'slider_slides_elink',
                                html:JSBlocks.linkButton(this.name,"slides_elink",key,content,container,false)
                            })]
                        }),
                        $("<input>",{
                           type:'hidden',
                           class:'slider_slides_src',
                           id: "slider_slides_src_" + key,
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
                                    self.data.slides.splice(key,1);
                                    $(this).closest('.slider_slides').remove();
                                    proB._resize();
                                }
                            })
                        })
                    ]
                });
        }
    }
});


