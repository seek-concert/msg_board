<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 后台首页 ]
namespace app\message_admin\controller;

class Index extends Base
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
     * 后端框架 目录加载
     *
     * @return void
     */
    public function index()
    {
        return view();
    }

    /**
     * 后端首页
     *
     * @return void
     */
    public function home(){
        //今日开始时间
        $start_time=strtotime(date("Y-m-d",time()));
        //今日结束时间
        $end_time=$start_time+60*60*24;

        //总用户数量
        $member_count = $this->member_model->get_all_count();
        //日增用户数量
        $today_member_count = $this->member_model->get_all_count(['inputtime'=>['between',[$start_time,$end_time]]]);
        
        //总收益量
        $pay_sum = $this->pay_model->where(['status'=>1])->sum('num');
        //日收益
        $today_pay_sum = $this->pay_model->where(['status'=>1,'inputtime'=>['between',[$start_time,$end_time]]])->sum('num');

        //总留言数量
        $message_count = $this->message_board->get_all_count();
        //日留言数量
        $today_message_count = $this->message_board->get_all_count(['inputtime'=>['between',[$start_time,$end_time]]]);

        //总分享转发数量
        $share_sum = $this->share_model->get_all_count();
        //日分享转发数量
        $today_share_sum = $this->share_model->get_all_count(['inputtime'=>['between',[$start_time,$end_time]]]);

        $return_data = [];
        $return_data['member_count'] = $member_count;
        $return_data['pay_sum'] = $pay_sum;
        $return_data['message_count'] = $message_count;
        $return_data['share_sum'] = $share_sum;
        $return_data['today_member_count'] = $today_member_count;
        $return_data['today_pay_sum'] = $today_pay_sum;
        $return_data['today_message_count'] = $today_message_count;
        $return_data['today_share_sum'] = $today_share_sum;
        return view('',$return_data);
    }

    /**
     * 首页走势图数据
     *
     * @return void
     */
    public function get_month_count(){
        //今年时间
        $year = date('Y',time());
        $yeararr = [];
        $month = [];
        //轮询12个月
        for ($i=1; $i <=12 ; $i++) { 
            $yeararr[$i] = $year.'-'.$i;
        }
        $series = [];
        $series[0]['name'] = '用户增长';
        $series[0]['type'] = 'line';
        $series[1]['name'] = '效益';
        $series[1]['type'] = 'line';
        foreach ($yeararr as $key => $value) {
            //计算每月统计
            $timestamp = strtotime( $value );
            $start_time = date( 'Y-m-1 00:00:00', $timestamp );
            $mdays = date( 't', $timestamp );
            $end_time = date( 'Y-m-' . $mdays . ' 23:59:59', $timestamp );
            //用户增长
            $series[0]['data'][$key-1] = $this->member_model->get_all_count(['inputtime'=>['between',[$start_time,$end_time]]]);
            //效益增长
            $series[1]['data'][$key-1] = $this->pay_model->where(['status'=>1,'inputtime'=>['between',[$start_time,$end_time]]])->sum('num');

        }
       $this->success('','',$series);
    }
}
