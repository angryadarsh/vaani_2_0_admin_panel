<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use phpseclib3\Net\SFTP;
use common\models\VaaniClientMaster;
use yii\helpers\Url;
use common\models\VaaniKpTab;


/**
 * This is the model class for table "vaani_kp_templete".
 *
 * @property int $templete_id
 * @property string|null $template_name
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property string|null $del_status
 */
class vaani_kp_templete extends \yii\db\ActiveRecord
{   
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;
    /**
     * {@inheritdoc}
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public static function tableName()
    {
        return 'vaani_kp_templete';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
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
            [['templete_id'], 'safe'],
            [['template_name', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'safe'],
            [['template_name'],'required'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            [['templete_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'templete_id' => 'ID',
            'template_name' => 'Template Name',
            'created_date' => 'Created date',
            'modified_date' => 'Modified date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }
    public function TempName()
    {   
        return vaani_kp_templete::find()->select(['templete_id','template_name'])->Where(['del_status' => User::STATUS_NOT_DELETED])->all();
         
    }

    public function getTabs() {

        return $this->hasMany(VaaniKpTab::className(), ['templete_id' => 'templete_id'])->andOnCondition(['del_status' => VaaniKpTab::STATUS_NOT_DELETED]);
        
    }  

}
