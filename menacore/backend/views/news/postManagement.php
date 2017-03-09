<?php
/**
 * Created by PhpStorm.
 * User: silvia
 * Date: 01/03/2017
 * Time: 12:56
 *
**/
use yii\helpers\Url;
use yii\grid\GridView;
use common\components\Html;

                               echo GridView::widget([
                                   'dataProvider' => $postdataProvider,
                                   'id'=>'news_post_grid',
                                   'filterModel' => true,
//                                   'filterUrl'=>Url::to(['news/filter']),
                                   'pager'=>[
                                       'linkOptions' => ['class'=>'post_pag_link'],
                                   ],
                                   'columns' => [
                                       [
                                           'attribute'=>'id',
                                           'filter'=>Html::input('text','news_post_search_id',null,['class'=>'form-control filter_post','id'=>'news_post_search_id'])
                                       ],
                                       [
                                           'attribute'=>'author',
                                           'format' => 'raw',
                                           'filter'=> Html::dropDownList('news_post_search_author',null,$users,['class'=>'form-control filter_post','prompt'=>'','id'=>'news_post_search_author']),
                                           'contentOptions' => ['class' => 'text_center_column'],
                                           'value' => function ($item, $index) {
                                               return $item->postauthor->username;

                                           }
                                       ],
                                       [
                                           'attribute'=>'title',
                                           'filter'=>Html::input('text','news_post_search_title',null,['class'=>'form-control filter_post','id'=>'news_post_search_title'])
                                       ],
                                       [
                                           'label'=> Yii::t('app', 'Tags'),
                                           'format' => 'raw',
                                           'filter'=> Html::dropDownList('news_post_search_tags',null,$tags,['class'=>'form-control filter_post','prompt'=>'','id'=>'news_post_search_tags']),
                                           'contentOptions' => ['class' => 'text_center_column'],
                                           'value' => function ($item, $index) {
                                             $text="";
                                             if(isset($item->tags) && sizeof($item->tags)>0){
                                                 foreach($item->tags as $k=>$v){
                                                     $tg[]=$v->name;
                                                 }
                                                 $text=implode(', ',$tg);
                                             }
                                             return $text;
                                           }
                                       ],
                                       [
                                           'attribute'=>'published',
                                           'filter'=> Html::dropDownList('news_post_search_published',null,[
                                                   0=>Yii::t('app', 'NO'),
                                                   1=>Yii::t('app', 'YES'),

                                               ],['class'=>'form-control filter_post','prompt'=>'','id'=>'news_post_search_published']),

                                           'format' => 'raw',
                                           'contentOptions' => ['class' => 'text_center_column'],
                                           'value' => function ($item, $index) {
                                               return Html::tag('span', $item->published ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>', ['data-info'=>['id'=>$item->id],'data-action'=>'news/togglepublished','data-callback'=>'news.cb_togglepublished(data,sender);','class' => "mn_ajax post_published_label label label-" . ($item->published ? "success" : "danger")]);

                                           }
                                       ],

                                      ['class' => 'backend\components\CustomActionColumn',
                                          'id_reset_button'=>'news_post_search_reset',
                                          'enable_reset_button'=>true,
                                          'buttons' => [

                                               'view'=>function ($url, $model, $key) {
                                                   /** @var ActionColumn $column */
                                                   return Html::a('<span class="fa fa-eye"></span>', $url, [
                                                       'title' => Yii::t('yii', 'Send mail'),
                                                       //        'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                                                       'data-method' => 'post',
                                                   ]);
                                               },
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
                                                        'data-action'=>'news/delete',
                                                        'data-info'=>['id'=>$model->id]]);
                                                },
                                               'update'=>function ($url, $model, $key) {
                                                   /** @var ActionColumn $column */
                                                   return Html::a('<span class="fa fa-pencil"></span>', '#', [
                                                       'title' => Yii::t('yii', 'Update'),
                                                       'class'=>'update_post',
    //                                                   'data-callback'=>'news.cb_update(data,sender)',
    //                                                   'data-action'=>'news/update',
                                                       'data-info'=>['model'=>$model,'tags'=>$model->tags]]);
                                               }
                                           ],
                                          'template'=>'{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}','options'=>['class'=>'news_post_buttons'],
                                       ],
                                   ]]);
?>