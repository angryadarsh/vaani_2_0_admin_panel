<?php

namespace backend\controllers;

use Yii;
// use common\models\VaaniMenu;
// use common\models\search\VaaniMenuSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use mdm\admin\models\Route;
use yii\helpers\ArrayHelper;

/**
 * EmailController implements the CRUD actions for VaaniEmails model.
 */
class EmailController extends Controller
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
                        // 'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all VaaniEmails models.
     * @return mixed
     */
    public function actionInbox()
    {
        /* $searchModel = new VaaniMenuSearch();
        $searchModel->del_status = VaaniMenu::STATUS_NOT_DELETED;
        
        $dataProvider = $searchModel->search($this->request->queryParams); */

        return $this->render('inbox');
    }
}
