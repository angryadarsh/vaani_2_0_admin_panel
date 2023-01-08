<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_client_operators".
 *
 * @property int $id
 * @property string|null $client_id
 * @property string|null $operator_id
 * @property string|null $created_date
 * @property string|null $modified_date
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 */
class VaaniClientOperators extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_client_operators';
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
            [['del_status'], 'integer'],
            [['client_id', 'operator_id', 'created_date', 'modified_date', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 255],
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
            'client_id' => 'Client ID',
            'operator_id' => 'Operator ID',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }
}
