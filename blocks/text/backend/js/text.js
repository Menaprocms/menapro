/**
 * Created by Eros on 26/06/2016.
 */

$.extend(JSBlocks.blocks, {
    text: {
        group: 'text',
        contentClass: 'eText',
        icon: 'fa fa-align-left',
        name: 'text',
        configurable: true,
        dataStructure: {
            text: {}
        },
        editor: null,
        data: null,
        events: {
            click: [
                {
                    el: ".editor_options li[data-group='common']",
                    ck: function () {
                        JSBlocks.blocks.text.editor.formatter.toggle($(this).data('format').toLowerCase());
                        JSBlocks.blocks.text.editor.focus();
                    }
                },
                {
                    el: ".editor_options li[data-group='list']",
                    ck: function () {
                        JSBlocks.blocks.text.editor.execCommand($(this).data('format').toLowerCase());
                        JSBlocks.blocks.text.editor.focus();
                    }
                }
            ]
        },

        ready: function () {
            var self = this;
            setTimeout(function () {
                self.initTinyEditor();
                setTimeout(function () {

                    $('#editor_myfile').banana(JSBlocks.getFileBananaSettings('text_editor_ifr'));
                }, 400);
            }, 400);

        },
        collecttext: function () {
            this.data.text = this.editor.getContent();
            this.data.text = this.data.text.replace(/\+/g,'&plus;');
        },
        loadtext: function (value) {
            this.editor.setContent(value);
        },
        cleartext: function () {
            this.editor.setContent("");
        },
        getPreview: function (content) {
            JSBlocks.log("Preview of text");
            var html = '';
            if (content.text.length) {
                var a = content.text.replace(/upload\//g, '../upload/');
                html = '<div class="eTextBlock">' + a + '</div>';

            } else {
                html = '';
            }
            return html;
        },
        afterOpen: function () {
            return true;
        },
        beforeClose: function () {
            return true;
        }, initTinyEditor: function () {

            var self = this;
            //fixme: Revisar que botones son necesarios, tanto añadir como eliminar.
            tinyMCE.init({
                selector: "#text_editor",
                theme: "modern",
                skin: "lightgray",
                minHeight: 800,
                file_picker_types: 'file image media',
                plugins: "link  paste table code media textcolor autoresize image",//autoresize colorpicker
                toolbar1: ",|,alignleft,aligncenter,alignright,alignjustify,|pasteword,|,outdent,indent,|,link,unlink,|,cleanup,|,media, image, myimage,bold,italic,underline,strikethrough,|,removeformat,",
                //toolbar2: "",//,forecolor, backcolor, |,bullist,numlist,
                content_css: 'blocks/text/backend/css/editor.css',
                plugin_preview_height: 500,
                object_resizing: true,
                autoresize_min_height: 350,
                //todo: Add link list with all active pages and redesign link plugin Panel
                link_class_list: [
                    {title: 'None', value: ''},
                    {title: 'Button', value: 'btn btn-mena'},
                ],
                link_list:function(success) {
                    var links=[];
                    $.each(cms.availablePages, function(k,v){
                        links.push({title: v.name+(v.published?'':'*'),value: v.url});
                    });
                    success(links);
                },
                setup: function (editor) {
                        editor.addButton('myimage', {
                        text: '',
                        icon: 'no eIcoFile',
                        id: 'editor_myfile',
                        onclick: function () {

                        }
                    });
                },
                file_browser_callback: function (field_name, url, type, win) {
                    tinyMCE.activeEditor.windowManager.open({});
                    cms.options.file_aux = field_name;
                },
                filemanager_title: "File manager",
                filemanager_access_key: uTokManager,
                relative_urls: true,
                document_base_url: baseFrontDir + '/',
                remove_script_host: false,
                extended_valid_elements: "em[class|name|id]",
                init_instance_callback:function(editor){
                    JSBlocks.blocks.text.editor=editor;
                    self.bindSelectorChanged();
                },

                menu:{}
            });
        },



        bindSelectorChanged: function () {
            var self=this;
            $(".editor_options li").each(function(key,nitem){
                var item=$(nitem),
                    selection = self.editor.selection,
                    format=item.data('format');

                function setActiveItem(name) {

                    return function (state, args) {
                        //$(".editor_options li").removeClass('selected');

                        var nodeName, i = args.parents.length;

                        while (i--) {
                            nodeName = args.parents[i].nodeName;
                            if (nodeName == "OL" || nodeName == "UL") {
                                break;
                            }
                        }

                        var cssClass="selected";

                        if(state)
                        {
                            //$(".editor_options li").not(item).removeClass('selected');
                            item.addClass(cssClass);
                        }else{
                            $('[data-format="'+name+'"]').removeClass(cssClass)
                        }

                    };
                }

                var itemName=format;
                if (itemName == "bullist") {
                    selection.selectorChanged('ul > li', setActiveItem("UL"));
                }else if (itemName == "numlist") {
                    selection.selectorChanged('ol > li', setActiveItem("OL"));
                }else{
                    selection.selectorChanged(item.data('selector'), setActiveItem(format));
                }

                /*if (item.settings.stateSelector) {
                    selection.selectorChanged(item.settings.stateSelector, function (state) {
                        item.active(state);
                    }, true);
                }

                if (item.settings.disabledStateSelector) {
                    selection.selectorChanged(item.settings.disabledStateSelector, function (state) {
                        item.disabled(state);
                    });
                }*/

            });
        }

    }
}, true);


