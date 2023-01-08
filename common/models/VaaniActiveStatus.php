<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_active_status".
 *
 * @property int $auto_id
 * @property string $session_id
 * @property string|null $user_id
 * @property string|null $unique_id
 * @property int|null $status
 * @property string|null $created_datetime
 * @property string|null $modified_date
 * @property int|null $del_status
 *
 * @property VaaniSession $unique
 */
class VaaniActiveStatus extends \yii\db\ActiveRecord
{
    // 1-login, 2-logout, 3-ready, 4-pause, 5-incall, 6-login fail,7-login fail due to already login , 8-unknow error, 9-force logout	
    const STATUS_LOGIN = 1;
    const STATUS_LOGOUT = 2;
    const STATUS_READY = 3;
    const STATUS_PAUSE = 4;
    const STATUS_INCALL = 5;
    const STATUS_LOGIN_FAIL = 6;
    const STATUS_ALREADY_LOGIN = 7;
    const STATUS_UNKNOWN_ERROR = 8;
    const STATUS_FORCE_LOGOUT = 9;

    public static $statuses = [
        self::STATUS_LOGIN => 'Login',
        self::STATUS_LOGOUT => 'Logout',
        self::STATUS_READY => 'Ready',
        self::STATUS_PAUSE => 'Not Ready',
        self::STATUS_INCALL => 'In Call',
        self::STATUS_LOGIN_FAIL => 'Login Fail',
        self::STATUS_ALREADY_LOGIN => 'Login Fail Due to Already Logged in',
        self::STATUS_UNKNOWN_ERROR => 'Wrap',
        self::STATUS_FORCE_LOGOUT => 'Force Logout',
    ];
	
    const SUB_STATUS_RINGING = 'ringing';
    const SUB_STATUS_HOLD = 'hold';
    const SUB_STATUS_UNHOLD = 'unhold';
    const SUB_STATUS_CONF_S = 'conf_s';
    const SUB_STATUS_CONF_E = 'conf_e';
    const SUB_STATUS_CONF_EP = 'conf_ep';
    const SUB_STATUS_CONS_S = 'cons_s';
    const SUB_STATUS_CONS_E = 'cons_e';
    const SUB_STATUS_TRF_B = 'trf_b';
    const SUB_STATUS_DISPO = 'dispo';

    public static $sub_statuses = [
        self::SUB_STATUS_RINGING => 'Ringing',
        self::SUB_STATUS_HOLD => 'Hold',
        self::SUB_STATUS_UNHOLD => 'Unhold',
        self::SUB_STATUS_CONF_S => 'Conference',
        self::SUB_STATUS_CONF_E => 'Conference End',
        self::SUB_STATUS_CONF_EP => 'Conference End Participant',
        self::SUB_STATUS_CONS_S => 'Consult',
        self::SUB_STATUS_CONS_E => 'Consult End',
        self::SUB_STATUS_TRF_B => 'Transfer',
        self::SUB_STATUS_DISPO => 'Wrap',
    ];

    // delete status
    
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public static $delete_statuses = [
        self::STATUS_NOT_DELETED => 'Not Deleted',
        self::STATUS_PERMANENT_DELETED => 'Permanent Deleted',
        self::STATUS_TEMPORARY_DELETED => 'Temporary Deleted',
    ];

    // fetch parent db
    public static function getDb()
    {
        return Yii::$app->db;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_active_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['session_id'], 'required'],
            [['status', 'del_status'], 'integer'],
            [['created_datetime', 'modified_date'], 'safe'],
            [['session_id'], 'string', 'max' => 200],
            [['user_id', 'unique_id'], 'string', 'max' => 50],
            [['session_id'], 'unique'],
            [['unique_id'], 'exist', 'skipOnError' => true, 'targetClass' => VaaniSession::className(), 'targetAttribute' => ['unique_id' => 'unique_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auto_id' => 'Auto ID',
            'session_id' => 'Session ID',
            'user_id' => 'User ID',
            'unique_id' => 'Unique ID',
            'status' => 'Status',
            'created_datetime' => 'Created Datetime',
            'modified_date' => 'Modified Date',
            'del_status' => 'Del Status',
        ];
    }

    /**
     * Gets query for [[Unique]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnique()
    {
        return $this->hasOne(VaaniSession::className(), ['unique_id' => 'unique_id']);
    }

    // get session model
    public function getSession() {
        return $this->hasOne(VaaniSession::className(), ['unique_id' => 'unique_id']);
    }

    // fetch agents calls
    public function getAgentCalls() {
        return $this->hasMany(VaaniAgentLiveStatus::className(), ['agent_id' => 'user_id']);
    }

    public static function agentStatus($status, $campaign_id=null)
    {
        $query = self::find()->where(['IN', 'vaani_active_status.status', $status])->andWhere(['vaani_active_status.del_status' => self::STATUS_NOT_DELETED]);
        
        if($campaign_id || ((Yii::$app->user->identity->userRole && strtolower(Yii::$app->user->identity->userRole->role_name) != 'superadmin') && strtolower(Yii::$app->user->identity->role) != 'superadmin')){
            // $query->joinWith('session')->andWhere(['IN', 'campaign', $campaign_id]);
        }
            
        return $query->all();
    }
}
