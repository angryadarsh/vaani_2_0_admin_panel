<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_qms_template".
 *
 * @property int $id
 * @property string|null $qms_id
 * @property string|null $template_name
 * @property int|null $cq_score_target
 * @property string|null $categorization
 * @property int|null $action_status
 * @property string|null $pip_status
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniQmsTemplate extends \yii\db\ActiveRecord
{

    public static $action_statuses = [
        30 => '30',
        60 => '60',
        90 => '90',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_qms_template';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_created',
                'updatedAtAttribute' => 'date_modified',
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
            [['template_name', 'categorization', 'cq_score_target', 'action_status', 'pip_status'], 'required'],
            [['cq_score_target', 'action_status', 'del_status'], 'integer'],
            [['qms_id', 'categorization'], 'safe'],
            [['template_name'], 'string', 'max' => 100],
            [['pip_status'], 'string', 'max' => 50],
            [['date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
            [['template_name'], 'match', 'pattern' => '/^[a-zA-Z0-9_]*$/', 'message' => 'Name cannot contain special characters & space, except underscore.'],
            ['del_status', 'default', 'value' => User::STATUS_NOT_DELETED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_name' => 'Template Name',
            'cq_score_target' => 'Cq Score Target',
            'categorization' => 'Categorization',
            'action_status' => 'Action Status',
            'pip_status' => 'Pip Status',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->categorization){
            $this->categorization = json_encode($this->categorization);
        }
        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();

        if($this->categorization){
            $this->categorization = json_decode($this->categorization, TRUE);
        }
    }

    // fetch all active qms templates
    public static function allActiveQms()
    {
        return self::find()->where(['del_status' => User::STATUS_NOT_DELETED])->all();
    }

    // fetch all active sheets
    public function getAllSheets()
    {
        return $this->hasMany(VaaniQmsSheet::className(), ['qms_id' => 'qms_id'])->andOnCondition(['del_status' => VaaniSessionLog::STATUS_NOT_DELETED]);
    }

    // fetch all active transactional sheets
    public function getTransactionalSheets()
    {
        return $this->hasMany(VaaniQmsSheet::className(), ['qms_id' => 'qms_id'])->andOnCondition(['type' => VaaniQmsSheet::TYPE_TRANSACTIONAL])->andWhere(['del_status' => VaaniSessionLog::STATUS_NOT_DELETED]);
    }

    // fetch all active analytical sheets
    public function getAnalyticalSheets()
    {
        return $this->hasMany(VaaniQmsSheet::className(), ['qms_id' => 'qms_id'])->andOnCondition(['type' => VaaniQmsSheet::TYPE_ANALYTICAL])->andWhere(['del_status' => VaaniSessionLog::STATUS_NOT_DELETED]);
    }

    // fetch assigned campaigns
    public function getCampaigns()
    {
        return $this->hasMany(EdasCampaign::className(), ['qms_id' => 'qms_id']);
    }
}