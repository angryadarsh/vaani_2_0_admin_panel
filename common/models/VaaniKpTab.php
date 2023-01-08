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

/**
 * This is the model class for table "vaani_CL1420220822050210945150.vaani_kp_tab".
 *
 * @property int $id
 * @property int|null $templete_id
 * @property string|null $tab_name
 * @property string|null $file
 * @property string|null $mandatory_info
 * @property string|null $additional_info
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property string|null $del_status
 */
class VaaniKpTab extends \yii\db\ActiveRecord
{   
    const STATUS_NOT_DELETED = 1;
    const STATUS_PERMANENT_DELETED = 2;
    const STATUS_TEMPORARY_DELETED = 3;

    public $uploadfile, $tempfile;
    /**
     * {@inheritdoc}
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public static function tableName()
    {
        return 'vaani_kp_tab';
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
            [['id', 'templete_id'], 'safe'],
            [['tempfile'],'safe'],
            [['tab_name', 'mandatory_info', 'additional_info', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'safe'],
            [['file'],'file','extensions' => 'xlsx,pdf','skipOnEmpty' => true],
            [['tab_name', 'mandatory_info', 'additional_info'],'required'],
            ['del_status', 'default', 'value' => self::STATUS_NOT_DELETED],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'templete_id' => 'Templete ID',
            'tab_name' => 'Tab Name',
            'file' => 'File',
            'mandatory_info' => 'Mandatory Info',
            'additional_info' => 'Additional Info',
            'created_date' => 'Created date',
            'modified_date' => 'Modified date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }
    
    public static function getTabList($ids=null)
    {
        $query = self::find()
            ->select(['id', 'tab_name', 'del_status'])
            ->where(['del_status' => VaaniKpTab::STATUS_NOT_DELETED]);

        if($ids){
            $query->andWhere(['IN', 'id', $ids]);
        }
            
        return $query->all();
    }
 

}
