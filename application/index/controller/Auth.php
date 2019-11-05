<?php
namespace app\index\controller;
use app\index\model\Member;
use think\Controller;

/**
 * 初始化
 * Class Auth
 * @package app\index\controller
 */
class Auth extends Controller
{
    const JSON_SUCCESS_STATUS = 1;
    const JSON_ERROR_STATUS = 0;
    public $user_info;
    public function _initialize()
    {
//        $wx_info=$this->getuserinfo();
//        if(session('remember_url')){
//            $url=session('remember_url');
//            session('remember_url',null);
//            $this->redirect($url);
//        }
//        /* +++++++++获取用户信息+++++++++ */
//        $openid=cookie('openid');
//        if($openid){
//            cookie('openid',$openid,3*24*60*60);
//        }else{
//            session('remember_url',request()->url());
//            $this->redirect('Base/index');
//        }
//
//        $model=new Member();
//        $user_info=$model->where('open_id',$openid)->find();
//        if(!$user_info){
//            $data['open_id']=$openid;
//            $data['nickname']=$wx_info['nickname'];
//            $data['avatar']=$wx_info['headimgurl'];
//            $data['login_at']=time();
//            $data['login_ip']=request()->ip();
//            $model->save($data);
//            $user_info=$model;
//
//        }else{
//            $data['nickname']=$wx_info['nickname'];
//            $data['avatar']=$wx_info['headimgurl'];
//            $data['login_at']=time();
//            $data['login_ip']=request()->ip();
//            $model->save($data,['open_id'=>$openid]);
//        }
//        cookie('userid',$user_info['id']);
//        if(!session('openid')){
//            $user_info->save($user_info->login_data(),['id',$user_info->id]);
//            session('openid',$openid);
//        }
//        if(!session('wx_info')){
//            session('wx_info',$wx_info);
//        }
//        $user_infos=$user_info->toArray();
//        $this->user_info=array_merge($user_infos,session('wx_info'));
    }

    /**
     * 获取微信用户信息
     * @return mixed
     */
    public function getuserinfo(){
        $check_valid_url=' https://api.weixin.qq.com/sns/auth?access_token='.cookie('access_token').'&openid='.cookie('openid');
        $result=https_request($check_valid_url);
        $result=json_decode($result,true);
        if(!$result||$result['errcode']){
            $base=new Base();
            $base->refreshtoken();
        }

        $userinfo_url='https://api.weixin.qq.com/sns/userinfo?access_token='.cookie('access_token').'&openid='.cookie('openid').'&lang=zh_CN';
        $result=https_request($userinfo_url);

        $result=json_decode($result,true);
        return $result;
    }

    /**
     * 返回操作成功json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderSuccess($msg = 'success', $data = [])
    {
        return renderJson(self::JSON_SUCCESS_STATUS, $msg, $data);
    }

    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderError($msg = 'error', $data = [])
    {
        return renderJson(self::JSON_ERROR_STATUS, $msg, $data);
    }
}