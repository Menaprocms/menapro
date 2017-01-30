/**
 * Created by Silvia on 13/07/2016.
 */

    $.extend(true,JSBlocks.blocks, {
        contactform: {
            group: 'other',
            contentClass: 'eContactform',
            icon: 'eIco eIcoContactform',
            name: 'contactform',
            configurable: false,
            dataStructure:{
                check:{}
            },
            data: null,
            events: null,

            addItem:function(){
                proB.hide(cHtml.clearAll);
                if (cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content!=null && typeof(cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content.check) != 'undefined') {
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content.check = 'ok';
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].type = this.name;
                } else {
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content=$.extend(true,{},{check:'ok'});
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].type=this.name;
                }
                cHtml.drawCms();
            },
            getPreview:function(content){
                    var html = '<div class="row"><div class="col-xs-12"><span><i class="eIco eIcoContactform"></i></span></div></div>';
                    return html;
            }
        }
    });

