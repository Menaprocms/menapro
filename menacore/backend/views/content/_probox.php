
<div id="proBox" class="animate">
    <div class="container">
        <?php echo $this->render('_seleccionTipo', [

        ]) ?>

        <div class="trash_fancybox" id="proBox-trash" >
            <div class="proBoxTitle">
                Trash
            </div>
            <div class="row" id="trash_pages">
                <?php echo $this->render('_trash', [
                    'trash'=>$trash
                ]) ?>

            </div>
        </div>
        <?php echo $this->render('_blocksPanels', [
            'blocks'=>$blocks

        ]) ?>
        <?php echo $this->render('_clonableElements', [

        ]) ?>

    </div>
</div>