<?php
namespace App\Utils;
use \Firebase\JWT\JWT;

define('METHOD','AES-256-CBC');
define('SECRET_KEY','key_pass_decriptor');
define('SECRET_IV','key_pass_decriptor_2');

class Utils {

    public static function ValidarCampo($arg){

        if(isset($arg) && !empty($arg) && !is_null($arg)){
            return true;
        }
        else{
            return false;
        }
    }

    public static function EncodeToken($payload){
        return JWT::encode($payload, "key_parcial");
    }

    public static function DecodeToken($token){
        return JWT::decode($token, "key_parcial", array('HS256'));
    }

	public static function EncodePass($password){
		$output=FALSE;
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_encrypt($password, METHOD, $key, 0, $iv);
		$output=base64_encode($output);
		return $output;
	}
        
    public static function DecodePass($password){
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_decrypt(base64_decode($password), METHOD, $key, 0, $iv);
		return $output;
    }
    
    public static function ParseDatePHPmySQL($fecha){
        $var = strtotime($fecha);
        return date('Y-m-d',$var);
    }
}
?>