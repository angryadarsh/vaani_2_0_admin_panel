<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "tblaskme_file".
 *
 * @property int $id
 * @property string|null $clientid
 * @property string|null $process
 * @property string|null $tab
 * @property string|null $file_server
 * @property string|null $file_count
 * @property string|null $file_name
 * @property string|null $file_path
 * @property string|null $file_content
 * @property string|null $created_by
 * @property string|null $created_date
 * @property string|null $updated_date
 * @property int $status
 * @property int|null $priority
 */
class TblaskmeFile extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblaskme_file';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db1');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_content'], 'string'],
            [['status', 'priority'], 'integer'],
            [['clientid'], 'string', 'max' => 200],
            [['process', 'tab', 'created_date'], 'string', 'max' => 45],
            [['file_server', 'created_by', 'updated_date'], 'string', 'max' => 50],
            [['file_name'], 'string', 'max' => 256],
            [['file_path'], 'string', 'max' => 5000],
            [['file', 'file_count'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clientid' => 'Clientid',
            'process' => 'Process',
            'tab' => 'Tab',
            'file_server' => 'File Server',
            'file_count' => 'File Count',
            'file_name' => 'File Name',
            'file_path' => 'File Path',
            'file_content' => 'File Content',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'status' => 'Status',
            'priority' => 'Priority',
        ];
    }

    /**
     * Get Client Name By Client ID
     */
    public static function getClientName($id){
        $campaign_name = VaaniClientMaster::find()->select('client_name')->where(['client_id' => $id])->all();
        return $campaign_name[0]['client_name'];
    }

    /**
     * Get Campaign Name By Campaign ID
     */
    public static function getCampaignName($id){  
        $campaign_name = EdasCampaign::find()->select('campaign_name')->where(['campaign_id' => $id])->all();
        return $campaign_name[0]['campaign_name'];
    }

    /**
     * Make client Directory by client name
     * Make campaign Directory by campaign name
     */

    public static function makeDirectory($client,$campaign){
        $path = '../../vendor/knowledge_portal_files/'. $client."/".$campaign;
        FileHelper::createDirectory($path, $mode = 0777, $recursive = true);
        return $path;
    }

    /**
     * Find Highest priority by client and campaign base
     */

    public static function getPriority($client,$campaign){
        $priority = TblaskmeFile::find()->select('priority')->where(['and',['clientid' => $client],['process' => $campaign]])->orderBy(['priority' => SORT_DESC])->all();
        return $priority;
    }

    /**
     * Get Content of Excel File
     */

    public static function getExcelContent($filename){
        $fileType = \PHPExcel_Iofactory::identify($filename); // the file name automatically determines the type
        $excelReader = \PHPExcel_IOFactory::createReader($fileType);

        $phpexcel = $excelReader->Load($filename)->getsheet(0); // load the file and get the first sheet
        $total_Line = $phpexcel->gethighestrow(); // total number of rows
        $total_Column = $phpexcel->gethighestcolumn(); // total number of columns
        echo $total_Line." | ".$total_Column;
    }


    /**
     * Add Log
     */
    public static function addLog($string)
    {
        $date = date("Y-m-d H:i:s");
        $logdate = date("d-m-Y");
        $my_file = __DIR__ . '/../../backend/logs/knowledge_portal_' . $logdate . '.log';
        $data = "[" . $date . "]#" . $string . "\r\n";
        file_put_contents($my_file, $data, FILE_APPEND);
    }
}
