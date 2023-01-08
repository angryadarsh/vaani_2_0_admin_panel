<?php

namespace backend\controllers;

use Yii;
use common\models\EdasDniMaster;
use common\models\search\EdasDniMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\VaaniClientMaster;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\User;

/**
 * DniController implements the CRUD actions for EdasDniMaster model.
 */
class DniController extends Controller
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
     * Lists all EdasDniMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $searchModel = new EdasDniMasterSearch();
        $searchModel->del_status = EdasDniMaster::STATUS_NOT_DELETED;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $searchModel->client_id = $user->clientIds;
        }else if(isset($_SESSION['client_connection'])){
            $searchModel->client_id = $_SESSION['client_connection'];
        }
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EdasDniMaster model.
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
     * Creates a new EdasDniMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $user = Yii::$app->user->identity;

        $model = new EdasDniMaster();
        $model->scenario = 'create_update';
        $model->DNI_from = '';
        $model->client_id = (isset($_SESSION['client_connection'])?$_SESSION['client_connection']:'');// Ashish:01-jun-2022

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $is_error = false;

                // if($model->dni_type == EdasDniMaster::TYPE_RANGE) {
                    $prev_dnis = EdasDniMaster::dniList();
                    if($prev_dnis){
                        foreach($prev_dnis as $prev_dni){
                            if($model->DNI_other){
                                if($model->DNI_other == $prev_dni->DNI_other){
                                    Yii::$app->session->setFlash('error', 'DNI ' . $model->DNI_other . ' is already been taken.');
                                    $is_error = true;
                                }else if($model->DNI_other >= $prev_dni->DNI_from && $model->DNI_other <= $prev_dni->DNI_to){
                                    Yii::$app->session->setFlash('error', 'DNI is already been taken.');
                                    $is_error = true;
                                }
                            }else{
                                if($model->DNI_from == $prev_dni->DNI_other){
                                    Yii::$app->session->setFlash('error', 'DNI ' . $model->DNI_from . ' is already been taken.');
                                    $is_error = true;
                                }else if($model->DNI_to == $prev_dni->DNI_other){
                                    Yii::$app->session->setFlash('error', 'DNI ' . $model->DNI_to . ' is already been taken.');
                                    $is_error = true;
                                }else if($model->DNI_from >= $prev_dni->DNI_from && $model->DNI_from <= $prev_dni->DNI_to) {
                                    Yii::$app->session->setFlash('error', 'DNI From in between the range: '. $prev_dni->DNI_from.' - '.$prev_dni->DNI_to);
                                    $is_error = true;
                                } elseif($model->DNI_to >= $prev_dni->DNI_from && $model->DNI_to <= $prev_dni->DNI_to) {
                                    Yii::$app->session->setFlash('error', 'DNI To in between the range: '. $prev_dni->DNI_from.' - '.$prev_dni->DNI_to);
                                    $is_error = true;
                                }else if($prev_dni->DNI_other >= $model->DNI_from && $prev_dni->DNI_other <= $model->DNI_to){
                                    Yii::$app->session->setFlash('error',  'DNI ' . $prev_dni->DNI_other . ' is already been taken.');
                                    $is_error = true;
                                }
                            }
                        }
                    }
                // }
                if(!$is_error && $model->save()){
                    Yii::$app->session->setFlash('success', 'DNI added successfully.');
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        // fetch list of clients
        $clients = [];
        $client_ids = null;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $client_ids = $user->clientIds;
        }
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList($client_ids), 'client_id', 'client_name');
        
        $dni_types = EdasDniMaster::$dni_types;
        
        return $this->render('create', [
            'model' => $model,
            'clients' => $clients,
            'dni_types' => $dni_types,
        ]);
    }

    /**
     * Updates an existing EdasDniMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = User::decrypt_data($id);
        $user = Yii::$app->user->identity;

        $model = $this->findModel($id);
        $model->scenario = 'create_update';

        if($model->DNI_other){
            $model->dni_type = EdasDniMaster::TYPE_OTHER;
        }else{
            $model->dni_type = EdasDniMaster::TYPE_RANGE;
        }
        $prev_dni_type = $model->dni_type;
        $prev_dni_from = $model->DNI_from;
        $prev_dni_to = $model->DNI_to;
        $prev_dni_other = $model->DNI_other;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }else if ($this->request->isPost && $model->load($this->request->post())) {
            $is_error = false;
            $is_edit = false;

            if($model->dni_type == EdasDniMaster::TYPE_RANGE) {
               /*  if($model->DNI_from != $prev_dni_from || $model->DNI_to != $prev_dni_to) {
                    $is_edit = true;
                } */
                $model->DNI_other = null;
            }else{
               /*  if($model->DNI_other != $prev_dni_other) {
                    $is_edit = true;
                } */
                $model->DNI_from = null;
                $model->DNI_to = null;
            }
            // if($is_edit){
                $prev_dnis = EdasDniMaster::dniList();
                if($prev_dnis){
                    foreach($prev_dnis as $prev_dni){
                        if($prev_dni->id != $model->id){
                            if($model->DNI_other){
                                if($model->DNI_other == $prev_dni->DNI_other){
                                    Yii::$app->session->setFlash('error', 'DNI ' . $model->DNI_other . ' is already been taken.');
                                    $is_error = true;
                                }else if($model->DNI_other >= $prev_dni->DNI_from && $model->DNI_other <= $prev_dni->DNI_to){
                                    Yii::$app->session->setFlash('error', 'DNI is already been taken.');
                                    $is_error = true;
                                }
                            }else{
                                if($model->DNI_from == $prev_dni->DNI_other){
                                    Yii::$app->session->setFlash('error',  'DNI ' . $model->DNI_from . ' is already been taken.');
                                    $is_error = true;
                                }else if($model->DNI_to == $prev_dni->DNI_other){
                                    Yii::$app->session->setFlash('error',  'DNI ' . $model->DNI_to . ' is already been taken.');
                                    $is_error = true;
                                }else if($model->DNI_from >= $prev_dni->DNI_from && $model->DNI_from <= $prev_dni->DNI_to) {
                                    Yii::$app->session->setFlash('error', 'DNI From in between the range: '. $prev_dni->DNI_from.' - '.$prev_dni->DNI_to);
                                    $is_error = true;
                                } elseif($model->DNI_to >= $prev_dni->DNI_from && $model->DNI_to <= $prev_dni->DNI_to) {
                                    Yii::$app->session->setFlash('error', 'DNI To in between the range: '. $prev_dni->DNI_from.' - '.$prev_dni->DNI_to);
                                    $is_error = true;
                                }else if($prev_dni->DNI_other >= $model->DNI_from && $prev_dni->DNI_other <= $model->DNI_to){
                                    Yii::$app->session->setFlash('error',  'DNI ' . $prev_dni->DNI_other . ' is already been taken.');
                                    $is_error = true;
                                }
                            }
                        }
                    }
                }
            // }

            if(!$is_error && $model->save()){
                // fetch dni assigned queue
                $queue = $model->queue;
                if($queue){
                    $queue->asterisk_write('edit', $queue->queue, $queue->dni_id);
                }
                Yii::$app->session->setFlash('success', 'DNI updated successfully.');
                return $this->redirect(['index']);
            }
        }

        // fetch list of clients
        $clients = [];
        $client_ids = null;
        if(($user->userRole && $user->userRole->role_name != 'superadmin') && $user->role != 'superadmin'){
            $client_ids = $user->clientIds;
        }
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList($client_ids), 'client_id', 'client_name');
        
        $dni_types = EdasDniMaster::$dni_types;

        return $this->render('update', [
            'model' => $model,
            'clients' => $clients,
            'dni_types' => $dni_types,
        ]);
    }

    /**
     * Deletes an existing EdasDniMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = EdasDniMaster::STATUS_PERMANENT_DELETED;
        
        // fetch dni assigned queue
        $queue = $model->queue;
        if($queue){
            $prev_dni = $queue->dni_id;
            $queue->dni_id = null;
            $queue->asterisk_write('edit', $queue->queue, $prev_dni);
        }

        if($model->save()){
            Yii::$app->session->setFlash('success', 'DNI deleted successfully.');
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the EdasDniMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return EdasDniMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EdasDniMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
