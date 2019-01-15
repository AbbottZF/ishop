<?php
namespace app\utils\service;
/**
 * 配置参数
 * Class Upload
 * @package app\api\controller
 */
class ConfigService
{
    
   /**
    * 合并配置参数
    */
    public static function config(){
        //系统配置
        $config = cache('system_config');
        if(empty($config)){
         $config = model('System')->getConfig();
        }
        //网站配置
       if (cache('site_config')) {
            $site_config = cache('site_config');
        } else {
            $site_config = model('System')->field('value')->where('name', 'site_config')->find();
            $site_config = unserialize($site_config['value']);
            cache('site_config', $site_config);
        }
        $config = array_merge($config,$site_config);
        return config($config);
    }
    
    /**
     * 更新系统配置
     */
    public static function setConfig(){
        $config = model('System')->getConfig();
        cache('system_config',$config);
        $site_config = model('System')->field('value')->where('name', 'site_config')->find();
        $site_config = unserialize($site_config['value']);
        cache('site_config', $site_config);
        $config = array_merge($config,$site_config);
        return $config;
    }    
}