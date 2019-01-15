<?php
namespace app\utils\controller;
use app\common\controller\SockBase;
use app\common\model\WechatFansTags;
use app\common\model\User;
use app\common\model\NewsPushRecord;
class NewsPush extends SockBase{
    public $pageSize = 50;

    public function index($page = 1) {
           //查询所有开发者
        $user_ids = $this->params['user_ids'];
        $record_id = $this->params['record_id'];
        $newsinfo = $this->params['newsinfo'];
        //添加记录
        $map['subscribe'] = 1;
        if ($user_ids != 0) {
            $map['id'] = ['in', $user_ids];
        }
        $sendP = [
                  'record_id' => $record_id,
                  'newsinfo' => $newsinfo
               ];
        $result = (new User())->where($map)->field('openid')->paginate($this->pageSize, false)->each(function($item) use ($sendP){
               $sendP['openid'] = $item['openid'];
               $url = 'utils/news_push/send';
               sock_post($url,$sendP);
        });
        if(count($result->toArray()['data'])>=$this->pageSize){
            //循环查询
            $page+=1;
            $url = 'utils/news_push/index/page/'.$page;
            $sendP = [
              'user_ids' => $user_ids,
              'record_id' => $record_id,
              'newsinfo' => $newsinfo
           ];
           sock_post($url,$sendP);
        }
    }
    
    public function send(){
        $openid = $this->params['openid'];
        $newsinfo = $this->params['newsinfo'];
        $record_id = $this->params['record_id'];
        $weObj = cache('weObj'.$record_id);
        if(empty($weObj)){
            $weObj = getWeObject();
            cache('weObj'.$record_id,$weObj);
        }
         // 数据拼装
        $data = [
            "touser"=>$openid,
            "msgtype"=>"mpnews",
            "mpnews"=>
            [
                 "media_id"=>$newsinfo['media_id']
            ] 
        ];
        if (false !== $weObj->sendCustomMessage($data)) {
           (new NewsPushRecord())->where('id',$record_id)->setInc('success_num');
        }else{
            (new NewsPushRecord())->where('id',$record_id)->setInc('error_num');
        }
    }

}
