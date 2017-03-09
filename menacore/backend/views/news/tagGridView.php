<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 03/03/2017
 * Time: 10:11
 */
use yii\grid\GridView;
use common\components\Html;
use yii\helpers\StringHelper;

echo GridView::widget([
    'dataProvider' => $tagdataProvider,
    'filterModel' => true,
    'pager'=>[
        'linkOptions' => ['class'=>'tag_pag_link'],
    ],
    'columns' => [
        [
            'attribute'=>'name',
            'filter'=>Html::input('text','news_tag_search_name',null,['class'=>'form-control filter_tag','id'=>'news_tag_search_name'])
        ],
        [
            'attribute'=>'description',
            'format' => 'raw',
            'contentOptions' => ['class' => 'text_center_column'],
            'value' => function ($item, $index) {
                if(strlen($item->description)>0){
                    return StringHelper::truncateWords($item->description,10);
                }else{
                    return $item->description;
                }
            }
        ],
        'friendly_url',
        [
            'class' => 'backend\components\CustomActionColumn',
            'id_reset_button'=>'news_tag_search_reset',
            'enable_reset_button'=>true,
            'buttons' => [
            'delete'=>function ($url, $model, $key) {
                /** @var ActionColumn $column */
                return Html::a('<span class="fa fa-trash"></span>','#', [
                    'title' => Yii::t('yii', 'Delete post'),
                    'class'=>'mn_ajax',
                    'data-beforesend'=>'
                               if(confirm("'. Yii::t('yii', 'Are you sure to delete this item?').'")){
                                    return true;
                               }else{
                                    return false;
                               }',
                    'data-callback'=>'news.cb_delete(data,sender)',
                    'data-action'=>'news/deletetag',
                    'data-info'=>['id'=>$model->id,'id_lang'=>$model->id_lang]]);
            },
            'update'=>function ($url, $model, $key) {
                /** @var ActionColumn $column */
                return Html::a('<span class="fa fa-pencil"></span>', '#', [
                    'title' => Yii::t('yii', 'Update'),
                    'class'=>'update_tag',
                    'data-info'=>['model'=>$model]]);
            }
        ],'template'=>'{update}&nbsp;&nbsp;{delete}'],
    ]]);
