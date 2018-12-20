<?php
/**
 * 后台接口返回
 * @param type $data
 * @param type $code
 * @param type $msg
 * @return type
 */
function adminMsg($data=[],$code=1,$msg=''){
    $arr = [
        'data'=>$data,'code'=>$code,
//        'msg'=>empty($msg)?app\admin\service\ApiService::getMsg($code):$msg,
    ];
    return json_encode($arr,JSON_UNESCAPED_UNICODE);
}
/**
 * 
 */
function adminErr($msg='',$code=0){
    $arr = [
        'msg'=>$msg,'code'=>$code,
//        'msg'=>empty($msg)?app\admin\service\ApiService::getMsg($code):$msg,
    ];
    return json_encode($arr,JSON_UNESCAPED_UNICODE);
}