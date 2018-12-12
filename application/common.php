<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * UTF8字符串加密
 * @param string $string
 * @return string
 */
function encode($string) {
    $chars = '';
    $len = strlen($string = iconv('utf-8', 'gbk', $string));
    for ($i = 0; $i < $len; $i++) {
        $chars .= str_pad(base_convert(ord($string[$i]), 10, 36), 2, 0, 0);
    }
    return strtoupper($chars);
}

/**
 * UTF8字符串解密
 * @param string $string
 * @return string
 */
function decode($string) {
    $chars = '';
    foreach (str_split($string, 2) as $char) {
        $chars .= chr(intval(base_convert($char, 36, 10)));
    }
    return iconv('gbk', 'utf-8', $chars);
}
/**
 * 后台接口成功返回
 * @param type $data
 * @param type $code
 */
function adminMsg($data = [], $code = 1,$msg='') {
    $api = [
        'code' => $code,
        'msg' => empty($msg)?app\admin\service\AdminService::getMsg($code):$msg,
        'data' => $data,
    ];
    return json($api);
}
/**
 * 后台接口错误返回
 * @param type $data
 * @param type $code
 */
function adminErrorMsg($data = [], $code = 0,$msg='') {
    $api = [
        'code' => $code,
        'msg' => empty($msg)?app\admin\service\AdminService::getMsg($code):$msg,
        'data' => $data,
    ];
    return json($api);
}