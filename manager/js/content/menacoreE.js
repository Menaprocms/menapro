/**
 * Created by Xenon on 11/06/2015.
 */

$(document).ready(function () {
	cms.availablePages=availablePages;
    cms.fillLangArray();
    cms.init();
});


var cms = {
    options: {
        autosave: false,
        tiny: 0,
        cms_id: 0,
        proBox: '',
        debug: false,
        last_copy: '',
        work_in: {row: 0, col: 0, lang: 0},
        social: {type: ''},
        uploader: '',
        hasChanges: false,
        last_theme: '',
        rowOptionsDefault: {
            'inverse': 'Inverse',
            'striking': 'Striking',
            'inforow': 'Inforow',
            'eCustom': 'Use Custom Class'
        },
		availablePages:'',
        rowOptions: {},
        routes: {
            templates: '../data/content/json_templates.json',
            icons: '../data/content/icons.json',
            themes: '../themes/'

        },
        _langs: []
    },
    model: {},
    modelLang:{},
    data: {
        menu_text: "",
        published: false,
        cms_id: 0,
        structure: [],
        theme: '',
        trash: {
            elements: []
        }
    },
    getIsoCode: function () {
        if (JSBlocks.lang == default_lang) return "";

        return langs.filter(function (item) {
                if (item.id == JSBlocks.lang) return true
            })[0].iso + "/";
    },
    /**
     * Toggles active status in pagesbar.
     * @param id integer Id of model
     */
    toggleActivePage: function (id) {
        var curLi = $("#menuItem_" + id);
        curLi.addClass('mn_page_active');
        $(".mn_pages li").not(curLi).removeClass('mn_page_active');
    },
    /**
     * Thumbnails class.
     */
    min: {

        refreshThumbnail: function (id, route) {
            var iframeSelector = 'snapshot_iframe';
            if (!$("#" + iframeSelector).length) {
                //console.error("no estaba instanciado")

                var iframe = $("<iframe>", {
                    class: 'thumb_iframe',
                    id: iframeSelector,
                    //scrolling: "no",1
                    frameborder: 0,
                    allowtransparency: true,
                    allowfullscreen: true
                });


                var div = $('<div>', {
                    id: 'iframe_cont',
                    class: 'iframe_cont',
                    html: iframe
                });

                $('body').prepend(div);


            } else {
                var iframe = $("#" + iframeSelector);
                var div = $("iframe_cont");


            }

            var addressToLoad = baseFrontDir + '/' + cms.getIsoCode() + route + '.html?liveview&thumb=true&token=' + uTok;// + '&_lang=' + default_lang;
            iframe.attr("src", addressToLoad);
            div.css("top", $(window).scrollTop());

            $(window).scroll(function(e){
                div.css("top", $(window).scrollTop());

            });
            $('#snapshot_iframe').load(function (e) {

                cms.min.thumb(id);
            });
        },
        thumb: function (id) {
            $('#iframe_cont').css("top", $(window).scrollTop()).removeClass("corner");

            html2canvas($('#snapshot_iframe'), {
                onrendered: function (canvas) {
                    var extra_canvas = document.createElement("canvas");
                    extra_canvas.setAttribute('width', 400);
                    extra_canvas.setAttribute('height', 333);
                    var ctx = extra_canvas.getContext('2d');

                    ctx.drawImage(canvas, 0, 0, canvas.width, canvas.height, 0, 0, 400, 333);
                    var dataURL = extra_canvas.toDataURL();
                    setTimeout(function () {
                        cms.min.sendDataThumb(dataURL, id);
                    }, 500);

                    $('#iframe_cont').remove();
                    //$('#iframe_cont').addClass("corner");
                }
            });
        },
        sendDataThumb: function (data, idpage) {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: baseDir + '/index.php?r=content/pagethumb',
                async: true,
                cache: false,
                headers: {"cache-control": "no-cache"},
                data: {
                    menacsrf: csrfToken,
                    image: data,
                    id: idpage
                },
                beforeSend: function (a, b, c) {

                },
                success: function (data) {
                    if (data.success) {
                        $('#pagethumb_' + idpage).addClass('refresh');
                        $('#pagethumb_' + idpage).attr('src', data.img_src + "?" + new Date().getTime());
                        setTimeout(function () {
                            $('#pagethumb_' + idpage).removeClass('refresh');
                        }, 200);
                    }
                }
            });

        }
    },

    /**
     * pagesBar class.
     * Used to sort, draw and interact with pages column∫
     */
    pb: {
        reorderItems: function (items) {
            var data = [];
            cms.pb.conts = [];
            $.each(items, function (k, v) {
                if (v.id != null) {
                    var id_parent = 0;
                    if (v.parent_id != null) {
                        id_parent = v.parent_id;
                    }
                    if (typeof(cms.pb.conts[id_parent]) == 'undefined') {
                        cms.pb.conts[id_parent] = 1;
                    } else {
                        cms.pb.conts[id_parent]++;
                    }

                    var d = {
                        id: v.id,
                        id_parent: id_parent,
                        position: cms.pb.conts[id_parent]
                    };
                    data.push(d);
                }
            });
            cms.pb.sendItemsToUpdate(data);
        },

        sendItemsToUpdate: function (items) {
            var config={
                data:{pages:items},
                url:baseDir+"/index.php?r=content/pagesorder"

            };
            cp.sendAjax(config);
        }
    },

    fillLangArray: function () {
        $.each(langs, function (k, v) {
            var idL = v.id;
            var isoL = v.iso;
            cms.options._langs.push({id: idL, iso: isoL});
        });
    },

    attachGeneralSettingsEvents: function () {
        $('#web_name').keyup(function (e) {

            var txt = $('#web_name').val().trim();
            var cont = 50 - txt.length;
            $('#countCharWebName').html(cont);
        });
    },
    attachEvents: function () {
        
        if (cms.options.last_theme != cms.data.theme) {
            new Promise(
                function (resolve, reject) {
                    cms.checkRowOptions(cms.data.theme, resolve);
                }).then(function () {
                    cms.options.last_theme = cms.data.theme.toString();
                    cms.drawRowOptions();
                    cms._attachEvents();
                });
        } else {
            cms._attachEvents();
        }
    },
    _attachEvents: function () {
        cms.checkStorage();


        if (cms.model.id != 0) {
            var id = cms.model.id;
            if (localStorage.getItem('cmsData-' + id) && typeof(action) != 'undefined') {
                if (localStorage.getItem('cmsData-' + id) != JSON.stringify(cms.data)) {
                    $('#confirm-load').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    cHtml.drawCms();
                }
            } else {
                cHtml.drawCms();
            }
        } else {
            if (localStorage.getItem('cmsData') && typeof(action) != 'undefined') {
                $('#confirm-load').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            } else {
                cHtml.drawCms();
            }
        }


        $(cp.containers.save_cont).click(function (e) {
            e.preventDefault();
            e.stopPropagation();

            cms.saveLocalStorage();
            cms.sendDataToSave();

        });



        $('#contentlang-meta_description').keyup(function (e) {
            var txt = $('#contentlang-meta_description').val().trim();
            var cont = 256 - txt.length;
            $('#countCharMetadescription').html(cont);
        });
    },
    init: function () {
        //console.clear();
        console.log("%cMENAPROcms",'color:#00C8B9; 	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:50px; font-weight:100');
        if(this.options.debug) console.log("%c Debug is enabled",'color:#00C8B9; 	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:14px; font-weight:100');
        console.log("%c----------",'color:#00C8B9; 	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; font-size:50px; font-weight:100');
        attachDropdownEvent();


        if(typeof (themeRowOptions)!= 'undefined' && !$.isEmptyObject(themeRowOptions)){
            cms.options.rowOptions=themeRowOptions;

        }

        setTimeout(function () {
            JSBlocks.init();
        }, 5);

        cms.attachGeneralSettingsEvents();
        //************************************Added from menatema.js
        $('html').click(function (e) {
            //@todo: check if is correct to close fancybox ever you click outside

            if (typeof($.fancybox) != 'undefined') {
                $.fancybox.close();
            }

            var tbConfig = $(".tb_config");

            if (!$(e.target).closest('.mn_dropdown').length && !$(e.target).closest('.select2-search').length) {
                $(".sub_panel").addClass('hidden');

                if (tbConfig.find('.mn_float_panel').hasClass('mn_slided')) {
                    $(".tb_config").find('.mn_float_panel').toggleClass('mn_slided');
                }
                $('.mn_dropdown').removeClass('open');

            } else if (!$(e.target).closest('#settings').length && !$(e.target).closest('.sub_panel').length && !$(e.target).closest('.select2-search').length) {
                if (tbConfig.find('.mn_float_panel').hasClass('mn_slided')) {
                    $(".tb_config").find('.mn_float_panel').toggleClass('mn_slided');
                }
                if (tbConfig.find('.mn_float_panel').hasClass('mn_slided_subpanel')) {
                    tbConfig.find('.mn_float_panel').toggleClass('mn_slided_subpanel');
                    $(".without_height").removeClass("without_height");
                }
            }

            if (!$(e.target).closest('#sp_myaccount').length) {
                //To lock user parameters ever you click out of user panel
                $('.icon_pass_access').removeClass('fa-unlock').addClass('fa-lock');
                $('#password_access').val('');
                $('.user_form').addClass('hidden');
                $('.select_user_field').addClass('user_field_locked');
            }

        });
        setTimeout(function () {
            $(".mn_pages").removeClass('closed')

        }, 200);
        //Init scrollbars
        $(".mn_scrollbar").mCustomScrollbar({
            setTop: '35px',
            theme: "dark-thin",
            autoExpandScrollbar: true,
            mouseWheel: {preventDefault: true},
            scrollInertia: 1
        });
        //Init others

        attachDropdownEvent();
//    Slider
        $("#mn_slider").bxSlider({
            controls: false,
            auto: true
        });
        //************************************Added from menatema.js
        // ************************************Added from pagesbar.js
        $('.pagesortable').nestedSortable({

            items: 'li.mn_page',
            placeholder: 'placeholder',
            toleranceElement: '> a, > div',
            listType: 'ul',
            maxLevels: 3,
            tabSize: 83,

            relocate: function () {
                var arraied = $('ul.pagesortable').nestedSortable('toArray', {startDepthCount: 0});
                cms.pb.reorderItems(arraied);
            }
        });
        // ************************************Added from pagesbar.js


        //Outside click for rowOptions panel
        $(document).mouseup(function (e) {
            var container = $(".eRowOptions");
            if (!container.is(e.target)
                && container.has(e.target).length === 0 && !$('.eOptionsBtn').is(e.target)) {
                var row = $('#row_' + JSBlocks.row);
                row.removeClass('config');
            }
        });

        //Outside click event for structures row
        $(document).click(function (event) {
            if ($('.eRowCreate').hasClass('eStructure')) {
                if (!$(event.target).closest('.eRowCreate').length) {
                    $('.eRowCreate').removeClass('eStructure');
                }
            } else {
                return true
            }
        });

        //$('.cms-content').closest('.form-group').hide();
        $('#show_trash_btn').click(function (e) {
            cHtml.clearProBox('proBox-trash');
            cHtml.openProBox('trash');
        });


        //@todo: resume all code before

        cms.initTypesClick();
        cms.initChangeEvents();
        cms.initKeyupEvents();
        cms.initSortables();
        cms.initClicksEvents();
        cms.checkHash();

        //Disable all preview links
        $('#cms-content').delegate('a', 'click', function (e) {
            e.preventDefault();
        });

    },
    initChangeEvents: function () {

    },
    initKeyupEvents: function () {





    },
    initClicksEvents: function () {

        $('#btn-switch').click(function () {
            if ($(this).hasClass('eActive')) {
                $(this).removeClass('eActive');
                $('#active_off').attr('checked', 'checked');
            } else {
                $(this).addClass('eActive');
                $('#active_on').attr('checked', 'checked');
            }
        });
        $('.eColumns').delegate('.col-xs-2', 'click', function (e) {
            cHtml.options.tempScrollTop = $(window).scrollTop();
            var row = '';
            if (typeof (cms.data.structure[JSBlocks.lang]) == 'undefined' || cms.data.structure[JSBlocks.lang] == null) {
                row = 0;
            } else {
                row = cms.data.structure[JSBlocks.lang].length;
            }
            var p = $(this).data('col').toString().split(',');
            cms.createCmsJson(p, row, 0);
        });

        $('.eRowCreate').click(function (e) {
            $(e.currentTarget).addClass('eStructure');
        });
        $('.ProBoxBackButton').click(function (e) {
            $(window).scrollTop(cHtml.options.tempScrollTop);
            cHtml.clearProBox('proBox-select');
        });

        $('.toTrash').click(function (e) {
            var row = JSBlocks.row;
            var col = JSBlocks.col;
            cms.toTrash();
            cHtml.openSelection(row, col, JSBlocks.subrow);
        });
        $('.iframe-btn').click(function (e) {
            if (e.currentTarget.id.indexOf('-upload') > -1) {
                cms.options.uploader = 1;
            } else {
                cms.options.uploader = 0;
            }
        });

        $('ul.dropdown-menu li').find('a[href^="javascript:hideOtherLanguage"]').each(function (k, v) {
            $(v).click(function (e) {
                var langId = e.currentTarget.href;
                langId = langId.replace('javascript:hideOtherLanguage(', '');
                langId = langId.replace(');', '');
                $('.langs').removeClass('active');
                $('#lang_' + langId).addClass('active');
            });
        });
    },
    /**
     * Initialize the sortable event for rows
     */
    initSortables: function () {
//          Sortable rows
        $('#cms-content').sortable({
            placeholder: "ePlaceholder",
            handle: '.eHandler',
            start: function (event, ui) {

            },
            stop: function (event, ui) {
                var sortedIDs = $('#cms-content').sortable("toArray");
                var newArr = [];
                $.each(sortedIDs, function (k, v) {
                    if (!v.match(/gR_/)) {
                        var rIndex = v.replace('row_', '');
                        newArr.push(cms.data.structure[JSBlocks.lang][rIndex])
                    }
                });
                cms.data.structure[JSBlocks.lang] = newArr;
                cHtml.drawCms();
            }
        });

//        Fin sortable rows
    },
    initTypesClick: function () {

        $.each(JSBlocks.categories, function (k, v) {
            $("#" + k + "-group").click(function () {
                cHtml.options.tempScrollTop = $(window).scrollTop();
                cHtml.clearProBox("proBox-" + k + "-group");
                cHtml.openProBox("");
                cms.log.info("proBox-" + k + "-group");
            });
        });


    },
    checkHash:function(){
      if(window.location.hash)
      {
          var pieces=window.location.hash.split(":");
          if(pieces.length==2 && !isNaN(pieces[1]))
          {

              var id="#menuItem_" + (parseInt(pieces[1]));
              $("#sidebar-wrapper").mCustomScrollbar("scrollTo", id, {
                  //scrollEasing: "easeOut",
                  scrollInertia: 00
              });
              this.log.info("Requested page id is: "+id);

              $(id+" a").triggerHandler('click');
          }
      }
    },
    checkLangInputs: function (id, val) {
        $.each(cms.options._langs, function (k, v) {
            if ($(id + v.id).val() == '') {
                $(id + v.id).val(val);
                $('#link_rewrite_' + v.id).val(str2url(val, 'UTF-8'));
            }
        });
    },
    toTrash: function () {

        var row = JSBlocks.row;
        var col = JSBlocks.col;
        cms.trashCol(row, col);

        JSBlocks.clear();
        cHtml.drawCms();
    },
    trashCol: function (row, kcol) {
        this.log.info('trashColFunction');
        var col = JSBlocks.getCurrentTarget();//cms.data.structure[cms.options.work_in.lang][row].content[kcol];
        if (col.content != '' && col.content !=null) {
            var toTrash = $.extend(true, {}, col);
            toTrash.class = 3;
            if (typeof(cms.data.trash.elements[JSBlocks.lang]) == "undefined" || cms.data.trash.elements[JSBlocks.lang] == null) {
                cms.data.trash.elements[JSBlocks.lang] = new Array();
            }
            cms.data.trash.elements[JSBlocks.lang].push(toTrash);
            col.content = '';
        }
        col.type = '';
        this.log.info('subrowK' + JSBlocks.subrow);

        if (JSBlocks.subrow != null) {
            this.log.info('is splitted col');
            var key = (JSBlocks.subrow == 0 ? 1 : 0);
            var keep_data = cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content[key];
            var col_class = cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].class;
            cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col] = keep_data;
            cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].class = col_class;
            JSBlocks.subrow = null;
        }
        proB.hide(cHtml.clearAll);
    },
    trashRow: function (row) {
        $.each(cms.data.structure[JSBlocks.lang][row].content, function (k, v) {
            if (v.content != '') {
                var toTrash = $.extend(true, {}, v);
                toTrash.class = 3;
                if (typeof(  cms.data.trash.elements[JSBlocks.lang]) == "undefined" || cms.data.trash.elements[JSBlocks.lang] == null) {
                    cms.data.trash.elements[JSBlocks.lang] = new Array();
                }
                cms.data.trash.elements[JSBlocks.lang].push(toTrash);
            }
        });
        cms.data.structure[JSBlocks.lang].splice(row, 1);
        cHtml.drawCms();
    },
    addFromTrash: function (row, col, tk) {
        var obj = $.extend(true, {}, cms.data.trash.elements[JSBlocks.lang][tk]);
        JSBlocks.getCurrentTarget().type = obj.type;
        JSBlocks.getCurrentTarget().content = obj.content;
        JSBlocks.getCurrentTarget().htmlOptions = obj.htmlOptions;
        cms.data.trash.elements[JSBlocks.lang].splice(tk, 1);
        cHtml.drawCms();
        proB.hide();
    },
    /**
     * Creates an empty structure based on current working lang
     * @param cols
     * @param row
     * @param index
     */
    createCmsJson: function (cols, row, index) {

        if (typeof(cms.data.structure[JSBlocks.lang]) == "undefined" || cms.data.structure[JSBlocks.lang] == null) {
            cms.data.structure[JSBlocks.lang] = new Array();
        }
        if (index == 0) {
            cms.data.structure[JSBlocks.lang].push({htmlOptions: {}, content: []});
        } else {
            cms.data.structure[JSBlocks.lang].splice(index, 0, {htmlOptions: {}, content: []});
        }
        $.each(cols, function (k, v) {
            var col = {
                type: '',
                class: parseInt(v),
                content: ''
            };
            cms.data.structure[JSBlocks.lang][row].content.push(col);
        });
        cHtml.drawCms();
    },
    /**
     * Creates branch in structure for column
     * @param row
     * @param key
     * @param type
     */
    createColStructure: function (row, key, type) {
        cms.data.structure[JSBlocks.lang][row].content[key].type = type;
        cms.data.structure[JSBlocks.lang][row].content[key].content = null;
    },
    addRowHtmlOptions: function (rowClass,rowKey) {
         if(typeof rowKey == "undefined"){
            rowKey=JSBlocks.row;
        }
        //console.warn(rowClass,rowKey);
            if (cms.data.structure[JSBlocks.lang][rowKey].htmlOptions.length > 0) {
            cms.data.structure[JSBlocks.lang][rowKey].htmlOptions.rowClass = rowClass;
        } else {
            var rowClassEl = {rowClass: rowClass};
            var obj = cms.data.structure[JSBlocks.lang][rowKey].htmlOptions;
            $.extend(true,obj, rowClassEl);
        }

    },
    checkSettingsDifferences: function () {
        if (cms.model.theme != 'default') {
            $('.tb_config').addClass('modified');
            $('li[data-subpanel="sp_theme"]').addClass('modified');
        } else {
            $('.tb_config').removeClass('modified');
            $('li[data-subpanel="sp_theme"]').removeClass('modified');
        }
    },
    checkRowOptions: function (theme, resolve) {
        if (typeof(resolve) == 'undefined') {
            resolve = false;
        }
        cms.options.rowOptions = {};
        $.getJSON(cms.options.routes.themes + theme + '/rowOptions.json', function (json) {
            $.each(json, function (k, v) {
                cms.options.rowOptions[k] = v;
            });
            if (resolve !== false) {
                resolve();
            }
        }).fail(function (jqxhr, textStatus, error) {

            if (resolve !== false) {
                resolve();
            }
            //alert("Ocurrio un error al cargar la aplicación, recomendamos cargue la página de nuevo. Recomendamos navegar con google chrome");
        });
    },
    drawRowOptions: function () {
        var optionsContainers=$('#design .selHtmlOptions');
        optionsContainers.html('');

        $("<option>",{
            html:"Choose an option",
            value:0
        }).appendTo(optionsContainers);
        if (!$.isEmptyObject(cms.options.rowOptions)) {
            $.each(cms.options.rowOptions, function (k, v) {
                $('<option>', {
                    value: k
                }).html(v).appendTo(optionsContainers);
            });
        }
        $.each(cms.options.rowOptionsDefault, function (k, v) {
            $('<option>', {
                value: k
            }).html(v).appendTo(optionsContainers);
        });
    },
    /**
     * @todo: Translate messages
     * Read current menu and published status and updates selectors.
     */
    toggleMenuStatus: function () {

        if ($("#content-in_menu").is(':checked')) {
            $(cp.containers.menu_sub_options).removeClass('hidden');
        } else {
            $(cp.containers.menu_sub_options).addClass('hidden');

        }

        var html = "";
        if (!this.model.active) {
            html = "The page will not be available in menu"
        } else {
            html="The page will appear in menu as "+this.modelLang.menu_text;
            //todo: Get if parent page is enabled
            //if (this.model.parentActive) {
            //    html = "The page will appear in menu as <b>" + this.model.menu_text + "</b>";
            //} else
            //    html = "The page will not appear in menu because a parent is disabled";

        }
        $("#page_header_legend").html(html);


    },
    //setPageHeaderMenuText: function (text) {
    //    $("#page_header_menu_text").html(text);
    //},
    /**
     * Not implemented
     * Saves current content to localstorage in order to prevent data loss.
     */
    saveLocalStorage: function () {
        if (cms.model.id != 0) {
            var id = cms.model.id;
            if (!localStorage.getItem('cmsData-' + id)) {
                localStorage.setItem('cmsData-' + id, JSON.stringify(cms.data));
                var d = new Date();
                var n = d.getTime();
                localStorage.setItem('cmsTimer-' + id, n);
                if (localStorage.getItem('cmsData') == localStorage.getItem('cmsData-' + id)) {
                    localStorage.removeItem('cmsData');
                }
            } else {
                localStorage.setItem('cmsData-' + id, JSON.stringify(cms.data));
            }
        } else {
            localStorage.setItem('cmsData', JSON.stringify(cms.data));
        }
    },
    checkStorage: function () {
        var msForExpire = 24 * 60 * 60 * 1000;
        var d = new Date();
        var now = d.getTime();
        var limit = now - msForExpire;
        $.each(localStorage, function (k, v) {
            if (k.match(/cmsTimer-/)) {
                if (v < limit) {
                    var id = k.replace('cmsTimer-', '');
                    localStorage.removeItem('cmsTimer-' + id);
                    localStorage.removeItem('cmsData-' + id);
                }
            }
        });
    },

    /**
     * Log messages of this class when debug is enabled
     * @param msg
     * @param type
     */
    log: {
        info:function(){
            var self=this;
            $.each(arguments,function(k,v){
                self.log(v,"info");
            })
        },warn:function(){
            var self=this;
            $.each(arguments,function(k,v){
                self.log(v,"warn");
            })
        },
        error:function(){
            var self=this;
            $.each(arguments,function(k,v){
                self.log(v,"error");
            })
        },
        log: function (msg, type) {
            if (cms.options.debug) {
                if (!type) {
                    type = "log";
                }
                if(typeof msg=="string")
                {

                    console[type]("%c[MENA-CORE]" +(type=="error"?"%c":"") +msg,'background:#02fcd6; color: #222',(type=="error"?'background:#f00; color: #fff':""))
                }else
                {
                    console.group("[MENA-CORE · Detailed object] ");
                    console[type](msg);
                    console.groupEnd();
                }

            }
        }
    },
    /**
     * Fired on page load. Fills the languages container with available languages and current page translation languages
     */
    addCmsTranslatedLangs: function () {
        $('#translated_langs_list').html('');
        $.each(langs, function (k, v) {
            if (typeof(cms.data.structure[v.id]) != 'undefined' && cms.data.structure[v.id] != null && cms.data.structure[v.id].length>0) {
                $('<li>', {
                    class: 'translated_lang lang_' + v.id + ' flag flag_' + v.iso,
                    id: 'tlang_' + v.id,
                    data: {'iso': v.iso},
                    click: function (e) {
                        var id = $(e.currentTarget).attr('id');
                        id = id.replace('tlang_', '');
                        $('#lang_' + id).prop('checked', true);
                        JSBlocks.lang = parseInt(id);

                        var c = $('#selectLang').attr("class");
                        $('#selectLang').removeClass(c).addClass('flag flag_' + v.iso + '  mn_dropdown open');

                        cHtml.drawCms();
                        cp.setLangFields();
                    }
                }).appendTo('#translated_langs_list');
            }
        });

    },
    pageThemeCallback:function(data,sender){

        new Promise(
            function(resolve, reject) {
                cms.checkRowOptions(data.theme,resolve);
            }).then(function(){
                cms.model.theme=data.theme==theme_default?"default":data.theme;
                $("#theme_"+data.theme).prop("checked","checked");
                cms.options.last_theme= cms.data.theme;
                cms.drawRowOptions();
                cHtml.drawCms();
                cms.checkSettingsDifferences();

            });
        //cms.min.refreshThumbnail('.$model->id.',"'.$model->langFields[0]->link_rewrite.'")'
    },
    /**
     * Extended callback for loading a page
     */
    loadPageCallback: function (data, sender) {

        this.log.info("loadPageCallback");
        $.extend(true, cms.model, data.model);
        $.extend(true, cms.modelLang, data.modelLang);
        cms.options.autosave = parseInt(data.autosave);
        $(".superMedinapro").removeClass('closed hidden');
        var currentPanel = cms._getCurrentPanel();

        this.toggleActivePage(data.cms_id);

        cms.model.id = parseInt(data.cms_id);

        if (cms.data = JSON.parse(data.cms_json)) {
            if (cms.options.last_copy = JSON.stringify(cms.data)) {
                cHtml.drawCms();
            }
        } else {
            this.log.error("Error decodificando el json. Data received was... ",data);
        }

        window.location.hash="page:"+data.model.id;

        $(cp.containers.page_header).html(data.page_header);
        $(cp.containers.published_btn).html(data.visible_btn);
        $(cp.containers.liveview_btn).remove();
        $(data.liveview_btn).insertAfter(cp.containers.published_btn);

         if(typeof (data.curLang)!='undefined'){
            JSBlocks.lang=data.curLang;
            $('.langs').removeClass('active');
            $('#lang_' + JSBlocks.lang).addClass('active');
        }


        $(cp.containers.liveview_btn).click(function (e) {
            e.preventDefault();
            var tok = $(cp.containers.liveview_btn).data('token');
            var url = $(cp.containers.liveview_btn).data('url') + '?liveview&token=' + tok; //+ '&_lang=' + JSBlocks.lang;//.replace("manager/index.php/","") + $('#contentlang-link_rewrite').val()
            var win = window.open(url, '_blank');
            win.focus();
        });
        $(cp.containers.page_settings_tab).html(data.page_settings);
        $(cp.containers.lang_supercont).html(data.lang_btn);

        if (typeof (data.page_subpanels) != 'undefined') {
            //first clear
            $([cp.containers.sp_menu, cp.containers.sp_theme, cp.containers.sp_seo, cp.containers.sp_delete].join(",")).remove();
            //now append
            $(cp.containers.panels).append(data.page_subpanels);

            if (currentPanel)
                $(currentPanel).removeClass("hidden");

            $(cp.containers.sp_theme).find('.mn_scrollbar').mCustomScrollbar({
                setTop: '35px',
                theme: "dark-thin",
                autoExpandScrollbar: true,
                scrollInertia: 1
            });
        }
        cms.toggleMenuStatus(cms.model.id);
        cms.addCmsTranslatedLangs();
        cms.checkSettingsDifferences();
        cp.delegateEvents();
        attachDropdownEvent();
        cms._attachLangChange();

    },
    _attachLangChange:function(){
        $('input[name=select-lang]').change(function (e) {

            var elm = $('input[name=select-lang]:checked');
            var id = elm.attr('id');
            id = id.replace('lang_', '');
            JSBlocks.lang = parseInt(id);
            var c = $('#selectLang').attr("class");
            var nc = $(e.currentTarget).data('info');
            $('#selectLang').removeClass(c).addClass('flag flag_' + nc + '  mn_dropdown open');

            cHtml.drawCms();
            cp.setLangFields();

        })
    },
    updateScrollbars:function(){
        $('.mn_scrollbar').mCustomScrollbar("update");
        this.log.info("Refreshing scrollbars...")
    },
    /**
     * Extended callback when creating a page
     */
    createPageCallback: function (data, sender) {
        this.log.info("createPageCallback");
        var li = $('<li>', {
            id: 'menuItem_' + data.model.id,
            class: 'mn_page mn_ajax mjs-nestedSortable-branch mn_page_unpublished ui-sortable-handle'
        }).attr(
            {
                'data-action': 'content/load',
                'data-info': '{"id":' + data.model.id + '}',
                'data-callback': 'cb_mn_page',
                'data-beforesend': 'bs_mn_page'

            });

        //'action', 'content/load').attr('data-info', '{"id":' + data.model.id + '}');
        var a = $('<a>', {
            href: "#"
        });
        var img = $('<img>', {
            id: 'pagethumb_' + data.model.id,
            class: 'img img-responsive page_thumb mCS_img_loaded',
            src: 'images/pagethumbs/thumb_default.jpg',
            alt: data.title
        }).data('id', data.model.id);
        var hand = $('<div>', {
            id: data.model.id
            //class: 'block_hidden_only_for_screen'
        }).html(data.model.title);
        var ul = $('<ul>', {
            class: 'mn_childs'
        });
        a.append(img);
        li.append([a, hand, ul]);


        $('ul.pagesortable').append(li);

        $("#sidebar-wrapper").mCustomScrollbar("scrollTo", "bottom", {
            scrollEasing: "easeOut",
            scrollInertia: 1500
        });

        cms.loadPageCallback(data, sender);

        this.toggleActivePage(data.model.id);
        cp.delegateEvents();

    },
    recoverPageCallback: function (data, sender) {
        $('#trash_' + cms.model.id).remove();
        proB.hide();
        this.createPageCallback(data, sender);
    },
    /**
     * After delete page callback;
     */
    afterDeletePage: function () {
        var menuId='#menuItem_' + cms.model.id;
        $(menuId).addClass('closed');
        $('.superMedinapro').addClass('closed');
        $('#page_header').html('');
        $('#page_settings').html('');
        $('#selectLang').remove();
        $('.tb_published').remove();
        $('#submitLiveview').remove();
        $('.langs-wrapper').remove();
        setTimeout(function () {
            $(menuId).remove();
        }, 350);
        cms.model={};
    }
    , _getCurrentPanel: function () {
        var cpanel = $(".sub_panel").not('.hidden');
        return cpanel.length > 0 ? "#" + $(cpanel)[0].id : false;
    }

};

function attachDropdownEvent() {
    var o = $(".mn_dropdown");
    o.unbind("click");
    $(".menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });


    o.click(function (e) {
        $(".mn_dropdown").not($(e.currentTarget)).removeClass('open');
        if (e.target == this)
            $(this).toggleClass('open');
    })




}

//@todo: Put this inside a helpers class
function str2url(str, encoding, ucfirst, PS_ALLOW_ACCENTED_CHARS_URL) {
    str = str.toUpperCase();
    str = str.toLowerCase();
    str = str.trim();
    if (PS_ALLOW_ACCENTED_CHARS_URL)
        str = str.replace(/[^a-z0-9\s\'\:\/\[\]-]\\u00A1-\\uFFFF/g, '');
    else {
        /* Lowercase */
        str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E4\u00E5\u0101\u0103\u0105\u0430]/g, 'a');
        str = str.replace(/[\u0431]/g, 'b');
        str = str.replace(/[\u00E7\u0107\u0109\u010D\u0446]/g, 'c');
        str = str.replace(/[\u010F\u0111\u0434]/g, 'd');
        str = str.replace(/[\u00E8\u00E9\u00EA\u00EB\u0113\u0115\u0117\u0119\u011B\u0435\u044D]/g, 'e');
        str = str.replace(/[\u0444]/g, 'f');
        str = str.replace(/[\u011F\u0121\u0123\u0433\u0491]/g, 'g');
        str = str.replace(/[\u0125\u0127]/g, 'h');
        str = str.replace(/[\u00EC\u00ED\u00EE\u00EF\u0129\u012B\u012D\u012F\u0131\u0438\u0456]/g, 'i');
        str = str.replace(/[\u0135\u0439]/g, 'j');
        str = str.replace(/[\u0137\u0138\u043A]/g, 'k');
        str = str.replace(/[\u013A\u013C\u013E\u0140\u0142\u043B]/g, 'l');
        str = str.replace(/[\u043C]/g, 'm');
        str = str.replace(/[\u00F1\u0144\u0146\u0148\u0149\u014B\u043D]/g, 'n');
        str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F6\u00F8\u014D\u014F\u0151\u043E]/g, 'o');
        str = str.replace(/[\u043F]/g, 'p');
        str = str.replace(/[\u0155\u0157\u0159\u0440]/g, 'r');
        str = str.replace(/[\u015B\u015D\u015F\u0161\u0441]/g, 's');
        str = str.replace(/[\u00DF]/g, 'ss');
        str = str.replace(/[\u0163\u0165\u0167\u0442]/g, 't');
        str = str.replace(/[\u00F9\u00FA\u00FB\u00FC\u0169\u016B\u016D\u016F\u0171\u0173\u0443]/g, 'u');
        str = str.replace(/[\u0432]/g, 'v');
        str = str.replace(/[\u0175]/g, 'w');
        str = str.replace(/[\u00FF\u0177\u00FD\u044B]/g, 'y');
        str = str.replace(/[\u017A\u017C\u017E\u0437]/g, 'z');
        str = str.replace(/[\u00E6]/g, 'ae');
        str = str.replace(/[\u0447]/g, 'ch');
        str = str.replace(/[\u0445]/g, 'kh');
        str = str.replace(/[\u0153]/g, 'oe');
        str = str.replace(/[\u0448]/g, 'sh');
        str = str.replace(/[\u0449]/g, 'ssh');
        str = str.replace(/[\u044F]/g, 'ya');
        str = str.replace(/[\u0454]/g, 'ye');
        str = str.replace(/[\u0457]/g, 'yi');
        str = str.replace(/[\u0451]/g, 'yo');
        str = str.replace(/[\u044E]/g, 'yu');
        str = str.replace(/[\u0436]/g, 'zh');

        /* Uppercase */
        str = str.replace(/[\u0100\u0102\u0104\u00C0\u00C1\u00C2\u00C3\u00C4\u00C5\u0410]/g, 'A');
        str = str.replace(/[\u0411]/g, 'B');
        str = str.replace(/[\u00C7\u0106\u0108\u010A\u010C\u0426]/g, 'C');
        str = str.replace(/[\u010E\u0110\u0414]/g, 'D');
        str = str.replace(/[\u00C8\u00C9\u00CA\u00CB\u0112\u0114\u0116\u0118\u011A\u0415\u042D]/g, 'E');
        str = str.replace(/[\u0424]/g, 'F');
        str = str.replace(/[\u011C\u011E\u0120\u0122\u0413\u0490]/g, 'G');
        str = str.replace(/[\u0124\u0126]/g, 'H');
        str = str.replace(/[\u0128\u012A\u012C\u012E\u0130\u0418\u0406]/g, 'I');
        str = str.replace(/[\u0134\u0419]/g, 'J');
        str = str.replace(/[\u0136\u041A]/g, 'K');
        str = str.replace(/[\u0139\u013B\u013D\u0139\u0141\u041B]/g, 'L');
        str = str.replace(/[\u041C]/g, 'M');
        str = str.replace(/[\u00D1\u0143\u0145\u0147\u014A\u041D]/g, 'N');
        str = str.replace(/[\u00D3\u014C\u014E\u0150\u041E]/g, 'O');
        str = str.replace(/[\u041F]/g, 'P');
        str = str.replace(/[\u0154\u0156\u0158\u0420]/g, 'R');
        str = str.replace(/[\u015A\u015C\u015E\u0160\u0421]/g, 'S');
        str = str.replace(/[\u0162\u0164\u0166\u0422]/g, 'T');
        str = str.replace(/[\u00D9\u00DA\u00DB\u00DC\u0168\u016A\u016C\u016E\u0170\u0172\u0423]/g, 'U');
        str = str.replace(/[\u0412]/g, 'V');
        str = str.replace(/[\u0174]/g, 'W');
        str = str.replace(/[\u0176\u042B]/g, 'Y');
        str = str.replace(/[\u0179\u017B\u017D\u0417]/g, 'Z');
        str = str.replace(/[\u00C6]/g, 'AE');
        str = str.replace(/[\u0427]/g, 'CH');
        str = str.replace(/[\u0425]/g, 'KH');
        str = str.replace(/[\u0152]/g, 'OE');
        str = str.replace(/[\u0428]/g, 'SH');
        str = str.replace(/[\u0429]/g, 'SHH');
        str = str.replace(/[\u042F]/g, 'YA');
        str = str.replace(/[\u0404]/g, 'YE');
        str = str.replace(/[\u0407]/g, 'YI');
        str = str.replace(/[\u0401]/g, 'YO');
        str = str.replace(/[\u042E]/g, 'YU');
        str = str.replace(/[\u0416]/g, 'ZH');

        str = str.toLowerCase();

        str = str.replace(/[^a-z0-9\s\'\:\/\[\]-]/g, '');
    }
    str = str.replace(/[\u0028\u0029\u0021\u003F\u002E\u0026\u005E\u007E\u002B\u002A\u002F\u003A\u003B\u003C\u003D\u003E]/g, '');
    str = str.replace(/[\s\'\:\/\[\]-]+/g, ' ');

    // Add special char not used for url rewrite
    str = str.replace(/[ ]/g, '-');
    str = str.replace(/[\/\\"'|,;%]*/g, '');

    if (ucfirst == 1) {
        var first_char = str.charAt(0);
        str = first_char.toUpperCase() + str.slice(1);
    }

    return str;
}