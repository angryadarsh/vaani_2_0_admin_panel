<?php

namespace backend\controllers;

use Yii;
use common\models\VaaniDispositions;
use common\models\VaaniCampaignQueue;
use common\models\search\VaaniDispositionsSearch;
use common\models\search\VaaniDispositionPlanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;
use common\models\EdasCampaign;
use common\models\VaaniDispositionPlan;
/**
 * DispositionController implements the CRUD actions for VaaniDispositions model.
 */
class DispositionController extends Controller
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
     * Lists all VaaniDispositions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VaaniDispositionPlanSearch();
        $searchModel->del_status = VaaniDispositions::STATUS_NOT_DELETED;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VaaniDispositions model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates/updates a new VaaniDispositions model.
     * $id = plan id
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd($id = null)
    {
        // $c_id = null;
        $user = Yii::$app->user->identity;

        $planModel = null;
        if($id){
            $planModel = VaaniDispositionPlan::find()->where(['plan_id' => User::decrypt_data($id)])->one();
        }
        
        if(!$planModel){
            $planModel = new VaaniDispositionPlan();
            $planModel->plan_id = User::newID('7','DIPL');
        }

        $model = new VaaniDispositions();
        $model->plan_id = $planModel->plan_id;
        // $model->campaign_id = User::decrypt_data($id);

        // fetch list of campaigns
        $campaign_list = [];
        /* $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        if($campaign_ids){
            $campaigns = EdasCampaign::campaignsList($campaign_ids);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }else if(isset($_SESSION['client_connection'])){
            $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        } */           

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {
            
            if($model->disposition_name){
                // check short code unique validation
                $short_codes = [];
                $repeated_short_codes = [];
                
                foreach ($model->short_code as $k => $sc) {
                    if(is_array($sc)){
                        foreach ($sc as $k2 => $sc2) {
                            if(is_array($sc2)){
                                foreach ($sc2 as $k3 => $sc3) {
                                    if(!empty($short_codes[$sc3]))
                                    {
                                        $repeated_short_codes[$sc3] = $sc3;
                                    }
                                    else 
                                    {
                                        $short_codes[$sc3] = $sc3;
                                    }
                                    
                                }
                            }else{
                                if(!empty($short_codes[$sc2]))
                                {
                                    $repeated_short_codes[$sc2] = $sc2;
                                }
                                else 
                                {
                                    $short_codes[$sc2] = $sc2;
                                }
                            }
                        }
                    }else{
                        if(!empty($short_codes[$sc]))
                        {
                            $repeated_short_codes[$sc] = $sc;
                        }
                        else 
                        {
                            $short_codes[$sc] = $sc;
                        }
                    }
                }
                
                if($repeated_short_codes){
                    Yii::$app->session->setFlash('error', 'Short Code ' . implode(", ", $repeated_short_codes) . ' is repeated. Must be unique!');
                    return $this->redirect(['add', 'id' => $id]);
                }
                
                if($planModel->load(Yii::$app->request->post()) && $planModel->save()){

                    $parent_disposition = null;
                    foreach ($model->disposition_name as $key => $disposition) {
                        $disposition_element = $disposition;
                        $disposition_short_code = $model->short_code[$key];
                        $disposition_type = $model->type[$key];
                        $disposition_id = $model->disposition_id[$key];
                        $disposition_parent_id = null;
                        $disposition_level = 1;
                        
                        if($disposition){
                            if(is_array($disposition_element) && $parent_disposition){
                                $sub_disposition = reset($disposition);
                                $sub_short_code = reset($disposition_short_code);
                                $sub_type = reset($disposition_type);
                                $sub_disposition_id = reset($disposition_id);
                                
                                $disposition_level = ((is_array($sub_disposition)) ? 3 : 2);

                                $disposition = ((is_array($sub_disposition)) ? reset($sub_disposition) : $sub_disposition);

                                $disposition_short_code = ( (is_array($sub_short_code)) ? reset($sub_short_code) : $sub_short_code );
                                
                                $disposition_type = ( (is_array($sub_type)) ? reset($sub_type) : $sub_type );
                                
                                $disposition_parent_id = $parent_disposition;

                                $disposition_id = ( (is_array($sub_disposition_id)) ? reset($sub_disposition_id) : $sub_disposition_id );
                            }
                            
                            if(isset($disposition_id) && $disposition_id){
                                $dispositionModel = $this->findModel($disposition_id);
                                
                                if(!$dispositionModel) $dispositionModel = new VaaniDispositions();
                            }else{
                                $dispositionModel = new VaaniDispositions();
                            }
                            if(!$dispositionModel->disposition_id) $dispositionModel->disposition_id = User::newID('7','DISP');
                            
                            if(!$dispositionModel->plan_id) $dispositionModel->plan_id = $planModel->plan_id;
                            // if(!$dispositionModel->campaign_id) $dispositionModel->campaign_id = $model->campaign_id;
                            // if(!$dispositionModel->queue_id) $dispositionModel->queue_id = $model->queue_id;
                            
                            $dispositionModel->disposition_name = $disposition;
                            $dispositionModel->short_code = $disposition_short_code;
                            $dispositionModel->type = $disposition_type;
                            $dispositionModel->parent_id = $disposition_parent_id;
                            $dispositionModel->level = $disposition_level;
                            $dispositionModel->sequence = $key+1;
                            
                            if($dispositionModel->save()){
                                if(!is_array($disposition_element)){
                                    $parent_disposition = $dispositionModel->id;
                                }else{
                                    if(isset($model->disposition_name[$key+1])){
                                        $next_disposition = $model->disposition_name[$key+1];
                                        $current_disposition = reset($disposition_element);
                                        
                                        if(is_array($next_disposition)){
                                            $next_disposition = reset($next_disposition);
                                            if(!is_array($next_disposition)){
                                                if(!is_array($current_disposition)){
                                                    // if next [] & current []
                                                    $parent_disposition = $dispositionModel->parent_id;
                                                }else{
                                                    // if next [] & current [][]
                                                    $parent_disposition = ($dispositionModel->parent ? $dispositionModel->parent->parent_id : $dispositionModel->parent_id);
                                                }
                                            }else{
                                                if(!is_array($current_disposition)){
                                                    // if next [][] & current []
                                                    $parent_disposition = $dispositionModel->id;
                                                }else{
                                                    // if next [][] & current [][]
                                                    $parent_disposition = $dispositionModel->parent_id;
                                                }
                                            }

                                        }else{
                                            // if next not array
                                            $parent_disposition = null;
                                        }
                                    }
                                }
                            }else{
                                foreach($dispositionModel->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                }
                            }
                        }
                    }
                    Yii::$app->session->setFlash('success', 'Disposition added successfully.');
                    return $this->redirect(['index']);
                }
            }else{
                Yii::$app->session->setFlash('error', 'Kindly add atleast one disposition!');
                return $this->redirect(['add', 'id' => $id]);
            }
        } else {
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
            $model->loadDefaultValues();
        }
        
        return $this->render('create', [
            'model' => $model,
            'planModel' => $planModel,
            // 'queuesList' => $queuesList,
            'campaigns' => $campaign_list,
        ]);
    }

    /**
     * Updates an existing VaaniDispositions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        return $this->redirect(['create', 'id' => $model->campaign_id]);
        
        /*
        $user = Yii::$app->user->identity;

        // fetch list of campaigns
        $campaign_list = [];
        $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        // fetch list of queues
        $queuesList = [];
        if($campaign_ids){
            $queues = VaaniCampaignQueue::queuesList($campaign_ids);

            $queuesList = (ArrayHelper::map($queues, 'queue_id', 'queue_name'));
        }
        
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'queuesList' => $queuesList,
        ]);
        */
    }

    /**
     * Deletes an existing VaaniDispositions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        if($id){
            $model = $this->findModel($id);
            if($model){
                $model->del_status = VaaniDispositions::STATUS_PERMANENT_DELETED;
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
            return "Disposition NOT Found!";
        }
        return "Id NOT Found!";
    }
    
    // delete all the Dispositions of the plan
    public function actionDeleteAll($id)
    {
        if($id){
            $id = User::decrypt_data($id);
            $planModel = VaaniDispositionPlan::find()->where(['plan_id' => $id])->andWhere(['del_status' => VaaniDispositions::STATUS_NOT_DELETED])->one();
            
            if($planModel){
                $planModel->del_status = VaaniDispositions::STATUS_PERMANENT_DELETED;

                if($planModel->save()){
                    if($planModel->dispositions){
                        $dispositionsModels = $planModel->dispositions;
                        $success = 0;
                        $plan_name = $dispositionsModels[0]->plan->name;
                        foreach($dispositionsModels as $dispositions){
                            $dispositions->del_status = VaaniDispositions::STATUS_PERMANENT_DELETED;
                            if($dispositions->save()){
                                $success++;
                            }else{
                                foreach($dispositions->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                }
                            }
                        }
                        if($success > 0) Yii::$app->session->setFlash('success', 'dispositions deleted for the Plan ' . $plan_name);
                    }
                    Yii::$app->session->setFlash('success', 'Plan deleted Successfully.');
                }else{
                    Yii::$app->session->setFlash('error', 'Something went wrong. Please try again.');
                }
            }else{
                Yii::$app->session->setFlash('error', 'Plan Not Found. Please try again.');
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the VaaniDispositions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniDispositions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniDispositions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // assign dispositions to queues
    public function actionAssign()
    {
        $user = Yii::$app->user->identity;

        // fetch list of campaigns
        $campaign_list = [];
        $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        // fetch list of queues
        $queuesList = [];
        if($campaign_ids){
            $queues = VaaniCampaignQueue::queuesList($campaign_ids);

            $queuesList = (ArrayHelper::map($queues, 'queue_id', 'queue_name', 'campaign.campaign_name'));
        }
        
        $model = new VaaniDispositions();
        
        return $this->render('create', [
            'model' => $model,
            'queuesList' => $queuesList,
        ]);
    }

    public function actionGetDispositions()
    {
        $plan_id = Yii::$app->request->get()['plan_id'];
        // $campaign_id = Yii::$app->request->get()['campaign_id'];
        // $queue_id = Yii::$app->request->get()['queue_id'];
       
        $dispositionsModels = VaaniDispositions::find()
            ->where(['plan_id' => $plan_id])
            ->andWhere(['del_status' => VaaniDispositions::STATUS_NOT_DELETED])
            ->andWhere(['parent_id' => NULL])
            ->orderBy('sequence')
            ->all();

        return $this->renderPartial('_dispositions_list',[
            'data' => $dispositionsModels
        ]);
    }
}
