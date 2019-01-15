<?php

use think\Db;
use think\Request;

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
 * 获取分类所有子分类
 * @param int $cid 分类ID
 * @return array|bool
 */
function get_category_children($cid) {
    if (empty($cid)) {
        return false;
    }

    $children = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->select();

    return array2tree($children);
}

/**
 * 根据分类ID获取文章列表（包括子分类）
 * @param int   $cid   分类ID
 * @param int   $limit 显示条数
 * @param array $where 查询条件
 * @param array $order 排序
 * @param array $filed 查询字段
 * @return bool|false|PDOStatement|string|\think\Collection
 */
function get_articles_by_cid($cid, $limit = 10, $where = [], $order = [], $filed = []) {
    if (empty($cid)) {
        return false;
    }

    $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
    $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

    $fileds = array_merge(['id', 'cid', 'title', 'introduction', 'thumb', 'reading', 'publish_time'], (array) $filed);
    $map = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array) $where);
    $sort = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array) $order);

    $article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->limit($limit)->select();

    return $article_list;
}

/**
 * 根据分类ID获取文章列表，带分页（包括子分类）
 * @param int   $cid       分类ID
 * @param int   $page_size 每页显示条数
 * @param array $where     查询条件
 * @param array $order     排序
 * @param array $filed     查询字段
 * @return bool|\think\paginator\Collection
 */
function get_articles_by_cid_paged($cid, $page_size = 15, $where = [], $order = [], $filed = []) {
    if (empty($cid)) {
        return false;
    }

    $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
    $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

    $fileds = array_merge(['id', 'cid', 'title', 'introduction', 'thumb', 'reading', 'publish_time'], (array) $filed);
    $map = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array) $where);
    $sort = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array) $order);

    $article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->paginate($page_size);

    return $article_list;
}

/**
 * 数组层级缩进转换
 * @param array $array 源数组
 * @param int   $pid
 * @param int   $level
 * @return array
 */
function array2level($array, $pid = 0, $level = 1) {
    static $list = [];
    foreach ($array as $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $list[] = $v;
            array2level($array, $v['id'], $level + 1);
        }
    }

    return $list;
}

/**
 * Emoji原形转换为String
 * @param string $content
 * @return string
 */
function emojiEncode($content) {
    return json_decode(preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i", function ($str) {
                return addslashes($str[0]);
            }, json_encode($content)));
}

/**
 * Emoji字符串转换为原形
 * @param string $content
 * @return string
 */
function emojiDecode($content) {
    return json_decode(preg_replace_callback('/\\\\\\\\/i', function () {
                return '\\';
            }, json_encode($content)));
}

/**
 * 构建层级（树状）数组
 * @param array  $array          要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
 * @param string $pid_name       父级ID的字段名
 * @param string $child_key_name 子元素键名
 * @return array|bool
 */
function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children') {
    $counter = array_children_count($array, $pid_name);
    if (!isset($counter[0]) || $counter[0] == 0) {
        return $array;
    }
    $tree = [];
    while (isset($counter[0]) && $counter[0] > 0) {
        $temp = array_shift($array);
        if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
            array_push($array, $temp);
        } else {
            if ($temp[$pid_name] == 0) {
                $tree[] = $temp;
            } else {
                $array = array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
            }
        }
        $counter = array_children_count($array, $pid_name);
    }
    return $tree;
}

function array2string($array) {
    return implode(',', $array);
}

function string2array($string) {
    return explode(',', $string);
}

/**
 * 子元素计数器
 * @param array $array
 * @param int   $pid
 * @return array
 */
function array_children_count($array, $pid) {
    $counter = [];
    foreach ($array as $item) {
        $count = !empty($counter[$item[$pid]]) ? $counter[$item[$pid]] : 0;
        $count++;
        $counter[$item[$pid]] = $count;
    }
    return $counter;
}

/**
 * 把元素插入到对应的父元素$child_key_name字段
 * @param        $parent
 * @param        $pid
 * @param        $child
 * @param string $child_key_name 子元素键名
 * @return mixed
 */
function array_child_append($parent, $pid, $child, $child_key_name) {
    foreach ($parent as &$item) {
        if ($item['id'] == $pid) {
            if (!isset($item[$child_key_name]))
                $item[$child_key_name] = [];
            $item[$child_key_name][] = $child;
        }
    }

    return $parent;
}

/**
 * 树形排序
 */
function tree_sort(&$array, $sort = "sort", $direction = "SORT_DESC") {
    if (empty($array)) {
        return [];
    }
    array_sort($array, $sort);
    foreach ($array as $key => &$val) {
        if (isset($val['children']) && !empty($val['children'])) {
            array_sort($val['children'], $sort);
            tree_sort($val['children']);
        }
    }
}

/**
 * 排序
 */
function array_sort(&$arr, $field, $direction = "SORT_DESC") {
    $arrSort = array();
    foreach ($arr AS $uniqid => $row) {
        foreach ($row AS $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($direction), $arr);
}

/**
 * 循环删除目录和文件
 * @param string $dir_name
 * @return bool
 */
function delete_dir_file($dir_name) {
    $result = false;
    if (is_dir($dir_name)) {
        if ($handle = opendir($dir_name)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($dir_name . DS . $item)) {
                        delete_dir_file($dir_name . DS . $item);
                    } else {
                        unlink($dir_name . DS . $item);
                    }
                }
            }
            closedir($handle);
            if (rmdir($dir_name)) {
                $result = true;
            }
        }
    }

    return $result;
}

/**
 * 数组转对象
 * @param type $array
 * @return type
 */
function array2object($array) {
    if (is_array($array)) {
        $obj = new StdClass();
        foreach ($array as $key => $val) {
            $obj->$key = $val;
        }
    } else {
        $obj = $array;
    }
    return $obj;
}

/**
 * 对象装数组
 * @param type $object
 * @return type
 */
function object2array($object) {
    if (is_object($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = $value;
        }
    } else {
        $array = $object;
    }
    return $array;
}

/**
 * 判断是否为手机访问
 * @return  boolean
 */
function is_mobile() {
    static $is_mobile;

    if (isset($is_mobile)) {
        return $is_mobile;
    }

    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        $is_mobile = false;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
    ) {
        $is_mobile = true;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

/**
 * 手机号格式检查
 * @param string $mobile
 * @return bool
 */
function check_mobile_number($mobile) {
    if (!is_numeric($mobile)) {
        return false;
    }
    $reg = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';

    return preg_match($reg, $mobile) ? true : false;
}

// 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
    if (strpos($string, ':')) {
        $value = array();
        foreach ($array as $val) {
            list($k, $v) = explode(':', $val);
            $value[$k] = $v;
        }
    } else {
        $value = $array;
    }
    return $value;
}

//设置导航高亮
function sub_heigh_light($menu=[]){
    $return = [
        'currentMenu' => '',
        'tag'=>''
    ];
    if(empty($menu))
        return $return;
    $request = Request::instance();
    $module = $request->module();
    $controller = $request->controller();
    $action = $request->action();
    $currentUrl = strtolower($module . '/' . $controller . '/' . $action);
    $result = current(array_filter($menu, function($v) use ($currentUrl) {
        return strtolower($v['name']) == $currentUrl;
    }));
    if($result===FALSE)
        return $return;
    $pid = $result['pid'];
    if($pid==0){
        $return['currentMenu'] = $result['name'];
        $return['tag'] = $result['tag'];
        return $return;
    }
    $return['currentMenu'] = $result['name'];
    while(true){
        $res = current(array_filter($menu, function($v) use ($pid) {
            return $v['id'] == $pid;
        })); 
        if($res['pid']==0){
            $return['tag'] = $res['tag'];
            break;
        }
        $pid = $res['pid'];
        $return['currentMenu'] = $res['name'];
    }
    return $return;
    
}

/**
 * alert弹出窗口
 * @param type $msg
 */
function alert($msg, $url = "", $develop = "", $dev_url = "") {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">';
    echo "<title>温馨提示</title>";
    echo '<script src="' . config('view_replace_str.__JS__') . '/jquery.min.js"></script>';
    echo '<link rel="stylesheet" href="' . config('view_replace_str.__STATIC__') . '/lawnson/css/weui.min.css">';
    echo <<<EOF
<div class="weui-msg weui-self" id="msgConfirm_php">
    <div class="weui-msg__icon-area"><i class="weui-icon-waiting weui-icon_msg"></i></div>
    <div class="weui-msg__text-area">
        <h2 class="weui-msg__title">信息提示</h2>
        <div class="weui-msg__desc" id="alert_msg_php">
           
        </div>
    </div>
    <div class="weui-msg__opr-area">
        <p class="weui-btn-area">
            <a href="javascript:;" class="weui-btn weui-btn_primary">确定</a>
            <a href="javascript:;" class="weui-btn weui-btn_default" style="display:none"></a>
        </p>
    </div>
    <div class="weui-msg__extra-area">
        <div class="weui-footer">
            <p class="weui-footer__links" style="padding-bottom:10px">
                <a href="javascript:void(0);" class="weui-footer__link">
EOF;
    echo config('site_title');
    echo <<<EOF
                </a>
            </p>
        </div>
    </div>
</div>
EOF;

    echo <<<EOF
<script>
$(document).ready(function(){
EOF;
    echo "$('#alert_msg_php').html('" . $msg . "');";
    if (!empty($develop)) {
        echo "$('#msgConfirm_php').find('.weui-btn_default').html('" . $develop . "').show().click(function(){";
        if (!empty($dev_url)) {
            echo "window.location.replace('" . $dev_url . "')";
        } else {
            if (isWeixinBrowser()) {
                echo "WeixinJSBridge.call('closeWindow')";
            } else {
                echo "history.back()";
            }
        }
        echo "});";
    }
    echo <<<EOF
    $('#msgConfirm_php').find('.weui-btn_primary').click(function(){
EOF;
    if (!empty($url)) {
        echo "window.location.replace('" . $url . "')";
    } else {
        if (isWeixinBrowser()) {
            echo "WeixinJSBridge.call('closeWindow')";
        } else {
            echo "history.back()";
        }
    }
    echo "});";
    echo "});";
    echo "</script>";
    exit;
}

/**
 * 获取图片路径
 */
function getCover($id = "", $field = "path") {
    if (is_numeric($id)) {
        $pictrue = Db::name('picture')->field($field)->where(['id' => $id])->find();
        if (!empty($pictrue)) {
            return WEB_PATH.$pictrue[$field];
        } else {
            return false;
        }
    }
    return $id;
}

/**
 * 生成随机字符串
 */
function randStr($s = 32) {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars = str_shuffle($chars);
    return substr($chars, 3, $s);
}

/**
 * ajaxMsg
 * @param type $msg 提示信息
 * @param type $status 状态
 * @param type $url 是否有跳转地址
 */
function ajaxMsg($msg = "", $code = 0, $url = "", $data = "") {
    $result = [
        'msg' => $msg,
        'code' => $code,
        'url' => $url,
        'data' => $data
    ];
    return json($result);
}

/**
 * 
 * @param type $data
 * @param type $code
 */
function apiMsg($data = [], $code = 0,$msg='') {
    $api = [
        'code' => $code,
        'msg' => empty($msg)?app\api\service\ApiService::getMsg($code):$msg,
        'data' => $data,
    ];
    return json($api);
}

//php 异步执行
function sock_post($url, $query)
{	
    $_post = strval(NULL);
    if(!empty($query)){
        $_post = "query=".  urlencode(json_encode($query));
    }
    $http = "http://";
    if(isset($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on'){
        $http = "https://";
    }
    $goUrl = WEB_PATH.'/index.php?s=/'.$url;
    $info = parse_url($goUrl);
    $fp = fsockopen($info["host"], 80, $errno, $errstr, 3);
    $head = "POST ".$info['path']."?".$info["query"]." HTTP/1.0\r\n";
    $head .= "Host: ".$info['host']."\r\n";
    $head .= "Referer: $http".$info['host'].$info['path']."\r\n";
    $head .= "Content-type: application/x-www-form-urlencoded\r\n";
    $head .= "Content-Length: ".strlen(trim($_post))."\r\n";
    $head .= "\r\n";
    $head .= trim($_post);
    fwrite($fp, $head);
    fclose($fp);
}

//获取网络图片的64位编码
function curl_url($url) {
    $dir = pathinfo($url);
    $host = $dir['dirname'];
    $refer = $host . '/';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_REFERER, $refer); //伪造来源地址  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //返回变量内容还是直接输出字符串,0输出,1返回内容  
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1); //在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出  
    curl_setopt($ch, CURLOPT_HEADER, 0); //是否输出HEADER头信息 0否1是  
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); //超时时间  
    $data = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    $httpCode = intval($info['http_code']);
    $httpContentType = $info['content_type'];
    $httpSizeDownload = intval($info['size_download']);
    $base_64 = base64_encode($data);
    return "data:{$httpContentType};base64,{$base_64}";
}

//开启事务
function beginTransaction(){
    Db::startTrans();
}
//提交事务
function commitTransaction(){
    Db::commit();
}
//事务回滚
function rollbackTransaction(){
    Db::rollback();
}

/**
 * 获取积分抵扣金额,计算及返回金额值单位均为分
 * 返回：
 * offset_price:抵扣金额
 * min_use_bonus:最少使用积分数量
 */
function get_bonus_offset_price($rule,$orderPrice,$bonus,$tatol_money){
    $result = ['offset_price'=>0,'min_use_bonus'=>0,'have_bonus'=>$bonus,];
    if(empty($rule)){ 
        return $result = ['offset_price'=>0,'min_use_bonus'=>0,'have_bonus'=>$bonus,'title'=>'暂无积分规则!'];
    }
    if(empty($orderPrice)){
        return $result = ['offset_price'=>0,'min_use_bonus'=>0,'have_bonus'=>$bonus,'title'=>'订单金额为0，不能使用积分!'];
    }
    if(empty($bonus)){
        return $result = ['offset_price'=>0,'min_use_bonus'=>0,'have_bonus'=>$bonus,'title'=>'当前没有可用积分!'];
    }
    if($tatol_money < $rule['least_money_to_use_bonus']){//不达到抵扣条件
        return $result = ['offset_price'=>0,'min_use_bonus'=>0,'have_bonus'=>$bonus,'title'=>'订单金额满'.($rule['least_money_to_use_bonus']/100).'可用！'];
    }
    if($orderPrice < $rule['max_reduce_money']){//订单金额小于最大可抵扣金额
        //达到积分抵扣上限是，最少使用多少积分
        $min_use_bonus = (intval($orderPrice/$rule['reduce_money']))*$rule['cost_bonus_unit'];
        if($min_use_bonus <= $bonus){
            $result['min_use_bonus'] = $min_use_bonus;
//            $result['offset_price'] = $orderPrice;
            $result['offset_price'] =intval($min_use_bonus*$rule['reduce_money'])/$rule['cost_bonus_unit'];
        }else{
            $result['offset_price'] = (intval($bonus/$rule['cost_bonus_unit']))*$rule['reduce_money'];
            if($result['offset_price'] >= $rule['max_reduce_money']){//抵扣金额
                $result['offset_price'] = $rule['max_reduce_money'];
                //达到积分抵扣上限是，最少使用多少积分
                $min_use_bonus = (intval($rule['max_reduce_money']/$rule['reduce_money']))*$rule['cost_bonus_unit'];
                if($min_use_bonus < $bonus){
                    $result['min_use_bonus'] = $min_use_bonus;
                }
            }
        }
    }else{
        $multiple = (intval($bonus/$rule['cost_bonus_unit']));
        $result['min_use_bonus'] = $multiple*$rule['cost_bonus_unit'];//最少积分数
        $result['offset_price'] = $multiple*$rule['reduce_money'];
        if($result['offset_price'] > $rule['max_reduce_money']){//抵扣金额
            $result['offset_price'] = $rule['max_reduce_money'];
            //达到积分抵扣上限是，最少使用多少积分
            $multiple = (intval($rule['max_reduce_money']/$rule['reduce_money']));
            $min_use_bonus = $multiple*$rule['cost_bonus_unit'];
            if($min_use_bonus < $bonus){
                $result['min_use_bonus'] = $min_use_bonus;
            }
        }
    }
    $result['offset_price'] = $result['offset_price']<0?0:$result['offset_price'];
    $result['min_use_bonus']= intval( $result['offset_price']*$rule['cost_bonus_unit']/$rule['reduce_money']);
    return $result;
}
/**
 * 获取消费金额可获得积分，金额为支付金额,单位分
 * 返回：获得积分值
 */
function get_bonus_pay_order_bonus($rule,$orderPrice){
    $bonus = 0;
    if(empty($rule) || empty($orderPrice)){
        return $bonus;
    }
    if($orderPrice < $rule['cost_gt']){
        return $bonus;
    }
    $bonus = intval($orderPrice/$rule['cost_money_unit'])*$rule['increase_bonus'];//订单可获得积分
    if($bonus > $rule['max_increase_bonus']){
        $bonus = $rule['max_increase_bonus'];
    }
    return $bonus;
}
/**
 * 卡券抵扣金额计算,返回和计算值都为分
 */
function get_card_offset_perice($card_code_id,$orderPrice,$use_card=true,$customer_id = 0){
    $result = ['code'=>0,'msg'=>'此卡券不可用','offset_price'=>0];
    $card_code_info = Db::name('card_code')->where('id',$card_code_id)->find()->toArray();
    if(empty($card_code_info) || $card_code_info['status'] != 2){
        return $result;
    }
    if($card_code_info['canceltime'] < time()){
        $result['msg'] = '此卡券已过期！';
        return $result;
    }
    $card_info = Db::name('card')->where('id',$card_code_info['card_id'])->find()->toArray();
    if(empty($card_info) || $card_info['status'] != 'CARD_STATUS_VERIFY_OK' || in_array($card_info['usearea'], [0,1])){//卡券信息，状态，使用范围
        return $result;
    }
    //卡券使用时间段
    
    $offsetPrice = 0;
    switch ($card_info['card_type']) {
        case 'GROUPON'://团购券,暂不支持
            break;
        case 'CASH'://抵扣券(代金券)
            if($orderPrice>$card_info['least_cost']){//使用门槛
                $offsetPrice = $card_info['reduce_cost'];
                $result = ['code'=>1,'msg'=>'此卡券可正常使用！','offset_price'=>$offsetPrice];
            }else{
                $result['msg'] = '消费金额未达到！';
            }
            break;
        case 'DISCOUNT'://折扣券
            $offsetPrice = $orderPrice*$card_info['discount'];
            $result = ['code'=>1,'msg'=>'此卡券可正常使用！','offset_price'=>$offsetPrice];
            break;
        case 'GIFT'://兑换券,暂不支持
            break;
        case 'GENERAL_COUPON'://通用券,暂不支持
            break;
        default:
            break;
    }
    if($use_card && $result['code'] == 1){//需要修改卡券为已使用
        if(empty($customer_id)){
            $result = ['code'=>0,'msg'=>'修改卡券请提供会员信息！','offset_price'=>0];
        }else{
            $res = Db::name('card_code')->where('status','IN',[2,5])->update(['status'=>3]);
            if($res === false){
                $result = ['code'=>0,'msg'=>'卡券使用失败！','offset_price'=>0];
            }
        }
    }
    return $result;
}
