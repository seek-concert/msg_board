<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 快捷留言管理 ]
namespace app\message_admin\controller;

class Quickmessage extends Base
{

    protected $member_model;
    protected $quick_message_model;
    protected $message_board;
    protected $share_model;
    public function __construct()
    {
        parent::__construct();
        $this->member_model = model('Member');
        $this->quick_message_model = model('QuickMessage');
        $this->message_board = model('MessageBoard');
        $this->share_model = model('Share');
    }

    /**
     * 快捷留言模板
     *
     * @return void
     */
    public function quick_message_lists(){
        return view();
    }
    /**
     * 获取所有快捷留言
     *
     * @return void
     */
    public function get_quick_message_lists(){
        $param = input();
        $message = isset($param['message'])?$param['message']:'';
        $page = isset($param['page'])?$param['page']:'';
        $limit = isset($param['limit'])?$param['limit']:'';
        $quick_message_sql = [];

        //内容查询
        if(!empty($message)){
            $quick_message_sql['message'] = ['like','%'.$message.'%'];
        }
        $lists = $this->quick_message_model->get_all_data_page($quick_message_sql, $page, $limit, 'id desc', 'id,message,inputtime');
        $return_data = [];
        $return_data['code'] = 1;
        $return_data['count'] = $this->quick_message_model->get_all_count($quick_message_sql);
        $return_data['data'] = $lists;
        return $return_data;
    }
    /**
     * 添加新的快捷留言
     *
     * @return void
     */
    public function add_quick_message(){
        if(request()->isPost()){
            $param = input('');
            $message = isset($param['message'])?$param['message']:'';
            if(empty($message)){
                $this->error('留言信息不能为空');
            }
            $quick_message_sql = [];
            $quick_message_sql['message'] = $message;
            $ret = $this->quick_message_model->add_data($quick_message_sql);
            if($ret){
                $this->success('添加新的快捷留言成功');
            }else{
                $this->error('添加新的快捷留言出错,请重试');
            }
        }
         return view();
    }

    /**
     * 修改快捷留言
     *
     * @return void
     */
    public function edit_quick_message(){
        $param = input();
        if(request()->isPost()){
            $message = isset($param['message'])?$param['message']:'';
            if(empty($message)){
                $this->error('留言信息不能为空');
            }
            $id = isset($param['id'])?$param['id']:'';
            if(empty($id)){
                $this->error('请勿非法访问');
            }
            $quick_message_sql = [];
            $quick_message_sql['message'] = $message;
            $ret = $this->quick_message_model->update_data($quick_message_sql,['id'=>$id]);
            if($ret){
                $this->success('修改成功');
            }else{
                $this->error('您未做任何修改');
            }
        }
        $id = isset($param['id'])?$param['id']:0;
        if($id == 0){
            echo "<font color='red'>请勿非法访问10002</font>";
            die();
        }
        $quick_message_info = $this->quick_message_model->get_one_data(['id'=>$id],'','id,message');
        $return_data = [];
        $return_data['quick_message_info'] = $quick_message_info;
        return view('',$return_data);
    }

    /**
     * 删除快捷留言
     *
     * @return void
     */
    public function message_del(){
        $param = input();
        $id = isset($param['id'])?$param['id']:'';
        if(empty($id)){
            $this->error('请勿非法访问');
        }
        $ret = $this->quick_message_model->delete_data(['id'=>$id]);
        if($ret){
            $this->success('删除该留言成功');
        }else{
            $this->error('删除该留言失败,请重试');
        }
    }

}