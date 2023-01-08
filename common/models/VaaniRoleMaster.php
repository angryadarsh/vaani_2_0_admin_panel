<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_role_master".
 *
 * @property int $id
 * @property string|null $role_group_id
 * @property string|null $role_assign_id
 * @property string $role_id
 * @property string $client_id
 * @property string $campaign_id
 * @property string $queue_id
 * @property string|null $menu_ids
 * @property string|null $add 1- access, 2= not access
 * @property string|null $modify 1- access, 2= not access
 * @property string|null $delete 1- access, 2= not access
 * @property string|null $view 1- access, 2= not access
 * @property int $download 1- access, 2= not access
 * @property string|null $status 1 - custom, 2= default setting
 * @property string|null $created_ip
 * @property string|null $modified_by
 * @property string|null $modified_date
 * @property string|null $modified_ip
 * @property string|null $last_activity
 * @property string|null $del_status 1- not deleted, 2-deleted
 */
class VaaniRoleMaster extends \yii\db\ActiveRecord
{
    // delete status
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // access
    const ACCESS_YES = 1;
    const ACCESS_NO = 2;

    // status
    const STATUS_CUSTOM = 1;
    const STATUS_DEFAULT_SETTING = 2;

    public static $statuses = [
        self::STATUS_DEFAULT_SETTING => 'Default',
        self::STATUS_CUSTOM => 'Client',
    ];

    // fetch parent db
    public static function getDb()
    {
        return Yii::$app->pa_db;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_role_master';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_date',
                'updatedAtAttribute' => 'modified_date',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'modified_by',
                'value' => function($event){
                    $user = Yii::$app->get('user', false);
                    return $user && !$user->isGuest ? $user->identity->user_name : null;
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_ip',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'modified_ip',
                ],
                'value' => function ($event) {
                    return $_SERVER['REMOTE_ADDR'];
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id'], 'required'],
            [['download'], 'integer'],
            [['modified_date', 'del_status', 'role_group_id', 'role_assign_id', 'role_id', 'client_id', 'campaign_id', 'queue_id', 'menu_ids', 'add', 'modify', 'delete', 'view', 'status', 'created_ip', 'modified_by', 'modified_ip', 'last_activity'], 'safe'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['status', 'default', 'value' => self::STATUS_DEFAULT_SETTING],
            [['menu_ids', 'add', 'modify', 'delete', 'view'], 'default', 'value' => self::ACCESS_YES],
            [['client_id', 'campaign_id', 'queue_id'], 'default', 'value' => ''],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_group_id' => 'Role Group ID',
            'role_assign_id' => 'Role Assign ID',
            'role_id' => 'Role ID',
            'client_id' => 'Client ID',
            'campaign_id' => 'Campaign ID',
            'menu_ids' => 'Menu Ids',
            'add' => 'Add',
            'modify' => 'Modify',
            'delete' => 'Delete',
            'view' => 'View',
            'download' => 'Download',
            'status' => 'Status',
            'created_ip' => 'Created Ip',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'modified_ip' => 'Modified Ip',
            'last_activity' => 'Last Activity',
            'del_status' => 'Del Status',
        ];
    }

    // get role
    public function getRole() {
        return $this->hasOne(VaaniRole::className(), ['role_id' => 'role_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED]);
    }

    // fetch client's campaigns
    public static function rolesList($client_id=null, $campaign_id=null, $status=null, $role_id = null, $is_array = false, $queue_id=null)
    {
        $query = self::find()
            // ->select(['role_id'])
            ->where(['del_status' => self::STATUS_NOT_DELETED]);
        
        if($role_id){
            $query->andFilterWhere(['IN', 'role_id', $role_id]);
        }
        if($campaign_id){
            $query->andFilterWhere(['IN', 'campaign_id', $campaign_id]);
        }
        if($client_id){
            $query->andFilterWhere(['IN', 'client_id', $client_id]);
        }
        if($status){
            $query->andFilterWhere(['IN', 'status', $status]);
        }
        if($queue_id){
            $query->andFilterWhere(['IN', 'queue_id', $queue_id]);
        }
        if($is_array){
            return $query->orderBy('role_id DESC')->asArray()->all();
        }else{
            return $query->orderBy('role_id DESC')->all();
        }
    }

    public static function addDefaultRoleAccess($client_id, $campaign_id='', $queue_id='')
    {
        $roles = VaaniRole::rolesData(null, true);

        $role_group_id = User::newID('7','RGI');

        foreach ($roles as $key => $value) {
            $role_assign_id = User::newID('9','RAI');
            // check which default role is enable for the client
            if($value['role_enable'] == 1){
                $default_menu_access = VaaniRoleMaster::rolesList(null, null, VaaniRoleMaster::STATUS_DEFAULT_SETTING, $value['role_id'], true);
                if($default_menu_access){
                    foreach ($default_menu_access as $k => $val) 
                    {
                        $accessModel = new VaaniRoleMaster();
                        $accessModel->role_group_id = $role_group_id;
                        $accessModel->role_assign_id = $role_assign_id;
                        $accessModel->role_id = $value['role_id'];
                        $accessModel->client_id = $client_id;
                        $accessModel->campaign_id = $campaign_id;
                        $accessModel->queue_id = $queue_id;
                        $accessModel->menu_ids = $val['menu_ids'];
                        $accessModel->add = $val['add'];
                        $accessModel->modify = $val['modify'];
                        $accessModel->delete = $val['delete'];
                        $accessModel->view = $val['view'];
                        $accessModel->status = VaaniRoleMaster::STATUS_CUSTOM;
                        $accessModel->save();
                    }
                }
            }
        }
    }
}
