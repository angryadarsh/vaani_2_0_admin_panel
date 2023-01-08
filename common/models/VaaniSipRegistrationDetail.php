<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_sip_registration_detail".
 *
 * @property int|null $sip
 * @property string|null $host
 * @property string|null $port
 * @property string|null $status
 */
class VaaniSipRegistrationDetail extends \yii\db\ActiveRecord
{

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
        return 'vaani_sip_registration_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sip'], 'integer'],
            [['host', 'port', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sip' => 'Sip',
            'host' => 'Host',
            'port' => 'Port',
            'status' => 'Status',
        ];
    }
}
