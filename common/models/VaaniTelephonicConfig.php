<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * PBX form
 */
class VaaniTelephonicConfig extends Model
{       
       
    public $codecs;
    public $configtype;
    public $externip;
    public $localnet;
    public $nat;
    public $register;
    public $operator;
    public $username;
    public $secret;
    public $fromdomain;
    public $fromuser;
    public $host;

    public static $config_type = [
        'SIP' => 'SIP',
        'PRI' => 'PRI',
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['configtype','codecs','externip','localnet','nat','register','operator'],'safe'],
            [['username','secret','fromdomain','fromuser','host'],'safe'],
        ];
    }
}
