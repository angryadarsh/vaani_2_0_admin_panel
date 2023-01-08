<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\Pbx;
use common\models\User;

/**
 * PbxController implements the AGENT actions.
 */
class PbxController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                    ],
                ],
            ]
        );
    }

    /**
     * Displays configuration form.
     * @return mixed
     */
    public function actionConfiguration()
    {
        $model = new Pbx();
        $config_keys = [];

        $feature_file = Yii::$app->params['features_url'];
        
        $lines = file($feature_file);
        
        foreach ($lines as $key => $value) {
            if(strpos($value, 'atxferabort =') > '-1'){
                $param_value = substr(explode("atxferabort = ", $value)[1], 0, 2);
                $config_keys['atxferabort'] = ['Abort Transfer' => $param_value];

            }elseif(strpos($value, 'atxferthreeway =') > '-1'){
                $param_value = substr(explode("atxferthreeway = ", $value)[1], 0, 2);
                $config_keys['atxferthreeway'] = ['Three way Transfer' => $param_value];

            }elseif(strpos($value, 'atxferswap =') > '-1'){
                $param_value = substr(explode("atxferswap = ", $value)[1], 0, 2);
                $config_keys['atxferswap'] = ['Swap Transfer' => $param_value];

            }elseif(strpos($value, 'blindxfer =>') > '-1'){
                $param_value = substr(explode("blindxfer => ", $value)[1], 0, 2);
                $config_keys['blindxfer'] = ['Blind Transfer' => $param_value];
            }elseif(strpos($value, 'atxfer =>') > '-1'){
                $param_value = substr(explode("atxfer => ", $value)[1], 0, 2);
                $config_keys['atxfer'] = ['Attend Transfer' => $param_value];
            }
        }

        if($this->request->post() && $model->load($this->request->post())){
            if($model->config_keys){
                // create backup file
                $backupFileName = Yii::$app->params['asterisk_backup'] . '/features_temp_' . time() . '.conf';
                copy(Yii::$app->params['features_url'], $backupFileName);        // copy file from remote server to local server
                // open feature file to write
                $writing = fopen($feature_file, 'w') or die("Unable to open file!:".$feature_file."-w");

                foreach ($lines as $key => $value) {
                    if(isset($model->config_keys['atxferabort']) && (strpos($value, 'atxferabort =') > '-1')){
                        $param_value = substr(explode("atxferabort = ", $value)[1], 0, 2);
                        $value = str_replace($param_value, $model->config_keys['atxferabort'], $value);

                    }elseif(isset($model->config_keys['atxferthreeway']) && (strpos($value, 'atxferthreeway =') > '-1')){
                        $param_value = substr(explode("atxferthreeway = ", $value)[1], 0, 2);
                        $value = str_replace($param_value, $model->config_keys['atxferthreeway'], $value);

                    }elseif(isset($model->config_keys['atxferswap']) && (strpos($value, 'atxferswap =') > '-1')){
                        $param_value = substr(explode("atxferswap = ", $value)[1], 0, 2);
                        $value = str_replace($param_value, $model->config_keys['atxferswap'], $value);

                    }elseif(isset($model->config_keys['blindxfer']) && (strpos($value, 'blindxfer =>') > '-1')){
                        $param_value = substr(explode("blindxfer => ", $value)[1], 0, 2);
                        $value = str_replace($param_value, $model->config_keys['blindxfer'], $value);

                    }elseif(isset($model->config_keys['atxfer']) && (strpos($value, 'atxfer =>') > '-1')){
                        $param_value = substr(explode("atxfer => ", $value)[1], 0, 2);
                        $value = str_replace($param_value, $model->config_keys['atxfer'], $value);

                    }
                    fputs($writing,$value);
                }
                fclose($writing);
                
                User::reload_call();
                Yii::$app->session->setFlash('success', 'Pbx configurations set successfully.');

                return $this->redirect('configuration');
            }
        }

        return $this->render('configuration', [
            'model' => $model,
            'config_keys' => $config_keys,
        ]);
    }
}
