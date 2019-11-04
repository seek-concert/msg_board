<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 支付管理 ]
namespace app\message_admin\controller;

class Pay extends Base
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
     * 支付列表模板
     *
     * @return void
     */
    public function pay_lists(){
        return view();
    }

    /**
     * 获取支付列表
     *
     * @return void
     */
    public function get_pay_lists(){
        $param = input();
        $nickname = isset($param['nickname'])?$param['nickname']:'';
        $status = isset($param['status'])?$param['status']:'';
        $page = isset($param['page'])?$param['page']:'';
        $limit = isset($param['limit'])?$param['limit']:'';
        $pay_sql = [];

        //用户名模糊查找
        if(!empty($nickname)){
            $member_ids = $this->member_model->get_one_column(['nickname'=>['like','%'.$nickname.'%']]);
            $pay_sql['member_id'] = ['in',$member_ids];
        }
        //是否付费查找
        if(!empty($status)){
            $pay_sql['status'] = $status;
        }
        $lists = $this->pay_model->get_all_data_page($pay_sql, $page, $limit, 'id desc', 'id,num,status,inputtime,member_id',['member']);
        $return_data = [];
        $return_data['code'] = 1;
        $return_data['count'] = $this->pay_model->get_all_count($pay_sql);
        $return_data['data'] = $lists;
        return $return_data;
    }
}