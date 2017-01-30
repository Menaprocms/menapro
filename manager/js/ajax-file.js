/*

MenaPRO

 */

$(document).ready(function(){

    var input=$('[name="imageFile"]').change(
        function(){
            microBanana._fileUpload(this)}
    )
    var input2=$('[name="blockFile"]').change(
        function(){
            microBanana.block=true;
            microBanana._fileUpload(this)}
    )
    var input3=$('[name="themeFile"]').change(
        function(){
            microBanana.theme=true;
            microBanana._fileUpload(this)}
    )

});

var microBanana={
    block:false,
    theme:false,
    file_name:'',
    _settings:{
        targetAdress:baseDir+"/index.php?r=configuration/upload",
        blockAdress:baseDir+"/index.php?r=block/upload",
        themeAdress:baseDir+"/index.php?r=configuration/addtheme"
    },
    _fileUpload: function (input) {
        var name=$(input).parent().find('input[name="name"]').val();

        microBanana.file_name=name;
        jQuery.each(jQuery(input)[0].files, function (i, file) {
            var data = new FormData();
            if(microBanana.block){
                data.append('blockFile[]', file);
                data.append('name',name);
            }else if(microBanana.theme){
                data.append('themeFile[]', file);
                data.append('name',name);
            }else{
                data.append('imageFile[]', file);
                data.append('name',name);
            }

            microBanana._ajaxFiles(data,input);

        });


    }, _ajaxFiles: function (data,input) {

        var hidden = false;
        data.append('action', "upload");
        data.append('menacsrf', csrfToken);
        data.append('uploadDir',  microBanana._settings.targetAdress);
        //console.log(data);
        var url='';
        if(microBanana.block){
            url=microBanana._settings.blockAdress;
        }else if(microBanana.theme){
            url=microBanana._settings.themeAdress;
        }else{
            url=microBanana._settings.targetAdress;
        }
        jQuery.ajax({
            url:url,
            data: data,
            dataType: 'json',
            cache: false,
            async: true,
            contentType: false,
            processData: false,
            type: 'POST',
            beforeSend: function () {
                if(!microBanana.block){
                    $('#'+microBanana.file_name+'_thumb').addClass('hidden');
                }
                $('#loader_'+microBanana.file_name).addClass('fa-spin').removeClass('hidden');


            },
            success: function (data) {
                microBanana._getData(false);
                // console.log(data);
                $('#loader_'+microBanana.file_name).addClass('hidden').removeClass('fa-spin');
                if(data.success){
                    //console.log('upload success');
                    //console.log(data.image+'?'+ Date.now());
                    //console.log( $('#'+data.name+'_thumb'))

                    if(microBanana.block){
                        microBanana.block=false;
                        $('#block_notice').removeClass('hidden');
                        setTimeout(function(){
                            location.reload();
                        },300);
                    }else if(microBanana.theme){
                        microBanana.theme=false;
                        $('#theme_notice').removeClass('hidden');
                        setTimeout(function(){
                            location.reload();
                        },300);
                    }else{
                        $('#'+data.name+'_thumb').attr('src',data.image).removeClass('hidden');//+'?'+ Date.now()
                    }

                    $('input[name=imageFile]').val('');

                }else{
                    if(typeof(input)!="undefined"){
                        cp._appendAjaxError($(input).closest('form'), data.error);
                        return;

                    }

                }
            }, xhr: function () {
                var bProgress = $("#bananaProgress"),hidden=false;
                var xhrobj = $.ajaxSettings.xhr();
                if (xhrobj.upload) {
                    xhrobj.upload.addEventListener('progress', function (event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                            if (percent >= 95 && !hidden) {
                                hidden = true;

                                bProgress.hide();


                            } else if (percent < 95) {
                                bProgress.css({
                                    width: percent + "%"

                                });
                            }
                        }
                        //Set progress


                    }, false);
                }
                return xhrobj;
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            $('#loader_'+microBanana.file_name).addClass('hidden').removeClass('fa-spin');
            if(typeof(input)!="undefined"){
                cp._appendAjaxError($(input).closest('form'), jqXHR.responseText);
            }
        });
    },
    _getData:function(data){
        console.log(data);
    }
};