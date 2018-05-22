<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Tool
 *
 */
class Tool
{
    /**
     * httpGet
     *
     * @param  string $url 地址
     * @param  array $param
     * @return string
     */
    public static function http_get($url, $param = [])
    {
        if (empty($url)) return false;
        // 参数
        if (!empty($param)) {
            if (strpos($url, '?') == false) $url .= '?';
            foreach ($param as $_key => $_value) {
                $url .= $_key . '=' . $_value . '&';
            }
            $url = rtrim($url, '&');
        }
        // create curl resource
        $curl = curl_init();
        // set url

        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置获取的信息以文件流的形式返回，而不是直接输 @return string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // https request
        if (strlen($url) > 15 && strtolower(substr($url, 0, 5)) == 'https') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }
        // 执行会话
        $output = curl_exec($curl);
        // 关闭会话 释放资源句柄
        curl_close($curl);
        // 返回字符串
        return $output;
    }

    /**
     * httpPost
     *
     * @param  string $url 地址
     * @param  array $param
     * @return string
     */
    public static function http_post($url = '', $param = array())
    {
        if (empty($url)) return false;
        $data = '';
        if (!empty($param)) {
            foreach ($param as $_key => $_value) {
                $data .= $_key . '=' . $_value . '&';
            }
            $data = rtrim($data, '&');
        }
        // create curl resource
        $curl = curl_init();
        // set url
        curl_setopt($curl, CURLOPT_URL, $url);
        // 设置获取的信息以文件流的形式返回，而不是直接输 @return string
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 使用POST协议发送请求
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        // https request
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == 'https') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }
        // 执行会话
        $output = curl_exec($curl);
        // 关闭会话 释放资源句柄
        curl_close($curl);
        // 返回字符串
        return $output;
    }


    /**
     * 获取随机code
     *
     * @param  string $type
     * @param  int $length
     * @return string
     */
    public static function rand_code_get($type = 'string', $length = 0)
    {
        $code = '';
        switch ($type) {
            case 'string':
                $code_str = 'abcdefghijplmnopqrstuvwxyz';
                for ($i = 0; $i < $length; $i++) {
                    $code .= $code_str[mt_rand(0, strlen($code_str) - 1)];
                }
                break;
            case 'number':
                $code_num = '01234567890';
                for ($i = 0; $i < $length; $i++) {
                    $code .= $code_num[mt_rand(0, strlen($code_num) - 1)];
                }
                break;
                break;
        }
        return $code;
    }

    /**
     * @return bool
     *
     * 判断单页请求
     */
    public static function is_pjax()
    {
        return array_key_exists('HTTP_X_PJAX', $_SERVER) && $_SERVER['HTTP_X_PJAX'];
    }

    /**
     * 提示信息
     *
     * @param  array $tooltip 提示信息
     * @return string
     */
    public static function show_tooltip($tooltip)
    {
        $CI = &get_instance();
        $CI->load->view('frontend/components/tooltip', $tooltip);
        $CI->output->_display();
        exit;
    }

    /**
     * 获取IP地址
     *
     * @return array|false|string
     */
    public static function get_ip()
    {
        $ip=false; 
		if(!empty($_SERVER['HTTP_CLIENT_IP'])){ 
			$ip=$_SERVER['HTTP_CLIENT_IP']; 
		}
		if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']); 
			if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
			for ($i=0; $i < count($ips); $i++){
				if(!eregi ('^(10│172.16│192.168).', $ips[$i])){
					$ip=$ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
    }
}
