<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * PBX form
 */
class Pbx extends Model
{
    public $config_keys;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['config_keys', 'required'],
            ['config_keys', 'safe'],
        ];
    }
}
