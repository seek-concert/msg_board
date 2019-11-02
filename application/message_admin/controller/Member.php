<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 用户管理 ]
namespace app\message_admin\controller;

class Member extends Base
{

    protected $member_model;
    protected $pay_model;
    protected $message_board;
    protected $share_model;
    public function __construct()
    {
        parent::__construct();
        $this->member_model = model('Member');
        $this->pay_model = model('Pay');
        $this->message_board = model('MessageBoard');
        $this->share_model = model('Share');
    }

    /**
     * 用户列表模板
     *
     * @return void
     */
    public function member_lists(){
        return view();
    }

    /**
     * 获取用户列表
     *
     * @return void
     */
    public function get_member_lists(){
        $param = input();
        $nickname = isset($param['nickname'])?$param['nickname']:'';
        $is_pay = isset($param['is_pay'])?$param['is_pay']:'';
        $page = isset($param['page'])?$param['page']:'';
        $limit = isset($param['limit'])?$param['limit']:'';
        $member_sql = [];
        //用户名模糊查找
        if(!empty($nickname)){
            $member_sql['nickname'] = ['like','%'.$nickname.'%'];
        }
        //是否付费查找
        if(!empty($is_pay)){
            $member_sql['is_pay'] = $is_pay;
        }
        $lists = $this->member_model->get_all_data_page($member_sql, $page, $limit, 'id desc', 'id,nickname,avatar,inputtime,is_pay');
        $return_data = [];
        $return_data['code'] = 1;
        $return_data['count'] = $this->member_model->get_all_count($member_sql);
        $return_data['data'] = $lists;
        return $return_data;
    }
}