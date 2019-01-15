<?php
namespace app\utils\controller;
use app\common\controller\SockBase;
use app\common\model\WechatFansTags;
class SendError extends SockBase{

    public function send() {
           //查询所有开发者
          $openids = (new WechatFansTags())->getOpenids('开发者');
           if(!empty($openids)){
                   $weObj = getWeObject();
                   //给每个开发者发送信息
                   $errinfo = "";
                   foreach ($this->params['error'] as $error){
                      if(config('app_debug')===false){
                         if(!empty($errinfo)){ 
                           $errinfo.='\n';
                         }
                         $errinfo.=$error;
                      }else{ $errinfo.=$error."\n\n";}
                   }
                   $err = "===== 异常提示 =====\n\n";
                   $err .= "报警信息：".$errinfo;
                   $err .= "处理级别：最高级别"."\n\n"."错误位置：".$this->params['error_url']."\n\n报警时间：".date('Y-m-d H:i:s')."\n\n";
                   $err.="===== 异常提示 =====";
                   foreach ($openids as $openid){
                       if(config('app_debug')===false){
                            $array = [];
                            $array[] = date('Y-m-d H:i:s');
                            $array[] = '最高级别';
                            $first = '错误位置：' . $this->params['error_url'];
                            $remark = str_replace('\\', '/', $errinfo);
                           send_templ_msg($openid, "OPENTM204628126",$first, $array, $remark);
                       }else{
                           $data = [
                               'touser'=>$openid,
                               'msgtype'=>'text',
                               'text'=>[
                                   'content'=>$err
                               ]
                           ];
                           $weObj->sendCustomMessage($data);
                       }
                   }
           }
    }

}
