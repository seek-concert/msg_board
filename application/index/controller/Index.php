<?php
namespace app\index\controller;
use think\Controller;
/**
 * 首页
 * Class Index
 * @package app\index\controller
 */
class Index extends Auth
{
    /**
     * @var $member_model @用户模型
     * @var $setting_model @设置模型
     * @var $quick_message_model @快捷留言标签模型
     * @var $message_board_model @留言模型
     */
    private $member_model;
    private $setting_model;
    private $quick_message_model;
    private $message_board_model;
    /**
     * 初始化操作
     * @access protected
     */
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 首页视图
     * @return array|\think\response\View
     */
    public function index()
    {
        $data = [];
        $id = input('id');
        if(!$id){
            $id = $this->user_info['id'];
        }
        $data['user_id'] = $id;
        /* +++++++++[初始化模型]+++++++++ */
        $this->member_model = model('Member');
        $this->setting_model = model('Setting');
        $this->quick_message_model = model('QuickMessage');
        $this->message_board_model = model('MessageBoard');
        /* +++++++++[获取用户信息及获取留言]+++++++++ */
        $member_info = $this->member_model->get_member_info($id);
        $data['member_info'] = $member_info;
        $get_msg_count = $this->message_board_model->get_msg_count($id);
        $data['get_msg_count'] = $get_msg_count;
        $get_msg_data = $this->message_board_model->get_msg_data($id);
        $data['get_msg_data'] = $get_msg_data?:[];
        /* +++++++++[获取配置]+++++++++ */
        $setting_info = $this->setting_model->get_setting();
        $new_set_arr = [];
        if(!empty($setting_info)&&is_array($setting_info)){
            foreach ($setting_info as $k=>$v){
                $new_set_arr[$v['key']] = $v['value'];
            }
        }
        $data['setting_info'] = $new_set_arr;
        /* +++++++++[获取快捷留言标签]+++++++++ */
        $quick_message_info = $this->quick_message_model->get_label(1);
        $new_msg_arr = [];
        if(!empty($quick_message_info)&&is_array($quick_message_info)){
            foreach ($quick_message_info as $k=>$v){
                $new_msg_arr[$k] = $v['message'];
            }
        }
        $data['quick_message_info'] = $new_msg_arr;
        return view('',$data);
    }

    /**
     * 获取标签
     * @return array
     */
    public function switch_label(){
        $page = input('page',1);
        $this->quick_message_model = model('QuickMessage');
        $ret = $this->quick_message_model->get_label($page);
        $new_msg_arr = [];
        if(!empty($ret)&&is_array($ret)){
            $new_msg_arr['page'] = $ret['page'];
            unset($ret['page']);
            foreach ($ret as $k=>$v){
                $new_msg_arr['msgs'][$k] = $v['message'];
            }
        }
        if($new_msg_arr){
            return $this->renderSuccess('获取成功',$new_msg_arr);
        }else{
            return $this->renderError('获取失败',[]);
        }
    }

    /**
     * 留言
     * @return array
     */
    public function insert_msg_data()
    {
        $data = input('');
        if(!$data){
            die('非法访问');
        }
        $this->message_board_model = model('MessageBoard');
        $data['send_member_id'] = $this->user_info['id'];
        $ret = $this->message_board_model->insert_msg($data);
        if($ret){
            return $this->renderSuccess('留言成功',[]);
        }else{
            return $this->renderError('留言失败',[]);
        }
    }

    /**
     * 删除留言
     * @return array
     */
    public function del_msg_data()
    {
        $data = input('');
        if(!$data){
            die('非法访问');
        }
        $this->message_board_model = model('MessageBoard');
        $where['send_member_id'] = $data['send_id'];
        $ret = $this->message_board_model->del_msg($where);
        if($ret){
            return $this->renderSuccess('删除成功',[]);
        }else{
            return $this->renderError('删除失败',[]);
        }
    }

    /**
     * 留言详情管理
     * @return \think\response\View
     */
    public function msg_detail(){
        $data = [];
        $id = $this->user_info['id'];
        if(!$id){
            die('非法访问');
        }
        $data['user_id'] = $id;
        /* +++++++++[初始化模型]+++++++++ */
        $this->member_model = model('Member');
        $this->message_board_model = model('MessageBoard');
        /* +++++++++[获取用户信息及获取留言]+++++++++ */
        $member_info = $this->member_model->get_member_info($id);
        $data['member_info'] = $member_info;
        $get_msg_data = $this->message_board_model->get_msg_data($id);
        $data['get_msg_data'] = $get_msg_data?:[];
        return view('',$data);
    }

    /**
     * 分享记录
     *
     * @return void
     */
    public function share(){
        $id = $this->user_info['id'];
        $share_sql = [];
        $share_sql['member_id'] = $id;
        $ret = model('Share')->add_data($share_sql);
    }
}
