<?php

namespace Weitac\User\Http\Models;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class User_WxResponse {

    private $token;

    public function __construct()
    {
        
    }

	public  function get_token(){
		$url = 'https://qyapi.weixin.qq.com/cgi-bin/gettoken';
		$type = 'get';
		//weitac
		$data['corpid'] = 'wxa65ffb67d05cf4da'; //$this->_appid;
		$data['corpsecret'] = 'XWdJ0AZVtBJBWWc9OZoCz6OHMeCRcfYMxWH1In73HY4aXd-G_VZ5OJAopD1nO6ub';
		//齐鲁台
		//$data['corpid'] = 'wx8efbae2f6087ba9e'; //$this->_appid;
		//$data['corpsecret'] = '7jTIRZNyfexiYC2TXE03AwYgzA_IVmLF7wBlBpeHfhBH9hkmvZA2tfgl74zd3D19';
		$result = $this->_sendHttps($url, $data, $type);
		$contu = json_decode($result, true);
		
		 $token=$contu['access_token'];
			 return $token;
		
		}
	
	 public function _sendHttps($url, $data, $type = 'get')
    {
        if (is_array($data))
            $data = http_build_query($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        if ($type == 'post')
            curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);

        if (curl_errno($ch))
            return 'Errno' . curl_error($ch);
        curl_close($ch);

        return $result;
    }
	
	
    

}
