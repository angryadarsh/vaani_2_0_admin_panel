<?php

namespace backend\controllers;

use Yii;
use common\models\VaaniRole;
use common\models\search\VaaniRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use common\models\VaaniRoleMaster;
use common\models\VaaniClientMaster;
use yii\helpers\ArrayHelper;
use mdm\admin\models\AuthItem;
use yii\rbac\Item;

/**
 * RoleController implements the CRUD actions for VaaniRole model.
 */
class RoleController extends Controller
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
     * Lists all VaaniRole models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VaaniRoleSearch();
        $searchModel->del_status = VaaniRole::STATUS_NOT_DELETED;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VaaniRole model.
     * @param string $id Role ID
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
     * Creates a new VaaniRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VaaniRole();
        $model->role_id = User::newID('55','ROL');

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if($model->parent_id){
                    $parent_model = VaaniRole::getRoleByRoleId($model->parent_id);
                    if($parent_model)
                        $model->level = ($parent_model->level) + 1;
                }else{
                    $model->level = 1;
                }
                if($model->save()){
                    // create yii2-admin role
                    $authItemModel = new AuthItem(null);
                    $authItemModel->type = Item::TYPE_ROLE;
                    $authItemModel->name = $model->role_name;
                    $authItemModel->save();

                    $role_access = new VaaniRoleMaster();
                    $role_access->role_group_id = 'rgi1';
                    $role_access->role_assign_id = 'rgi1';
                    $role_access->role_id = $model->role_id;
                    $role_access->save();
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $roles = VaaniRole::getAllRolesList();

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Updates an existing VaaniRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id Role ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            // update yii2-admin role
            /* $authItemModel = AuthItem::find()->where();
            $authItemModel->type = Item::TYPE_ROLE;
            $authItemModel->name = $model->role_name;
            $authItemModel->save(); */
            return $this->redirect(['index']);
        }

        $roles = VaaniRole::getAllRolesList();

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Deletes an existing VaaniRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id Role ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $id = User::decrypt_data($id);
        $model = $this->findModel($id);
        $model->del_status = VaaniRole::STATUS_PERMANENT_DELETED;
        if($model->save()){
            Yii::$app->session->setFlash('success', 'Role deleted successfully.');
        }else{
            foreach($model->errors as $error){
                Yii::$app->session->setFlash('error', json_encode($error));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the VaaniRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id Role ID
     * @return VaaniRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VaaniRole::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    // set active/inactive status of the role
    public function actionSetStatus($id, $status)
    {
        if($id && $status){
            $model = $this->findModel($id);
            $model->role_enable = $status;
            if($model->save()){
                Yii::$app->session->setFlash('success', $model->role_name . ' is now ' . VaaniRole::$enable_statuses[$model->role_enable] . '.');
            }
        }else{
            Yii::$app->session->setFlash('error', 'Something went wrong! Please try again.');
        }
        return $this->redirect(['index']);
    }

    // role access action
    public function actionAccess()
    {
        $statuses = VaaniRoleMaster::$statuses;
        $roles = VaaniRole::getAllRolesList();

        // fetch list of clients
        $clients = [];
        $clients = ArrayHelper::map(VaaniClientMaster::clientsList(), 'client_id', 'client_name');

        $menus = [];

        return $this->render('access', [
            'statuses' => $statuses,
            'roles' => $roles,
            'clients' => $clients,
            'menus' => $menus,
        ]);
    }

    // get assigned and unassigned menus based on selected role/client/campaign
    public function actionGetAssignedMenus()
    {
        $role_id = Yii::$app->request->post('role_id');
        $client_id = Yii::$app->request->post('client_id');
        $campaign_id = Yii::$app->request->post('campaign_id');
        $queue_id = Yii::$app->request->post('queue_id');
        $html = '';
        $unassigned_menus = [];

        if($role_id){
            $menu = User::userMenus('', [], true);
            $default_roles = User::roleWiseMenus($role_id, $client_id, $campaign_id, $queue_id);

            foreach ($menu['menu'] as $key => $value) {
                $role_val = null;

                $role_key = array_search($value['menu_id'], array_column($default_roles, 'menu_ids'));
                if($role_key){
                    $role_val = $default_roles[$role_key];
                }
                // if (!empty($default_roles[$key])) {
                    $chk = '';
                    $sub_html = '';
                    $is_row = false;
                    
                    if (isset($value['sub'])) {
                        $sub_html = User::sub_menu_string($value, '', $role_id, $client_id, $campaign_id, $queue_id);
                        if($sub_html) {
                            $is_row = true;
                            $html .= "<tr>";
                            $html .= $sub_html;
                        }
                    } else {
                        $is_row = true;
                        $html .= "<tr>";
                        if (array_search($value['menu_id'], array_column($default_roles, 'menu_ids')) || array_search($value['menu_id'], array_column($default_roles, 'menu_ids')) === 0) {
                            $chk = 'checked';
                        }
                        // echo array_search($value['menu_id'], array_column($default_roles, 'menu_ids'));
                        $html .= '<td>
                            <ul class="list-unstyled menu-tabs">
                                <li class="define_role" data-menu="' . $key . '">
                                    <div class="row">
                                        <a href="javascript:void(0);" class="col-9">
                                            ' . $value['menu_name'] . '
                                        </a> 
                                        <div class="col-3 text-right pr-0">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input class_role_update" data-parent="' . $key . '" data-check="' . $key . '" id="customSwitch~' . $key . '" ' . $chk . '>
                                                <label class="custom-control-label" for="customSwitch~' . $key . '"></label>
                                            </div>
                                        </div> 
                                    </div>
                                </li>
                            </ul> 
                        </td>
                        ';
                    }
                    if($is_row){
                        $html .= '
                            <td class="action opacitycls">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="my-checkbox" class="custom-control-input class_role_update" data-on-text="Yes" data-off-text="No" data-check="' . $key . '" data-check-action="a" data-on-color="success" id="customSwitch~a' . $key . '" '. (($role_val && $role_val['add'] == 1) ? "checked" : "") .'>
                                    <label class="custom-control-label" for="customSwitch~a' . $key . '"></label>
                                </div>
                            </td>
                            <td class="action opacitycls">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="my-checkbox" class="custom-control-input class_role_update" data-on-text="Yes" data-off-text="No" data-check="' . $key . '" data-check-action="b" data-on-color="success" id="customSwitch~b' . $key . '" '. (($role_val && $role_val['view'] == 1) ? "checked" : "") .'>
                                    <label class="custom-control-label" for="customSwitch~b' . $key . '"></label>
                                </div>
                            </td>
                            <td class="action opacitycls">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="my-checkbox" class="custom-control-input class_role_update" data-on-text="Yes" data-off-text="No" data-check="' . $key . '" data-check-action="c" data-on-color="success" id="customSwitch~c' . $key . '" '. (($role_val && $role_val['modify'] == 1) ? "checked" : "") .'>
                                    <label class="custom-control-label" for="customSwitch~c' . $key . '"></label>
                                </div>
                            </td>
                            <td class="action opacitycls">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="my-checkbox" class="custom-control-input class_role_update" data-on-text="Yes" data-off-text="No" data-check="' . $key . '" data-check-action="d" data-on-color="success" id="customSwitch~d' . $key . '" '. (($role_val && $role_val['download'] == 1) ? "checked" : "") .'>
                                    <label class="custom-control-label" for="customSwitch~d' . $key . '"></label>
                                </div>
                            </td>
                            <td class="action opacitycls">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="my-checkbox" class="custom-control-input class_role_update" data-on-text="Yes" data-off-text="No" data-check="' . $key . '" data-check-action="e" data-on-color="success" id="customSwitch~e' . $key . '" '. (($role_val && $role_val['download'] == 1) ? "checked" : "") .'>
                                    <label class="custom-control-label" for="customSwitch~e"></label>
                                </div>
                            </td> ';                          
                        $html .= '</tr>';
                    }
                // }else{
                    /* $unassigned_menus[$value['menu_id']] = $value['menu_name'];
                    if(isset($value['sub'])) {
                        foreach($value['sub'] as $k => $sub_menu) {
                            $unassigned_menus[$value['menu_name']][$sub_menu['menu_id']] = $sub_menu['menu_name'];
                        }
                    } */
                // }
            }
        }
        return json_encode(['assigned' => $html, 'unassigned' => $unassigned_menus]);
    }

    // get sub menus actions data on click of menu
    public function actionSubMenuActions()
    {
        $type = Yii::$app->request->post('type');
        $role_id = Yii::$app->request->post('role_id');
        $menu = Yii::$app->request->post('menu');
        $client_id = Yii::$app->request->post('client_id');
        $campaign_id = Yii::$app->request->post('campaign_id');
        $queue_id = Yii::$app->request->post('queue_id');
        
        $query = VaaniRoleMaster::find()
                ->where(['role_id' => $role_id])
                ->andWhere(['status' => $type])
                ->andWhere(['del_status' => VaaniRole::STATUS_NOT_DELETED]);
        
        if($type == VaaniRoleMaster::STATUS_CUSTOM){
            if($queue_id)
                $query->andWhere(['queue_id' => $queue_id]);
            else if($campaign_id)
                $query->andWhere(['campaign_id' => $campaign_id]);
            else
                $query->andWhere(['client_id' => $client_id]);
        }

        $query->andFilterWhere(['in', 'menu_ids', $menu]);

        return json_encode($query->asArray()->one());
    }

    // assign access for the role to the menu actions
    public function actionSetMenuAccess()
    {
        $type = Yii::$app->request->post('type');
        $role_id = Yii::$app->request->post('role_id');
        $menu_id = Yii::$app->request->post('menu_id');
        $client_id = Yii::$app->request->post('client_id');
        $campaign_id = Yii::$app->request->post('campaign_id');
        $queue_id = Yii::$app->request->post('queue_id');
        $sub_action = Yii::$app->request->post('sub_action');
        $check_value = Yii::$app->request->post('check_value');

        $query = VaaniRoleMaster::find()
                ->where(['role_id' => $role_id])
                ->andWhere(['status' => $type])
                ->andWhere(['del_status' => VaaniRoleMaster::STATUS_NOT_DELETED]);
        
        if($type == VaaniRoleMaster::STATUS_CUSTOM){
            if($queue_id){
                $query->andWhere(['queue_id' => $queue_id]);
            }else if($campaign_id){
                $query->andWhere(['campaign_id' => $campaign_id]);
            }else{
                $query->andWhere(['client_id' => $client_id]);
            }
        }

        $prev_model = $query->andWhere(['menu_ids' => $menu_id])->orderBy('id DESC')->one();

        if($prev_model){
            $update_model = VaaniRoleMaster::findOne($prev_model->id);
            if(!empty($sub_action)){
                switch ($sub_action) {
                    case 'a':
                        $update_model->add = $check_value;
                        break;
                    case 'b':
                        $update_model->view = $check_value;
                        break;
                    case 'c':
                        $update_model->modify = $check_value;
                        break;
                    case 'd':
                        $update_model->download = $check_value;
                        break;
                    case 'e':
                        $update_model->delete = $check_value;
                        break;
                }
            }else{
                $update_model->del_status = $check_value;
            }

            if($update_model->save()){
                return json_encode(['is_success' => true]);
            }else{
                return json_encode($update_model->errors);
            }
        }else{
            $model = new VaaniRoleMaster();
            $model->menu_ids = $menu_id;
            $model->role_id = $role_id;
            $model->role_assign_id = User::newID('9','RAI');
            $model->add = ($sub_action == 'a' ? $check_value : VaaniRoleMaster::ACCESS_NO);
            $model->view = ($sub_action == 'b' ? $check_value : VaaniRoleMaster::ACCESS_NO);
            $model->modify = ($sub_action == 'c' ? $check_value : VaaniRoleMaster::ACCESS_NO);
            $model->download = ($sub_action == 'd' ? $check_value : VaaniRoleMaster::ACCESS_NO);
            $model->delete = ($sub_action == 'e' ? $check_value : VaaniRoleMaster::ACCESS_NO);
            $model->status = $type;
            
            if($type == VaaniRoleMaster::STATUS_CUSTOM){
                $model->client_id = $client_id;
                $model->campaign_id = $campaign_id;
                $model->queue_id = $queue_id;

                $prev_group_model = VaaniRoleMaster::find()
                    ->where(['role_id' => $role_id])
                    ->andWhere(['status' => $type])
                    ->andWhere(['client_id' => $client_id]);
                $prev_group_model = $prev_group_model->one();
                if($prev_group_model){
                    $model->role_group_id = $prev_group_model->role_group_id;
                }else{
                    return "No role group data";
                }
            }
            
            if($model->save()){
                return json_encode(['is_success' => true]);
            }else{
                return json_encode($model->errors);
            }
        }
    }
}
