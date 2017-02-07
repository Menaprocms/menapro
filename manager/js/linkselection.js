/**
 * Creado por Xenon .
 * Autor: xnx
 * Date: 09/12/2015
 * Time: 20:54
 */
$(document).ready(function(){


});
var ls= {
    type:'',
    id_link:'',
    open_blank:0,
    attachEvent:function(){
        console.log('LINK SELECTION FUNCTION IS UISED!!');
        $('.link_selection').unbind('change').change(function(e){
            if($(this).val()!=''){
                var id=$(this).attr('id').replace('-link_selection','');

                if($(this).val()=='NONE'){
                    $('#'+id+'-id_link').val('');
                    //$('#'+id+'-type').val('NONE');
                }
                $(this).closest('.linkselection').find('.linktype_url').val('');
                $(this).closest('.linkselection').find('.chk_page_blank').removeAttr('checked');
                ls.getInput($(this).val(),id);
            }

        });
        ls.addSelectPageOptions();
        $('.linktype_url').unbind('change').change(function(e){

            var pref=$(this).data('prefix');
            var type=$('#'+pref+'-link_selection').val();

            var link=$(this).val();
            $('#'+pref+'-type').val(type);
            if(type=='FILE'){
                $('#'+pref+'-FILE_text').text(link).removeClass('hidden');
            }
            $('#'+pref+'-id_link').val(link).trigger('change');

        });


    },
    addSelectPageOptions:function(){
        console.log('LINK SELECTION FUNCTION IS UISED!!');
        $('.PAGE_selection').html('');
        $.each(cms.availablePages, function (i, item) {
            $('.PAGE_selection').append($('<option>', {
                value: i,
                text : item
            }));
        });
    },
    getInput:function(type,id){
        console.log('LINK SELECTION FUNCTION IS UISED!!');
        //$('select[id*="_selection"]').not('select[id*="link_selection"]').addClass("hidden");
        $('#container_'+id).find('.chk_page_blank_cont').addClass('hidden');
        $('#container_'+id).find('.linktype').addClass('hidden');

        if(type!='NONE') {
            console.log('ueee no es NONE');
            $('#container_'+id).find('.chk_page_blank_cont').removeClass('hidden');
            if (type != 'FILE') {
                if (type == 'URL') {
                    $('#' + id + '-' + type + '_selection').closest('.hidden').removeClass('hidden');
                } else {
                    $('#' + id + '-' + type + '_selection').removeClass('hidden');
                }
            } else {
                $('#' + id + '-' + type + '_selection').parent().find('.FILE_selection').removeClass('hidden');
            }
        }
    },
    clear:function(){
        console.log('LINK SELECTION FUNCTION IS UISED!!');
        console.log('clear link selection');
        $('select[id*="-link_selection"]').val('NONE');
        $('.linktype').addClass('hidden');
        $('p[id*="-FILE_text"]').text('');
        $('input[id*="-FILE_selection"]').val('');
        $('input[id*="-URL_selection"]').val('');
        $('input[id*="-type"]').val('');
        $('input[id*="-id_link"]').val('');
        $('.chk_page_blank_cont').addClass('hidden');
    },
    displayTypeField:function(id,type){
        console.log('LINK SELECTION FUNCTION IS UISED!!');
        var item='';
        if(type!='NONE') {
            if (type != 'FILE') {
                item = $('#' + id + '-' + type + '_selection');
            } else {
                item = $('#' + id + '-' + 'FILE_text');
            }
            if (item.hasClass('hidden')) {
                item.removeClass('hidden');
            } else {
                item.closest('.hidden').removeClass('hidden');
            }
            $('.chk_page_blank_cont').removeClass('hidden');
        }
    }

    
}

