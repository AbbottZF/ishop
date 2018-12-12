<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace app\admin\service;

class AdminService {
    
    public static $msg = [
        0 => '数据验证不通过！',
        1 => '请求成功！',
        2 => '',
        808 => "未识别错误！",
    ];
    
    /**
     * 获取错误消息
     */
    public static function getMsg($code) {
        return isset(self::$msg[$code]) ? self::$msg[$code] : self::getMsg(100);
    }
}
