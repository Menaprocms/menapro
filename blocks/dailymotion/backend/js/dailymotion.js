/**
 * Created by Silvia on 13/07/2016.
 */
    $.extend(true,JSBlocks.blocks, {
        dailymotion: {
            group: 'video',
            contentClass: 'eDailymotion',
            icon: 'eIcoT eIcoDailymotion',
            name: 'dailymotion',
            configurable: true,
            dataStructure:{
                src:{}
            },
            data: null,
            events:{
                keyup: [
                    {
                        el: "#dailymotion_url",
                        ck: function (e) {
                            e.stopPropagation();
                            var url=$('#dailymotion_url').val();
                            url=cleanText(url);
                            var nUrl='';

                            var patt= new RegExp(/\/[\w\d]{7}_/g);
                            var videoId=patt.exec(url);
                            videoId= videoId[0].replace('_','');
                            nUrl='//www.dailymotion.com/embed/video'+videoId;
                            $('#dailymotion_src').val(nUrl);
                            var videoHtml=JSBlocks.blocks.dailymotion.getHtml(nUrl);
                            $('#dailymotion_item').html('').html(videoHtml);
                        }
                    }
                ]
            },
            afterOpen: function () {
                if(typeof(this.data.src) && this.data.src!=''  && this.data.src!=null){
                    $('#dailymotion_url').val(this.data.src);
                    var videoHtml=this.getHtml(this.data.src);
                    $('#dailymotion_item').html('').html(videoHtml);
                }
            },
            beforeClose: function () {
                $('#dailymotion_item').html('');
                $('#dailymotion_url').val('');
                JSBlocks.currentBlock = null;
                cHtml.drawCms();
            },
            getPreview:function(content){
                if(typeof(content.src)!='undefined') {
                    html = '<div class="row"><div class="col-xs-12"><span><i class="eIco eIcoDailymotion"></i> '+ content.src + '</span></div></div>';
                    return html;
                }

            },
            getHtml:function(src){
                var html='';
                if(typeof(src)!='undefined' && src!="") {
                    html = '<div class="video-container"><iframe src="' + src + '" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
                }
                return html;
            }

        }
    });

