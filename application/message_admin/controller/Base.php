<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 后台底层 ]
namespace app\message_admin\controller;

use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        //判断登录
        if (empty(session('username'))) {
            $loginUrl = url('login/index');
            $this->error('请登录', $loginUrl);
        }
    }

}
