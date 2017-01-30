/**
 * Creado por Xenon .
 * Autor: xnx
 * Date: 09/12/2015
 * Time: 20:54
 */



var is= {
    type:'',
    id_link:'',
    attachEvent:function(){


    },
    clear:function(){

    },
    getProbox:function(id){
        console.log('icon selection getProbox');
        $('#icons-container').undelegate('.icons-li','click');
        $('#icons-container').delegate('.icons-li','click',function(e){
                console.log('icon click');
            console.log('triggered element');
                console.log('#'+id+'_iconClass');
                console.log('########################3');
                console.log($(e.currentTarget).data('class'));

                $('#'+id+'-icon_selection').removeClass().addClass('fa fa-'+$(e.currentTarget).data('class'));
                $('#'+id+'_iconClass').val($(e.currentTarget).data('class')).trigger('change');


        });
        $('#icons-back').unbind('click');
        $('#icons-back').click(function(e){
            cHtml.clearProBox('proBox-'+id);
        });
        cHtml.clearProBox('proBox-icons');
        cHtml.openProBox('icon-icons');
        //$("#icons").idTabs('arrows');
    }

    
}

