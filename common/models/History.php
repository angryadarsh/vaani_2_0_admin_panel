<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property int $activity_id
 * @property string|null $vaani_lead_id
 * @property string|null $emp_code
 * @property string|null $emp_name
 * @property string|null $contact_no_1
 * @property string|null $contact_no_2
 * @property string|null $contact_no_3
 * @property string|null $email
 * @property string|null $insertdate
 * @property string|null $agent_name
 * @property string|null $agent_id
 * @property string|null $callbackdatetime
 * @property string|null $disposition
 * @property string|null $sub_disposition
 * @property string|null $remark
 * @property string|null $mail_body
 * @property string|null $sms_response
 * @property string|null $unique_id
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['insertdate'], 'safe'],
            [['mail_body', 'sms_response'], 'string'],
            [['vaani_lead_id', 'emp_code', 'agent_id', 'callbackdatetime', 'unique_id'], 'string', 'max' => 50],
            [['emp_name', 'email', 'agent_name', 'disposition', 'sub_disposition'], 'string', 'max' => 100],
            [['contact_no_1', 'contact_no_2', 'contact_no_3'], 'string', 'max' => 15],
            [['remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'activity_id' => 'Activity ID',
            'vaani_lead_id' => 'Vaani Lead ID',
            'emp_code' => 'Emp Code',
            'emp_name' => 'Emp Name',
            'contact_no_1' => 'Contact No 1',
            'contact_no_2' => 'Contact No 2',
            'contact_no_3' => 'Contact No 3',
            'email' => 'Email',
            'insertdate' => 'Insertdate',
            'agent_name' => 'Agent Name',
            'agent_id' => 'Agent ID',
            'callbackdatetime' => 'Callbackdatetime',
            'disposition' => 'Disposition',
            'sub_disposition' => 'Sub Disposition',
            'remark' => 'Remark',
            'mail_body' => 'Mail Body',
            'sms_response' => 'Sms Response',
            'unique_id' => 'Unique ID',
        ];
    }
}
