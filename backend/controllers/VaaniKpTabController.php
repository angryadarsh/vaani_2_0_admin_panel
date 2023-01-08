<?php

namespace backend\controllers;

use Yii;
use common\models\VaaniKpTab;
use common\models\VaaniKpTabSearch;
use common\models\vaani_kp_templete;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\bootstrap4\Html;
use common\models\User;
use yii\helpers\ArrayHelper;


/**
 * VaaniKpTabController implements the CRUD actions for VaaniKpTab model.
 */
class VaaniKpTabController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all VaaniKpTab models.
     * @return mixed
     */
    public function actionIndex($id)
    {   
        $id = User::decrypt_data($id);
        $searchModel = new VaaniKpTabSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->andWhere(['templete_id' =>$id]);
        // $templete_id = vaani_kp_templete::find()->select(['templete_id'])->where(['del_status' => User::STATUS_NOT_DELETED])->one();
        // if ($id) {
        //     # code...
        //     $templete_id = VaaniKpTab::find()->where(['del_status' => User::STATUS_NOT_DELETED, 'templete_id' => $id])->one();
        // }
        //echo "<pre>"; print_r($templete_id); exit;

        return $this->render('index', [
            'templete_id' => User::encrypt_data($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VaaniKpTab model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   
        $id = User::decrypt_data($id);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new VaaniKpTab model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {   
        $id = User::decrypt_data($id);
        $model = new VaaniKpTab();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if ($model->load($this->request->post())){
                $model->uploadfile = UploadedFile::getInstance($model, 'file');
                //file upload code
                if($model->uploadfile){
                    $model->file=$model->uploadfile->baseName.'.'.$model->uploadfile->extension;
                    $file_path=Yii::$app->basePath . '/web/uploads/knowledge_portal/client/'.$model->file;
                    $model->uploadfile->saveAs($file_path);
                    $model->uploadfile=$model->file;
                }
            }
            if($model->save()){
                Yii::$app->session->setFlash('success', 'tab created successfully.');
                return $this->redirect(['index', 'id' => User::encrypt_data($id)]);
            }
            else{
                //Yii::$app->session->setFlash('error', json_encode($error));
                echo "<pre>";print_r(($model->getErrors()));exit;
            }
        }else{
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'templete_id' => User::encrypt_data($id),
        ]);
    }

    /**
     * Updates an existing VaaniKpTab model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {   
        $id = User::decrypt_data($id);
          //echo "<pre>"; echo $id; echo"<br>";
        $model = $this->findModel($id);
        //echo $model->templete_id;

        $file = VaaniKpTab:: find()->select(['file'])->Where(['id' =>$id])->one();
        // $model->templete_id =
        // $model->tab_name =
        // $model->mandatory_info =
        // $model->additional_info =

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {

            if ($model->load($this->request->post())){
        // echo"<pre>";print_r($model);exit;
        // echo"<pre>"; print_r($model); exit;
                $model->uploadfile = UploadedFile::getInstance($model, 'file');
                //file upload code
                if($model->uploadfile){
                    $model->file=$model->uploadfile->baseName.'.'.$model->uploadfile->extension;
                    $file_path=Yii::$app->basePath . '/web/uploads/knowledge_portal/client/'.$model->file;
                    $model->uploadfile->saveAs($file_path);
                    $model->uploadfile=$model->file;
                }
                if($model->uploadfile){
                $model->uploadfile = $model['tempfile'];
                }else{
                    $model->file = $model['tempfile'];   
                }
            }
            if($model->save()){
                Yii::$app->session->setFlash('success', 'tab updated successfully.');
                return $this->redirect(['index', 'id' => User::encrypt_data($model->templete_id)]);
            }
            else{

                echo "<pre>";print_r(($model->getErrors()));exit;
            }
        }
        else{
            $model->loadDefaultValues();
        }
        return $this->render('update', [
            'model' => $model,
            'templete_id' => User::encrypt_data($model->templete_id),
            'file' => $file,
        ]);
    
    }

    /**
     * Deletes an existing VaaniKpTab model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {   
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = VaaniKpTab::STATUS_PERMANENT_DELETED;


        if($model->save()){
            Yii::$app->session->setFlash('success', 'tab deleted successfully.');
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        return $this->redirect(['index', 'id' => User::encrypt_data($model->templete_id)]);
    }

    /**
     * Finds the VaaniKpTab model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniKpTab the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniKpTab::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionArrangetab($id){
        //$id
        $id = User::decrypt_data($id);
        $model = new VaaniKpTab();
        $getTabList = VaaniKpTab::find()->select(['id','tab_name'])->where(['del_status' => User::STATUS_NOT_DELETED])->andWhere(['templete_id' => $id])->OrderBy('sequence')->asArray()->all();
        $tabs = ArrayHelper::map($getTabList , 'id', 'tab_name');
        $temp_name = vaani_kp_templete::find()->select(['template_name'])->Where(['del_status' => User::STATUS_NOT_DELETED,'templete_id' => $id])->one();


        return $this->render('arrangetab', [
            'model' => $model,
            'tabs' =>  $tabs,
            'id' => $id,
            'temp_name' => $temp_name,
        ]);
    }

    public function actionUpdateArrangeTab(){
            //$id = Yii::$app->request->post('id');
            
            $sequence = Yii::$app->request->post('sequences');
            foreach ($sequence as $key => $value) {

                $model = VaaniKpTab::find()->where(['id' => $value['id'],'del_status' => User::STATUS_NOT_DELETED])->one();
                if($model){
                    $model->updateAttributes(['sequence' => $value['seq']]);
                }
            }
            
    }

}
