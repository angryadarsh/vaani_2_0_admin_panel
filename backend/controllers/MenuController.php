<?php

namespace backend\controllers;

use Yii;
use common\models\VaaniMenu;
use common\models\search\VaaniMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\models\Route;
use yii\helpers\ArrayHelper;

/**
 * MenuController implements the CRUD actions for VaaniMenu model.
 */
class MenuController extends Controller
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
     * Lists all VaaniMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VaaniMenuSearch();
        $searchModel->del_status = VaaniMenu::STATUS_NOT_DELETED;
        $searchModel->level = 1;
        $searchModel->status = 1;
        $searchModel->is_sequence = true;
        $dataProvider = $searchModel->search($this->request->queryParams);

        // list of all menus
        $menus = ArrayHelper::map(VaaniMenu::allMenus(), 'menu_id', 'menu_name');
        
        // to add new menu
        $model = new VaaniMenu();

        $routeModel = new Route();
        $available_routes = array_combine($routeModel->getRoutes()['available'], $routeModel->getRoutes()['available']);
        $assigned_routes = array_combine($routeModel->getRoutes()['assigned'], $routeModel->getRoutes()['assigned']);

        $routes = array_merge($available_routes, $assigned_routes);

        return $this->render('index', [
            'model' => $model,
            'routes' => $routes,
            'menus' => $menus,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VaaniMenu model.
     * @param int $menu_id Menu ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($menu_id)
    {
        return $this->redirect(['index']);
        /* return $this->render('view', [
            'model' => $this->findModel($menu_id),
        ]); */
    }

    /**
     * Creates/edit a new VaaniMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        $model = new VaaniMenu();

        $last_sequence = (VaaniMenu::lastMenu() ? VaaniMenu::lastMenu()->sequence : 0);

        if(Yii::$app->request->post()){
            if(Yii::$app->request->post()['VaaniMenu']['menu_id']){
                $model = $this->findModel(Yii::$app->request->post()['VaaniMenu']['menu_id']);
                // edit menu
                $action = 'Updated';
            }else{
                $action = 'Added';
            }
            if($model->load(Yii::$app->request->post())){
                if(!$model->menu_id){
                    // add menu
                    $model->status = 1;
                    $model->sequence = $last_sequence + 1;
                }
                if($model->icon==null){
                    $table = 'fas fa-table';
                    $model->icon = $table;
                }
                if($model->save()){
                    Yii::$app->session->setFlash('success', 'Menu ' . $action . ' successfully.');
                }else{
                    foreach($model->errors as $error){
                        Yii::$app->session->setFlash('error', json_encode($error));
                    }
                }
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Updates an existing VaaniMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $menu_id Menu ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($menu_id)
    {
        return $this->redirect(['index']);
        /* $model = $this->findModel($menu_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'menu_id' => $model->menu_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]); */
    }

    /**
     * Deletes an existing VaaniMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $menu_id Menu ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $menu_id = Yii::$app->request->post()['menu_id'];
        if($menu_id){
            $model = $this->findModel($menu_id);
            $model->del_status = VaaniMenu::STATUS_PERMANENT_DELETED;
            if($model->save()){
                Yii::$app->session->setFlash('success', 'Menu deleted successfully.');
            }else{
                foreach($model->errors as $error){
                    Yii::$app->session->setFlash('error', json_encode($error));
                }
            }
        }else{
            Yii::$app->session->setFlash('error', "Kindly select the menu!");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the VaaniMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $menu_id Menu ID
     * @return VaaniMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniMenu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // change sequence of the menu
    public function actionChangeSequence()
    {
        $data = Yii::$app->request->post()['item_list'];
        $rtn=1;
        if($data){
            foreach ($data as $key => $value) {
                $item = explode('~', $key);
                if(count($item)==2) {
                    $seq = $item[1];
                    $level = $value;
                    $id = $item[0];
                    // query
                    $model = VaaniMenu::find()->where(['menu_id' => $id])->andWhere(['del_status' => VaaniMenu::STATUS_NOT_DELETED])->one();
                    if($model){
                        $model->level = $level;
                        $model->sequence = $seq;
                        $model->save();
                    }
                } elseif (count($item)==3) {          
                    $p_id = $item[0];
                    $item_arr[$p_id] = $value;
                }
            }
            if(!empty($item_arr)) {
                $rtn = $this->nested_sort($item_arr);
            }
        }
        return $rtn;
    }

    protected function nested_sort($data)
    {
        $rtn=1;
        if(!empty($data))
        {
            $item_arr = array();
            foreach ($data as $k => $val) {
                foreach ($val as $key => $value) {
                    $tmp = explode('~', $key);
                    if(count($tmp)==2) {
                        $seq = $tmp[1];
                        $level = $value;
                        $id = $tmp[0];
                        // $query
                        $model = VaaniMenu::find()->where(['menu_id' => $id])->andWhere(['del_status' => VaaniMenu::STATUS_NOT_DELETED])->one();
                        if($model){
                            $model->level = $level;
                            $model->sequence = $seq;
                            $model->parent_id = $k;
                            $model->save();
                        }
                        $rtn=2;
                    } elseif (count($tmp)==3) {          
                        $p_id = $tmp[0];
                        $item_arr[$p_id] = $value;
                    } 
                }
            }
            if(!empty($item_arr)) {
                $rtn = $this->nested_sort($item_arr);
            }
        }
        return $rtn;
    }
}
