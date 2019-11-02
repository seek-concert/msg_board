<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

namespace app\message_admin\controller;

use think\Cache;
use think\Controller;
class Login extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->admin_model = model('Admin');

    }

    /**
     * 登录页面
     * @author    leimon <leimon1314@gmail.com>
     */
    public function index()
    {
        return view();
    }

    /**
     * 登录操作
     */
    public function do_login()
    {
        if (request()->post()) {
            $param = input('post.');
            //获取数据
            $username = isset($param['username'])?$param['username']:'';
            $password = isset($param['password'])?$param['password']:'';
            $vercode = isset($param['vercode'])?$param['vercode']:'';
            //判断数据
            if (!$username) {
                $this->error('请输入用户名');
            }
            if (!$password) {
                $this->error('请输入登录密码');
            }
//            if(!$vercode){
//                $this->error('请输入验证码');
//            }
//
//            if(!captcha_check($vercode)){
//                $this->error('验证码错误');
//            };
            //获取管理员详细信息
            $admin_info = $this->admin_model->get_one_data(['username' => $username]);
            if (!$admin_info) {
                $this->error('该用户不存在');
            }
            //验证密码
            $user_password = md5(md5($password) . $admin_info['code']);
            if ($user_password != $admin_info['password']) {
                $this->error('登录密码错误');
            }

            //是否为驾校管理员
            $ds_info = model('Ds')->get_one_data(['admin_id' => $admin_info['id']], 'id desc', 'id');
            $ds_info = $ds_info?$ds_info['id']:0;
            session('ds_id', $ds_info);

            //是否为商品管理员
            $shop_info = model('ShopBrand')->get_all_data(['admin_id' => $admin_info['id']], 'id desc', 'id');
            $shop_info = $shop_info?$shop_info:[];
            $shop_arr = [];
            foreach ($shop_info as $k => $v){
                $shop_arr[] = $v['id'];
            }
            $shop_info = implode(',',$shop_arr);
            session('shop_brand_id', $shop_info);

            session('username', $admin_info['username']);
            session('id', $admin_info['id']);
            session('role_id', $admin_info['role_id']);

            //记录登录次数 登录时间
            $admin_sql = [];
            $admin_sql['login_num'] = $admin_info['login_num'] + 1;
            $admin_sql['last_login_time'] = time();
            $this->admin_model->update_data(['id' => $admin_info['id']], $admin_sql);
            //写入管理员操作记录
            set_admin_log($admin_info['id'], '管理员[ID:' . $admin_info['id'] . ']登录平台');
            $this->success('登录成功');
        }
    }

    /**
     * 退出登录操作
     */
    public function out_login()
    {
        session(null);
        return $this->redirect(url('/admin/login/index'));
    }


    /**
     * 测试REDIS
     */
    public function get_redis()
    {
        $han = new Cache;
        $redis = $han->getHandler();
        //写入哈希--列表名--key值--value值
        $redis->hSet('admin', 'username', 'leimon');
        //读取哈希--列表名--key值
        echo $redis->hGet('admin', 'username');
        //print_r($redis->lRange('username',0,-1));
        //$han->set('name',$data);
        //$data = $han->get('name');
        die();
    }
}
