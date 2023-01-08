<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vaani_user_report_columns".
 *
 * @property int $id
 * @property string|null $userid
 * @property string|null $formname
 * @property string|null $reportcolumn
 * @property string|null $del_status
 */
class VaaniUserReportColumns extends \yii\db\ActiveRecord
{
    // fetch parent db
    public static function getDb()
    {
        return Yii::$app->pa_db;
    }
    
    public static function tableName()
    {
        return 'vaani_user_report_columns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reportcolumn', 'del_status'], 'string'],
            [['userid', 'formname'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => 'Userid',
            'formname' => 'Formname',
            'reportcolumn' => 'Reportcolumn',
            'del_status' => 'Del Status',
        ];
    }
}
