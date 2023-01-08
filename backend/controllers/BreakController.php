<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\VaaniCampaignBreak;
use common\models\search\VaaniCampaignBreakSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\EdasCampaign;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * BreakController implements the CRUD actions for VaaniCampaignBreak model.
 */
class BreakController extends Controller
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
     * Lists all VaaniCampaignBreak models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $searchModel = new VaaniCampaignBreakSearch();
        $searchModel->del_status = VaaniCampaignBreak::STATUS_NOT_DELETED;
        $searchModel->camp_listing = true;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $searchModel->campaign_id = ($user->campaignIds ? $user->campaignIds : []);
        }
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VaaniCampaignBreak model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->redirect(['add', 'c_id' => $model->campaign_id]);
    }

    /**
     * Creates a new VaaniCampaignBreak model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->redirect(['add']);
    }

    public function actionAdd($c_id=null)
    {
        $user = Yii::$app->user->identity;

        $model = new VaaniCampaignBreak();
        $model->campaign_id = User::decrypt_data($c_id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {
            
            if($model->break){
                foreach ($model->break as $key => $break) {
                    if($break){
                        if(isset($model->b_id) && isset($model->b_id[$key]) && $model->b_id[$key]){
                            $breakModel = $this->findModel($model->b_id[$key]);
                        }else{
                            $breakModel = new VaaniCampaignBreak();
                        }
                        if(!$breakModel->break_id) $breakModel->break_id = User::newID('7','BRK');
                        if(!$breakModel->campaign_id) $breakModel->campaign_id = $model->campaign_id;
                        $breakModel->break_name = $break;
                        $breakModel->is_active = $model->is_active[$key];
                        $breakModel->save();
                    }
                }
                Yii::$app->session->setFlash('success', 'Break added successfully.');
            }else{
                Yii::$app->session->setFlash('error', 'Kindly add atleast one break!');
            }
            return $this->redirect(['index']);
        } else {
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
            $model->loadDefaultValues();
        }

        // fetch list of campaigns
        $campaign_list = [];
        $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        if($campaign_ids){
            $campaigns = EdasCampaign::campaignsList($campaign_ids);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }else if(isset($_SESSION['client_connection'])){
            $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }

        return $this->render('create', [
            'model' => $model,
            'campaigns' => $campaign_list,
        ]);
    }

    /**
     * Updates an existing VaaniCampaignBreak model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->redirect(['add', 'c_id' => $model->campaign_id]);

        /* $user = Yii::$app->user->identity;


        if(!$model->break_id) $model->break_id = User::newID('7','BRK');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Break updated successfully.');
            return $this->redirect(['index']);
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        // fetch list of campaigns
        $campaign_list = [];
        $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        if($campaign_ids || (($user->userRole && $user->userRole->role_name == 'superadmin') || $user->role == 'superadmin')){
            $campaigns = EdasCampaign::campaignsList($campaign_ids);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }

        return $this->render('update', [
            'model' => $model,
            'campaigns' => $campaign_list,
        ]); */
    }

    /**
     * Deletes an existing VaaniCampaignBreak model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];
        $id = User::decrypt_data($id);

        if($id){
            $model = $this->findModel($id);
            if($model){
                $model->del_status = VaaniCampaignBreak::STATUS_PERMANENT_DELETED;
                if($model->save()){
                    return 'success';
                }else{
                    $result = '';
                    foreach($model->errors as $error){
                        $result .= json_encode($error) . '! ';
                    }
                    return $result;
                }
            }
            return "Break NOT Found!";
        }
        return "Id NOT Found!";
    }

    // delete all the breaks of the campaign - id(campaign_id)
    public function actionDeleteAll($id)
    {
        if($id){
            $breakModels = VaaniCampaignBreak::find()->where(['campaign_id' => $id])->andWhere(['del_status' => VaaniCampaignBreak::STATUS_NOT_DELETED])->all();

            if($breakModels){
                $success = 0;
                $campaign_name = $breakModels[0]->campaign->campaign_name;
                foreach($breakModels as $break){
                    $break->del_status = VaaniCampaignBreak::STATUS_PERMANENT_DELETED;
                    if($break->save()){
                        $success++;
                    }else{
                        foreach($break->errors as $error){
                            Yii::$app->session->setFlash('error', json_encode($error));
                        }
                    }
                }
                if($success > 0) Yii::$app->session->setFlash('success', 'Breaks deleted for the campaign ' . $campaign_name);
            }
        }
        return $this->redirect(['index']);
    }

    // fetch campaign breaks
    public function actionGetBreaks()
    {
        $campaign_id = Yii::$app->request->get()['campaign_id'];

        if($campaign_id){
            $breakModels = VaaniCampaignBreak::find()
                ->where(['campaign_id' => $campaign_id])
                ->andWhere(['del_status' => VaaniCampaignBreak::STATUS_NOT_DELETED])
                ->andWhere(['is_active' => VaaniCampaignBreak::STATUS_ACTIVE])->asArray()
                ->all();

            return $this->renderPartial('_break_list',[
                'data' => $breakModels
            ]);
        }
    }

    /**
     * Finds the VaaniCampaignBreak model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniCampaignBreak the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniCampaignBreak::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
