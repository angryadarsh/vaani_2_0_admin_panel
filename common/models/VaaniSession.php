<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_session".
 *
 * @property int $id
 * @property string $session_id
 * @property string|null $user_id
 * @property string|null $campaign
 * @property string|null $login_datetime
 * @property string|null $last_action_epoch
 * @property string|null $logout_datetime
 * @property string|null $date_created
 * @property string|null $created_ip
 * @property string|null $created_datetime
 * @property string|null $modified_date
 * @property int|null $del_status
 * @property string|null $unique_id
 *
 * @property VaaniActiveStatus[] $vaaniActiveStatuses
 */
class VaaniSession extends \yii\db\ActiveRecord
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
        return 'vaani_session';
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
            [['session_id'], 'required'],
            [['login_datetime', 'logout_datetime', 'date_created', 'created_datetime', 'modified_date', 'last_action_epoch'], 'safe'],
            [['del_status'], 'integer'],
            [['session_id'], 'string', 'max' => 200],
            [['user_id', 'created_ip', 'unique_id', 'campaign'], 'string', 'max' => 50],
            [['unique_id'], 'unique'],
            [['session_id'], 'unique'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_id' => 'Session ID',
            'user_id' => 'User ID',
            'login_datetime' => 'Login Datetime',
            'last_action_epoch' => 'Last Action Epoch',
            'logout_datetime' => 'Logout Datetime',
            'date_created' => 'Date Created',
            'created_ip' => 'Created Ip',
            'created_datetime' => 'Created Datetime',
            'modified_date' => 'Modified Date',
            'del_status' => 'Del Status',
            'unique_id' => 'Unique ID',
        ];
    }

    /**
     * Gets query for [[VaaniActiveStatuses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVaaniActiveStatuses()
    {
        return $this->hasMany(VaaniActiveStatus::className(), ['unique_id' => 'unique_id']);
    }

    // update epoch time of current session
    public static function updateEpochTime($session_id, $user_id)
    {
        if($session_id && $user_id)
		{
            $last_action_time = time();

            $model = self::find()->where(['del_status' => self::STATUS_NOT_DELETED])
                        ->andWhere(['session_id' => $session_id])
                        ->andWhere(['user_id' => $user_id])->one();
            
            if($model){
                $model->last_action_epoch = $last_action_time;
                if(!$model->save()){
                    foreach($model->errors as $error){
                        Yii::$app->session->setFlash('error', json_encode($error));
                    }
                }
            }
        }
    }
}
