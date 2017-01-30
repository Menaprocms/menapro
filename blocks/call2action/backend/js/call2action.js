/**
 * Created by Silvia on 13/07/2016.
 */
    $.extend(true,JSBlocks.blocks,{
        call2action:{
            group: 'text',
            contentClass: 'eCallToAction',
            icon: 'eIco eIcoCallToAction',
            name: 'call2action',
            configurable: true,
            dataStructure: {
                header:{
                    required:true,
                    validator:'string'
                },
                body:{
                    validator:'string'
                },
                text:{
                    required:true,
                    validator:'string'
                },
                eicon:{},
                elink:{
                    required:true
                }
            },
            data: null,
            events:null,
            ready: function () {
                JSBlocks.linkButton(this.name,"elink",'','',$('#call2action_elink'),true);
                JSBlocks.iconButton(this.name,"eicon",'','',$('#call2action_eicon'),true);
            },
            afterOpen: function () {
                JSBlocks.setLinkData(this.name,"elink",this.data,$('#call2action_elink'));
                JSBlocks.setIconData(this.name+'_eicon',this.data.eicon);
                return true;
            },
            beforeClose: function () {
                this.data = null;
                return true;
            },
            getPreview:function(content){
                if(content!=="") {
                    if (typeof(content) != 'undefined') {
                        var item = $('#clonable-call2action-preview').clone();
                        item.removeAttr('id').removeClass('hidden');
                        if(typeof(content.header)!='undefined'){
                            var txt = content.header;
                            txt = txt.substr(0, 139);
                            item.find('.call-title-preview').html(txt);
                            item.find('.call-body-preview').html(content.body);
                        }
                        return $(item).outerHTML();
                    }
                }
            }
        }

    });
