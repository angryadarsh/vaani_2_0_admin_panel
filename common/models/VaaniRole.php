<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vaani_role".
 *
 * @property string $id
 * @property string $role_id
 * @property string $parent_id
 * @property int $level
 * @property string|null $role_name
 * @property string|null $role_description
 * @property string|null $role_enable
 * @property string|null $created_by
 * @property string|null $created_date
 * @property string|null $created_ip
 * @property string|null $modified_by
 * @property string|null $modified_date
 * @property string|null $modified_ip
 * @property string|null $last_activity
 * @property string|null $change_set
 * @property int|null $del_status
 */
class VaaniRole extends \yii\db\ActiveRecord
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

    // enable status
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public static $enable_statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive'
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
        return 'vaani_role';
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
            [['role_id', 'parent_id'], 'required'],
            [['level', 'del_status'], 'integer'],
            [['modified_date'], 'safe'],
            [['role_id', 'parent_id', 'role_name', 'role_description', 'role_enable', 'created_by', 'created_date', 'created_ip', 'modified_by'], 'string', 'max' => 50],
            [['modified_ip', 'last_activity', 'change_set'], 'string', 'max' => 45],
            [['role_id'], 'unique'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['role_enable', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'parent_id' => 'Parent ID',
            'level' => 'Level',
            'role_name' => 'Role Name',
            'role_description' => 'Role Description',
            'role_enable' => 'Role Enable',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'created_ip' => 'Created Ip',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'modified_ip' => 'Modified Ip',
            'last_activity' => 'Last Activity',
            'change_set' => 'Change Set',
            'del_status' => 'Del Status',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (isset($changedAttributes['parent_id']) && $changedAttributes['parent_id'] != $this->parent_id) {
            $parent_model = VaaniRole::getRoleByRoleId($this->parent_id);
            if($parent_model){
                $this->level = ($parent_model->level) + 1;
                $this->save();
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    // get parent role
    public function getParent()
    {
        return $this->hasOne(self::className(), ['role_id' => 'parent_id']);
    }

    // fetch all roles
    public static function getAllRolesList()
    {
        return ArrayHelper::map(self::find()->where(['del_status' => self::STATUS_NOT_DELETED])->andWhere(['NOT IN', 'role_enable', [3]])->all(), 'role_id', 'role_name');
    }

    // fetch all roles
    public static function getDefaultRoles()
    {
        return self::find()->where(['del_status' => self::STATUS_NOT_DELETED])->andWhere(['role_enable' => self::STATUS_ACTIVE])->all();
    }

    // fetch all roles
    public static function getRoleByRoleId($role_id)
    {
        return self::find()
            ->where(['role_id' => $role_id])
            ->andWhere(['del_status' => self::STATUS_NOT_DELETED])
            ->andWhere(['NOT IN', 'role_enable', [3]])
            ->one();
    }

    // fetch roles data
    public static function rolesData($roles=null, $is_array = false)
    {
        $query = self::find()
            ->select(['role_id', 'role_name', 'role_enable', 'parent_id', 'level'])
            ->where(['del_status' => self::STATUS_NOT_DELETED])
            ->where(['NOT IN', 'role_enable', [3]]);
        
        if($roles)
            $query->andFilterWhere(['IN', 'role_id', $roles]);
            
        
        if($is_array)
            return $query->orderBy('role_name')->asArray()->all();
        else
            return $query->orderBy('role_name')->all();
    }
}
