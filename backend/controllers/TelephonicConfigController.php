<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\models\VaaniTelephonicConfig;
use common\models\VaaniOperators;
use common\models\User;

/**
 * VaaniTelephonicConfig implements the index actions.
 */
class TelephonicConfigController extends Controller
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
                        'index'
                    ],
                ],
            ]
        );
    }

    /**
     * Displays VaaniTelephonicConfig index.
     * @return mixed
     */
    public function actionIndex()
    {   
        $model = new VaaniTelephonicConfig();
        $operators = ArrayHelper::map(VaaniOperators::getOperatorList(), 'operator_name', 'operator_name');

        if($this->request->isPost && $model->load($this->request->post())){
            echo "<pre>"; print_r($model);exit;
        }
        
        return $this->render('index', [
            'model' => $model,
            'operators' => $operators,
        ]);
    }
}
