<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vaani_menu".
 *
 * @property int $menu_id
 * @property int|null $parent_id
 * @property string|null $menu_name
 * @property int|null $level
 * @property string|null $route
 * @property string|null $link
 * @property string|null $icon
 * @property int|null $sequence
 * @property int|null $status
 * @property string|null $created_by
 * @property string|null $created_date
 * @property string|null $created_ip
 * @property string|null $modified_by
 * @property string|null $modified_date
 * @property string|null $modified_ip
 * @property int|null $del_status
 * @property int|null $not_in_group 1-all , 2- only super admin
 */
class VaaniMenu extends \yii\db\ActiveRecord
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

    // STATIC MENUS
    public static $default_menus = [
        'Configuration' => [
            'Batches' => 'lead/index',
            'Breaks' => 'break/index',
            'Client License' => 'client/index',
            'CRM' => 'crm/index',
            'Disposition' => 'disposition/index',
            'DNI List' => 'dni/index',
            'Menu Master' => 'menu/index',
            'Operators' => 'operator/index',
            'PBX' => 'pbx/configuration',
            'QMS' => 'qms/index',
            'Schedules' => 'call-times/index',
            'Telephony' => null,
            'Knowledge Portal' => 'kp/index',
        ],
        'Integration' => [
            'Email' => null,
            'Facebook' => null,
            'WhatsApp' => null,
            'Instagram' => null,
        ],
        'Administer' => [
            'Admin Termination' => null,
            'Agent Termination' => null,
            // 'Audit Dispute' => null,
            'Campaign List' => 'campaign/index',
            'Pending Feedback' => null,
            'Evaluation' => 'report/recordings',
            'Users' => 'user/index',
        ],
        'Monitoring' => [
            'Call Barge' => null,
            'Lead Performance' => null,
            'Live Monitoring' => 'report/monitoring',
        ],
        'Reports' => [
            'ACD Report' => 'report/acd-report',
            'APR Report' => 'report/agent-performance-report',
            'Call Register' => 'report/call-register-report',
            'CRM History' => 'report/crm-history-report',
            'Email History' => null,
            'Intelligent APR' => null,
            'Intelligent QMS' => null,
            'Login History' => 'report/agent-login-report',
            'QMS Report' => 'report/qms-report',
        ]
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
        return 'vaani_menu';
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
            [['level', 'menu_name'], 'required'],
            [['parent_id', 'level', 'sequence', 'status', 'del_status', 'not_in_group'], 'integer'],
            [['created_date'], 'safe'],
            [['menu_name', 'link', 'icon', 'created_by', 'created_ip', 'modified_by', 'modified_date', 'modified_ip'], 'string', 'max' => 50],
            [['route'], 'string', 'max' => 100],
            [['del_status'], 'default', 'value' => self::STATUS_NOT_DELETED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'menu_id' => 'Menu ID',
            'parent_id' => 'Parent ID',
            'menu_name' => 'Menu Name',
            'level' => 'Level',
            'link' => 'Link',
            'icon' => 'Icon',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'created_ip' => 'Created Ip',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
            'modified_ip' => 'Modified Ip',
            'del_status' => 'Del Status',
            'not_in_group' => 'Not In Group',
        ];
    }

    // get parent menu
    public function getParent()
    {
        return $this->hasOne(self::className(), ['menu_id' => 'parent_id']);
    }

    // get sub menus
    public function getSubMenus()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'menu_id'])->andOnCondition(['del_status' => self::STATUS_NOT_DELETED])->orderBy('sequence');
    }

    // list of all menus
    public static function allMenus()
    {
        $menus = self::find()->where(['del_status' => VaaniMenu::STATUS_NOT_DELETED])->andWhere(['status' => 1])->all();

        return $menus;
    }

    // list of all menus
    public static function lastMenu()
    {
        $menu = self::find()->where(['del_status' => VaaniMenu::STATUS_NOT_DELETED])->andWhere(['status' => 1])->orderBy('sequence DESC')->one();

        return $menu;
    }

    // display submenus
    public static function displaySubMenus($menu, $sub_menus)
    {
        if($menu && $sub_menus){
            echo '<ul class="sortable ui-sortable">';
            foreach($sub_menus as $k => $sub_menu){
                if($menu->menu_id != $sub_menu->menu_id){
                    $menus[$sub_menu->menu_id] = $sub_menu->menu_name;

                    echo '<li id="'. $sub_menu->menu_id .'">
                        <div class="chk_li_div">
                            <a href="javascript:void(0)" class="inline_div chk_div ui-sortable-handle">
                                <label class="container-checkbox">
                                    <input type="radio" class="menu_select check" name="radio1" data-level="'. $sub_menu->level .'" data-route="'. $sub_menu->route .'" data-icon="'. $sub_menu->icon .'" data-seq="'. $sub_menu->sequence .'" data-parent="'. $sub_menu->parent_id .'" data-name="'. $sub_menu->menu_name .'" value="'. $sub_menu->menu_id .'">
                                    <span class="checkmark"></span>
                                    <span class="category_name lvl_1">'. $sub_menu->menu_name .'</span>
                                </label>
                            </a>
                        </div>
                    </li>';

                    if($sub_menu->subMenus){
                        self::displaySubMenus($sub_menu, $sub_menu->subMenus);
                    }
                }
            }
            echo '</ul>';
        }
    }
}
