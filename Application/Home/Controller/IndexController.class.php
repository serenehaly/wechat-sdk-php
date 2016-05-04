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
        /*加载微信SDK*/ 
        $wechat = new Wechat($token, $appid, $crypt);
        /* 获取请求信息 */
        $data = $wechat->request();
        /*判断分析请求信息，并作出对应的操作*/
        if($data && is_array($data)){
            //判断请求信息的数据类型
            switch ($data['MsgType']) {
                //数据类型为EVENT
                case Wechat::MSG_TYPE_EVENT:
                switch ($data['Event']) {
                    //关注
                    case Wechat::MSG_EVENT_SUBSCRIBE:
                        $wechat->replyText('欢迎！回复“文本管理”，“查询IP”，“以及其他文本”和机器人聊天，查看相应的信息！');
                        break;

                    case Wechat::MSG_EVENT_UNSUBSCRIBE:
                        //取消关注，记录日志
                        break;
                    default:
                        $wechat->replyText("欢迎！您的事件类型：{$data['Event']}，EventKey：{$data['EventKey']}");
                        break;
                }
                break;
                
                
                //数据类型为TEXT
                case Wechat::MSG_TYPE_TEXT:
                    //查找该请求信息是否有对应的操作
                    $result = M('answer')->where(array('problem'=>$data['Content']))->find();
                    if($result){
                        //分类做出对应的操作
                        switch($result['problem']){
                            //查询IP
                            case '查询IP':
                                //获取本机IP地址
                                $IP = $_SERVER["REMOTE_ADDR"];
                                //获取IP的信息
                                $file_contents = file_get_contents("http://opendata.baidu.com/api.php?query=" . $IP . "&co=&resource_id=6006&t=1329357746681&ie=utf8&oe=utf8&format=json&tn=baidu");
                                //强制转换成数组
                                $data = json_decode($file_contents,true);
                                //信息的拼接
                                $string =  "你的location：" . $data['data'][0]['location'] . "\n" . 
                                            "你的origip：" . $data['data'][0]['origip'] . "\n" . 
                                          "你的origipquery：" . $data['data'][0]['origipquery'];
                                $wechat->replyText($string);
                                break;
                            case '文本管理':
                                //跳转到文本管理页面进行增删改查操作
                                $wechat->replyNewsOnce('文本管理', '文本的增删改查', 'http://mqt.tingin.cn/WechatSDK/index.php/Home/Text/getlist.html', 'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo/bd_logo1_31bdc765.png');
                                break;
                            default:
                                //输出数据库已存在的数据的回复
                                $wechat->replyText($result['answer']);
                                break;
                        }
                    }else{
                        //数据库里不存在该请求信息，则输出“你说啥”
                        $pro=$data['Content'];
                        $one=strpos($pro,":")+1;
                        $two=strpos($pro,":");
                        $three=strrpos($pro, ':')+1;
                        //$needle = "a";//判断是否包含a这个字符
                        // $tmparray = explode($needle,$str);
                        // if(count($tmparray)>1){
                        // return true;
                        // } else{
                        // return false;
                        // }
                        
                        $yes=strstr($pro,':');
                        if($yes!=null){
                            $problem=mb_strcut($m, $a, $b-$a-3, 'utf-8');
                            $answer=substr($pro,$three);
                            $add['problem']=$problem;
                            $add['answer']=$answer;
                            $res=M('answer')->add($add);
                            $wechat->replyText("问答文本已录入数据库，即刻可问答！");
                        }else{
                            $wechat->replyText("你说啥"); 
                        }
                        // $a = strpos($m,":")+1;
                        // $b = strrpos($m, ':');
                        // echo mb_strcut($m, $a, $b-$a-3, 'utf-8') ."\n" .
                        // substr($m,strrpos($m, ':')+1) ;
                        // echo $a.$b;
                       //$wechat->replyText("你说啥");
                    }
                    break;
 
                    case Wechat::MSG_TYPE_IMAGE:
                        $wechat->replyText(json_encode($data));
                        break;
            }
        }
    }
}










