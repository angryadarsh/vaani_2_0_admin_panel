<?php

namespace backend\controllers;

use Yii;
use common\models\VaaniQmsTemplate;
use common\models\VaaniQmsSheet;
use common\models\VaaniQmsParameter;
use common\models\User;
use common\models\EdasCampaign;
use common\models\VaaniAgentCallReport;
use common\models\VaaniAgentAuditSheet;
use common\models\VaaniAgentAuditMarkings;
use common\models\VaaniCallRecordings;
use common\models\search\VaaniQmsTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 * QmsController implements the CRUD actions for VaaniQmsTemplate & VaaniQmsSheet models.
 */
class QmsController extends Controller
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
     * Lists all VaaniQmsTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VaaniQmsTemplateSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $user = Yii::$app->user->identity;
        
        // fetch list of outbound campaigns
        $campaign_list = [];
        $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        if($campaign_ids){
            $campaigns = EdasCampaign::campaignsList($campaign_ids, null);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }else if(isset($_SESSION['client_connection'])){
            $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection']);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }

        $_SESSION['para_qms_id'] = null;
        $_SESSION['para_sheet_id'] = null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'campaign_list' => $campaign_list,
        ]);
    }

    /**
     * Displays a single VaaniQmsTemplate model.
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
     * Creates a new VaaniQmsTemplate model.
     * If creation is successful, the browser will be redirected to the 'add sheet' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VaaniQmsTemplate();
        $model->qms_id = User::newID('55','QMS');
        $model->pip_status = 'Yes,No';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['add-sheet']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new VaaniQmsSheet model.
     * If creation is successful, the browser will be redirected to the 'template list' page.
     * @return mixed
     */
    public function actionAddSheet($id=null)
    {
        $model = new VaaniQmsSheet();
        $model->type = 1;

        if($id){
            $model->qms_id = User::decrypt_data($id);
        }

        // fetch all qms templates for the dropdown
        $templates = ArrayHelper::map(VaaniQmsTemplate::allActiveQms(), 'qms_id', 'template_name');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $proceed = true;
                if($model->type == VaaniQmsSheet::TYPE_TRANSACTIONAL){
                    // check transactional sheets count
                    $prev_sheets = count($model->template->transactionalSheets);
                    if($prev_sheets > 0){
                        $proceed = false;
                        Yii::$app->session->setFlash('warning', 'Already a Transactional sheet exists against the template ' . $model->template->template_name);
                    }
                }else{
                    $model->out_of = null;
                }
                if ($proceed && $model->save()) {
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('add-sheet', [
            'model' => $model,
            'templates' => $templates,
        ]);
    }

    /**
     * Updates an existing VaaniQmsTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing VaaniQmsTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = User::STATUS_PERMANENT_DELETED;
        
        if($model->save()){
            Yii::$app->session->setFlash('success', 'QMS Template deleted successfully.');
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the VaaniQmsTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniQmsTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniQmsTemplate::findOne(['qms_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // FETCH SHEETS BASED ON SELECTED QMS TEMPLATE ID
    public function actionGetSheets()
    {
        $qms_id = Yii::$app->request->post()['qms_id'];
        $sheets = [];
        
        if($qms_id) $sheets = ArrayHelper::map(VaaniQmsSheet::fetchSheets($qms_id), 'id', 'sheet_name');

        return json_encode($sheets);
    }

    // FETCH PARAMETERS BASED ON SELECTED SHEET ID
    public function actionGetParameters()
    {
        $qms_id = Yii::$app->request->post()['qms_id'];
        $sheet_id = Yii::$app->request->post()['sheet_id'];
        $sheets = [];
        
        if($sheet_id) $sheets = ArrayHelper::map(VaaniQmsParameter::fetchParameters($qms_id, $sheet_id), 'id', 'name');

        return json_encode($sheets);
    }

    // Add parameter / sub-parameter
    public function actionAddParameter()
    {
        $model = new VaaniQmsParameter();
        $model->type = 1;

        if(isset($_SESSION['para_qms_id'])){
            $model->qms_id = $_SESSION['para_qms_id'];
        }
        if(isset($_SESSION['para_sheet_id'])){
            $model->sheet_id = $_SESSION['para_sheet_id'];
        }

        // fetch all qms templates for the dropdown
        $templates = ArrayHelper::map(VaaniQmsTemplate::allActiveQms(), 'qms_id', 'template_name');
        // fetch all qms sheets for the dropdown
        $sheets = [];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                if($model->type == VaaniQmsParameter::TYPE_SUB_PARAMETER)
                {
                    $model->name = $model->sub_name;
                }
                if($model->save()){
                    Yii::$app->session->setFlash('success', VaaniQmsParameter::$types[$model->type] . " added successfully.");
                    $_SESSION['para_qms_id'] = $model->qms_id;
                    $_SESSION['para_sheet_id'] = $model->sheet_id;
                    return $this->redirect(['add-parameter']);
                }else{
                    foreach($model->errors as $error){
                        Yii::$app->session->setFlash('error', json_encode($error));
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('add-parameter', [
            'model' => $model,
            'templates' => $templates,
            'sheets' => $sheets
        ]);
    }

    // PREVIEW AUDIT SHEET
    public function actionAuditSheet()
    {
        // fetch all qms templates for the dropdown
        $templates = ArrayHelper::map(VaaniQmsTemplate::allActiveQms(), 'qms_id', 'template_name');

        if ($this->request->isPost)
        {
            $post = $this->request->post();
            if(isset($post['qms_id']) && $post['qms_id'] && isset($post['sheet_id']) && $post['sheet_id'])
            {
                // $parameters = VaaniQmsParameter::fetchParameters($post['qms_id'], $post['sheet_id']);
                $sheetModel = VaaniQmsSheet::find()->where(['id' => $post['sheet_id']])->one();
                
                return $this->render('audit-sheet-view', [
                    'qms_id' => $post['qms_id'],
                    'sheet_id' => $post['sheet_id'],
                    // 'parameters' => $parameters,
                    'sheetModel' => $sheetModel,
                ]);
            }
        }
        
        return $this->render('audit-sheet', [
            'templates' => $templates,
        ]);
    }

    // fetch campaign sheets
    public function actionGetCampaignSheets()
    {
        $campaign = Yii::$app->request->post()['campaign'];
        if($campaign){
            $campaignModel = EdasCampaign::find()->where(['campaign_name' => $campaign])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->one();

            if($campaignModel){
                $sheets = VaaniQmsSheet::find()
                    ->leftJoin('vaani_qms_template', '`vaani_qms_sheet`.`qms_id` = `vaani_qms_template`.`qms_id`')
                    ->where(['vaani_qms_template.qms_id' => $campaignModel->qms_id])
                    ->andWhere(['vaani_qms_sheet.del_status' => User::STATUS_NOT_DELETED])
                    ->all();

                if($sheets){
                    return json_encode(ArrayHelper::map($sheets, 'id', 'sheet_name'));
                }
            }
        }
        return false;
    }

    // FETCH SHEET TABS & PARAMETERS
    public function actionGetSheetParameters()
    {
        $sheet_id = Yii::$app->request->get()['sheet_id'];
        $data = [];
        $para_count = 1;

        if($sheet_id){
            $sheetModel = VaaniQmsSheet::find()->where(['id' => $sheet_id])->one();
            if($sheetModel && $sheetModel->parameters){
                foreach ($sheetModel->parameters as $key => $parameter) {
                    if($parameter->subParameters){
                        foreach ($parameter->subParameters as $k => $sub_para) {
                            $data[$para_count]['parameter'] = $sub_para->parentParameter->name;
                            $data[$para_count]['sub_name'] = $sub_para->name;
                            $data[$para_count]['sub_type'] = VaaniQmsParameter::$sub_types[$sub_para->sub_type];
                            $data[$para_count]['score'] = $sub_para->score;
                            $data[$para_count]['remarks'] = $sub_para->remarks;
                            
                            $para_count++;
                        }
                        /* $data[] = ArrayHelper::toArray($parameter->subParameters, [
                            'common\models\VaaniQmsParameter' => [
                                'id',
                                'name',
                                'score' => function ($sub_parameter) {
                                    return explode(",", $sub_parameter->score);
                                },
                                'remarks',
                            ],
                        ]); */
                    }
                }
            }
            return json_encode($data);
        }
    }

    public function actionGetEvaluateSheet()
    {
        $sheet_id = Yii::$app->request->get()['sheet_id'];
        $unique_id = Yii::$app->request->get()['unique_id'];

        $callModel = VaaniCallRecordings::find()->where(['unique_id' => $unique_id])->one();
        $evaluated_sheet = VaaniAgentAuditSheet::find()->where(['unique_id' => $unique_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->orderBy('id DESC')->one();
        
        $sheetModel = VaaniQmsSheet::find()->where(['id' => $sheet_id])->one();

        if($callModel && $sheetModel){
            return $this->renderPartial('/report/_evaluate-sheet', [
                'callModel' => $callModel,
                'sheetModel' => $sheetModel,
                'evaluated_sheet' => $evaluated_sheet,
                'is_preview' => false
            ]);
        }
    }

    // evaluate call recording
    public function actionEvaluate()
    {
        $model = new VaaniAgentAuditSheet();
        $model->audit_id = User::newID('7','AUD');
        $model->status = VaaniAgentAuditSheet::STATUS_PENDING;
        
        if($this->request->isPost)
        {
            $post_data = $this->request->post();

            $model->agent_id = $post_data['agent_id'];
            $model->sheet_id = $post_data['sheet_id'];
            $model->campaign = $post_data['campaign_name'];
            $model->language = $post_data['language'];
            $model->audit_type = $post_data['audit_type'];
            $model->location = $post_data['location'];
            $model->call_duration = $post_data['call_duration'];
            $model->call_date = $post_data['call_date'];
            $model->week = $post_data['week'];
            $model->month = $post_data['month'];
            // $model->call_id = $post_data['call_id'];
            $model->analysis_finding = $post_data['analysis_finding'];
            $model->agent_type = $post_data['agent_type'];
            $model->unique_id = $post_data['unique_id'];
            $model->disposition = $post_data['disposition'];
            $model->sub_disposition = $post_data['sub_disposition'];
            $model->pip_status = $post_data['pip_status'];
            $model->categorization = $post_data['categorization'];
            $model->action_status = $post_data['action_status'];
            $model->gist_of_case = (isset($post_data['gist_of_case']) ? $post_data['gist_of_case'] : null);
            $model->resolution_provided = (isset($post_data['resolution_provided']) ? $post_data['resolution_provided'] : null);
            $model->areas_of_improvement = (isset($post_data['areas_of_improvement']) ? $post_data['areas_of_improvement'] : null);
            $model->reason_for_fatal_call = (isset($post_data['reason_for_fatal_call']) ? $post_data['reason_for_fatal_call'] : null);
            $model->quality_score = (isset($post_data['quality_score']) ? $post_data['quality_score'] : 0);
            $model->out_of = (isset($post_data['out_of']) ? $post_data['out_of'] : 0);
            $model->final_score = (isset($post_data['final_score']) ? $post_data['final_score'] : 0);
            $model->yes_count = (isset($post_data['yes_count']) ? $post_data['yes_count'] : 0);
            $model->total_percent = $post_data['total_percent'];

            $model->start_time = $post_data['audit_start_time'];
            $model->end_time = date("Y-m-d H:i:s");

            $diff = strtotime($model->end_time) - strtotime($model->start_time);
            $model->audit_duration = gmdate('H:i:s', ($diff));

            // fetch call recording model to set status to audited
            $callRecording = VaaniCallRecordings::find()->where(['unique_id' => $model->unique_id])->one();

            // highlighter marker
            $highlight = [];
            if(isset($post_data['audio_marker_minute']) && isset($post_data['audio_marker_seconds']) && $post_data['audio_marker_minute'] && $post_data['audio_marker_seconds']){
                foreach ($post_data['audio_marker_minute'] as $min_key => $min_marker) {
                    $highlight[] = $min_marker . ":" . $post_data['audio_marker_seconds'][$min_key];
                }
            }
            $model->rec_markers = implode(",", $highlight);

            $message = [];
            
            if($model->save()){
                // set call recording model status to audited
                if($callRecording){
                    $callRecording->status = VaaniCallRecordings::STATUS_AUDITED;
                    $callRecording->save();
                }

                // save parameters markings
                $parameter_markings = $post_data['markings'];
                foreach ($parameter_markings as $sub_id => $marking) {
                    $auditParamModel = new VaaniAgentAuditMarkings();
                    $auditParamModel->audit_id = $model->audit_id;
                    $auditParamModel->sheet_id = $model->sheet_id;
                    $auditParamModel->agent_id = $model->agent_id;
                    $auditParamModel->unique_id = $model->unique_id;
                    $auditParamModel->sub_parameter_id = $sub_id;
                    $auditParamModel->marking = $marking;
                    $auditParamModel->remarks = $post_data['remarks'][$sub_id];
                    if(!$auditParamModel->save()){
                        foreach ($auditParamModel->errors as $k => $error) {
                            echo "<pre>"; print_r($error);exit;
                        }
                        $message[] = "Audit Parameter not saved properly.";
                    }
                }
                $message[] =  "Audit saved successfully.";
                return implode(" ", $message);
            }
            // return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
        return false;
    }

    // fetch list of pending feedback audits
    public function actionPending()
    {
        $query = VaaniCallRecordings::find()
                ->where(['vaani_call_recordings.status' => VaaniCallRecordings::STATUS_AUDITED])
                ->joinWith('audit')
                ->orderBy('date_created desc')
                ->all();
        
        return $this->render('pending-audit-feedback', [
            'models' => $query
        ]);
    }

    // fetch list of disputes audits
    public function actionDisputes()
    {
        $query = VaaniCallRecordings::find()
                ->where(['status' => VaaniCallRecordings::STATUS_DISPUTED_INITIATED])
                ->all();
        
        return $this->render('disputes-audit', [
            'models' => $query
        ]);
    }

    // fetch assigned campaigns to the qms template
    public function actionGetAssignedCampaigns()
    {
        $qms_id = Yii::$app->request->post()['qms_id'];

        if($qms_id){
            $qms_id = User::decrypt_data($qms_id);
            $model = $this->findModel($qms_id);
            // echo "<pre>"; print_r($model);exit;

            if($model){
                if($model->campaigns){
                    $campaign_ids = ArrayHelper::getColumn($model->campaigns, 'campaign_id');
                    return json_encode($campaign_ids);
                }
            }
        }
        return false;
    }

    // assign campaigns to qms template
    public function actionAssignCampaigns()
    {
        $qms_id = Yii::$app->request->post()['qms_id'];
        $campaigns = Yii::$app->request->post()['campaigns'];
        $message = '';

        if($qms_id && $campaigns){
            $qms_id = User::decrypt_data($qms_id);
            $qmsModel = $this->findModel($qms_id);

            if($qmsModel){
                foreach ($campaigns as $key => $campaign_id) {
                    $model = EdasCampaign::find()->where(['campaign_id' => $campaign_id])->one();
                    if($model){
                        $model->qms_id = $qmsModel->qms_id;
                        if(!$model->save()){
                            $message .= ' Cannot assign the Qms to the campaign ' . $model->campaign_name . '.';
                        }
                    }
                }
                if($message){
                    Yii::$app->session->setFlash('error', $message);
                }else{
                    Yii::$app->session->setFlash('success', 'Qms assigned to the campaigns successfully.');
                }
            }
        }
        return $this->redirect(['index']);
    }

    // list of sheets in qms template
    public function actionSheets($id)
    {
        $id = User::decrypt_data($id);
        $qms = $this->findModel($id);

        $models = VaaniQmsSheet::find()->where(['qms_id' => $id])->all();

        return $this->render('sheets', [
            'models' => $models,
            'qms' => $qms,
        ]);
    }

    // set set-inactive-sheet
    public function actionSetSheet($qms_id, $id, $status)
    {
        $id = User::decrypt_data($id);

        $model = VaaniQmsSheet::findOne($id);

        if($model){
            $proceed = true;

            if($model->type == VaaniQmsSheet::TYPE_TRANSACTIONAL && $status = 1){
                // check transactional sheets count
                $prev_sheets = count($model->template->transactionalSheets);
                if($prev_sheets > 0){
                    $proceed = false;
                    Yii::$app->session->setFlash('warning', 'Already a Transactional sheet exists against the template ' . $model->template->template_name);
                }
            }

            if($proceed){
                $model->del_status = $status;
                if($model->save()){
                    if($model->del_status == User::STATUS_PERMANENT_DELETED){
                        Yii::$app->session->setFlash('success', 'Sheet set to inactive.');
                    }else{
                        Yii::$app->session->setFlash('success', 'Sheet set to active.');
                    }
                }else{
                    echo "<pre>"; print_r($model->errors); exit;
                }
            }
        }

        return $this->redirect(['sheets', 'id' => $qms_id]);
    }

    // fetch sheet details
    public function actionSheetDetails()
    {
        $sheet_id = Yii::$app->request->get()['sheet_id'];

        if($sheet_id){
            $model = VaaniQmsSheet::find()->where(['id' => $sheet_id])->asArray()->one();
            if($model){
                return json_encode($model);
            }
        }
        return false;
    }
}
