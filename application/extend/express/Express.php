<?php

namespace express;

use CURLFile;

/**
 * 快递鸟接口
 * api接口说明 http://www.kdniao.com/api-track
 */
class Express {
    /*     * 快递鸟域名 */

    const BASE_URL = 'http://api.kdniao.cc/';
    const JSCX_URL = "Ebusiness/EbusinessOrderHandle.aspx"; //快递查询
    const WLGZ_URL = "api/dist"; //快递跟踪

    /** 执行错误消息及代码 */

    public $errMsg ="系统内部错误！";
    /*     * 基础信息* */
    public $EBusinessID;
    public $AppKey;
    public $DataType = 2; //请求、数据返回类型
    public $RequestType;

    /**
     * WechatPay constructor.
     * @param array $options
     */
    public function __construct() {
        $this->EBusinessID = !empty(config('ebusinessid')) ? config('ebusinessid') : '';
        $this->AppKey = !empty(config('appkey')) ? config('appkey') : '';
    }


    /**
     * 数据提交格式
     */
    protected  function _parseData($data) {
        
        $datas = [
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => $this->RequestType,
            'RequestData' => urlencode(json_encode($data)),
            'DataType' => $this->DataType,
        ];
        $datas['DataSign'] = ExpressTools::getSignature(json_encode($data), $this->AppKey);
        return $datas;
    }

    /**
     * 查询订单
     */
    public function ebusinessOrderHandle($ShipperCode, $LogisticCode) {
        $this->RequestType = '1002';
        if (empty($ShipperCode) || empty($LogisticCode)) {
            $this->errMsg = "快递公司编码和物流单号都不能为空！";
            return false;
        }
        $data['ShipperCode'] = $ShipperCode;
        $data['LogisticCode'] = $LogisticCode;
        $json = ExpressTools::httpsPost(self::BASE_URL . self::JSCX_URL,$this->_parseData($data));
        if ($json) {
            $result = json_decode($json, true);
            if(isset($result['Success'])&&$result['Success']===false){
                $this->errMsg = $result['Reason'];
                return false;
            }
            return $result;
        }
        return false;
    }

}

/**
 * 接口通用类
 */
class ExpressTools {

    /**
     * 获取签名
     * @param array $arrdata 签名数组
     * @param string $method 签名方法
     * @return bool|string 签名值
     */
    static public function getSignature($data, $appkey) {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    /**
     * 以get方式提交请求
     * @param $url
     * @return bool|mixed
     */
    static public function httpGet($url) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== false) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }

    /**
     * 以post方式提交请求
     * @param string $url
     * @param array|string $data
     * @return bool|mixed
     */
    static public function httpPost($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        if (is_array($data)) {
            foreach ($data as &$value) {
                if (is_string($value) && stripos($value, '@') === 0 && class_exists('CURLFile', false)) {
                    $value = new CURLFile(realpath(trim($value, '@')));
                }
            }
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {
            return $data;
        }
        return false;
    }

    /**
     * 使用证书，以post方式提交xml到对应的接口url
     * @param string $url POST提交的内容
     * @param array $postdata 请求的地址
     * @param string $ssl_cer 证书Cer路径 | 证书内容
     * @param string $ssl_key 证书Key路径 | 证书内容
     * @param int $second 设置请求超时时间
     * @return bool|mixed
     */
    static public function httpsPost($url, $postdata, $ssl_cer = null, $ssl_key = null, $second = 30) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        /* 要求结果为字符串且输出到屏幕上 */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* 设置证书 */
        if (!is_null($ssl_cer) && file_exists($ssl_cer) && is_file($ssl_cer)) {
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $ssl_cer);
        }
        if (!is_null($ssl_key) && file_exists($ssl_key) && is_file($ssl_key)) {
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        if (is_array($postdata)) {
            foreach ($postdata as &$data) {
                if (is_string($data) && stripos($data, '@') === 0 && class_exists('CURLFile', false)) {
                    $data = new CURLFile(realpath(trim($data, '@')));
                }
            }
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

}
