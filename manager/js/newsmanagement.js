/**
 * Created by silvia on 28/02/2017.
 */
$(document).ready(function () {

});

var news = {
    editor: null,
    update:false,
    postmodel:null,
    postmodel_tags:null,
    tagmodel:null,
    //*****************************************************Edit/Create post panel functions
    savePost:function(){
        $('.post_message').hide();
        var postdata=news.collectPostData();
        var sdata=news.collectPostSearchData();
        var action='savepost';
        if(news.update){

            $.extend(postdata,{'id':news.postmodel.id});

            action='updatepost';
        }
        var senderData = {
            data:postdata,
            url: baseDir + "/index.php?r=news/"+action,
            success: function (data) {

                if(data.success){
                    $('#post_ok').show();
                    //$.pjax.reload({container:'#postsGridView'});
                    news.clearPanel();
                    var fillsearch=false;
                    $.each(sdata,function(k,v){
                        if(v!=""){
                            fillsearch=true;
                        }
                    });
                    if(fillsearch){
                        news.fillPostSearchFiels(sdata);
                        $('#news_post_search_id').trigger('change');
                    }
                    if(typeof(data.postManagementHtml)!="undefined" && data.postManagementHtml!=false){
                        if(!fillsearch) {
                            $('#news_post_tab').html('').append(data.postManagementHtml);
                        }
                        news.bindUpdatePostEvent();
                        cp.delegateEvents();
                        //news.bindUpdateTagEvent();
                    }



                    if(news.update){
                        news.clearUpdateParams();
                        $('.post_message').hide();
                    }
                    news.clearTagsPanel();
                    $("a[href='#news_post_tab']").trigger("click");
                }else{
                    $('#post_ko').show();
                }
                $('body').scrollTop(0);
            }
        };

        cp.sendAjax(senderData);
    },
    saveTag:function(){
        $('.tag_message').hide();
        var tagdata=news.collectTagData();
        var action='savetag';
        var searchdata=$('#news_tag_search_name').val().trim();
        if(news.update){
            $.extend(tagdata,{'id':news.tagmodel.id});
            action='updatetag';
        }
        var senderData = {
            data:tagdata,
            url: baseDir + "/index.php?r=news/"+action,
            success: function (data) {
                if(data.success){
                    news.clearPanel();
                    if(typeof(data.tagManagementHtml)!="undefined" && data.tagManagementHtml!=false){
                        $('#showtags').html('').append(data.tagManagementHtml);
                        news.bindUpdateTagEvent();
                        cp.delegateEvents();
                    }
                    var show=true;
                    if(searchdata!=""){
                        show=false;
                        $('#news_tag_search_name').val(searchdata).trigger('change');
                    }
                    if(news.update){
                        news.clearUpdateTagParams();
                        $('.post_message').hide();
                    }
                    if(show){
                        $('#edittag').hide();
                        $('#showtags').show();
                    }

                    $('body').scrollTop(0);
                }else{
                    $('#tag_ko').show();
                }

                $('body').scrollTop(0);
            }
        };

        cp.sendAjax(senderData);
    },
    collectTagData:function(){
        var name=$('#news_tag_name').val();
        var friendly=$('#news_tag_friendly').val();
        var description=$('#news_tag_description').val();
        var data= {
            name:name,
            description:description,
            friendly:friendly
        };
        return data;
    },
    collectPostData:function(){
        var title=$('#news_post_title').val();
        var content=news.editor.getContent();
        var tags=(news.getCurrentTags()!=''?JSON.parse(news.getCurrentTags()):[]);
        var friendly=$('#news_post_friendly').val();
        var published=$('#news_post_published').is(':checked');
        var data= {
            title:title,
            content:content,
            tags:tags,
            friendly:friendly,
            published:published

        };

        return data;
    },
    collectPostSearchData:function(){
      var data={
         id:$('#news_post_search_id').val().trim(),
         title:$('#news_post_search_title').val().trim(),
         author:$('#news_post_search_author').val().trim(),
         tag:$('#news_post_search_tags').val().trim(),
         published:$('#news_post_search_published').val().trim()
      };
      return data;
    },
    getCurrentTags:function(){
        return $('#news_post_tags').parent().find('input[type=hidden]').val();
    },
    clearTabs:function(){
        news.clearPanel();
        news.clearUpdateParams();
        news.clearTagsPanel();
        news.clearUpdateTagParams();
    },
    clearPanel:function(){
        $('#news_post_title').val('');
        news.editor.setContent('');
        $('#news_post_friendly').val('');
        $('#news_post_published').attr('cheked',false);
        news.clearTags();

    },
    clearTags:function(){
        var self=$('#news_post_tags').textext()[0];
        if(typeof self.tags() != 'undefined')
        {
            // it is! remove all tags
            var elems = self.tags().tagElements();
            for(var i =0; i < elems.length;i++)
            {
                self.tags().removeTag($(elems[i]));
            }
        }
        // clear the text from the search area
        var element= $('#news_post_tags');
        element.parent().find('input[type=hidden]').val('');
        element.val('');
    },
    clearUpdateParams:function(){
        news.update=false;
        news.postmodel_tags=null;
        news.postmodel=null;
        $('#news_post_friendly').removeData("info");
    },
    clearUpdateTagParams:function(){
        news.update=false;
        news.tagmodel=null;
        $('#news_tag_friendly').removeData("info");
    },
    clearTagsPanel:function(){
        $('#news_tag_name').val('');
        $('#news_tag_friendly').val('');
        $('#news_tag_description').val('');
        $('#showtags').show();
        $('#edittag').hide();
    },
    changeTitleField:function(title){
        var link=$('#news_post_friendly');
        if(link.val().trim()==""){
            link.val(title).trigger('blur');
        }
    },
    changeNameField:function(title){
        var link=$('#news_tag_friendly');
        if(link.val().trim()==""){
            link.val(title).trigger('blur');
        }
    },
    //**********************************************UPDATEpost functions
    fillEditionFields:function(){
        $('#news_post_title').val(news.postmodel.title);
        $('#news_post_friendly').val(news.postmodel.friendly_url);
        if(news.postmodel.published){
            $('#news_post_published').attr('checked',true).trigger('change');
        }
        news.editor.setContent(news.postmodel.content);
        news.fillTagsField();

    },
    fillTagsField:function(){

        if(news.postmodel_tags.length>0){
            var tags=[];
            $.each(news.postmodel_tags,function(k,v){
               tags.push(v.name);
            });
            $('#news_post_tags').textext()[0].tags().addTags(tags);
        }

    },
    fillTagEditionFields:function(){
        $('#news_tag_name').val(news.tagmodel.name);
        $('#news_tag_friendly').val(news.tagmodel.friendly_url);
        $('#news_tag_description').val(news.tagmodel.description);

    },
    fillPostSearchFiels:function(sdata){
        $('#news_post_search_id').val(sdata.id);
        $('#news_post_search_title').val(sdata.title);
        $('#news_post_search_author').val(sdata.author);
        $('#news_post_search_tags').val(sdata.tag);
        $('#news_post_search_published').val(sdata.published);
    },
    // ********************************************END UPDATEpost functions
    //*****************************************************END Edit/Create post panel functions
    //*****************************************************bind functions
    initNewsPanel: function () {
        //news tabs
        $('#newstabs').tabs({
                activate: function(event, ui) {
                    news.clearTabs();
             }
           }
        );
        $(".news_editor_options li[data-group='common']").click(function (e) {
            news.editor.formatter.toggle($(this).data('format').toLowerCase());
            news.editor.focus();
        });
        $(".news_editor_options li[data-group='list']").click(function (e) {
            news.editor.execCommand($(this).data('format').toLowerCase());
            news.editor.focus();
        });
        tinyMCE.init({
            selector: "#news_text_editor",
            theme: "modern",
            skin: "lightgray",
            minHeight: 800,
            file_picker_types: 'file image media',
            plugins: "link  paste table code media textcolor autoresize image",//autoresize colorpicker
            toolbar1: ",|,alignleft,aligncenter,alignright,alignjustify,|pasteword,|,outdent,indent,|,link,unlink,|,cleanup,|,media, image, myimage,bold,italic,underline,strikethrough,|,removeformat,",
            //toolbar2: "",//,forecolor, backcolor, |,bullist,numlist,|
            content_css: 'css/newseditor.css',
            plugin_preview_height: 500,
            object_resizing: true,
            autoresize_min_height: 350,
            //todo: Add link list with all active pages and redesign link plugin Panel
            link_class_list: [
                {title: 'None', value: ''},
                {title: 'Button', value: 'btn btn-mena'},
            ],
            link_list: function (success) {
                var links = [];
                $.each(cms.availablePages, function (k, v) {
                    links.push({title: v.name + (v.published ? '' : '*'), value: v.url});
                });
                success(links);
            },
            setup: function (editor) {
                editor.addButton('myimage', {
                    text: '',
                    icon: 'no eIcoFile',
                    id: 'news_editor_myfile',
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
            init_instance_callback: function (editor) {
                news.editor = editor;
                news.bindNewsTextEditorSelectorChanged();
            },

            menu: {}
        });
        news.bindEditorTabEvents();
        news.bindTagTabEvents();
    },
    bindEditorTabEvents:function(){
        $('#news-save-post').click(function(e){

            news.savePost();
        });
        $('#news_post_title').change(function(e){
            var val=$(e.currentTarget).val();
            news.changeTitleField(val);
        });
    },
    bindTagTabEvents:function(){
        $('#news-save-tag').click(function(e){
            news.saveTag();
        });
        $('#news-new-tag').click(function(e){
            news.clearTagsPanel();
            news.clearUpdateTagParams();
            $('#showtags').hide();
            $('#edittag').show();
            $('body').scrollTop(0);
        });
        $('#news_tag_name').change(function(e){
            var val=$(e.currentTarget).val();
            news.changeNameField(val);
        });
    },
    bindNewsTextEditorSelectorChanged: function () {

        $(".news_editor_options li").each(function (key, nitem) {
            var item = $(nitem),
                selection = news.editor.selection,
                format = item.data('format');

            function setNewsActiveItem(name) {

                return function (state, args) {
                    //$(".editor_options li").removeClass('selected');

                    var nodeName, i = args.parents.length;

                    while (i--) {
                        nodeName = args.parents[i].nodeName;
                        if (nodeName == "OL" || nodeName == "UL") {
                            break;
                        }
                    }

                    var cssClass = "selected";

                    if (state) {
                        //$(".editor_options li").not(item).removeClass('selected');
                        item.addClass(cssClass);
                    } else {
                        $('[data-format="' + name + '"]').removeClass(cssClass)
                    }

                };
            }

            var itemName = format;
            if (itemName == "bullist") {
                selection.selectorChanged('ul > li', setNewsActiveItem("UL"));
            } else if (itemName == "numlist") {
                selection.selectorChanged('ol > li', setNewsActiveItem("OL"));
            } else {
                selection.selectorChanged(item.data('selector'), setNewsActiveItem(format));
            }
        });
    },
    bindUpdatePostEvent:function(){
        $('#news_post_search_reset').unbind('click').bind('click',function(e){
            e.preventDefault();
            var sdata={
                id:"",
                title:"",
                author:"",
                tag:"",
                published:""
            };
            var senderData = {
                data:sdata,
                url: baseDir + "/index.php?r=news/searchpost",
                success: function (data) {

                    if(data.success){
                        if(typeof(data.postManagementHtml)!="undefined" && data.postManagementHtml!=false){
                            $('#news_post_tab').html('').append(data.postManagementHtml);
                            news.fillPostSearchFiels(sdata);
                            news.bindUpdatePostEvent();
                            cp.delegateEvents();
                        }
                    }
                    $('body').scrollTop(0);
                }
            };
            cp.sendAjax(senderData);
        });
        $('.filter_post').unbind('change').bind('change',function(e){
            e.preventDefault();
            var sdata=news.collectPostSearchData();
            var senderData = {
                data:sdata,
                url: baseDir + "/index.php?r=news/searchpost",
                success: function (data) {

                    if(data.success){
                        if(typeof(data.postManagementHtml)!="undefined" && data.postManagementHtml!=false){
                            $('#news_post_tab').html('').append(data.postManagementHtml);
                            news.fillPostSearchFiels(sdata);
                            news.bindUpdatePostEvent();
                            cp.delegateEvents();
                        }
                    }
                    $('body').scrollTop(0);
                }
            };
            cp.sendAjax(senderData);

        });
        $('.update_post').unbind().bind('click',function(e){
            e.preventDefault();
            var data=$(e.currentTarget).data('info');

            $("a[href='#news_editpost_tab']").trigger("click");
            news.update=true;
            news.postmodel=data.model;
            news.postmodel_tags=data.tags;

            $('#news_post_friendly').data('info',{id:news.postmodel.id});

            news.fillEditionFields();
        });
        $('.post_pag_link').unbind().bind('click',function(e){

            e.preventDefault();
            var page=$(e.currentTarget).data('page');
            news.postPagination(page);
        });
    },
    bindUpdateTagEvent:function(){

        $('#news_tag_search_reset').unbind('click').bind('click',function(e){
            e.preventDefault();
            var sdata={
                name:""
            };
            var senderData = {
                data:sdata,
                url: baseDir + "/index.php?r=news/searchtag",
                success: function (data) {

                    if(data.success){
                        if(typeof(data.tagManagementHtml)!="undefined" && data.tagManagementHtml!=false){
                            $('#showtags').html('').html(data.tagManagementHtml);
                            $('#news_tag_search_name').val('');
                            news.bindUpdateTagEvent();
                            cp.delegateEvents();
                        }
                    }
                    $('body').scrollTop(0);
                }
            };
            cp.sendAjax(senderData);
        });
        $('.filter_tag').unbind('change').bind('change',function(e){
            e.preventDefault();
            var sdata={
                name:$('#news_tag_search_name').val().trim()
            };
            var senderData = {
                data:sdata,
                url: baseDir + "/index.php?r=news/searchtag",
                success: function (data) {

                    if(data.success){
                        if(typeof(data.tagManagementHtml)!="undefined" && data.tagManagementHtml!=false){
                            $('#showtags').html('').html(data.tagManagementHtml);
                            $('#news_tag_search_name').val(sdata.name);
                            news.bindUpdateTagEvent();
                            cp.delegateEvents();
                            $('#edittag').hide();
                            $('#showtags').show();
                        }
                    }
                    $('body').scrollTop(0);
                }
            };
            cp.sendAjax(senderData);

        });
        $('.update_tag').unbind().bind('click',function(e){

            e.preventDefault();
            var data=$(e.currentTarget).data('info');

            news.update=true;
            news.tagmodel=data.model;
            $('#news_tag_friendly').data('info',{id:news.tagmodel.id});
            news.fillTagEditionFields();
            $('#showtags').hide();
            $('#edittag').show();
            $('body').scrollTop(0);

        });
        $('.tag_pag_link').unbind().bind('click',function(e){

            e.preventDefault();
            var page=$(e.currentTarget).data('page');
            news.tagPagination(page);
        });
    },
    //*****************************************************END bind functions
    //*****************************************************custom pagination functions
    postPagination:function(page){

        var sdata=news.collectPostSearchData();
        $.extend(sdata,{page:page});
        var senderData = {
            data:sdata,
            url: baseDir + "/index.php?r=news/postpagination",
            success: function (data) {

                if(data.success){
                    if(typeof(data.postManagementHtml)!="undefined" && data.postManagementHtml!=false){

                        $('#news_post_tab').html('').html(data.postManagementHtml);
                        news.fillPostSearchFiels(sdata);
                        news.bindUpdatePostEvent();
                        cp.delegateEvents();
                    }
                }
                $('body').scrollTop(0);
            }
        };

        cp.sendAjax(senderData);
    },
    tagPagination:function(page){
        var sdata={
            page:page,
            name:$('#news_tag_search_name').val().trim()
        };
        var senderData = {
            data:sdata,
            url: baseDir + "/index.php?r=news/tagpagination",
            success: function (data) {

                if(data.success){
                    if(typeof(data.tagManagementHtml)!="undefined" && data.tagManagementHtml!=false){
                        $('#showtags').html('').html(data.tagManagementHtml);
                        $('#news_tag_search_name').val(sdata.name);
                        news.bindUpdateTagEvent();
                        cp.delegateEvents();
                    }
                }
                $('body').scrollTop(0);
            }
        };
        cp.sendAjax(senderData);
    },
    //*****************************************************END custom pagination functions
    //*****************************************************callbacks functions
    cb_confignews_click:function(data,sender){

        window.location.hash='news';
        $('.superMedinapro').addClass('closed');
        $(cp.containers.page_header).html('');
        $(cp.containers.page_settings_tab).html('');
        $(cp.containers.liveview_btn).remove();

        //LangButton
        $(cp.containers.lang_supercont).html(data.lang_btn);
            attachDropdownEvent();
            cms._attachLangChange();

        $('.tb_published').remove();
        if( $('#news_post_tab').html()=="") {
            $('#news_post_tab').append(data.postManagementHtml);
            $('#news_tag_tab').append(data.tagManagementHtml);
        }
        news.bindUpdatePostEvent();
        news.bindUpdateTagEvent();
        news.bindTagTabEvents();
        $("#newsconfig_panel").show();

        var exists=$('#news_post_tags').textext();
        if(exists.length==0){
            $('#news_post_tags').textext({
                plugins : 'tags suggestions autocomplete',
                suggestions: data.existing_tags
            }).bind('isTagAllowed', function(e, data)
            {
                var senderData = {
                    data: {tag: data.tag},
                    url: baseDir + "/index.php?r=news/istagallowed",
                    success: function (data) {
                        news.refreshSuggestions(data.existing_tags);
                        return data.isallowed;
                    }
                };
                cp.sendAjax(senderData);
            });
        }else{
            this.refreshSuggestions(data.existing_tags);
        }
        setTimeout(function(){

            $('body').scrollTop(0);
        },100);
    },
    cb_tagFriendlyUrl:function(data){

        $('#news_tag_friendly').val(data.friendlyUrl);
    },
    cb_postFriendlyUrl:function(data){
        $('#news_post_friendly').val(data.friendlyUrl);
    },
    cb_delete:function(data,sender){
        $(sender).closest('tr').remove();
    },
    cb_togglepublished:function(data,sender){
         $(sender).toggleClass('label-success label-danger').find('i').toggleClass('fa-check fa-remove');
    },
    //*****************************************************END callbacks functions
    //*****************************************************textextjs functions
    refreshSuggestions:function(suggestions){

        $('#news_post_tags').textext()[0].suggestions().setSuggestions(suggestions,false);
        //$('#news_post_tags').textext()[0].autocomplete().trigger('setSuggestions',{result:suggestions});
        $('#news_post_tags').textext()[0]._opts.suggestions=suggestions;
    }
    //*****************************************************END textextjs functions
};
