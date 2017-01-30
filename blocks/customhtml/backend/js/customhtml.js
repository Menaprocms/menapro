/**
 * Created by Silvia on 12/07/2016.
 */
    $.extend(true,JSBlocks.blocks,{
        customhtml:{
            group: 'other',
            contentClass: 'eCustomhtml',
            icon: 'eIco eIcoCustomhtml',
            name: 'customhtml',
            configurable: true,
            dataStructure: {
                code:{},
                purify:{}
            },
            data: null,
            events:null,
            getPreview:function(content){
                var html='';
                if(content.code.length>0) {
                    html = '<div class="eCustomhtml"><i class="'+this.icon+'"></i>Custom Html Code</div>';
                }else{
                    html='';
                }
                return html;
            }
        }
    });

