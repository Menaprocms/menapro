/**
 * Created by Silvia on 27/02/2017.
 */

    $.extend(true,JSBlocks.blocks, {
        news: {
            group: 'news',
            contentClass: 'eNews',
            icon: 'eIco eIcoNews',
            name: 'news',
            configurable: true,
            dataStructure:{
                type:{},//1=latest post --- 2=post by tag
                tag:{
                    validator:'own'
                },
                nposts:{}
            },
            data: null,
            events: {
                change:[
                    {
                        el:'#news_type',
                        ck:function(e){
                          if($(e.currentTarget).val()==2){
                              $('#news_tag').val(0);
                              $('#news_tag_container').show();

                          }else{
                              $('#news_tag_container').hide();
                              $('#news_tag').val(0);
                          }
                        }
                    }
                ]
            },
            ready: function () {
                var self = this;
                JSBlocks.blocks.news.getexistingtags();
            },
            getexistingtags:function(){

                var senderData = {
                    url: baseDir + "/index.php?r=news/gettags",
                    success: function (data) {

                        JSBlocks.blocks.news.fillTagSelect(data.tags);

                    }
                };
                cp.sendAjax(senderData);
            },
            validatetag:function(){

                var tag=$('#news_tag').val();

              if(this.data.type==2 &&  (tag==0 || tag==null)){
                  return false;
              }
              return true;
            },
            afterOpen:function(){
                if(this.data.type!=null){
                    $('#news_type').val(this.data.type);
                    $('#news_tag').val(0);
                    if(this.data.type==2){
                        $('#news_tag_container').show();
                        $('#news_tag').val(this.data.tag);
                    }else{
                        $('#news_tag_container').hide();
                    }
                }else{
                    $('#news_type').val(1);
                    $('#news_tag').val(0);
                    $('#news_tag_container').hide();
                }
                if(this.data.nposts!=null){
                    $('#news_nposts').val(this.data.nposts);
                }else{
                    $('#news_nposts').val(1);
                }
                return true;
            },

            fillTagSelect:function(tags){
                var select='#news_tag';
                $(select).html("");
                $('<option>',{
                    value:0,
                    text:news_lang.select_a_tag,
                    disabled:''
                }).appendTo(select);
                $.each(tags, function(k,v){
                    var opt=$('<option>',{
                        value:k,
                        text:v
                    }).appendTo(select);
                });
            },
            getPreview:function(content){
                    var html = '<div class="row"><div class="col-xs-12"><span><i class="eIco eIcoNews"></i></span>'+(content.type==1?news_lang.latest:news_lang.by_tag)+'</div></div>';
                    return html;
            }
        }
    });

