<?php
use think\Config;
/* * ***********************网站根目录***************************** */
if (isset($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on') {
    define('WEB_PATH', 'https://' . $_SERVER['HTTP_HOST']);
}else{
    define('WEB_PATH', 'http://' . $_SERVER['HTTP_HOST']);
}
/* * ***********************网站根目录***************************** */

//错误页面
if(!Config::get('app_debug')){
  Config::set('exception_tmpl',ROOT_PATH.DS.'public/404/404.tpl');
}
/* * ***********************写日志***************************** */
function writerLog($log = '', $type = 'log', $path = '') {
    \log\Log::writer($log, $type, $path);
}

/**
 * 获取微信工具类实例
 */
function getWeObject() {
    $options = [
        'token' => config('wechat_token'),
        'appid' => config('wechat_appid'),
        'appsecret' => config('wechat_secret'),
        'encodingaeskey' => config('wechat_encodingaeskey'),
    ];
    return new \wechat\Wechat($options);
}

/**
 * 获取微信工具类实例(服务商)
 */
function getWePayFwsObject() {
    return new \wechat_pay\WechatPayFws();
}
/**
 * 获取微信工具类实例(直连)
 */
function getWePayObject() {
    return new \wechat_pay\WechatPay();
}

/**
 * 获取快递鸟对象
 * @return boolean
 */
function getKdnObject() {
    return new \express\Express();
}

// 判断是否是在微信浏览器里
function isWeixinBrowser() {
    $agent = $_SERVER ['HTTP_USER_AGENT'];
    if (!strpos($agent, "icroMessenger")) {
        return false;
    }
    return true;
}

/**
 * 添加模板消息
 */
function add_templ($template_id_short) {
    $weObject = getWeObject();
    $info = $weObject->addTemplateMessage($template_id_short);
    return $info;
}

/**
 * 获取所有消息模板列表
 */
function get_templ_all() {
    $weObject = getWeObject();
    $tplList = $weObject->getTemplateList();
    if ($tplList) {
        return $tplList;
    } else {
        return false;
    }
}

/**
 * 返回制定template_id的模板消息
 * 
 */
function get_templ($template_id) {
    $arr = get_templ_all();
    if (!$arr)
        return false;
    $result = array_filter($arr['template_list'], function($v) use ($template_id) {
        return $v['template_id'] == $template_id;
    }
    );
    if ($result) {
        return current($result);
    } else {
        return false;
    }
}

/**
 * 发送模板消息arr的长度必须和keyword数量相同而且从上到下一一对应
 *  {{first.DATA}}
  用户昵称：{{keyword1.DATA}}
  签到时间：{{keyword2.DATA}}
  签到奖励：{{keyword3.DATA}}
  {{remark.DATA}}
 *  $arr = array();
  $arr[] = "lawnson";或者$arr[] = array('text'=>'lawnson','color'=>'#746A3A');
  $arr[] = "2015-08-24";
  $arr[] = "5积分";
  $template_id = "oDCueiffLxPP0DfhgKx7rFA84NKTrrR7C_wyPEhqLXI";
  $url = "http://we.ynjsy.com/index.php/Home/Index/index";
  $tpcolor="#746A3A";
 *  send_templ_msg($openid, $template_id, $arr1,$url,$tpcolor);
 */
function send_templ_msg($openid, $template_id_short, $first, $arr, $remark, $url = "") {
    $templ = cache('temp_' . $template_id_short);
    if (empty($templ)) {
        $mp['template_id_short'] = $template_id_short;
        $templ = model('Template')->where($mp)->find()->toArray();
        cache('temp_' . $template_id_short, $templ);
    }
    $key = _getContentArr($templ['content']);
    if (!$templ) {
        writerLog('模板消息:' . $template_id_short . "获取不到", 'template');
    } else {
        $template_id = $templ['template_id'];
        $template = array(
            'touser' => $openid, //发送的用户
            'template_id' => $template_id, //模板id
            'url' => $url, //点击后跳转的地址
            'topcolor' => "#000",
            'data' => array(
                'first' => array('value' => urlencode($first), 'color' => '#000'),
                'remark' => array('value' => urlencode($remark), 'color' => '#000'),
            )
        );
        for ($i = 0; $i < count($arr); $i++) {
            if (is_array(($arr[$i]))) {
                $template['data'][$key[$i]] = array('value' => urlencode($arr[$i][0]), 'color' => urlencode($arr[$i][1]));
            } else {
                $template['data'][$key[$i]] = array('value' => urlencode($arr[$i]), 'color' => '#000');
            }
        }
        $weObj = getWeObject();
        $result = $weObj->sendTemplateMessage($template);
        if ($result!==FALSE) {
            //获取模板除first和remark的内容
            $contentstr = preg_replace('/\{.*?\}}/', '', $templ['content']);
            $contentarr = explode("：", $contentstr);
            //添加模板消息记录
            $m = db('template_message');
            $msg['openid'] = $openid;
            $msg['title'] = $templ['title'];
            $msg['url'] = $url;
            $content['first'] = $first;
            for ($i = 1; $i <= count($arr); $i++) {
                $contentarr[$i - 1] .=": " . $arr[$i - 1];
            }
            $content['keyword'] = $contentarr;
            $content['remark'] = $remark;
            $msg['content'] = json_encode($content,JSON_UNESCAPED_UNICODE);
            $msg['create_time'] = time();
            $m->insert($msg);
            return true;
        }
        return $weObj->getError();
    }
}

/**
 * 获取模板消息键值
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function _getContentArr($content) {
    if (preg_match('/\n{2}/', $content)) {
        $res = explode(',', preg_replace('/\n/', ',', preg_replace('/\n/', '', $content, 1)));
    } else {
        $res = explode(',', preg_replace('/\n/', ',', $content));
    }
    $arr = array();
    $len = count($res);
    foreach ($res as $k => $v) {
        if (($k == $len - 1) || ($k == 0)) {
            continue;
        }
        $left = stripos($v, '{{') + 2;
        $right = stripos($v, '}}') - 2;
        $length = $right - $left;
        $arr[] = explode('.', substr($v, $left, $length))[0];
    }
    return $arr;
}

/**
 * 发送客服消息
 */
function send_text_msg($msg) {
    $weObject = getWeObject();
    $data['touser'] = $msg['openid'];
    $text = "===== " . $msg['title'] . " =====\n\n";
    foreach ($msg['msg'] as $k => $v) {
        $text.="【" . $k . "】" . $v . "\n";
    }
    $text.="\n===== 感谢您的使用! =====\n";
    $data['msgtype'] = "text";
    $data['text'] = array("content" => $text);
    $result = $weObject->sendCustomMessage($data);
    return $result;
}

//获取分享参数
function get_share($url = "") {
    if (empty($url)) {
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    $weObject = getWeObject();
    $signPackage = $weObject->getJsSign($url);
    return $signPackage;
}
