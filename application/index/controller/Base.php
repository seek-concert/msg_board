<?php
namespace app\index\controller;
use think\Controller;
/**
 * 微信授权登录
 * Class Base
 * @package app\index\controller
 */
class Base extends Controller
{
    /**
     * @var $AppId
     * @var $AppSecret
     */
    public $AppId;
    public $AppSecret;

    public function _initialize()
    {
        $app_id='wxe4f708db85c7f7c2';
        $app_secret='6306d6d81469297be7a7ba791c4bab4d';
        $this->AppId = $app_id;
        $this->AppSecret = $app_secret;
    }

    /**
     * 微信授权登录
     */
    public function index()
    {
        if(cookie('refresh_token')){
            $this->refreshtoken();
        }else{
            $this->authorize();
        }

        if(session('remember_url')){
            $url=session('remember_url');
            session('remember_url',null);
        }else{
            $url='/';
        }
        $this->redirect($url);
    }

    /**
     * 授权获取微信CODE
     */
    public function authorize(){
        $return_url='http://'.$_SERVER['HTTP_HOST'].'/index/Base/getaccesstoken';
        $code_url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->AppId.'&redirect_uri='.urlencode($return_url).'&response_type=code&scope=snsapi_userinfo&state=code#wechat_redirect';
        header('location:'.$code_url);
        exit;
    }

    /**
     * 用CODE换取微信用户OPENID
     */
    public function getaccesstoken(){
        $get=$_GET;
        $access_token_url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->AppId.'&secret='.$this->AppSecret.'&code='.$get['code'].'&grant_type=authorization_code';
        $result=https_request($access_token_url);
        $result=json_decode($result,true);
        cookie('access_token',$result['access_token'],6000);
        cookie('refresh_token',$result['refresh_token'],29*24*60*60);
        cookie('openid',$result['openid'],3*24*60*60);

        if(session('remember_url')){
            $url=session('remember_url');
            session('remember_url',null);
        }else{
            $url='/';
        }
        $this->redirect($url);
    }

    /**
     * 刷新ACCESS_TOKEN
     * @return mixed
     */
    public function refreshtoken(){
        $refresh_token_url='https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='.$this->AppId.'&grant_type=refresh_token&refresh_token='.cookie('refresh_token');
        $result=https_request($refresh_token_url);
        $result=json_decode($result,true);
        if(!$result || !isset($result['access_token'])){
            $this->authorize();
        }
        cookie('access_token',$result['access_token'],6000);
        cookie('refresh_token',$result['refresh_token'],29*24*60*60);
        cookie('openid',$result['openid'],3*24*60*60);
        return $result;
    }

}