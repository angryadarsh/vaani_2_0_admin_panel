<?php

namespace backend\controllers;

use Yii;
use common\models\VaaniLeadDump;
use common\models\search\VaaniLeadBatchSearch;
use common\models\search\VaaniLeadDumpSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Exception;
use yii\filters\VerbFilter;
use common\models\EdasCampaign;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use common\models\VaaniLeadMapping;
use common\models\VaaniLeadBatch;
use common\models\VaaniCrm;
use common\models\VaaniCrmLeadMapping;
use common\models\VaaniCampaignQueue;
use common\models\VaaniBatchFilter;
use common\models\VaaniRechurnDispositions;
use common\models\VaaniDispositions;
use common\models\VaaniActiveLead;
use common\models\VaaniRechurnFilter;
use common\models\VaaniCampDispositions;

/**
 * LeadController implements the CRUD actions for VaaniLeadBatch model.
 */
class LeadController extends Controller
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
     * Lists all VaaniLeadBatch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VaaniLeadBatchSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // batch filter model
        $filterModel = new VaaniBatchFilter();
        $batchModel = new VaaniLeadBatch();
        // echo "<pre>"; print_r($filterModel);exit;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'filterModel' => $filterModel,
            'batchModel' => $batchModel,
        ]);
    }

    /**
     * Displays a single VaaniLeadDump model.
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
     * Creates a new VaaniLeadDump model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VaaniLeadDump();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VaaniLeadDump model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing VaaniLeadDump model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    // UPLOAD LEADS
    public function actionUpload()
    {
        $user = Yii::$app->user->identity;

        $model = new VaaniLeadBatch();
        $model->mapping_id = User::newID('7','MAP');
        $model->batch_id = User::newID('7','BATCH');

        // mapping model
        $mapping_model = new VaaniLeadMapping();
        $mapping_model->mapping_id = $model->mapping_id;
        $mapping_model->batch_id = $model->batch_id;
   
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {
            // fetch queue of the campaign
            $queues = VaaniCampaignQueue::queuesList($model->campaign_id);
            $queue_id = ArrayHelper::getColumn($queues, 'queue_id');
            $model->queue_id = (($queue_id && isset($queue_id[0])) ? $queue_id[0] : null);
            
            $lead_mapping_data = $this->request->post()['VaaniLeadMapping'];

            if($model->save()){

                $primary_index = null;

                if($lead_mapping_data){
                    if($lead_mapping_data['field_name']){
                        $field_count = 0;
                        foreach ($lead_mapping_data['field_name'] as $index => $field) {
                            if($field){
                                /* $mapped_model = null;
                                if(isset($lead_mapping_data['id'][$index]) && $lead_mapping_data['id'][$index]){
                                    $mapped_model = VaaniLeadMapping::findOne($lead_mapping_data['id'][$index]);
                                }
                                if(!$mapped_model){
                                } */

                                $mapped_model = new VaaniLeadMapping();
                                $mapped_model->field_id = User::newID('7','FIELD');
                                $mapped_model->mapping_id = $model->mapping_id;
                                $mapped_model->campaign_id = $model->campaign_id;
                                $mapped_model->batch_id = $model->batch_id;
                                $mapped_model->field_name = $field;
                                $mapped_model->field_index = $field_count;

                                $mapped_model->is_primary = (isset($lead_mapping_data['is_primary'][$index]) ? $lead_mapping_data['is_primary'][$index] : 0);
                                
                                $mapped_model->is_callable = ($mapped_model->is_primary ? 1 : (isset($lead_mapping_data['is_callable'][$index]) ? $lead_mapping_data['is_callable'][$index] : 0));

                                $mapped_model->secondary_sequence = ($mapped_model->is_primary ? 1 : (isset($lead_mapping_data['secondary_sequence'][$index]) ? $lead_mapping_data['secondary_sequence'][$index] : 0));

                                if($mapped_model->save()){
                                    /* $crm_mapping = VaaniCrmLeadMapping::find()->where(['field_id' => $mapped_model->field_id])->one();
                                    if(empty($crm_mapping)){
                                    } */
                                    $crm_mapping = new VaaniCrmLeadMapping();
                                    $crm_mapping->crm_field_id = (isset($lead_mapping_data['crm_field_id'][$index]) ? $lead_mapping_data['crm_field_id'][$index] : null);
                                    
                                    $crm_mapping->field_id = $mapped_model->field_id;
                                    $crm_mapping->mapping_id = $mapped_model->mapping_id;
                                    
                                    if(!$crm_mapping->save()){
                                        foreach($crm_mapping->errors as $error){
                                            Yii::$app->session->setFlash('error', ($error));
                                        }
                                    }
                                }
                                $field_count++;
                            }
                        }
                    }
                    $primary_index = array_key_first($lead_mapping_data['is_primary']);
                }
            
                $model->upload_lead_file = UploadedFile::getInstance($model, 'upload_lead_file');

                if($model->upload_lead_file){
                    $model->upload_lead_file->saveAs(Yii::$app->basePath . '/web/uploads/lead_upload/' . $model->upload_lead_file->name);
                    
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $reader->setReadDataOnly(true);
                    $spreadsheet = $reader->load(Yii::$app->basePath . '/web/uploads/lead_upload/' . $model->upload_lead_file->name);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $cellData = $worksheet->toArray();
                
                    if($cellData){
                        $columns = array_shift($cellData);
                        
                        foreach ($cellData as $key => $column_value) {
                            // check if value exists
                            $is_record = false;
                            foreach ($column_value as $k => $cell_value) {
                                $cell_value = trim($cell_value);
                                if($cell_value){
                                    $is_record = true;
                                    break;
                                }
                            }
                            if($is_record){
                                $lead_model = new VaaniLeadDump();
                                $lead_model->lead_id = User::newID('7','LEAD');
                                $lead_model->batch_id = $model->batch_id;
                                $lead_model->campaign_id = $model->campaign_id;
                                $lead_model->primary_no = (($primary_index && isset($column_value[$primary_index])) ? $column_value[$primary_index] : null);
                                
                                $lead_data = array();
                                /* foreach ($columns as $key => $value) {
                                    if($column_value[$key]){
                                        // $lead_data[$value] = $column_value[$key];
                                    }
                                } */

                                $lead_model->lead_data = json_encode($column_value, JSON_FORCE_OBJECT);

                                if(!$lead_model->save()){
                                    foreach($lead_model->errors as $error){
                                        Yii::$app->session->setFlash('error', ($error));
                                    }
                                }else{
                                    Yii::$app->session->setFlash('success', 'Lead Uploaded & mapped successfully.');
                                }
                            }
                        }
                    }
                }
                return $this->redirect(['index']);
            }
        }

        // fetch list of campaigns
        $campaign_list = [];
        $campaign_ids = ((($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin') ? $user->campaignIds : null);
        
        if($campaign_ids){
            $campaigns = EdasCampaign::campaignsList($campaign_ids, null, [EdasCampaign::TYPE_OUTBOUND, EdasCampaign::TYPE_BLENDED]);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }else if(isset($_SESSION['client_connection'])){
            $campaigns = EdasCampaign::campaignsList(null, $_SESSION['client_connection'], [EdasCampaign::TYPE_OUTBOUND, EdasCampaign::TYPE_BLENDED]);
            $campaign_list = ArrayHelper::map($campaigns, 'campaign_id', 'campaign_name');
        }

        return $this->render('upload', [
            'model' => $model,
            'campaigns' => $campaign_list,
        ]);
    }

    // fetch column data for lead mapping
    public function actionGetLeadColumns()
    {
        $lead_file = UploadedFile::getInstanceByName('lead_file');
        $is_previous = Yii::$app->request->post()['is_previous'];
        $campaign_id = Yii::$app->request->post()['campaign_id'];
        
        if(!$is_previous && $lead_file){
            $lead_file->saveAs(Yii::$app->basePath . '/web/uploads/lead_upload/' . $lead_file->name);
            
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load(Yii::$app->basePath . '/web/uploads/lead_upload/' . $lead_file->name);
            $worksheet = $spreadsheet->getActiveSheet();
            $cellData = $worksheet->toArray();
            
            $columns = [];
            if($cellData){
                $columns = $cellData[0];
            }

            // fetch crm fields
            $crm_fields = [];
            $crm_id = null;
            if($campaign_id){
                $campaign_crm = VaaniCrm::find()->where(['campaign_id' => $campaign_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->orderBy('id DESC')->one();
                // echo "<pre>"; print_r($campaign_crm);exit;
                if($campaign_crm){
                    $crm_id = $campaign_crm->crm_id;
                    $crm_fields = ArrayHelper::map($campaign_crm->crmFields, 'crm_field_id', 'field_label');
                }else{
                    Yii::$app->session->setFlash('error', 'No CRM details exist for the campaign! Kindly add the details.');
                }
            }

            return $this->renderPartial('_mapping_list',[
                'columns' => $columns,
                'mapping_data' => null,
                'crm_fields' => $crm_fields,
                'crm_id' => $crm_id,
            ]);
        }
    }

    // fetch campaign wise lead mapping data
    public function actionGetLeadMapping()
    {
        $campaign_id = Yii::$app->request->post()['campaign_id'];
        if($campaign_id){
            // fetch latest batch of the campaign
            $batch = VaaniLeadBatch::find()->where(['campaign_id' => $campaign_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->orderBy('id DESC')->asArray()->one();

            $mapping_models = null;
            if($batch){
                $mapping_models = VaaniLeadMapping::find()->where(['mapping_id' => $batch['mapping_id']])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->asArray()->all();
            }

            // fetch crm fields
            $crm_fields = [];
            $crm_id = null;
            $campaign_crm = VaaniCrm::find()->where(['campaign_id' => $campaign_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->orderBy('id DESC')->one();
            
            if($campaign_crm){
                $crm_id = $campaign_crm->crm_id;
                $crm_fields = ArrayHelper::map($campaign_crm->crmFields, 'crm_field_id', 'field_label');
            }else{
                Yii::$app->session->setFlash('error', 'No CRM details exist for the campaign! Kindly add the details.');
            }

            if($mapping_models){
                return $this->renderPartial('_mapping_list',[
                    'columns' => null,
                    'mapping_data' => $mapping_models,
                    'crm_fields' => $crm_fields,
                    'crm_id' => $crm_id
                ]);
            }
        }
    }

    // set active/inactive of lead batch
    public function actionSetBatchInactive($id)
    {
        $user = Yii::$app->user->identity;

        if($id){
            $model = $this->findModel($id);
            $model->is_active = VaaniLeadBatch::STATUS_INACTIVE;
            if($model->save()){
                // fetch dialed leads from active table
                $dialed_leads = VaaniActiveLead::find()->where(['batch_id' => $model->batch_id])->andWhere(['status' => 2])->select(['lead_id'])->asArray()->all();

                $dialed_lead_ids = '"'. implode('", "' ,ArrayHelper::getColumn($dialed_leads, 'lead_id')) . '"';

                // change dialed leads status in lead dump table
                $result = \Yii::$app->db->createCommand("
                    UPDATE `vaani_lead_dump` SET
                    status = 2 WHERE batch_id = '".$model->batch_id."' and lead_id IN (".$dialed_lead_ids.");
                ")
                ->execute();

                // remove leads from basket table
                $result = \Yii::$app->db->createCommand("
                    DELETE FROM `vaani_lead_basket`
                    WHERE batch_id = '".$model->batch_id."';
                ")
                ->execute();
                // remove leads from active table
                $result = \Yii::$app->db->createCommand("
                    DELETE FROM `vaani_active_lead`
                    WHERE batch_id = '".$model->batch_id."';
                ")
                ->execute();

                // delete batch filter 
                $batch_filter = VaaniBatchFilter::find()->where(['batch_id' => $model->batch_id])->andWhere(['is_active' => VaaniBatchFilter::STATUS_ACTIVE, 'del_status' => User::STATUS_NOT_DELETED])->orderBy('id DESC')->one();
                if($batch_filter){
                    $batch_filter->is_active = VaaniBatchFilter::STATUS_INACTIVE;
                    $batch_filter->del_status = User::STATUS_PERMANENT_DELETED;
                    $batch_filter->save();
                }

                Yii::$app->session->setFlash('success', $model->batch_name . ' is now ' . VaaniLeadBatch::$statuses[$model->is_active] . '.');
            }else{
                Yii::$app->session->setFlash('error', 'Something went wrong! Please try again.');
            }
        }
        return $this->redirect(['index']);
    }

    // get batch fields
    public function actionGetBatchFields()
    {
        $batch_id = Yii::$app->request->post()['batch_id'];
        if($batch_id){
            $batch_fields = VaaniLeadMapping::find()->where(['batch_id' => $batch_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->asArray()->all();

            if($batch_fields){
                $fields = ArrayHelper::getColumn($batch_fields, 'field_name');
                $fields = implode('<br> ', $fields);
                return $fields;
            }
        }
        return false;
    }

    // Test query
    public function actionTestQuery()
    {
        $batch_id = Yii::$app->request->post()['batch_id'];
        $filter_query = Yii::$app->request->post()['filter_query'];
        $sort_query = Yii::$app->request->post()['sort_query'];

        if($batch_id){

            $batch_fields = VaaniLeadMapping::find()->where(['batch_id' => $batch_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->asArray()->all();

            if($batch_fields){
                $fields = ArrayHelper::map($batch_fields, 'field_index', 'field_name');

                // SELECT QUERY FIELDS
                $select_query = "id, lead_id, batch_id, campaign_id, primary_no";
                $batch_field_count = 0;
                foreach ($fields as $key => $field) {
                    $select_query .= ", ";
                    // if($batch_field_count > 0 && ($batch_field_count != count($fields))){
                    // }

                    $select_query .= "JSON_UNQUOTE(JSON_EXTRACT(lead_data, '$.\"".$key."\"')) AS '" . $field . "'";
                    $batch_field_count++;
                }

                // WHERE QUERY
                $where_query = " batch_id = '" . $batch_id . "'";
                if($filter_query){
                    $filter_query = explode(','.PHP_EOL, $filter_query);
                    foreach ($filter_query as $k => $filter) {
                        $filter_string = explode(" ", trim($filter));
                        $column_name = (isset($filter_string[0]) ? $filter_string[0] : null);
                        $column_key = array_search($column_name, $fields);

                        $operator = (isset($filter_string[1]) ? $filter_string[1] : null);
                        $value = (isset($filter_string[2]) ? $filter_string[2] : null);

                        if($k != count($filter_query)) $where_query .= " AND ";
                        $where_query .= "JSON_UNQUOTE(JSON_EXTRACT(lead_data, '$.\"".$column_key."\"')) " . $operator . " " . $value;
                    }
                }

                // SORT QUERY
                $sorting_query = null;
                if($sort_query){
                    $sort_query = explode(','.PHP_EOL, $sort_query);
                    foreach ($sort_query as $k => $sort) {
                        $sort_string = explode(" ", trim($sort));
                        $column_name = (isset($sort_string[0]) ? $sort_string[0] : null);
                        $operator = (isset($sort_string[1]) ? $sort_string[1] : null);

                        if($k > 0 && ($k != count($sort_query))) $sorting_query .= " , ";
                        $sorting_query .= $column_name . " " . $operator;
                    }
                }

                // MAIN QUERY
                $main_query = "SELECT " . $select_query . " FROM `vaani_lead_dump`";

                if($where_query){
                    $main_query .= " WHERE " . $where_query;
                }
                if($sorting_query){
                    $main_query .= " ORDER BY " . $sorting_query;
                }
                // $main_query .= ";";

                // echo $main_query;exit;

                try {
                    $result = \Yii::$app->db->createCommand($main_query)->execute();
                    if($result > 0){
                        return json_encode(['message' => 'success', 'data' => $main_query]);
                    }else{
                        return json_encode(['message' => 'error', 'data' => 'No Records Found! Kindly check the filter/sort.']);
                    }
                } catch (yii\db\Exception $e) {
                    // return json_encode(['message' => 'error', 'data' => 'Query failed! ' . $e ]);
                    return json_encode(['message' => 'error', 'data' => 'Query failed! ' . implode($e->errorInfo) ]);
                }
            }
        }
        return false;
    }

    // set batch active
    // move dump leads to active
    // add filter query
    public function actionSetBatchActive()
    {
        $user = Yii::$app->user->identity;

        $model = new VaaniBatchFilter();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {
            $batchModel = $this->findModel($model->lead_batch_id);
            $batchModel->is_active = VaaniLeadBatch::STATUS_ACTIVE;

            if($batchModel->save() && $model->save()){
                // copy leads from dump table to active
                $result = \Yii::$app->db->createCommand("
                    INSERT INTO `vaani_active_lead` 
                        (`lead_id`, `batch_id`, `campaign_id`, `lead_data`, `primary_no`, `date_created`, `created_by`, `created_ip`, `del_status`)
                    SELECT 
                        `lead_id`, `batch_id`, `campaign_id`, `lead_data`, `primary_no`, NOW(), '".$user->id."', '".$_SERVER['REMOTE_ADDR']."', '".User::STATUS_NOT_DELETED."'
                    FROM `vaani_lead_dump`
                    WHERE batch_id = '".$batchModel->batch_id."' and status = 1;
                ")
                ->execute();

                Yii::$app->session->setFlash('success', $batchModel->batch_name . ' is now ' . VaaniLeadBatch::$statuses[$batchModel->is_active] . '.');
            }else{
                Yii::$app->session->setFlash('error', 'Something went wrong! Please try again.');
            }
        }
        return $this->redirect(['index']);
    }

    // fetch batch filter, if any
    public function actionGetBatchFilter()
    {
        $batch_id = Yii::$app->request->post()['batch_id'];

        if($batch_id){
            $filter_query = null;
            $sort_query = null;

            $filterModel = VaaniBatchFilter::find()->where(['batch_id' => $batch_id])->orderBy('id DESC')->one();

            if($filterModel){
                $filter_query = $filterModel->filter_query;
                $sort_query = $filterModel->sort_query;
            }

            return json_encode(['filter_query' => $filter_query, 'sort_query' => $sort_query]);
        }
        return false;
    }

    // fetch campaign dispositions
    public function actionGetCampaignDispositions()
    {
        $campaign_id = Yii::$app->request->post()['campaign_id'];
        if($campaign_id){
            $campaign = EdasCampaign::find()->where(['campaign_id' => $campaign_id])->one();
            if($campaign){
                $disposition_list = [];
                $dispositions = ($campaign->planDisposition ? $campaign->planDisposition->dispositions : null);

                if($dispositions){
                    foreach ($dispositions as $key => $disposition) {
                        if($disposition->subDispositions){
                            foreach ($disposition->subDispositions as $k2 => $sub_disp) {
                                if($sub_disp->subDispositions){
                                    foreach ($sub_disp->subDispositions as $k3 => $sub3_disp) {
                                        $disposition_list[$disposition->disposition_name][$sub_disp->disposition_name][$sub3_disp->disposition_id] = $sub3_disp->disposition_name;
                                    }
                                }else{
                                    $disposition_list[$disposition->disposition_name][$sub_disp->disposition_id] = $sub_disp->disposition_name;
                                }
                            }
                        }else{
                            $disposition_list[$disposition->disposition_id] = $disposition->disposition_name;
                        }
                    }
                }

                return json_encode($disposition_list ? $disposition_list : []);
            }
        }
        return [];
    }
    
    // fetch batch rechurn data
    public function actionGetBatchRechurn()
    {
        $batch_id = Yii::$app->request->get()['batch_id'];

        if($batch_id){
            $batch = VaaniLeadBatch::find()->where(['batch_id' => $batch_id])->one();
            if($batch){
                $dispositions = ($batch->is_rechurn ? ArrayHelper::getColumn($batch->rechurnDispositions, 'disposition_id') : []);

                return json_encode([
                    'is_rechurn' => $batch->is_rechurn,
                    'dispositions' => $dispositions,
                    'call_attempts' => $batch->call_attempts,
                    'rechurn_time' => $batch->rechurn_time,
                    'last_dial_datetime' => $batch->last_dial_datetime
                ]);
            }
        }
    }

    // Test Query For Rechurn 
    public function actionTestQueryRechurn()
    {
        // echo "hello";
        $batch_id = Yii::$app->request->post()['batch_id'];
        
        $filter_query_rechurn = Yii::$app->request->post()['filter_query_rechurn'];
        // echo "<pre>";
        // print_r($batch_id);
        // exit();
        if($batch_id){

            $batch_fields = VaaniLeadMapping::find()->where(['batch_id' => $batch_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->asArray()->all();
            if($batch_fields){
                $fields = ArrayHelper::map($batch_fields, 'field_index', 'field_name');
                
                // SELECT QUERY FIELDS
                $select_query = "id, lead_id, batch_id, campaign_id, primary_no";
                $batch_field_count = 0;
                foreach ($fields as $key => $field) {
                    $select_query .= ", ";
                    // if($batch_field_count > 0 && ($batch_field_count != count($fields))){
                        // }
                        
                        $select_query .= "JSON_UNQUOTE(JSON_EXTRACT(lead_data, '$.\"".$key."\"')) AS '" . $field . "'";
                        $batch_field_count++;
                    }
                    
                    // WHERE QUERY
                    $where_query = " batch_id = '" . $batch_id . "'";
                    if($filter_query_rechurn){
                        $filter_query_rechurn = explode(','.PHP_EOL, $filter_query_rechurn);
                        foreach ($filter_query_rechurn as $k => $filter) {
                            $filter_string = explode(" ", trim($filter));
                            $column_name = (isset($filter_string[0]) ? $filter_string[0] : null);
                            $column_key = array_search($column_name, $fields);
                            
                            $operator = (isset($filter_string[1]) ? $filter_string[1] : null);
                            $value = (isset($filter_string[2]) ? $filter_string[2] : null);
                            
                            if($k != count($filter_query_rechurn)) $where_query .= " AND ";
                            $where_query .= "JSON_UNQUOTE(JSON_EXTRACT(lead_data, '$.\"".$column_key."\"')) " . $operator . " " . $value;
                        }
                    }
                    
                    
                    // MAIN QUERY
                    $main_query = "SELECT " . $select_query . " FROM `vaani_lead_dump`";
                    if($where_query){
                        $main_query .= " WHERE " . $where_query;
                        $query = json_encode(['main_query' => $main_query]);
                    }
                    try {
                        $result = \Yii::$app->db->createCommand($main_query)->execute();
                        if($result > 0){
                
                            return json_encode(['message' => 'success', 'data' => $main_query]);
                        }else{
                            return json_encode(['message' => 'error', 'data' => 'No Records Found! Kindly check the filter.']);
                        }
                    } catch (yii\db\Exception $e) {
                        // return json_encode(['message' => 'error', 'data' => 'Query failed! ' . $e ]);
                        return json_encode(['message' => 'error', 'data' => 'Query failed! ' . implode($e->errorInfo) ]);
                    }
                    
                }

        }
        return false;
    }

    // set batch rechurn and rechurn dispositions
    public function actionSetBatchRechurn()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            $batchModel = VaaniLeadBatch::find()->where(['batch_id' => $this->request->post()['VaaniLeadBatch']['batch_id']])->one();
            
            if($batchModel){
                $prev_call_attempts = $batchModel->call_attempts;
                $prev_rechurn_time = $batchModel->rechurn_time;
                $prev_last_dial_datetime = $batchModel->last_dial_datetime;
                $prev_rechurn_dispos = ($batchModel->rechurnDispositions ? ArrayHelper::getColumn($batchModel->rechurnDispositions, 'disposition_id') : []);
                
                if($batchModel->load($this->request->post())){
                    
                    // SET RECHURN ID FOR ANY NEW CHANGE
                    if(!$batchModel->rechurn_id || ( $prev_call_attempts != $batchModel->call_attempts || $prev_rechurn_time != $batchModel->rechurn_time || $prev_last_dial_datetime != $batchModel->last_dial_datetime || array_diff($prev_rechurn_dispos,  ['batchModel->dispositions' => $batchModel->dispositions]) ) ){
                        $batchModel->rechurn_id = User::newID('7','RC');
                    }
                    $rechurnModel = new VaaniRechurnFilter();
                    $query = trim($batchModel->query);
                    $exist = VaaniRechurnFilter::find()->where(['batch_id' => $batchModel->batch_id])->one();
                  
                    if($exist){
                        
                        if($rechurnModel){
                            $rechurn_query = VaaniRechurnFilter::find()->where(['batch_id' => $batchModel->batch_id,'is_active' => VaaniRechurnFilter::STATUS_ACTIVE, 'del_status' => User::STATUS_NOT_DELETED])->one();
                        
                            if($query == $rechurn_query->query){

                            }else{
                                if($rechurn_query){
                                    $rechurn_query->is_active = VaaniRechurnFilter::STATUS_INACTIVE;
                                    $rechurn_query->del_status = VaaniRechurnFilter::STATUS_INACTIVE;
                                    $rechurnModel->batch_id = $batchModel->batch_id;
                                    $rechurnModel->is_active = VaaniRechurnFilter::STATUS_ACTIVE;
                                    $rechurnModel->del_status = VaaniRechurnFilter::STATUS_ACTIVE;
                                    $rechurnModel->query = $query;
                                    $rechurnModel->filter_query = $batchModel->filter_query_rechurn;
                                    if(!$rechurn_query->save()){
                                        echo 'Error to save user model<br />';
                                        var_dump($rechurn_query->getErrors());
                                    }
                                }
                            }
                        }
                    }else{
                        $rechurnModel->batch_id = $batchModel->batch_id;
                        $rechurnModel->is_active = VaaniRechurnFilter::STATUS_ACTIVE;
                        $rechurnModel->del_status = VaaniRechurnFilter::STATUS_ACTIVE;
                        $rechurnModel->query = $query;
                        $rechurnModel->filter_query = $batchModel->filter_query_rechurn;
                    }
                    if($batchModel->save()){
                        if($rechurnModel->save()){
                            Yii::$app->session->setFlash('success', 'Stored data to rechurn successfully.');
                        }else{
                            foreach($rechurnModel->errors as $error){
                                Yii::$app->session->setFlash('error', json_encode($error));
                            }
                        }
                        if($batchModel->is_rechurn && $batchModel->dispositions){
                            if($batchModel->rechurnDispositions){
                                $prev_dispositions = $batchModel->rechurnDispositions;
                                $prev_disp_ids = ArrayHelper::getColumn($prev_dispositions, 'disposition_id');
                                
                                // delete if unselected previous rechurn dispositions
                                if(array_diff($prev_disp_ids, $batchModel->dispositions)){
                                    foreach ($prev_dispositions as $k => $rechurn_disp) {
                                        if(!in_array($batchModel->dispositions, $rechurn_disp)){
                                            $rechurn_disp->del_status = User::STATUS_PERMANENT_DELETED;
                                            $rechurn_disp->save();
                                        }
                                    }
                                }
                            }

                            foreach ($batchModel->dispositions as $key => $disposition) {
                                // fetch disposistion model
                                $dispo_model = VaaniDispositions::find()->where(['disposition_id' => $disposition])->one();
                                
                                if($dispo_model){
                                    $camp_dispo_model = VaaniCampDispositions::find()->where(['disposition_id' => $disposition])->andWhere(['campaign_id' => $batchModel->campaign_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->one();

                                    // check if exists
                                    $dispModel = VaaniRechurnDispositions::find()->where(['batch_id' => $batchModel->batch_id])->andWhere(['disposition_id' => $disposition])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->one();

                                    if(!$dispModel){
                                        $dispModel = new VaaniRechurnDispositions();
                                        $dispModel->batch_id = $batchModel->batch_id;
                                        $dispModel->campaign_id = $batchModel->campaign_id;
                                        $dispModel->disposition_id = $disposition;
                                        $dispModel->dispo_code = $dispo_model->short_code;
                                        $dispModel->max_retry_count = ($camp_dispo_model ? $camp_dispo_model->max_retry_count : 0);
                                        $dispModel->retry_delay = ($camp_dispo_model ? $camp_dispo_model->retry_delay : 0);
                                        $dispModel->save();
                                    }
                                }
                            }
                            Yii::$app->session->setFlash('success', 'Batch is set to Rechurn successfully.');
                        }
                    }
                }
            }else{
                Yii::$app->session->setFlash('error', 'No Batch Found.');
            }
        }
        return $this->redirect(['index']);
    }

    //get batch Fields For Rechurn
    public function actionGetBatchFieldsRechurn()
    {
        $batch_id = Yii::$app->request->post()['batch_id'];
        if($batch_id){
            $batch_fields = VaaniLeadMapping::find()->where(['batch_id' => $batch_id])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->asArray()->all();

            if($batch_fields){
                $fields = ArrayHelper::getColumn($batch_fields, 'field_name');
                $fields = implode('<br> ', $fields);
                return $fields;
            }
        }
        return false;
    }
    
    public function actionGetBatchFilterRechurn()
    {
        $batch_id = Yii::$app->request->post()['batch_id'];

        if($batch_id){
            $filter_query_rechurn = null;
            $filter_rechurn_model = VaaniLeadBatch::find()->where(['batch_id' => $batch_id])->orderBy('id DESC')->one();

            if($filter_rechurn_model){
                $filter__rechurn_query = $filter_rechurn_model->filter_query_rechurn;
                
            }

            return json_encode(['filter__rechurn_query' => $filter__rechurn_query]);
        }
        return false;
    }

    /**
     * Finds the VaaniLeadBatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniLeadBatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniLeadBatch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
