/**
 * Created by Silvia on 01/03/2016.
 */

    $.extend(true,JSBlocks.blocks,{
        icon:{
            group: 'text',
            contentClass: 'eIcon',
            icon: 'eIco eIcoIcon',
            name: 'icon',
            configurable: true,
            dataStructure: {
                title:{},
                text:{},
                eicon:{},
                elink:{}
            },
            data: null,
            events: {
                keyup:[
                    {
                        el:'#icon_text',
                        ck:function(e){
                            JSBlocks.blocks.icon.refreshCountChar();
                        }
                    }
                ]
            },
            ready: function () {
                JSBlocks.linkButton(this.name,"elink",'','',$('#icon_elink'),true);
                JSBlocks.iconButton(this.name,"eicon",'','',$('#icon_eicon'),true);
            },

            afterOpen: function () {
                this.refreshCountChar();
                JSBlocks.setLinkData(this.name,"elink",this.data,$('#icon_elink'));
                JSBlocks.setIconData(this.name+'_eicon',this.data.eicon);
                return true;
            },
            beforeClose: function () {
                this.data=null;
                return true;
            },
            refreshCountChar:function(){
                var txt=$('#icon_text').val().trim();
                var cont=140-txt.length;
                $('#countChar').html(cont);
            },
            getPreview:function(content){
                var html=JSBlocks.blocks.icon.getHtml(content);
                return html;

            },
            getHtml:function(content){
                var item=$('#clonable-icon-item').clone();
                item.removeAttr('id').removeClass('hidden');
                if(typeof(content)!='undefined') {
                    if (typeof(content.eicon) != 'undefined') {
                        item.find('.eIcon_icon').addClass(content.eicon);
                    }else{
                        item.find('.eIcon_icon').addClass('fa fa-circle');
                    }
                    if (typeof(content.title) != 'undefined') {
                        item.find('.eIcon_title').html(content.title);
                    }else{
                        item.find('.eIcon_title').remove();
                    }
                    if (typeof(content.text) != 'undefined') {
                        item.find('.eIcon_text').html(content.text);
                    }else{
                        item.find('.eIcon_text').remove();
                    }
                    if(typeof(content.elink) != 'undefined') {
                        item.find('a').attr('href',content.elink.url);
                    }else{
                        var cont=item.find('a').html();
                        item.find('a').remove();
                        item.html(cont);
                    }
                    return $(item).outerHTML();
                }else{
                    item.find('.eIcon_icon').addClass('fa-circle');
                    item.find('.eIcon_text').remove();
                    return item.html();
                }
            }
        }
    });

