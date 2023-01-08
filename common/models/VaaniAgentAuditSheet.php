<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "vaani_agent_audit_sheet".
 *
 * @property int $id
 * @property string|null $audit_id
 * @property string|null $agent_id
 * @property string|null $sheet_id
 * @property string|null $campaign_id
 * @property string|null $campaign
 * @property string|null $language
 * @property string|null $audit_type
 * @property string|null $location
 * @property string|null $call_duration
 * @property string|null $call_date
 * @property string|null $week
 * @property string|null $month
 * @property string|null $call_id
 * @property string|null $analysis_finding
 * @property string|null $agent_type
 * @property string|null $unique_id
 * @property string|null $disposition
 * @property string|null $sub_disposition
 * @property string|null $pip_status
 * @property string|null $categorization
 * @property string|null $action_status
 * @property string|null $gist_of_case
 * @property string|null $resolution_provided
 * @property string|null $areas_of_improvement
 * @property string|null $reason_for_fatal_call
 * @property float|null $quality_score
 * @property float|null $out_of
 * @property float|null $final_score
 * @property float|null $yes_count
 * @property float|null $total_percent
 * @property string|null $rec_markers
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $audit_duration
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property string|null $created_by
 * @property string|null $modified_by
 * @property string|null $created_ip
 * @property string|null $modified_ip
 * @property int|null $del_status
 * @property string|null $feedback
 * @property string|null $status
 */
class VaaniAgentAuditSheet extends \yii\db\ActiveRecord
{
    // status values
    const STATUS_ACCEPTED = '1';
    const STATUS_REJECTED = '2';
    const STATUS_PENDING = '3';

    public static $statuses = [
        self::STATUS_ACCEPTED => 'Accepted',
        self::STATUS_REJECTED => 'Dispute Raised',
        self::STATUS_PENDING => 'Feedback Pending',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vaani_agent_audit_sheet';
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
            [['gist_of_case', 'resolution_provided', 'areas_of_improvement', 'reason_for_fatal_call'], 'string'],
            [['quality_score', 'out_of', 'final_score', 'total_percent', 'yes_count'], 'number'],
            [['del_status'], 'integer'],
            [['rec_markers', 'start_time', 'end_time', 'audit_duration', 'feedback', 'status'], 'safe'],
            [['audit_id', 'agent_id', 'sheet_id', 'campaign_id', 'campaign', 'language', 'audit_type', 'location', 'call_duration', 'call_date', 'week', 'month', 'call_id', 'analysis_finding', 'agent_type', 'unique_id', 'disposition', 'sub_disposition', 'pip_status', 'categorization', 'action_status', 'date_created', 'date_modified', 'created_by', 'modified_by', 'created_ip', 'modified_ip'], 'string', 'max' => 45],
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
            'audit_id' => 'Audit ID',
            'agent_id' => 'Agent ID',
            'sheet_id' => 'Sheet ID',
            'campaign_id' => 'Campaign ID',
            'campaign' => 'Campaign',
            'language' => 'Language',
            'audit_type' => 'Audit Type',
            'location' => 'Location',
            'call_duration' => 'Call Duration',
            'call_date' => 'Call Date',
            'week' => 'Week',
            'month' => 'Month',
            'call_id' => 'Call ID',
            'analysis_finding' => 'Analysis Finding',
            'agent_type' => 'Agent Type',
            'unique_id' => 'Unique ID',
            'disposition' => 'Disposition',
            'sub_disposition' => 'Sub Disposition',
            'pip_status' => 'Pip Status',
            'categorization' => 'Categorization',
            'action_status' => 'Action Status',
            'gist_of_case' => 'Gist Of Case',
            'resolution_provided' => 'Resolution Provided',
            'areas_of_improvement' => 'Areas Of Improvement',
            'reason_for_fatal_call' => 'Reason For Fatal Call',
            'quality_score' => 'Quality Score',
            'out_of' => 'Out Of',
            'final_score' => 'Final Score',
            'total_percent' => 'Total Percent',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_ip' => 'Created Ip',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
        ];
    }

    // fetch agent details
    public function getAgent()
    {
        return $this->hasOne(User::className(), ['user_id' => 'agent_id']);
    }
}
