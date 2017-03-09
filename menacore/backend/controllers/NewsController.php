<?php

namespace backend\controllers;




use Yii;
use common\models\Tag;
use common\models\Post;
use common\models\User;
use common\models\PostTag;
use common\components\Tools;
use yii\helpers\HtmlPurifier;
use yii\filters\VerbFilter;
use backend\components\Controller;
use yii\data\ActiveDataProvider;
use common\widgets\Langbutton;


/**
 * NewsController implements the CRUD actions for Post, Tags and Post_tags model.
 */
class NewsController extends Controller
{
    public $curL;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                    [
                        'actions' => ['loadpanel','istagallowed','deletetag','savepost','savetag','checkposturl',
                            'checktagurl','delete','updatetag','updatepost','togglepublished','tagpagination',
                            'postpagination','searchpost','searchtag','gettags'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)    {
        if (isset(Yii::$app->session['_worklang']) && Yii::$app->session['_worklang'] != 0) {
            $this->curL = Yii::$app->session['_worklang'];
        } else {
            $this->curL = $this->default_lang;
        }
        return parent::beforeAction($action);
    }
    public function actionLoadpanel(){
        $existing_tags=$this->getExistingTags();


        $postdataProvider = new ActiveDataProvider([
            'query' => Post::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $tagdataProvider = new ActiveDataProvider([
            'query' => Tag::find()->where(['id_lang'=>Yii::$app->session['_worklang']]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $langBtn = Langbutton::widget();
        $tgs=[];
        foreach(Tag::find()->where(['id_lang'=>$this->curL])->all() as $k=>$t){
            $tgs[$t->id]=$t->name;
        }

        $users=[];
        foreach(User::find()->all() as $k=>$t){
            $users[$t->id]=$t->username;
        }

        $postManagementHtml=$this->renderPartial('postManagement',[
            'postdataProvider'=> $postdataProvider,

            'tags'=>$tgs,
            'users'=>$users
            ]);

        $tagManagementHtml=$this->renderPartial('tagManagement',[
            'tagdataProvider'=> $tagdataProvider]);

        die(json_encode(
            array(
                'success' => true,
                'existing_tags'=>$existing_tags,
                'postManagementHtml'=>$postManagementHtml,
                'tagManagementHtml'=>$tagManagementHtml,
                'lang_btn' => $langBtn,
                )));

    }
    //****************************************************TAG FUNCTIONS
    public function actionGettags(){
        $tgs=[];
        foreach(Tag::find()->where(['id_lang'=>$this->curL])->all() as $k=>$t){
            $tgs[$t->id]=$t->name;
        }

        die(json_encode(
            array(
                'success' => true,
                'tags'=>$tgs)));
    }
    public function getExistingTags(){
        $tg=Tag::find()->where(['id_lang'=>$this->curL])->all();
        $tags =[];
        foreach($tg as $k=>$tag){
            $tags[]=$tag->name;
        }
        return $tags;
    }
    public function actionIstagallowed(){

        $data = Yii::$app->request->post();
        $tag=$data['tag'];
        $existing_tags=Tag::find()->where(['id_lang'=>$this->curL])->all();
        $isAllowed=true;
        foreach($existing_tags as $k=>$tg){
            if(strtolower($tag)==strtolower($tg->name)){
                $isAllowed=false;
            }
        }
        if($isAllowed){
            //creo tag
            $this->addTag($tag);

        }
        $existing_tags=$this->getExistingTags();
        die(json_encode(
            array(
                'success' => true,
                'isallowed'=>true,
                'existing_tags'=>$existing_tags)));
    }
    public function addTag($tag){

        $t=new Tag();
        if(is_string($tag)){
            $t->name=$tag;
            $t->friendly_url=$this->getValidTagUrl($tag,false);
        }else{

            $t->name=$tag['name'];
            $t->friendly_url=$this->getValidTagUrl($tag['friendly'],false);
            $t->description=$tag['description'];
        }
        $t->id_lang=$this->curL;

        if(!$t->save()){
            return false;
        }
        return true;

    }
    public function getValidTagUrl($friendly,$id){
        $friendlyUrl = Tools::format_uri($friendly);
        $notValid=false;

        if($id!=false){
            if ($something=Tag::find()->where(
                ['friendly_url' => $friendlyUrl])
                ->andWhere(['id_lang' => $this->curL])->andWhere(['<>','id',$id])->count()
            ) {
                $notValid=true;
            }
        }else{
            if ($something=Tag::find()->where(
                ['friendly_url' => $friendlyUrl])
                ->andWhere(['id_lang' => $this->curL])->count()
            ) {
                $notValid=true;
            }
        }
        if($notValid){
            $friendlyUrl .= "-2";
        }

        return $friendlyUrl;
    }
    public function actionChecktagurl(){
        $data = Yii::$app->request->post();
        if(isset($data['id'])){
            $id= $data['id'];
        }else{
            $id=false;
        }
        $friendlyUrl=$this->getValidTagUrl($data['value'],$id);

        die(json_encode(
            array(
                'success' => true,
                'friendlyUrl'=>$friendlyUrl)));
    }
    public function actionSavetag(){

        $data = Yii::$app->request->post();

        $succ=false;
        $tagManagementHtml=false;
        if($this->addTag($data)){

            $succ=true;
            $tagdataProvider = new ActiveDataProvider([
                'query' => Tag::find()->where(['id_lang'=>$this->curL]),
                'pagination' => [
                    'pageSize' => 10
                ],
            ]);
            $tagManagementHtml=$this->renderPartial('tagGridView',[
                'tagdataProvider'=> $tagdataProvider]);

        }
        die(json_encode(
            array(
                'success' =>$succ,
                'tagManagementHtml'=>$tagManagementHtml)));


    }
    public function actionUpdatetag(){
        $data = Yii::$app->request->post();
        $succ=false;
        $tagManagementHtml=false;
        if(isset($data['id'])) {
            $tag = Tag::find()->where(['id' => $data['id']])->one();
            $tag->name = $data['name'];
            $tag->description = $data['description'];
            $tag->friendly_url = $this->getValidTagUrl($data['friendly'],$data['id']);
            if ($tag->save()) {
                $succ=true;
                $tagdataProvider = new ActiveDataProvider([
                    'query' => Tag::find()->where(['id_lang'=>$this->curL]),
                    'pagination' => [
                        'pageSize' => 10
                    ],
                ]);
                $tagManagementHtml=$this->renderPartial('tagGridView',[
                    'tagdataProvider'=> $tagdataProvider]);
            }
        }

        die(json_encode(
            array(
                'success' => $succ,
                'tagManagementHtml'=>$tagManagementHtml)));


    }
    public function actionDeletetag(){
        $data = Yii::$app->request->post();
        $succ=false;
        if(isset($data['id'])) {
            $id=$data['id'];
            $post = Tag::find()->where(['id' => $id])->one();
            if($post->delete()){
                PostTag::deleteAll(['id_tag' => $id]);
                $succ=true;
            }
        }
        die(json_encode(
            array(
                'success' => $succ)));
    }
    //****************************************************TAG FUNCTIONS

    //****************************************************POST FUNCTIONS
    public function actionSavepost(){

        $data = Yii::$app->request->post();

        $post=new Post();
        $post->title=$data['title'];
        $post->content=HtmlPurifier::process($data['content']);
        $post->friendly_url=$this->getValidPostUrl($data['friendly'],false);
        $post->published=($data['published']?1:0);
        $post->author=Yii::$app->user->id;

        $succ=false;

        if($post->save()){
            $succ=true;
            if(isset($data['tags']) && sizeof($data['tags'])>0) {
                foreach ($data['tags'] as $k => $tag) {
                    $t = Tag::findByName($tag, $this->curL);
                    $asso = new PostTag();
                    $asso->id_post = $post->id;
                    $asso->id_tag = $t->id;
                    $asso->save();
                }
            }
        }
        $postManagementHtml=false;
        if($succ) {
            $postdataProvider = new ActiveDataProvider([
                'query' => Post::find(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            $tgs=[];
            foreach(Tag::find()->where(['id_lang'=>$this->curL])->all() as $k=>$t){
                $tgs[$t->id]=$t->name;
            }

            $users=[];
            foreach(User::find()->all() as $k=>$t){
                $users[$t->id]=$t->username;
            }
            $postManagementHtml = $this->renderPartial('postManagement', [
                'postdataProvider' => $postdataProvider,
                'tags'=>$tgs,
                'users'=>$users]);
        }
        die(json_encode(
            array(
                'success' => $succ,
                'postManagementHtml'=>$postManagementHtml)));


    }
    public function actionUpdatepost(){
        $data = Yii::$app->request->post();
        $succ=false;

        if(isset($data['id'])) {
            $post = Post::find()->where(['id' => $data['id']])->one();

            $post->title = $data['title'];
            $post->content = HtmlPurifier::process($data['content']);
            $post->friendly_url = $this->getValidPostUrl($data['friendly'],$data['id']);
            $post->published = ($data['published'] ? 1 : 0);
            if ($post->save()) {
                $succ = true;
                PostTag::deleteAll(['id_post'=>$post->id]);
                if (isset($data['tags']) && sizeof($data['tags']) > 0) {
                    foreach ($data['tags'] as $k => $tag) {
                        $t = Tag::findByName($tag, $this->curL);
                        $asso = new PostTag();
                        $asso->id_post = $post->id;
                        $asso->id_tag = $t->id;
                        $asso->save();

                    }
                }
            }
        }
        $postManagementHtml=false;
        if($succ) {
            $postdataProvider = new ActiveDataProvider([
                'query' => Post::find(),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            $tgs=[];
            foreach(Tag::find()->where(['id_lang'=>$this->curL])->all() as $k=>$t){
                $tgs[$t->id]=$t->name;
            }

            $users=[];
            foreach(User::find()->all() as $k=>$t){
                $users[$t->id]=$t->username;
            }
            $postManagementHtml = $this->renderPartial('postManagement', [
                'postdataProvider' => $postdataProvider,
                'tags'=>$tgs,
                'users'=>$users]);
        }

        die(json_encode(
            array(
                'success' => $succ,
                'postManagementHtml'=>$postManagementHtml)));


    }
    public function getValidPostUrl($friendly,$id){
        $friendlyUrl = Tools::format_uri($friendly);
        $notValid=false;
        if($id!=false){
            if ($something=Post::find()->where(
                ['friendly_url' => $friendlyUrl])->andWhere(['<>','id',$id])->count()
            ) {
                $notValid=true;
            }
        }else{
            if ($something=Post::find()->where(
                ['friendly_url' => $friendlyUrl])->count()
            ) {
                $notValid=true;
            }
        }
        if($notValid){
            $friendlyUrl .= "-2";
        }

        return $friendlyUrl;
    }
    public function actionCheckposturl(){
        $data = Yii::$app->request->post();
        if(isset($data['id'])){
           $id= $data['id'];
        }else{
            $id=false;
        }
        $friendlyUrl=$this->getValidPostUrl($data['value'],$id);

        die(json_encode(
            array(
                'success' => true,
                'friendlyUrl'=>$friendlyUrl)));
    }
    public function actionTogglepublished(){
        $data = Yii::$app->request->post();
        $succ=false;
        if(isset($data['id'])) {
            $post = Post::find()->where(['id' => $data['id']])->one();
            if($post->published){
                $post->published=0;
            }else{
                $post->published=1;
            }
            if($post->save()){
                $succ=true;
            }
        }
        die(json_encode(
            array(
                'success' =>$succ)));

    }
    public function actionDelete(){
        $data = Yii::$app->request->post();
        $succ=false;
        if(isset($data['id'])) {
            $id=$data['id'];
            $post = Post::find()->where(['id' => $id])->one();
            if($post->delete()){
                PostTag::deleteAll(['id_post' => $id]);
                $succ=true;
            }
        }
        die(json_encode(
            array(
                'success' => $succ)));
    }
    //****************************************************POST FUNCTIONS

    ///***************************************************Pagination functions
    public function actionTagpagination(){
        $data = Yii::$app->request->post();

        if(isset($data['page'])){
            $page=(int)$data['page'];
            $query= $this->getSearchTagQuery($data);
            $tagdataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                    'page'=>$page
                ],
            ]);
            $tagManagementHtml=$this->renderPartial('tagGridView',[
                'tagdataProvider'=> $tagdataProvider]);
            die(json_encode(
                array(
                    'success' =>true,
                    'tagManagementHtml'=>$tagManagementHtml)));
        }
        die(json_encode(
            array(
                'success' =>false)));
    }
    public function actionPostpagination(){
        $data = Yii::$app->request->post();

        if(isset($data['page'])){
            $page=(int)$data['page'];
            $query=$this->parseSearchPostQuery($data);
            $postdataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                    'page'=>$page
                ],
            ]);
            $tgs=[];
            foreach(Tag::find()->where(['id_lang'=>$this->curL])->all() as $k=>$t){
                $tgs[$t->id]=$t->name;
            }

            $users=[];
            foreach(User::find()->all() as $k=>$t){
                $users[$t->id]=$t->username;
            }
            $postManagementHtml=$this->renderPartial('postManagement',[
                'postdataProvider'=> $postdataProvider,
                'tags'=>$tgs,
                'users'=>$users]);
            die(json_encode(
                array(
                    'success' =>true,
                    'postManagementHtml'=>$postManagementHtml)));
        }
        die(json_encode(
            array(
                'success' =>false)));
    }
    public function actionSearchpost(){
        $data = Yii::$app->request->post();

        $query=$this->parseSearchPostQuery($data);

        $postdataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
        ]);
        $tgs=[];
        foreach(Tag::find()->where(['id_lang'=>$this->curL])->all() as $k=>$t){
            $tgs[$t->id]=$t->name;
        }

        $users=[];
        foreach(User::find()->all() as $k=>$t){
            $users[$t->id]=$t->username;
        }
        $postManagementHtml=$this->renderPartial('postManagement',            [
                'postdataProvider'=> $postdataProvider,
                'tags'=>$tgs,
                'users'=>$users
            ]);
        die(json_encode(
            array(
                'success' =>true,
                'postManagementHtml'=>$postManagementHtml)));
    }
    public function parseSearchPostQuery($data){
        $SMA=[];

        $id = isset($data['id'])?$data['id']:false;
        if ($id!=false && $id!="")
        {
            $SMA['id'] = [
                'rule'=>['id' => (int)$id]
            ];
        }

        $author = isset($data['author'])?$data['author']:false;
        if ($author!=false && $author!="")
        {
            $SMA['author'] = [
                'rule'=>['author' => (int)$author]
            ];
        }

        $published = isset($data['published'])?$data['published']:false;
        if ($published!==false && $published!="")
        {
            $SMA['published'] = [
                'rule'=>['published' => (int)$published]
            ];
        }

        $title =isset($data['title'])?$data['title']:false;
        if ($title!=false && $title!="")
        {
            $SMA['title'] = [
                'rule'=>['LIKE','title',$title]
            ];
        }

        $query=Post::find();

        $tag =isset($data['tag'])?$data['tag']:false;

        if ($tag!=false && $tag!="")
        {
            $query->joinWith('tags')
                ->where(['id_tag' =>(int)$tag]);

        }

        foreach ($SMA as $k=>$where)
        {
           $query->andWhere($where['rule']);
        }



        return $query;


    }
    public function actionSearchtag(){
        $data = Yii::$app->request->post();

        $query= $this->getSearchTagQuery($data);

        $tagdataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
        ]);
        $tagManagementHtml=$this->renderPartial('tagGridView',            [
            'tagdataProvider'=> $tagdataProvider
        ]);
        die(json_encode(
            array(
                'success' =>true,
                'tagManagementHtml'=>$tagManagementHtml)));
    }
    public function getSearchTagQuery($data){
        $query= Tag::find()->where(['id_lang'=>$this->curL]);

        $name =isset($data['name'])?$data['name']:false;
        if ($name!=false && $name!="")
        {
            $query->andWhere(['LIKE','name',$name]);
        }
        return $query;
    }
}
