<?php
namespace app\admin\service;

class AdminService {
    
    public static $msg = [
        0 => '数据验证不通过！',
        1 => 'success！',
        101 => '数据获取成功！',
        1000=>'添加失败！',
        1001=>'添加成功！',
        2000=>'保存失败！',
        2001=>'保存成功！',
        808 => "未识别错误！",
    ];
    
    /**
     * 获取错误消息
     */
    public static function getMsg($code) {
        return isset(self::$msg[$code]) ? self::$msg[$code] : self::getMsg(0);
    }
}
