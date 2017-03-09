/**
 * MenaPRO ajax-engine.
 *
 */

$(document).ready(function () {

    cp.init();

});

var cp = {
    options: {
        debug: false
    },

    fixedCallbacks: {
        /**
         * In beforesend params received are {settings,sender}. Return false to stop request
         * In success Callback params received are {data,sender}
         * callBackName:function(param1[,param2[,...]){
         * }
         *
         */
        bs_mn_page: function (settings, sender) {
            $(".superMedinapro").addClass('closed');
            $("#newsconfig_panel").hide();
        },
        cb_mn_page: function (data, sender) {
            //This function is bigger so moved to main class
            cms.loadPageCallback(data, sender);
        },
		cb_deletelang:function(data,sender){
            if(data.success){
                $('#lang_item_'+data.id_lang).remove();
                $('#tlang_'+data.id_lang).remove();
                $('#lang_'+data.id_lang).closest('li').remove();
            }
        },
        cb_in_menu: function (data, sender) {
            cms.toggleMenuStatus();
            cms.model=data.model;
        },
        cb_del_page: function (data, sender) {
            cp.clearPanel(sender);
            $('#trash_pages').html(data.trash_pages);
            cms.afterDeletePage();
        },
        cb_create: function (data, sender) {
            cms.createPageCallback(data, sender);
        },
        cb_recover: function (data, sender) {
            cms.recoverPageCallback(data, sender);
        },
        cb_general_theme: function (data, sender) {
            sender.prop('checked', 'checked');
            new Promise(
                function (resolve, reject) {
                    cms.checkRowOptions(data.value, resolve);
                }).then(function () {
                    cms.options.last_theme = cms.data.theme;
                    cms.drawRowOptions();
                    cHtml.drawCms();
                });
        },
        cb_gmap_api_key: function (data, sender) {
            gmap_api_key=data.model.value;
        },
        bs_link_rewrite: function (settings, sender) {
            //settings.data.value=str2url(sender.val());
            //console.warn(settings);
        },
        cb_link_rewrite: function (data, sender) {
            if(data.success)
            {
                cms.modelLang=data.model;
                sender.val(data.model.link_rewrite);
                cms.model.link_rewrite = data.link_rewrite;
                //var regExp=/New(\s|-)Page(\s|-)[\d]{1,3}/i;
                var liveviewlink=$('#submitLiveview');
                var link=$("#contentlang-link_rewrite");
                //if(liveviewlink.data('url').match(regExp) != null)
                //{
                    var nUrl=liveviewlink.data('url').split("/");
                    var l=nUrl.length - 1;
                    nUrl[l]=link.val()+'.html';

                    var res= nUrl.join('/');
                    liveviewlink.data('url',res);
                //}


            }else{
                sender.val(cms.modelLang.link_rewrite)
            }

        },
        cb_pagetheme: function (data, sender) {
            cms.pageThemeCallback(data, sender);
        },
        cb_supervisor: function (data, sender) {

            var status = {
                0: "success",
                1: "info",
                2: "warning",
                3: "danger"
            };

            var cont = $("#supervisor_badge");
            console.info(data);
            var html=[];
            $.each(data.messages, function (k,v) {

              if(k!=0)
              {
                  html.push($("<span>",{
                      class:"label label-"+status[k],
                      html: [v.length , "<i class='fa fa-exclamation-triangle'></i>"," "]
                  }))
              }

            });
            cont.html(html);
            return data.response;

        },
        bs_supervisor_repair: function (settings,sender) {
            return confirm("Htaccess files will be overwrited! Continue? ")

        },
        clearcache:function(data,sender){
            if(data.success)
            {
                sender.toggleClass('btn-default btn-success').html("OK");
            }
        },
        cb_title:function(data,sender){
            if(data.success)
            {

                cms.modelLang=data.model;
                var link=$("#contentlang-link_rewrite"),
                    metatitle=$("#contentlang-meta_title"),
                    menu=$("#contentlang-menu_text"),
                    thumbtext=$("#"+cms.model.id),
                    liveviewlink=$('#submitLiveview'),
                    regExp=/New(\s|-)Page(\s|-)[\d]{1,3}/i;

                if(data.model.title.match(regExp) ==null)
                {
                    if(link.val().match(regExp) != null)
                    {
                        link.val(data.model.title).trigger('blur')
                    }
                    if(metatitle.val().match(regExp) !=null)
                    {
                        metatitle.val(data.model.title).trigger('blur')
                    }
                    if(menu.val().match(regExp) != null)
                    {
                        menu.val(data.model.title).trigger('blur')
                    }
                    if(thumbtext.html().match(regExp) != null)
                    {
                        thumbtext.html(data.model.title)
                    }
                    if(liveviewlink.data('url').match(regExp) != null)
                    {
                        var nUrl=liveviewlink.data('url').split("/");
                        var l=nUrl.length - 1;
                        nUrl[l]=link.val()+'.html';

                        var res= nUrl.join('/');
                        liveviewlink.data('url',res);
                    }
                }

            }
        },
        cb_changeusername:function(data,sender){
            var un=$('#username').val().charAt(0).toUpperCase() + $('#username').val().substr(1);
            $('#logout_btn').html(un+' (Logout)');
        },
        bs_changepass:function(settings,sender){
            if ($('#new_password_confirm').val().trim() == $('#new_password').val().trim()) {
                $('#not_match_err').addClass('hidden');
                //cp.sendAjax(senderData);
            } else {
                $('#not_match_err').removeClass('hidden');
                return false;
            }
        },
        bs_unlockfields:function(settings,sender){
            $('.icon_pass_access').addClass('hidden');
            $('#unlock_pass_loader').removeClass('hidden');
        },
        cb_unlockfields:function(data,sender){
            console.log('cb_unlockfields');
            $('.select_user_field').each(function(k,v){
                var field=$(v).data('field').trim();
                $(v).off("click").click(function(e){
                    //console.log(field);
                    //console.log('$(\'#change_'+field+'_form\').toggleClass(\'hidden\')');
                    $('#change_'+field+'_form').toggleClass('hidden');
                });
            });
            $('#unlock_pass_loader').addClass('hidden');
            $('.icon_pass_access').removeClass('hidden').removeClass('fa-lock').addClass('fa-unlock');
            $('.select_user_field').removeClass('user_field_locked');
        },
        cb_menutext:function(data,sender){
            if(data.success)
            {
                cms.modelLang=data.model;
                cms.toggleMenuStatus();
                //cms.setPageHeaderMenuText($("#contentlang-menu_text").val());
            }
        },
        cb_menutitle:function(data,sender){
            if(data.success)
            {
                cms.modelLang=data.model;
            }
        },
        cb_metatitle:function(data,sender){
            if(data.success)
            {
                cms.modelLang=data.model;
            }
        },
        cb_metadescription:function(data,sender){
            if(data.success)
            {
                cms.modelLang=data.model;
            }
        },
        cb_webname:function(data,sender){
            $('#web_name_h1').text(data.value);

        },
        cb_active:function(data,sender){
            cms.model=data.model;
            $("#menuItem_"+data.model.id).toggleClass("mn_page_unpublished")
        },
        cb_addlang:function(data,sender){
            $('.lang_alert').addClass('hidden');
            if (data.already_installed) {
                $('#alert_already_installed').removeClass('hidden');
            } else {
                $('#alert_successfully_installed').removeClass('hidden');
                $('<li>', {
                    class: 'list-group-item',
                    html: [$('<input>', {
                        type: 'radio',
                        id: 'lang_' + data.id,
                        class: 'langs',
                        name: 'select-lang',
                        value: data.id,
                        'data-info': data.iso,
                        click: function (e) {
                            var id = $(e.currentTarget).attr('id');
                            id = id.replace('lang_', '');
                            $('#lang_' + id).prop('checked', true);
                            JSBlocks.lang = parseInt(id);

                            var c = $('#selectLang').attr('class');
                            $('#selectLang').removeClass(c).addClass('flag flag_' + data.id + '  mn_dropdown open');

                            cHtml.drawCms();
                            cp.setLangFields();
                        }
                    }), $('<label>', {
                        for: 'lang_' + data.id,
                        html: [$('<div>', {
                            class: 'flag flag_' + data.iso
                        }), data.name]

                    })
                    ]
                }).appendTo('#selLang');

                $('<li>', {
                    id: 'lang_item_' + data.id,
                    class: 'list-group-item',
                    html: [
                        $('<span>',{
                            class:'mn_ajax btn btn-xs btn-danger langtrash-pull',
                            html:$('<i>',{
                                class:'glyphicon glyphicon-trash'
                            }),
                            'data-action':'language/delete',
                            'data-callback':'cb_deletelang'
                        }).data("info",{id:data.id}),
                        $('<label>', {
                            class: 'eSimple_label',
                            for: 'setlang_' + data.id,
                            html: data.name
                        }),
                        $('<div>',{
                            class:'onoffswitch pull-right',
                            html:[
                                $('<input>',{
                                    type:'checkbox',
                                    id:'setlang_' + data.id,
                                    class:'mn_ajax onoffswitch-checkbox',
                                    name:'active_lang',
                                    value:true,
                                    checked:true,
                                    'data-action':'language/toggleactive',
                                    'data-callback':'cb_toggleactivelang'
                                }).data("info",{id:data.id}),
                                $('<label>', {
                                    class: 'onoffswitch-label',
                                    for: 'setlang_' + data.id
                                })
                            ]})
                    ]//end li html
                }).appendTo('#lang_list');
                //cp.unbindDelegateEvents(cp.containers.sp_lang_settings);
                //cp.delegateEvents(cp.containers.sp_lang_settings);
                cp.delegateEvents();
            }

        },
        cb_toggleactivelang:function(data,sender){

            var el=$('#lang_item_'+data.id).find('span');
            if(data.status==0){
                $('#tlang_'+data.id).remove();
                $('#lang_'+data.id).closest('li').remove();
            }else{

                $('<li>',{
                    class:'list-group-item',
                    html:[$('<input>',{
                        type:'radio',
                        id:'lang_'+data.id,
                        class:'langs',
                        name:'select-lang',
                        value:data.id,
                        'data-info':data.iso,
                        click:function(e){
                            var id = $(e.currentTarget).attr('id');
                            id = id.replace('lang_', '');
                            $('#lang_'+id).prop('checked',true);
                            JSBlocks.lang = parseInt(id);

                            var c = $('#selectLang').attr('class');
                            $('#selectLang').removeClass(c).addClass('flag flag_'+data.iso+'  mn_dropdown open');

                            cHtml.drawCms();
                            cp.setLangFields();
                        }
                    }),$('<label>',{
                        for:'lang_'+data.id,
                        html:[$('<div>',{
                            class:'flag flag_'+data.iso
                        }),data.name]

                    })
                    ]
                }).appendTo('#selLang');

                cms.addCmsTranslatedLangs();

            }
        }

    },

    containers: {

        //Container that have delegated

        general_settings_tab: '#general_settings',
        sp_general_theme: '#sp_general_theme',
        sp_general_setings: '#sp_general_settings',
        sp_block_settings: '#sp_block_settings',
        sp_lang_settings: '#sp_language_settings',
        sp_social: '#sp_social_settings',
        sp_supervisor: '#sp_supervisor',
        page_settings_tab: '#page_settings',
        published_btn: '#published_btn',
        page_header: '#page_header',
        sp_menu: '#sp_menu',
        sp_seo: '#sp_seo',
        sp_theme: '#sp_theme',
        sp_delete: '#sp_delete_page_confirm',
        menu_sub_options: '#menu_sub_options',
        settings: '#settings',
        panels: '#subPanels',
        liveview_btn: '#submitLiveview',
        lang_supercont: '#lang-group',
        lang_cont: '#selectLang',
        sp_lang: '#lang_subpanel',
        save_supercont: '#save_btn',
        save_cont: '#showSaveButton',
        save_btn: '#submitAddcms'
    },
    refreshThumbs: false,
    /**
     * Log messages of this class when debug is enabled
     * @param msg
     * @param type
     */
    log: {
        info: function () {
            var self = this;
            $.each(arguments, function (k, v) {
                self.log(v, "info");
            })
        }, warn: function () {
            var self = this;
            $.each(arguments, function (k, v) {
                self.log(v, "warn");
            })
        },
        error: function () {
            var self = this;
            $.each(arguments, function (k, v) {
                self.log(v, "error");
            })
        },
        log: function (msg, type) {
            if (cp.options.debug) {
                if (!type) {
                    type = "log";
                }
                if (typeof msg == "string") {

                    console[type]("%c[MENA-AJAX]" + (type == "error" ? "%c" : "") + msg, 'background:#bada55; color: #222', (type == "error" ? 'background:#f00; color: #fff' : ""))
                } else {
                    console.group("%c[MENA-AJAX · Detailed object]", 'background:#bada55; color: #222');
                    console[type](msg);
                    console.groupEnd();
                }

            }
        }
    },
    init: function () {
        var body = $("body");
        body.delegate('.hasSubpanel', "click", function (e) {
            cp.openSubpanel(this);

        });
        //Silviapanel
        body.delegate('.hasSubpanelLevel', "click", function (e) {
            cp.openSubpanelLevel(this);
        });
        body.delegate('.btnAtras', "click", function (e) {
            e.stopPropagation();
            cp.clearPanel(this);
        });


        body.delegate('#btnAddBlock', "click", function (e) {
            e.stopPropagation();
            $('#upload_block_form').toggleClass('hidden');
        });
        body.delegate('#btnAddTheme', "click", function (e) {
            e.stopPropagation();
            $('#upload_theme_form').toggleClass('hidden');
        });
        body.delegate('#btnAddLang', "click", function (e) {
            e.stopPropagation();
            $('#add_lang_form').toggleClass('hidden');
        });
        body.delegate('#addLangSelect', "change", function (e) {
            e.stopPropagation();
            var value = $(this).val();
            $('#addLangBtn').data('info', {'value': value});
        });
        $('#addLangSelect').select2();//@todo: El panel que se crea de búsqueda de select2 no reacciona al click fuera del subpanel y se queda abierto.

        //**********//
        cp.delegateEvents();
    },
    _haveFixedCallback: function (name) {
        return typeof this.fixedCallbacks[name] == "function";
    },


    delegateEvents: function () {
        $(".mn_ajax").off("click blur change");
        this.log.info("Off mn_ajax events");

        $("#delpage_no").click(function (e) {
            e.preventDefault();
            cp.clearPanel(this);
        });

        $('input[type=radio].mn_ajax, span.mn_ajax, a.mn_ajax, li.mn_ajax').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            cp.sendToAction(this)
        });
        $('input[type=text].mn_ajax, textarea.mn_ajax').on('blur', function (e) {
            e.preventDefault();
            e.stopPropagation();
            cp.sendToAction(this)
        });
        $('input[type=checkbox].mn_ajax, select.mn_ajax').on('change', function (e) {
            e.preventDefault();
            e.stopPropagation();
            cp.sendToAction(this)
        });
    },
    //unbindDelegateEvents: function (cont) {
    //    //$(cont).undelegate('input[type=radio].mn_ajax, span.mn_ajax,a.mn_ajax', "click").undelegate('input[type=text].mn_ajax, textarea.mn_ajax', "blur").undelegate('input[type=checkbox].mn_ajax', "change");
    //    $(".mn_ajax").off("click blur change");
    //
    //
    //},

    clearPanel: function (target) {
        if ($(target).closest(".mn_slided_subpanel").length) {
            $(target).closest(".mn_slided_subpanel").removeClass('mn_slided_subpanel');
            $('.without_height').removeClass('without_height');

        } else {
            $(target).closest('.mn_float_panel').toggleClass('mn_slided');
            //Retrasamos el evento para ocultar para dar tiempo a la transición css
            setTimeout(function () {
                $(".sub_panel").addClass('hidden');

            }, 500);
        }

    },
    openSubpanel: function (target) {
        $(".sub_panel").addClass('hidden');
        var sp = $(target).data('subpanel');

        $('#' + sp).removeClass('hidden');
        //Retrasamos el evento de mostrar para dar tiempo a la transición css
        var a = target;
        setTimeout(function () {
            $(a).closest('.mn_float_panel').toggleClass('mn_slided');
            if ($('#' + sp).find('.mn_scrollbar').length > 0) {
                setTimeout(function () {
                    $('#' + sp).find('.mn_scrollbar').mCustomScrollbar("update");
                }, 500);
            }

        }, 150);
    },
    //Silviapanel
    openSubpanelLevel: function (target) {

        var sp = $(target).data('subpanel');

        var p = $(target).data('parent');
        $('#' + sp).removeClass('hidden');

        //Retrasamos el evento de mostrar para dar tiempo a la transición css
        var a = target;
        setTimeout(function () {
            $(a).closest('.mn_float_panel').toggleClass('mn_slided_subpanel');
            if ($('#' + sp).find('.mn_scrollbar').length > 0) {
                setTimeout(function () {
                    $('#' + sp).find('.mn_scrollbar').mCustomScrollbar("update");
                }, 500);

            }
            /*Silviapanel-->para redimensionar sin ocultar el panel que ha llamado al tercer panel*/
            $('#' + p).addClass('without_height');
        }, 150);
    },
    setLangFields: function () {
        var self=this;
        var isnews=window.location.hash=='#news';
        var data = {
                id: (isnews?0:cms.model.id),
                work_lang: JSBlocks.lang,
                //r:'content/setlang'
            },
            self = this;
        //@todo: Use common ajax engine instead this custom function
        var senderData = {
            data: data,
            url: baseDir + "/index.php?r=content/setlang",
            error: function (jqXHR, textStatus, errorThrown) {
                $(cp.containers.lang_cont).attr('tooltip', jqXHR.responseText).addClass('mn_warning');
            },
            success: function (data) {
                self.log.warn("llega", data);

                if (data.success) {

                    self.log.warn(data);
                    //@todo: Check cms attachevents;
                    cms.attachEvents();
                    JSBlocks.blocks.news.getexistingtags();
                    if(!isnews) {
                        cms.modelLang = data.modelLang;
                        if (typeof(data.availablePages) != 'undefined') {
                            cms.availablePages = JSON.parse(data.availablePages);
                            JSBlocks.refreshAvailablePages();
                        }
                        $(cp.containers.page_header).find('h1').text('Update Content:  ' + data.modelLang.title);
                        $('#contentlang-title').val(data.modelLang.title);
                        $('#contentlang-menu_text').val(data.modelLang.menu_text);
                        $('#contentlang-link_rewrite').val(data.modelLang.link_rewrite);
                        $('#contentlang-meta_title').val(data.modelLang.meta_title);
                        $('#contentlang-meta_description').val(data.modelLang.meta_description);
                        var url = $('#submitLiveview').data('url');
                        //url=url.replace(/\/[a-zA-z1-9-]+.html/,'/'+data.urlPrefix+data.modelLang.link_rewrite+'.html');
                        url = url.replace(/(\/[a-zA-Z]{2})?\/[a-zA-z1-9-]+.html/, '/' + data.urlPrefix + data.modelLang.link_rewrite + '.html');
                        $('#submitLiveview').data('url', url);
                        cms.toggleMenuStatus();
                    }else{
                        $('#showtags').html('').html(data.tagManagementHtml);
                        news.refreshSuggestions(data.suggestions);
                        news.bindUpdateTagEvent();
                        cp.delegateEvents();
                    }

                }
            }
        };
        cp.sendAjax(senderData);

    },

    /**
     * Appends error information to sender item or its parent li if exist.
     * If sender is an input sets previous value
     * @param o jQuery | object Sender of action
     * @param response string jqXHR.responseText
     * @private
     */
    _appendAjaxError: function (o, response) {
        var target;
        if (o.closest('li').length == 0) {
            target = o;
        } else {
            target = o.closest('li');
        }
        target.attr('tooltip', response).addClass('mn_warning');
        // If is input return to previous value
        //if (o.is('input[type=text]'))
        //    o.val(o.data('currentval'));

    },

    /**
     *
     * Process an inline ajax action.
     * An element can have defined:
     * * data-action        The ajax target action
     * * data-info          The parameters needed for action. Json encoded array
     * * data-target        Jquery selector to replace html with received in data.response or callback response (if callback configured)
     * * data-callback      Name of fixedCallback to execute on success | Inline javascript (as text) function to execute when request is success
     * * data-beforesend    Name of fixedCallback to execute on beforeSend | Inline javascript (as text) function to execute when request is success
     * @param sender
     */
    sendToAction: function (sender) {
        var config = {};
        if (cp.validate(sender)) {
            if (config = this._prepareAjaxConfiguration($(sender))) {
                this.sendAjax(config);
            } else {
                this.log.error("Could not retrieve configuration for ajax action")
            }
        } else {
            this.log.error("mn_Ajax operation not allowed");
        }
    },

    /**
     * Prepares all configuration needed for ajax request.
     * @param sender jQuery.data
     * @returns {{type: string, dataType: string, headers: {cache-control: string}, data: {data: jQuery.data, menacsrf: *}, url: string, beforeSend: Function, success: Function, error: Function}}
     * @private
     */
    _prepareAjaxConfiguration: function (sender) {
        this.log.info("Preparing ajax configuration");
        var senderData = $.extend(true, {}, sender.data()),
            self = this;

        if (senderData.info) {
            if (typeof senderData.info.value == "undefined") {
                senderData.info.value = cp._getInputValue(sender);
            } else {
                this.log.warn("Senderdata.info.value was defined in element property", "The id of element was" + sender.attr('id'));
            }
        } else {

            senderData.info = {
                value: cp._getInputValue(sender)
            };
        }
        return {

            data: senderData.info
            ,
            url: baseDir + "/index.php?r=" + senderData.action,
            beforeSend: function (jQxhr, settings) {

                self.log.info("Executing beforeSend callback", settings);
                if (!sender.is("li")) {
                    sender.closest("li").removeAttr('tooltip').removeClass('mn_warning');
                }
                sender.removeAttr('tooltip').removeClass('mn_warning');

                if (senderData.beforesend) {
                    if (cp._haveFixedCallback(senderData.beforesend)) {
                        return cp.fixedCallbacks[senderData.beforesend](settings, sender);

                    } else {
                        var fn = new Function("settings", "sender", senderData.beforesend);
                        return fn(settings, sender);
                    }

                }
            },
            success: function (data) {

                cp.log.info(data);
                if(data.success==false)
                {
                    cp._appendAjaxError(sender, data.error);
                    return;
                }
				if (typeof(data.availablePages) != 'undefined') {
                   cms.availablePages=JSON.parse(data.availablePages);
                   JSBlocks.refreshAvailablePages();

                }

                self.log.info("Executing SUCCESS callback", data);
                if (senderData.callback) {
                    if (cp._haveFixedCallback(senderData.callback)) {
                        var result = cp.fixedCallbacks[senderData.callback](data, sender);
                        if (senderData.target && typeof result != "undefined") {
                            $(senderData.target).html(result)
                        }
                    } else {
                        var fn = new Function("data", "sender", senderData.callback);
                        fn(data, sender);
                    }

                } else {
                    if (senderData.target && typeof data.response != "undefined") {
                        $(senderData.target).html(data.response)
                    }
                }

                setTimeout(function () {
                    cms.updateScrollbars();
                    cp.delegateEvents();
                    cms.attachEvents();


                }, 300);


            },
            error: function (jqXHR, textStatus, errorThrown) {
                cp._appendAjaxError(sender, jqXHR.responseText);
            }


        };


    },

    /**
     * Sends ajax request.
     * @param config
     */
    sendAjax: function (config) {
        $.extend(true, config, {
            headers: {"cache-control": "no-cache"},
            async: true,
            cache: false,
            type: 'POST',
            dataType: 'json',
            data: {
                menacsrf: csrfToken
            }
        });

        $.ajax(config).fail(function (jqXHR, textStatus, errorThrown) {
            cp.log.error(jqXHR, textStatus, errorThrown);
        });
        this.log.info("Action $.ajax executed", "-----------", "Parameters: ", config, "---------", "sendAjax trace: ");
        if (this.options.debug) console.trace();
    },

    /**
     * Autodetect and returns the value of an input
     * @param el jQuery valid Selector
     * @returns {*} Value of the item.
     * @private
     */
    _getInputValue: function (el) {
        if(typeof(el.data('field'))!='undefined'){
          el=$(el.data('field'));
        }
        switch ("Property name", el.prop("tagName")) {
            case "INPUT":
                if (el.attr('type') == "checkbox") {
                    //this.log(["Type checkbox", el.val()]);
                    return el.prop("checked") ? "1" : null;
                } else {
                    return el.val();
                }
                break;

            case "TEXTAREA":
            case "SELECT":
                return el.val();
                break;
            default:
                this.log.warn("An mn_ajax element does not match form input. Tag name of element was -" + el.prop('tagName') + "-");
                return false;
                break;
        }
    },


    saveContent: function () {
        if (cp.refreshThumbs != false) {
            clearTimeout(cp.refreshThumbs);
        }
        if (cms.options.autosave) {
            cp.refreshThumbs = setTimeout(function () {
                if (cms.options.hasChanges) {
                    if (!proB.open) {
                        cms.options.hasChanges = false;
                        cms.min.refreshThumbnail(cms.model.id, $('#contentlang-link_rewrite').val());
                    } else {
                    }
                }
            }, 4000);
        } else {
            cms.options.autosave = false;
        }

        var value = btoa(encodeURI(JSON.stringify(cms.data)));
        var senderData = {
            data: {id: parseInt(cms.model.id), value: value},
            //action: 'content/savecontent',
            url: baseDir + "/index.php?r=content/savecontent",
            success: function (data) {
                cms.addCmsTranslatedLangs();
            }
        };

        cp.sendAjax(senderData);
    },


    /**
     * Validate sender value before submit ajax.
     * @param sender
     * @returns boolean
     */
    validate: function (sender) {
        if ($(sender).is("input[type=text]") || $(sender).is("textarea")) {

            var field = $(sender).attr('id');
            var i = field.indexOf('-');
            i = i + 1;
            var f = field.substring(i);
            var v = $(sender).val().trim();

            var limit = '';
            var search = [];
            var filter = '';
            var empty = false;
            switch (f) {
                case 'meta_title':
                    limit = 140;
                    break;
                case 'title':
                    limit = 256;
                    break;
                case 'meta_description':
                    limit = 256;
                    break;
                case 'menu_text':
                    limit = 128;
                    break;
                case 'link_rewrite':
                    limit = 128;
                    break;
                case 'web_name':
                    limit = 50;
                    break;
                case 'social_facebook':
                    //search.push('https://www.facebook.com/');
                    //search.push('https://www.fb.com/');
                    empty = true;
                    break;
                case 'social_twitter':
                    //search.push('https://twitter.com/');
                    empty = true;
                    break;
                case 'social_instagram':
                    //search.push('https://www.instagram.com/');
                    empty = true;
                    break;
                case 'social_pinterest':
                    //search.push('https://www.pinterest.com/');
                    empty = true;
                    break;
                case 'social_youtube':
                    //search.push('https://www.youtube.com/channel/');
                    //search.push('https://www.youtube.com/user/');
                    //search.push('https://youtu.be/');
                    empty = true;
                    break;
                case 'contact_address':
                    empty = true;
                    break;
                case 'contact_phone':
                    empty = true;
                    //filter=/^\d{9}$/;
                    break;
                case 'contact_mobilephone':
                    empty = true;
                    //filter=/^\d{9}$/;
                    break;
                case 'contact_email':
                    empty = true;
                    //filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/;
                    break;
                case 'contact_openingtimes':
                    empty = true;
                    break;
                default:
                    return true;
                    break;
            }
            if (empty && v == '') {
                return true;
            } else {
                if (limit != '') {
                    if (v.length > limit) {
                        $(sender).addClass('fieldError');
                        if ($(sender).is("input[type=text]")) {
                            //var oldVal= $(sender).data('currentval');
                            //$(sender).val(oldVal);
                        }
                        return false;
                    } else {
                        $(sender).removeClass('fieldError');
                        return true;
                    }
                }
                if (search.length > 0) {
                    var find = false;
                    $.each(search, function (k, val) {
                        if (v.search(val) > -1) {
                            find = true;
                        }
                    });
                    return find;
                }
                if (filter != '') {
                    return filter.test(v);

                }
                return true;
            }

        } else {
            return true;
        }

    }

};
