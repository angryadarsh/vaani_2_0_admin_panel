<?php

namespace common\components;

use Yii;
use yii\base\Component;
use common\models\VaaniClientMaster;
use common\models\User;

/**
 * Login form
 */
class Utility extends Component
{

    /**
     * This method returns user browser detail.
     */
    public function getBrowser()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version = "";
		$ub = "";

		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
	
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i', $u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i', $u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i', $u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i', $u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i', $u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
	
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
	
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent, "Version") < strripos($u_agent,$ub)){
				$version = $matches['version'][0];
			}
			else {
				$version = $matches['version'][1];
			}
		}
		else {
			$version = $matches['version'][0];
		}
	
		// check if we have a number
		if ($version == null || $version == "") { $version = "?"; }
	
		return [
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		];
	}

	// add query logs (not in use)
	public function addLog($query, $process, $path=null)
	{
		$date = date("Y-m-d H:i:s");
		$logdate = date("d-m-Y");
		$process = trim(str_replace(' ', '_', trim($process)));
		$my_file = ($path ? $path : Yii::$app->params['LOG_LOCATION']) . $process . '_' . $logdate . '.log';
		$data = "[" . $date . "]# Query => " . ($query) . "\r\n\r\n";
		file_put_contents($my_file, $data, FILE_APPEND);

		return 1;
	}

	public function db_connect()
	{
		$VARDB_server 	= User::decrypt_data(Yii::$app->params['DUM_DB_HOST']);
		$VARDB_user 	= User::decrypt_data(Yii::$app->params['DUM_USER']);
		$VARDB_pass 	= User::decrypt_data(Yii::$app->params['DUM_DB_PASSWORD']);
		$VARDB_db 	= User::decrypt_data(Yii::$app->params['DUM_DB_NAME']);
		$conn			= mysqli_connect("$VARDB_server", "$VARDB_user", "$VARDB_pass", "$VARDB_db");
		// Check connection
		if (!$conn) {
			return false;
			// return "Connection failed: " . mysqli_connect_error();
		} 
		// debugLog("============ End Connect db Function ================");  
		return $conn;
	}

	public function client_db_connect($client_id, $is_db_created=true)
	{
		$client_model		= VaaniClientMaster::find()->where(['client_id' => $client_id])->one();
		if($client_model){
			$VARDB_server 	= User::decrypt_data($client_model->server);
			$VARDB_user 	= User::decrypt_data($client_model->username);
			$VARDB_pass 	= User::decrypt_data($client_model->password);
			

			$conn			= mysqli_connect("$VARDB_server", "$VARDB_user", "$VARDB_pass");
			if($is_db_created && !empty($client_id)){
				$VARDB_db		= User::decrypt_data($client_model->db);
				mysqli_select_db($conn, "$VARDB_db");
				
			}
			// Check connection
			if (!$conn) {
				return false;
				// return "Connection failed: " . mysqli_connect_error();
			} 
			// debugLog("============ End Connect db Function ================");  
			return $conn;
		}
		return "No client Found!";
	}
}
