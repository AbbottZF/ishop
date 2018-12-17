<?php

function adminMsg($data=[],$code=1,$msg=''){
    $arr = [
        'data'=>$data,'code'=>$code,
//        'msg'=>empty($msg)?app\admin\service\ApiService::getMsg($code):$msg,
    ];
    return json_encode($arr,JSON_UNESCAPED_UNICODE);
}