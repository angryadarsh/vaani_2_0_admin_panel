<?php

namespace backend\controllers;

use Yii;
use common\models\vaani_kp_templete;
use common\models\VaaniKpTempleteSearch;
use common\models\VaaniKpTab;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Reader\IReader;
use \PhpOffice\PhpSpreadsheet\Writer\IWriter;
use Mpdf\Mpdf;
use aspose\cells;
use aspose\cells\Workbook;
use aspose\cells\PdfSaveOptions;
use aspose\cells\PdfCompliance;
use Spipu\Html2Pdf\Html2Pdf;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Shuchkin\SimpleXLSX;
use yii\helpers\Url;
use common\models\VaaniSearchSuggestion;



/**
 * KpController implements the CRUD actions for vaani_kp_templete model.
 */
class KpController extends Controller
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
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    /**
     * Lists all vaani_kp_templete models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new VaaniKpTempleteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single vaani_kp_templete model.
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
     * Creates a new vaani_kp_templete model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new vaani_kp_templete();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Template created successfully .');
                return $this->redirect(['index', 'id' => User::encrypt_data($model->templete_id)]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing vaani_kp_templete model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {   
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Template updated successfully.');
            return $this->redirect(['index', 'id' =>  User::encrypt_data($model->templete_id)]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing vaani_kp_templete model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {   
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = vaani_kp_templete::STATUS_PERMANENT_DELETED;

                if($model->save()){
                    foreach ($model->tabs as $value) {
                        echo $value->del_status = VaaniKpTab::STATUS_PERMANENT_DELETED;
                        $value->save();
                        }
                    Yii::$app->session->setFlash('success', 'Template and Tabs deleted successfully.');
                }else{
                    foreach($model->errors as $error){
                        Yii::$app->session->setFlash('error', json_encode($error));
                    }
                }

       return $this->redirect(['index','id' =>  User::encrypt_data($model->templete_id)]);
    }

    /**
     * Finds the vaani_kp_templete model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return vaani_kp_templete the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = vaani_kp_templete::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    //preview tab
    public function actionPreview($id){

         $id = User::decrypt_data($id);
        //prevewing file on click of tab
        $model = new vaani_kp_templete();
        $getTabList = VaaniKpTab::find()->select(['id','tab_name','file'])->Where(['del_status' => User::STATUS_NOT_DELETED])->andWhere(['templete_id' => $id])->OrderBy('sequence')->asArray()->all();
        //echo"<pre>";print_r($id);exit;
        $tabs = ArrayHelper::map($getTabList , 'id', 'tab_name','file');
        
        $tab_files = ArrayHelper::map($getTabList , 'id', 'file');

        $temp_name = vaani_kp_templete::find()->select(['template_name'])->where(['del_status' => User::STATUS_NOT_DELETED,'templete_id' => $id])->one();

        $files = VaaniKpTab::find()->select(['id','file'])->andWhere(['del_status' => User::STATUS_NOT_DELETED])->OrderBy('sequence')->all();

        include_once __DIR__.'/SimpleXLSX.php';


        $tblData ='';
        $test = [];
        foreach ($files as $file) {

                $ext = pathinfo( $file['file'], PATHINFO_EXTENSION);
                
                $filepath = Yii::$app->basePath . '/web/uploads/knowledge_portal/client/'.$file->file;

                $fileid =$file['id'];

            if($ext == 'xlsx') {

                  
                    if ( $xlsx = SimpleXLSX::parse($filepath) ) {
                    if(isset($xlsx)){
                           
                            $tblData = '<table id="excel_table" class="table_excel" border="1" cellpadding="3" style="border-collapse: collapse">';
                            $tblData .= '<thead>';
                            $excelRows = $xlsx->rows(); 
                            foreach( $excelRows as $r ) {
                                if ( $r === reset( $excelRows ) ) {
                                    $tblData .= '<tr><th>'.implode('</th><th>', $r ).'</th></tr></thead><tbody>';
                                }   
                                $tblData .= '<tr><td>'.implode('</td><td>', $r ).'</td></tr>';
                            }
                            $tblData .= '</tbody></table>';
                    } else {
                        echo SimpleXLSX::parseError();
                    }
                    $test[$fileid] = $tblData;
                }
                else{

                    $tblData  = '';
                    $test[$fileid] = $tblData;
                    }
            }
        }
        // print_r($test);        
   
        return $this->render('viewtabs', [
            'model' => $model,
            'tabs' =>  $tabs,
            'id' => User::encrypt_data($id),
            'temp_name' => $temp_name,
            'file' => $file,
            'getTabList' => $getTabList,
            'tblData' => $test,
         
        ]);
    }
    

//fetching http:// 
    public function url(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }

    //fetching auto suggestion
    public function actionSuggestion(){

        $currentVal = Yii::$app->request->post('currentVal');

        $newVal = Yii::$app->request->post('newVal');
        if (isset($newVal)) {
        
            $model = new VaaniSearchSuggestion();

            $model->suggestion = $newVal;
            $model->save();
        }
        $allsuggestion = VaaniSearchSuggestion::find()->select(['suggestion'])->where(['del_status' => User::STATUS_NOT_DELETED])->andWhere(['like', 'suggestion',trim($currentVal).'%', false])->distinct()->limit(10)->asArray()->all();
        $allsuggestions = ArrayHelper::getColumn($allsuggestion, 'suggestion'); 


       return json_encode($allsuggestions);
        
    }
       // public function actionDeletesugguestion(){
    //     $value = Yii::$app->request->post('value');
    //     $deletesugg = VaaniSearchSuggestion::find()->where(['suggestion'=>$value])->one()->delete();
    // }

}
