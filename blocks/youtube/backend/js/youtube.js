/**
 * Created by Silvia on 01/03/2016.
 */
    $.extend(true,JSBlocks.blocks,{
        youtube:{
            group: 'video',
            contentClass: 'eYoutube',
            icon: 'eIco eIcoYoutube',
            name: 'youtube',
            configurable: true,
            dataStructure:{
                src:{}
            },
            data: null,
            events:{
                keyup: [
                    {
                        el: "#youtube_url",
                        ck: function (e) {
                            e.stopPropagation();
                            var url=$('#youtube_url').val();
                            url=cleanText(url);
                            var nUrl='';
                            nUrl=url.replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/');
                            nUrl+='?rel=0';
                            $('#youtube_src').val(nUrl);
                            var videoHtml=JSBlocks.blocks.youtube.getHtml(nUrl);
                            $('#youtube_item').html('').html(videoHtml);
                        }
                    }
                ]
            },
            afterOpen: function () {
                if(typeof(this.data.src) && this.data.src!='' && this.data.src!=null){
                    $('#youtube_url').val(this.data.src);
                    var videoHtml=this.getHtml(this.data.src);
                    $('#youtube_item').html('').html(videoHtml);
                }
            },
            beforeClose: function () {
                $('#youtube_item').html('');
                $('#youtube_url').val('');
                JSBlocks.currentBlock = null;
                cHtml.drawCms();
                return true;
            },
            getPreview:function(content){
                console.log('Youtube src--->'+content.src);
                if(typeof(content.src)!='undefined') {
                    html = '<div class="row"><div class="col-xs-12"><span class="eYoutubePreviewSrc"><i class="eIco eIcoYoutube"></i> '+ content.src + '</span></div></div>';
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

