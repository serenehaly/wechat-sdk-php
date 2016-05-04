<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

class IndexController extends Controller{
    /**
     * 微信消息接口入口
     * 所有发送到微信的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     */
    public function index($id = ''){
        //$appid = 'wx58aebef2023e68cd'; //AppID(应用ID)
        $token = 'weixin'; //微信后台填写的TOKEN
        $wechat = new Wechat($token, $appid, $crypt);
        $data = $wechat->request();
        if($data && is_array($data)){
            $IP = $_SERVER["REMOTE_ADDR"];
            1、实现微信文本答复；
            2、实现微信文本答复的文本管理（不需要登陆，直接访问地址管理）
            3、实现学习功能（发送内容：（问：你是傻逼吗？答：是的先生）发送后微信会自动学习）(加分,做出来亲你五十下)
            4、实现微信发指令查IP功能
            5、请为你的每一行代码都加上注释，以表示你看懂了你会了。
            $file_contents = file_get_contents("http://opendata.baidu.com/api.php?query=" . $IP . "&co=&resource_id=6006&t=1329357746681&ie=utf8&oe=utf8&format=json&tn=baidu");
            $data = json_decode($file_contents,true);
            //$string = $data['data'][0]['location']."xxx".$data['data'][0]['origipquery']."xxx". $data['data'][0]['origip'];
            $string =  "你的IP坐标地址：" . $data['data'][0]['location'] . "\n" . 
                        "你的origip：" . $data['data'][0]['origip'] . "\n" . 
                       "你的origipquery：" . $data['data'][0]['origipquery'];
            $wechat->replyText($string);
            //$wechat->replyText(json_encode($_SERVER["REMOTE_ADDR"]));
            /*
            if(Wechat::MSG_TYPE_TEXT){
                $result = M('answer')->where(array('problem'=>$data['Content']))->find();
                if($result){
                    $wechat->replyText($result['answer']);
                }else{
                    //$wechat->replyText("你说啥");
                    //$wechat->replyText(json_encode($data));
                    //$wechat->replyNewsOnce('$title', '$discription', 'http://www.baidu.com/', 'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png'); //回复单条图文消息
                    $wechat->replyImage('hYGaMEAeHGkfdku7u-Xinl8180EppqzjaRbQq9ZjyqjJAfgywD0OvPjDd9bpxUPJ');
                }
                
            }else{
                $wechat->replyText(json_encode($data));
            }
            */
        }
    }
    
    
    public function test(){
        $IP = $_SERVER["REMOTE_ADDR"];
        $file_contents =  file_get_contents("http://opendata.baidu.com/api.php?query=" . $IP . "&co=&resource_id=6006&t=1329357746681&ie=utf8&oe=gbk&cb=bd__cbs__9slgza&format=json&tn=baidu");
        var_dump($file_contents);
    }
    

}








/*
{
    "ToUserName": "gh_fb59a7914319",
    "FromUserName": "ovqbgt_JRll9sUE6wiq7lwJgB5os",
    "CreateTime": "1462292331",
    "MsgType": "text",
    "Content": "你好",
    "MsgId": "6280497739242314722"
}
*/










