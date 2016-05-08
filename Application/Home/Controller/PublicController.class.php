<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends Controller{
    public function login(){
    	if (IS_POST) {
    		$user['username']=I('post.username');
    		$user['password']=md5(I('post.password'));
    		$mess=M('user')->where($user)->find();
	    		if ($mess==null) {
                    $this->error('密码错误，登陆失败！');
	    			
	    		} else {
	    			$_SESSION=$mess;
                    $this->redirect('Home/Text/getlist');
	    		}
    		
		} else {
			$this->display();
		}
    		
    }
    
    public function logout()
	{
		session_destroy();
		$this->success('注销成功，跳转回前台', U("Home/Public/login"));
	}
}
?>