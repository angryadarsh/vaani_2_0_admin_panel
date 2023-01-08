<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Api extends Model
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public static function callApi($method, $url, $data, $username, $password)
	{
		$curl 		= curl_init();
		$username 	= $username;
		$password 	= $password;
 
		switch ($method){
		    case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
				    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			    break;
		    default:
			    if ($data)
				    $url = sprintf("%s?%s", $url, http_build_query($data));
		}

	    // OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type'=> 'application/json'));
		curl_setopt($curl, CURLOPT_USERNAME, $username);
		curl_setopt($curl, CURLOPT_PASSWORD, $password);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
 
		// EXECUTE CURL REQUEST AND RETURN THE DATA :
		$result = curl_exec($curl);
		$erro   = curl_error($curl);
		$info 	= curl_getinfo($curl);
		
		curl_close($curl);
		return $result;   
	}
}
