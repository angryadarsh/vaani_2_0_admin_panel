<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_session_log".
 *
 * @property int $auto_id
 * @property string $log_id
 * @property string|null $session_id
 * @property string|null $user_id
 * @property string|null $unique_id
 * @property int|null $status_id 1-login, 2-logout, 3-ready, 4-pause, 5-incall, 6-login fail,7-login fail due to already login , 8-unknow error, 9-force logout
 * @property string|null $action_start_datetime
 * @property string|null $action_end_datetime
 * @property int|null $action_duration_sec
 * @property string|null $created_ip
 * @property string|null $broswer
 * @property string $broswer_detail
 * @property string|null $created_datetime
 * @property string|null $modified_date
 * @property int $active_log 1-active, 2-inactive
 * @property int|null $del_status
 */
class VaaniSessionLog extends \yii\db\ActiveRecord
{
    // delete status
    const ACTIVE_LOG_ON = 1;
    const ACTIVE_LOG_OFF = 2;

    public static $active_logs = [
        self::ACTIVE_LOG_ON => 'On',
        self::ACTIVE_LOG_OFF => 'Off',
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
        self::STATUS_PAUSE => 'Pause',
        self::STATUS_INCALL => 'In Call',
        self::STATUS_LOGIN_FAIL => 'Login Fail',
        self::STATUS_ALREADY_LOGIN => 'Login Fail Due to Already Logged in',
        self::STATUS_UNKNOWN_ERROR => 'Unknown error',
        self::STATUS_FORCE_LOGOUT => 'Force Logout',
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
        return 'vaani_session_log';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_datetime',
                'updatedAtAttribute' => 'modified_date',
                'value' => new \yii\db\Expression('NOW()'),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'broswer',
                ],
                'value' => function ($event) {
                    $browser = Yii::$app->Utility->getBrowser();
                    return $browser['name'];
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'broswer_detail',
                ],
                'value' => function ($event) {
                    $browser = Yii::$app->Utility->getBrowser();
                    return $browser['userAgent'];
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_ip',
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
            [['log_id'], 'required'],
            [['status_id', 'action_duration_sec', 'active_log', 'del_status'], 'integer'],
            [['action_start_datetime', 'action_end_datetime', 'created_datetime', 'modified_date'], 'safe'],
            [['broswer_detail'], 'string'],
            [['log_id', 'user_id', 'unique_id', 'created_ip'], 'string', 'max' => 50],
            [['session_id'], 'string', 'max' => 200],
            [['broswer'], 'string', 'max' => 100],
            [['log_id'], 'unique'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['active_log', 'default', 'value' => self::ACTIVE_LOG_ON],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'auto_id' => 'Auto ID',
            'log_id' => 'Log ID',
            'session_id' => 'Session ID',
            'user_id' => 'User ID',
            'unique_id' => 'Unique ID',
            'status_id' => 'Status ID',
            'action_start_datetime' => 'Action Start Datetime',
            'action_end_datetime' => 'Action End Datetime',
            'action_duration_sec' => 'Action Duration Sec',
            'created_ip' => 'Created Ip',
            'broswer' => 'Broswer',
            'broswer_detail' => 'Broswer Detail',
            'created_datetime' => 'Created Datetime',
            'modified_date' => 'Modified Date',
            'active_log' => 'Active Log',
            'del_status' => 'Del Status',
        ];
    }

    // get session model
    public function getSession() {
        return $this->hasOne(VaaniSession::className(), ['unique_id' => 'unique_id']);
    }

    // fetch agents calls
    public function getAgentCalls() {
        return $this->hasMany(VaaniAgentLiveStatus::className(), ['agent_id' => 'user_id']);
    }

    public static function addLog($session_id, $user_id, $unique_id, $status_id)
    {
        $model = new VaaniSessionLog();
        $model->user_id = $user_id;
        $model->log_id = User::newID('51','Sl');
        $model->session_id = $session_id;
        $model->unique_id = $unique_id;
        $model->status_id = $status_id;
        $model->action_start_datetime = date('Y-m-d H:i:s');
        $model->save();

        return $model;
    }
}
