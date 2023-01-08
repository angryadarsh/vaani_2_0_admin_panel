<?php

namespace backend\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\VaaniCallTimesConfig;
use common\models\search\VaaniCallTimesConfigSearch;
use common\models\VaaniCallTimes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\User;

/**
 * CallTimesController implements the CRUD actions for VaaniCallTimesConfig model.
 */
class CallTimesController extends Controller
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
     * Lists all VaaniCallTimesConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VaaniCallTimesConfigSearch();
        $searchModel->del_status = VaaniCallTimesConfig::STATUS_NOT_DELETED;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VaaniCallTimesConfig model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Creates a new VaaniCallTimesConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VaaniCallTimesConfig();
        $dayModel = new VaaniCallTimes();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())){
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if($model->save()) {
                    $days_added = 0;
                    $days = $this->request->post()['VaaniCallTimes']['day'];
                    $day_ids = $this->request->post()['VaaniCallTimes']['day_id'];

                    if($model->type == VaaniCallTimesConfig::TYPE_ALL)
                    {
                        foreach ($days as $key => $day) {
                            $day_model = new VaaniCallTimes();
                            $day_model->config_id = $model->id;
                            $day_model->day_id = $day_ids[$key];
                            $day_model->day = $day;
                            $day_model->start_time = $model->default_start_time;

                            $split_day_model = null;
                            // check for split day time
                            if(substr($model->default_start_time, 0, 2) > substr($model->default_end_time, 0, 2)){
                                $day_model->end_time = '00:00';
                                
                                $split_day_model = new VaaniCallTimes();
                                $split_day_model->config_id = $model->id;
                                $split_day_model->day_id = (isset($day_ids[$key+1]) ? $day_ids[$key+1] : $day_ids[0]);
                                $split_day_model->day = (isset($days[$key+1]) ? $days[$key+1] : $days[0]);
                                $split_day_model->start_time = '00:00';
                                $split_day_model->end_time = $model->default_end_time;
                            }else{
                                $day_model->end_time = $model->default_end_time;
                            }
                            if(!$day_model->save()){
                                /* foreach($day_model->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                } */
                                Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for ' . $day_model->day);
                            }else{
                                $days_added++;
                                if($split_day_model){
                                    $split_day_model->parent_id = $day_model->id;
                                    if(!$split_day_model->save()){
                                        Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for split ' . $split_day_model->day);
                                    }else{
                                        $days_added++;
                                    }
                                }
                            }
                        }
                    } else if($model->type == VaaniCallTimesConfig::TYPE_CUSTOM)
                    {
                        // $day_checks = $this->request->post()['VaaniCallTimes']['day_check'];
                        $start_times = $this->request->post()['VaaniCallTimes']['start_time'];
                        $end_times = $this->request->post()['VaaniCallTimes']['end_time'];

                        foreach ($days as $key => $day) {
                            $day_model = new VaaniCallTimes();
                            $day_model->config_id = $model->id;
                            $day_model->day_id = $day_ids[$key];
                            $day_model->day = $day;
                            $day_model->start_time = $start_times[$key];

                            $split_day_model = null;
                            // check for split day time
                            if(substr($start_times[$key], 0, 2) > substr($end_times[$key], 0, 2)){
                                $day_model->end_time = '00:00';
                                
                                $split_day_model = new VaaniCallTimes();
                                $split_day_model->config_id = $model->id;
                                $split_day_model->day_id = (isset($day_ids[$key+1]) ? $day_ids[$key+1] : $day_ids[0]);
                                $split_day_model->day = (isset($days[$key+1]) ? $days[$key+1] : $days[0]);
                                $split_day_model->start_time = '00:00';
                                $split_day_model->end_time = $end_times[$key];
                            }else{
                                $day_model->end_time = $end_times[$key];
                            }
                            if(!$day_model->save()){
                                /* foreach($day_model->errors as $error){
                                    Yii::$app->session->setFlash('error', json_encode($error));
                                } */
                                Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for ' . $day_model->day);
                            }else{
                                $days_added++;
                                if($split_day_model){
                                    $split_day_model->parent_id = $day_model->id;
                                    if(!$split_day_model->save()){
                                        Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for split ' . $split_day_model->day);
                                    }else{
                                        $days_added++;
                                    }
                                }
                            }
                        }
                    }
                    if($days_added > 0){
                        Yii::$app->session->setFlash('success', 'Call Window added successfully.');
                        $transaction->commit();
                        return $this->redirect(['index']);
                    }
                }else{
                    Yii::$app->session->setFlash('error', 'Something went wrong! Please try again.');
                }
            }catch(Exception $e)
            {
                \Yii::$app->session->setFlash('error', $e);
                $transaction->rollBack();
            }
        } else {
            $model->loadDefaultValues();
        }

        // fetch data for dropdown
        $types = VaaniCallTimesConfig::$types;

        return $this->render('create', [
            'model' => $model,
            'dayModel' => $dayModel,
            'types' => $types,
        ]);
    }

    /**
     * Updates an existing VaaniCallTimesConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $dayModel = new VaaniCallTimes();

        $daysData = $model->daysData;
        $prev_type = $model->type;

        $model->default_start_time = ($daysData ? $daysData[0]->start_time : '00:00');
        $model->default_end_time = ($daysData ? $daysData[0]->end_time : '00:00');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if($model->save()) {
                    // if days type is changed
                    if($prev_type != $model->type){
                        foreach ($daysData as $k => $day_data) {
                            $day_data->del_status = VaaniCallTimes::STATUS_PERMANENT_DELETED;
                            if($day_data->save()){
                                Yii::$app->session->setFlash('error', "Deleted " . VaaniCallTimesConfig::$types[$prev_type] . "type's data.");
                            }
                        }
                    }
                    $days_added = 0;
                    $days = $this->request->post()['VaaniCallTimes']['day'];
                    $day_ids = $this->request->post()['VaaniCallTimes']['day_id'];

                    if($model->type == VaaniCallTimesConfig::TYPE_ALL)
                    {
                        if($prev_type != $model->type){
                            foreach ($days as $key => $day) {
                                $day_model = new VaaniCallTimes();
                                $day_model->config_id = $model->id;
                                $day_model->day_id = $day_ids[$key];
                                $day_model->day = $day;
                                $day_model->start_time = $model->default_start_time;
                                // check for split day time
                                if(($model->default_end_time - $model->default_start_time) < 0){
                                    $day_model->end_time = '00:00';
                                    
                                    $split_day_model = new VaaniCallTimes();
                                    $split_day_model->config_id = $model->id;
                                    $split_day_model->day_id = $day_ids[$key];
                                    $split_day_model->day = $day;
                                    $split_day_model->start_time = '00:00';
                                    $split_day_model->end_time = $model->default_end_time;
                                }else{
                                    $day_model->end_time = $model->default_end_time;
                                }
                                if(!$day_model->save()){
                                    Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for ' . $day_model->day);
                                }else{
                                    $days_added++;
                                    if(!$split_day_model->save()){
                                        Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for split ' . $split_day_model->day);
                                    }else{
                                        $days_added++;
                                    }
                                }
                            }
                        }else{
                            foreach ($daysData as $key => $day_model) {
                                $day_model->config_id = $model->id;
                                $day_model->day_id = $day_ids[$key];
                                $day_model->day = $days[$key];
                                $day_model->start_time = $model->default_start_time;
                                $day_model->end_time = $model->default_end_time;
                                if(!$day_model->save()){
                                    Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for ' . $day_model->day);
                                }else{
                                    $days_added++;
                                }
                            }
                        }
                    } else if($model->type == VaaniCallTimesConfig::TYPE_CUSTOM)
                    {
                        // $day_checks = $this->request->post()['VaaniCallTimes']['day_check'];
                        $start_times = $this->request->post()['VaaniCallTimes']['start_time'];
                        $end_times = $this->request->post()['VaaniCallTimes']['end_time'];

                        if($prev_type != $model->type){
                            foreach ($days as $key => $day) {
                                $day_model = new VaaniCallTimes();
                                $day_model->config_id = $model->id;
                                $day_model->day_id = $day_ids[$key];
                                $day_model->day = $day;
                                $day_model->start_time = $start_times[$key];
                                $day_model->end_time = $end_times[$key];
                                if(!$day_model->save()){
                                    Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for ' . $day_model->day);
                                }else{
                                    $days_added++;
                                }
                            }
                        }else{
                            $time_ids = $this->request->post()['VaaniCallTimes']['id'];
                            $prev_call_ids = ArrayHelper::getColumn($daysData, 'id');
                            // loop on days
                            foreach ($days as $key => $day) {
                                // if id exists
                                if(isset($time_ids[$key])){
                                    $day_model = VaaniCallTimes::findOne($time_ids[$key]);
                                    // if record exists - edit
                                    if($day_model){
                                        $day_model->config_id = $model->id;
                                        $day_model->day_id = $day_ids[$key];
                                        $day_model->day = $day;
                                        $day_model->start_time = $start_times[$key];
                                        $day_model->end_time = $end_times[$key];
                                        if(!$day_model->save()){
                                            Yii::$app->session->setFlash('error', 'Something went wrong while removing time configuration for ' . $day_model->day);
                                        }
                                    }else{
                                        // else create new
                                        $day_model = new VaaniCallTimes();
                                        $day_model->config_id = $model->id;
                                        $day_model->day_id = $day_ids[$key];
                                        $day_model->day = $day;
                                        $day_model->start_time = $start_times[$key];
                                        $day_model->end_time = $end_times[$key];
                                        if(!$day_model->save()){
                                            Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for ' . $day_model->day);
                                        }else{
                                            $days_added++;
                                        }
                                    }
                                }else{
                                    // else create new
                                    $day_model = new VaaniCallTimes();
                                    $day_model->config_id = $model->id;
                                    $day_model->day_id = $day_ids[$key];
                                    $day_model->day = $day;
                                    $day_model->start_time = $start_times[$key];
                                    $day_model->end_time = $end_times[$key];
                                    if(!$day_model->save()){
                                        Yii::$app->session->setFlash('error', 'Something went wrong while adding time configuration for ' . $day_model->day);
                                    }else{
                                        $days_added++;
                                    }
                                }
                            }
                        }
                    }
                    if($days_added > 0){
                        Yii::$app->session->setFlash('success', 'Call Window updated successfully.');
                    }
                    $transaction->commit();
                    return $this->redirect(['index']);
                }else{
                    Yii::$app->session->setFlash('error', 'Something went wrong! Please try again.');
                }
            }catch(Exception $e)
            {
                \Yii::$app->session->setFlash('error', $e);
                $transaction->rollBack();
            }
        }

        // fetch data for dropdown
        $types = VaaniCallTimesConfig::$types;

        return $this->render('update', [
            'model' => $model,
            'dayModel' => $dayModel,
            'types' => $types,
        ]);
    }

    /**
     * Deletes an existing VaaniCallTimesConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        if($model){
            if($model->queues){
                Yii::$app->session->setFlash('warning', 'Call window, ' . $model->call_time_name . ', cannot be deleted! Since it is assigned to one or more queues.');
            }else{
                $model->del_status = VaaniCallTimesConfig::STATUS_PERMANENT_DELETED;
                if($model->save()){
                    Yii::$app->session->setFlash('success', 'Call window deleted successfully!');
                    if($model->daysData){
                        foreach ($model->daysData as $key => $day_model) {
                            $day_model->del_status = VaaniCallTimes::STATUS_PERMANENT_DELETED;
                            $day_model->save();
                        }
                    }
                }
            }
        }else{
            Yii::$app->session->setFlash('error', 'Record cannot be found!');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the VaaniCallTimesConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return VaaniCallTimesConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniCallTimesConfig::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
