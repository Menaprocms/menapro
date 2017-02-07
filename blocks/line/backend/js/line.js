/**
 * Created by Silvia on 13/07/2016.
 */

    $.extend(true,JSBlocks.blocks, {
        line: {
            group: 'other',
            contentClass: 'eLine',
            icon: 'eIco eIcoLine',
            name: 'line',
            configurable: false,
            dataStructure:{
                check:{}
            },
            data: null,
            events: null,
            not_allowed:[1,2,3,4,5,6,7,8,9,10,11],
            getPreview: function (content) {
                return '<div class="row"><div class="col-xs-12"><span class="linePreview"></span></div></div>';
            },
            addItem:function(){
                proB.hide(cHtml.clearAll);
                if (typeof(cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content.check) != 'undefined') {
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content.check = 'ok';
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].type = this.name;
                } else {
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].content=$.extend(true,{},{check:'ok'});
                    cms.data.structure[JSBlocks.lang][JSBlocks.row].content[JSBlocks.col].type=this.name;
                }
                cHtml.drawCms();
            }
        }
    });

