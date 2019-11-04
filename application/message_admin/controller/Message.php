<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 留言管理 ]
namespace app\message_admin\controller;

class Message extends Base
{

    protected $member_model;
    protected $message_board;
    protected $share_model;
    public function __construct()
    {
        parent::__construct();
        $this->member_model = model('Member');
        $this->message_board = model('MessageBoard');
        $this->share_model = model('Share');
    }

    /**
     * 消息列表模板
     *
     * @return void
     */
    public function message_lists(){
        return view();
    }
    
    /**
     * 获取消息列表
     *
     * @return void
     */
    public function get_message_lists(){
        $param = input();
        $nickname = isset($param['nickname'])?$param['nickname']:'';
        $message = isset($param['message'])?$param['message']:'';
        $page = isset($param['page'])?$param['page']:'';
        $limit = isset($param['limit'])?$param['limit']:'';
        $message_sql = [];

        //用户名模糊查找
        if(!empty($nickname)){
            $member_ids = $this->member_model->get_one_column(['nickname'=>['like','%'.$nickname.'%']]);
            $message_sql['member_id'] = ['in',$member_ids];
        }
        //留言查找
        if(!empty($message)){
            $message_sql['message'] = ['like','%'.$message.'%'];
        }
        $lists = $this->message_board->get_all_data_page($message_sql, $page, $limit, 'id desc', 'id,send_member_id,inputtime,member_id,message',['member','send_member']);
        $return_data = [];
        $return_data['code'] = 1;
        $return_data['count'] = $this->message_board->get_all_count($message_sql);
        $return_data['data'] = $lists;
        return $return_data;
    }


    public function message_del(){
        $param = input('');
        $id = isset($param['id'])?$param['id']:0;
        if($id == 0){
            $this->error('请勿非法访问');
        }
        $ret = $this->message_board->delete_data(['id'=>$id]);
        if($ret){
            $this->success('删除该留言成功');
        }else{
            $this->error('删除该留言失败,请重试');
        }
    }
}