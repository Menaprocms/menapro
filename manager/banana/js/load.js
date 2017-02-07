/**
 * Created by XNX-PTL on 08/08/2015.
 */

var i18n={
    name:"Nombre",
    filesize:"Tamaño",
    date:"Fecha",
    type:"tipo",
    width:"ancho",
    height:"alto",
    newFolder:"Nueva carpeta",
    search:"Buscar",
    treeTitle:"Indice"
};
$(document).ready(function(){

    //Icons
    $("#iconos").banana({
        iconsMode:true,
        iconsData:"http://localhost/iconos/db.json",
        target:function(value){
            $("#icon").removeClass().addClass(value);
        },
        iconsPrefix:"fa-4x fa fa-",
        i18n:i18n
    });


    //End icons


    $("#simple").banana({
        url:"bananaManager.php",
        urlUpload:"bananaManager.php",
        tagTarget:"#tag",
        target:"#imagen",
        tagTargetClass:"img-responsive",
        color: "#556b2f",
        backgroundColor: "black",
        thumbsFolder:"thumbs/picker/",
        resetFolderOnStart:true,
        allowDelete:true,
        confirmDeletion:false,
        hideEmptyFolders:true,
        //selectedColor:"#eb6506",
        i18n:i18n,
        fileTypes:['image','psd','ai','eps']
    });

//**********************
    $("#multiple").banana({
        url:"bananaManager.php",
        urlUpload:"bananaManager.php",
        tagTarget:"#tag",
        //target:"#imagen",
        target:function(e){
            $("#tag").html("");

            $.each(e,function(k,v){
                if(v.fileType=="IMAGE")
                {
                    $("#tag").append(
                        $("<img>",{
                            src: v.fullDir,
                            class:"img-responsive"
                        })
                    )

                }else
                {
                    $("#tag").append(
                        $("<a>",{
                            href: v.fullDir,
                            class:"btn btn-default",
                            text: v.fileName
                        })
                    )
                }
            });
            console.info("Seleccion múltiple",e) },
        tagTargetClass:"img-responsive",
        color: "#556b2f",
        backgroundColor: "black",
        thumbsFolder:"thumbs/picker/",
        resetFolderOnStart:true,
        allowDelete:true,
        confirmDeletion:false,
        multiple:true,
        selectedColor:"#eb6506",
        hideEmptyFolders:true,
        i18n:i18n,
        fileTypes:['image','psd','ai','eps']
       });


});



