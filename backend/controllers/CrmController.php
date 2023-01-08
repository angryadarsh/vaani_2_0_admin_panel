<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\VaaniCrm;
use common\models\VaaniCrmFields;
use common\models\EdasCampaign;
use common\models\User;
use common\models\VaaniCallAccess;
use yii\helpers\ArrayHelper;

/**
 * CrmController implements all the crm actions.
 */
class CrmController extends Controller
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
     * Finds the VaaniCrm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniCrm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniCrm::find()->where(['id' => $id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionIndex()
    {
        $crmModels = VaaniCrm::find()
                    ->where(['del_status' => User::STATUS_NOT_DELETED])
                    ->asArray()
                    ->all();

        return $this->render('index', [
            'data' => $crmModels
        ]);
    }

    /**
     * Add Crm action.
     * $flag (status values from VaaniActiveStatus::$statuses)
     * @return Response
     */
    public function actionAdd($id=null,$clone_id = null)
    {
        $user = Yii::$app->user->identity;

        $model = new VaaniCrm();
        
        $model->crm_id = User::newID('7','CRMG');
        
        $id = User::decrypt_data($id);

        if($id){
            $model = VaaniCrm::find()->where(['id' => $id])->one();
        }
        
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if($clone_id){

            }else{
                    $crm_id = $this->request->post()['VaaniCrm']['crm_id'];
                    if($crm_id){
                        $model = VaaniCrm::find()->where(['crm_id' => $crm_id])->one();
                    }
            }
            if($model->load($this->request->post())){
                if(!$model->crm_id) $model->crm_id = User::newID('7','CRMG');
                if($clone_id){
                    $model->crm_id = null;
                    $model->crm_id = User::newID('7','CRMG');
                }
                if($model->save()){
                    if($model->field_names){
                        if($clone_id){
                            $model->field_ids = null;
                        }
                        foreach ($model->field_names as $key => $field_name) {
                            
                            if($model->field_ids && $model->field_ids[$key]){
                                $field_model = VaaniCrmFields::find()->where(['crm_field_id' => $model->field_ids[$key]])->one();
                            }else{
                                $field_model = new VaaniCrmFields();
                                $field_model->crm_id = $model->crm_id;
                                $field_model->crm_field_id = User::newID('7','CRMF');
                            }
                            if($field_name){
                                $field_model->field_name = $field_name;
                                $field_model->field_label = $model->field_labels[$key];
                                $field_model->sequence = $model->sequences[$key];
                                $field_model->field_type = $model->field_types[$key];
                                $field_model->is_required = $model->field_required[$key];
                                $field_model->is_editable = $model->field_editable[$key];
                               
                                if(!$field_model->save()){
                                    foreach($field_model->errors as $error){
                                        Yii::$app->session->setFlash('error', ($error));
                                    }
                                }
                            }
                            
                        }
                    }
                    Yii::$app->session->setFlash('success', 'Crm Details Added successfully.');
                    return $this->redirect(['index']);
                }
            }
        }

        // fetch list of outbound campaigns
        $campaign_list = [];
        $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        if($campaign_ids){
            $campaigns = EdasCampaign::campaignsList($campaign_ids, null, [EdasCampaign::TYPE_OUTBOUND, EdasCampaign::TYPE_BLENDED]);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }else if(isset($_SESSION['client_connection'])){
            $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection'], [EdasCampaign::TYPE_OUTBOUND, EdasCampaign::TYPE_BLENDED]);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }
        // fetch all campaign id and name of current client.
        $campaign_id = EdasCampaign::find()->select(['campaign_id','campaign_name'])->where(['client_id' => $_SESSION['client_connection'],'del_status' => EdasCampaign::STATUS_NOT_DELETED])->asArray()->all(); 
        // fetch campaign_id of vaaniCrm which already hai fields.
        $campaign_clone = VaaniCrm::find()->select(['campaign_id'])->where(['del_status' => EdasCampaign::STATUS_NOT_DELETED])->asArray()->all();
        // convert to associative array.
        $array_campaign_id = ArrayHelper::getColumn($campaign_id, 'campaign_id');
        $array_campaign_clone = ArrayHelper::getColumn($campaign_clone, 'campaign_id');
        // find the campaign_id which does not hold any fields.
        $different_campaigns = array_diff($array_campaign_id,$array_campaign_clone);
        // get model of different campaigns.
        $campaign_ida = EdasCampaign::find()->where(['campaign_id' => $different_campaigns, 'client_id' => $_SESSION['client_connection'],'del_status' => EdasCampaign::STATUS_NOT_DELETED])->all(); 
        // fetch campaign list .
        $campaigns = EdasCampaign::campaignsList($campaign_ida, null, [EdasCampaign::TYPE_OUTBOUND, EdasCampaign::TYPE_BLENDED]);
        // map the campaign name and campaing id to the drop down list .
        $campaign_list_clone = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
                

        $field_types = VaaniCrmFields::$types;
        $access_values = VaaniCallAccess::$access_values;

        return $this->render('add', [
            'model' => $model,
            'campaigns' => $campaign_list,
            'field_types' => $field_types,
            'access_values' => $access_values,
            'clone_id' => $clone_id,
            'campaign_list_clone' => $campaign_list_clone,
        ]);
    }

    // fetch crm fields
    public function actionGetCrmFields()
    {
        
        $clone_id = Yii::$app->request->post()['clone_id'];
        if($clone_id){
            $campaign = VaaniCrm::find()->where(['id' => $clone_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->one();
            
            $model = VaaniCrm::find()->where(['campaign_id' => $campaign->campaign_id])
                ->andWhere(['del_status' => User::STATUS_NOT_DELETED])
                ->andFilterWhere(['queue_id' => $campaign->queue_id])
                ->one();
            
           
            
            $crmFields = [];
            $crm_id = null;
            
            if($model){
                $crm_id = $model->crm_id;
                if($model->crmFields){
                    $crmFields = $model->crmFields;
                }
            }
            
            return $this->renderPartial('_fields_list', [
                'data' => $crmFields,
                'crm_id' => $crm_id
            ]);
        }else{
            $campaign_id = Yii::$app->request->post()['campaign_id'];
            $queue_id = Yii::$app->request->post()['queue_id'];
            $model = VaaniCrm::find()->where(['campaign_id' => $campaign_id])
                ->andWhere(['del_status' => User::STATUS_NOT_DELETED])
                ->andFilterWhere(['queue_id' => $queue_id])
                ->one();
           
            
            $crmFields = [];
            $crm_id = null;
            
            if($model){
                $crm_id = $model->crm_id;
                if($model->crmFields){
                    $crmFields = $model->crmFields;
                }
            }
            
            return $this->renderPartial('_fields_list', [
                'data' => $crmFields,
                'crm_id' => $crm_id
            ]);
        }
    }

    // delete crm
    public function actionDelete($id)
    {
        if($id){
            $id = User::decrypt_data($id);
            $model = VaaniCrm::findOne($id);

            if($model){
                $model->del_status = User::STATUS_PERMANENT_DELETED;

                $success = 0;
                $campaign_name = $model->campaign->campaign_name;

                if($model->crmFields){
                    foreach($model->crmFields as $field){
                        $field->del_status = User::STATUS_PERMANENT_DELETED;
                        if($field->save()){
                            $success++;
                        }else{
                            foreach($field->errors as $error){
                                Yii::$app->session->setFlash('error', json_encode($error));
                            }
                        }
                    }
                }
                if($model->save()){
                    Yii::$app->session->setFlash('success', 'CRM deleted for the campaign ' . $campaign_name);
                }
            }
        }
        return $this->redirect(['index']);
    }

    // delete crm field
    public function actionDeleteField()
    {
        $id = Yii::$app->request->post()['id'];
        
        if($id){
            $model = VaaniCrmFields::find()->where(['crm_field_id' => $id])->one();
            if($model){
                $model->del_status = User::STATUS_PERMANENT_DELETED;
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
            return "Field NOT Found!";
        }
        return "Id NOT Found!";
    }
}