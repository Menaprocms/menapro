/**
 * Created by Silvia on 01/05/2016.
 */

    $.extend(true,JSBlocks.blocks,{
        vimeo:{
            group: 'video',
            contentClass: 'eVimeo',
            icon: 'eIcoT eIcoVimeo',
            name: 'vimeo',
            configurable: true,
            dataStructure:{
                src:{}
            },
            data: null,
            events:{
                keyup: [
                    {
                        el: "#vimeo_url",
                        ck: function (e) {
                            e.stopPropagation();
                            var url=$('#vimeo_url').val();
                            url=cleanText(url);
                            var nUrl='';
                            nUrl=url.replace('https://vimeo.com/','https://player.vimeo.com/video/');
                            nUrl+='?title=0&byline=0&portrait=0';
                            $('#vimeo_src').val(nUrl);
                            var videoHtml=JSBlocks.blocks.vimeo.getHtml(nUrl);
                            $('#vimeo_item').html('').html(videoHtml);
                        }
                    }
                ]
            },
            afterOpen: function () {
                if(typeof(this.data.src) && this.data.src!='' && this.data.src!=null){
                    $('#vimeo_url').val(this.data.src);
                    var videoHtml=this.getHtml(this.data.src);
                    $('#vimeo_item').html('').html(videoHtml);
                }
            },
            beforeClose: function () {
                $('#vimeo_item').html('');
                $('#vimeo_url').val('');
                JSBlocks.currentBlock = null;
                cHtml.drawCms();
            },
            getPreview:function(content){
                if(typeof(content.src)!='undefined') {
                    html = '<div class="row"><div class="col-xs-12"><span><i class="eIco eIcoVimeo"></i> '+ content.src + '</span></div></div>';
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

