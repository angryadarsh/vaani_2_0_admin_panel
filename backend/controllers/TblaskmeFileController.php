<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\TblaskmeFile;
use common\models\search\TblaskmeFileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\VaaniClientMaster;
use yii\web\UploadedFile;
use yii\helpers\Url;

// use PHPExcel\PHPExcel\Classes\PHPExcel;
// echo Url::base();
// exit;
// Yii::import('vendor.PHPExcel.*');
/** Include PHPExcel */

/**
 * TblaskmeFileController implements the CRUD actions for TblaskmeFile model.
 */
class TblaskmeFileController extends Controller
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
     * Lists all TblaskmeFile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblaskmeFileSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblaskmeFile model.
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
     * Creates a new TblaskmeFile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblaskmeFile();
        if ($this->request->isPost) {
            require_once(__DIR__ . '/../../vendor/PHPExcel/PHPExcel/Classes/PHPExcel.php');
           
            $model->file = UploadedFile::getInstance($model, 'file');
            $clientid = $_POST['TblaskmeFile']["clientid"];
            $campaignid = $_POST['TblaskmeFile']["process"];
            $client_name = TblaskmeFile::getClientName($clientid);
            $campaign_name = TblaskmeFile::getCampaignName($campaignid);
            if ($model->validate()) {
               
                $path = TblaskmeFile::makeDirectory($client_name, $campaign_name);
                $filename = $path . '/' . $model->file->baseName . '.' . $model->file->extension;
                $model->file->saveAs($path . '/' . $model->file->baseName . '.' . $model->file->extension);
                $model->file_name = $model->file->baseName;
                $model->file_server = Yii::$app->getRequest()->getUserIP();
                $model->file_count = 1;
                $model->file_path = Url::base(true) . "/" . $path . "/" . $model->file->baseName . '.' . $model->file->extension;
                $model->created_by = Yii::$app->user->identity->user_name;
                $model->created_date = date("Y-m-d H:i:s");
                $model->status = 1;
                $model->priority = TblaskmeFile::getPriority($clientid, $campaignid);
                /* Fetch Content Of Excel file */
                $inputFileType = \PHPExcel_IOFactory::identify($filename);
                $excelReader   = \PHPExcel_IOFactory::createReader($inputFileType);
                $phpexcel = $excelReader->Load($filename)->getsheet(0); // load the file and get the first sheet
                $total_line = $phpexcel->getHighestRow(); // total number of rows
                $total_column = $phpexcel->getHighestColumn(); // total number of columns
             

                if ($total_line <= 0) {
                    exit('There is no data in the Excel table');
                }

                $html = '<table> <thead>';
                $flag = 0;
                for ($row = 1; $row <= $total_line; $row++) {
                    if($flag == 1){
                        $html .= "</thead><tbody>";
                    }
                    $data = [];
                    $data = $phpexcel->rangeToArray('A' . $row . ':' . $total_column . $row, NULL, TRUE, FALSE);
                    // var_dump($data);
                    $html .= "<tr>";
                    for ($i = 0; $i < count($data); $i++) {
                        for ($j = 0; $j < count($data[0]); $j++) {
                            if ($row == 1) {
                                $flag = 1;
                                $html .= "<th>" . $data[$i][$j] . "</th>";
                            } else {
                                $flag = 0;
                                $html .= "<td>" . $data[$i][$j] . "</td>";
                            }
                        }
                    }
                    $html .= "</tr>";
                }
                $html .= "</tbody></table>";
                $model->file_content = $html;
                $model->priority = 0;
            }
           
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect('index');
            }else{
                print_r($model->errors);
            }
        } else {  
            $model->loadDefaultValues();
        }

        $clients = [];
        $campaigns = [];
        $client_ids = null;
        if ((Yii::$app->user->identity->userRole && Yii::$app->user->identity->userRole->role_name != 'superadmin') && Yii::$app->user->identity->role != 'superadmin') {
            $client_ids = Yii::$app->user->identity->clientIds;
        }
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList($client_ids), 'client_id', 'client_name');
        return $this->render('create', [
            'model' => $model,
            'clients' => $clients,
            'campaigns' => $campaigns,
        ]);
    }

    /**
     * Updates an existing TblaskmeFile model.
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
     * Deletes an existing TblaskmeFile model.
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

    /**
     * Finds the TblaskmeFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return TblaskmeFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblaskmeFile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
