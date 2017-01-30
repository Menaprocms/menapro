/**
 * Created by Silvia on 01/05/2016.
 */
    $.extend(true,JSBlocks.blocks,{
        list:{
            group: 'text',
            contentClass: 'eList',
            icon: 'eIco eIcoList',
            name: 'list',
            configurable: true,
            dataStructure: {
                title:{},
                items: {
                    text:{
                        required:true
                    },
                    eicon:{},
                    elink:{}
                }
            },
            data: null,
            events: {
                click: [
                    {
                        el: '.eListAdd',
                        ck: function (e) {
                            var li=$('<li>',{
                                class:'list-group-item '+JSBlocks.blocks.list.name+'_items'
                            }).appendTo('#list_items');
                            var k=$('#list_items li').length;
                            var html=JSBlocks.blocks.list.getitemslicontent(k,'',li);
                            li.html(html);
                        }
                    }
                ]
            },
            ready:function(){
                $('#list_items').sortable({
                    axis: 'y',
                    handle:'.eHandler'
                });
            },
            afterOpen: function () {
                return true;
            },
            beforeClose: function () {
                this.data=null;
                return true;
            },
            clearitems: function () {
                $("#list_items").html("");
            },
            getitemslicontent:function(key, content, container){
                var self = this;
                var with_cont=true;

                if(content=='' || typeof(content)=='undefined'){
                    with_cont=false;
                }
                var eIcon=$('<div>',{
                    id:"list_items_eicon_"+key,
                    class:'icon_helper list_items_eicon'
                });
                JSBlocks.iconButton(this.name,"items_eicon",key,content,eIcon,true);

                var toreturn= $("<div>", {
                    class: "row",
                    html: [
                        //    eHandler
                        $("<div>", {
                            class: "col-xs-1 eHandler",
                            html: "<span></span><span></span><span></span>"
                        }),
                        //    preview
                        $("<div>", {
                            class: "col-xs-1",
                            html:eIcon
                        }),

                        //    Actions
                        $("<div>", {
                            class: "col-xs-8 list_actions",
                            html:[$("<input>", {
                                type: "text",
                                placeholder: "TEXT",
                                value:(with_cont?content.text:''),
                                class:"form-control list_items_text",
                                id: "list_items_text_" + key
								
                            }),$("<div>",{
                                id:"list_items_elink_" + key,
                                class:'list_items_elink',

                                html:JSBlocks.linkButton(this.name,"items_elink",key,content,container,false)
                            })]
                        }),

                        //    Delete button
                        $("<div>",{
                            class:"buttons col-xs-1",
                            html:$("<span>",{
                                class:"delete btn btn-danger",
                                html:$("<i>",{
                                    class:"fa fa-trash"
                                }),
                                click:function(e){
                                    e.stopPropagation();
                                    self.data.items.splice(key,1);
                                    $(this).closest('.list_items').remove();
                                    proB._resize();
                                }
                            })
                        })
                    ]
                });
                return toreturn;
            },
            getPreview:function(content){
                var lista=$('<ul>',{});
                lista.addClass('eList');
                $.each(content.items, function(k,v){
                    var item='';
                    var li=$('<li>',{});
                    item=' '+v.text;
                    li.html(item);
                    if(typeof(v.eicon)!='undefined'){
                        var icon=$('<i>',{
                            class: v.eicon
                        });
                        icon.prependTo(li);
                    }
                    li.appendTo(lista);
                });
                return $(lista).outerHTML();
            }
        }
    });

