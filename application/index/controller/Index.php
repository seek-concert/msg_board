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
        $id = 2;//XXX
//        $id = input('id');
        if(!$id){
            die('非法访问');
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

        return view('',$data);
    }

    /**
     * 留言
     * @return array
     */
    public function insert_msg_data()
    {
        $data = input('');
        $this->message_board_model = model('MessageBoard');
//        $data['send_member_id'] = $this->user_info['id'];
        $ret = $this->message_board_model->insert_msg($data);
        if($ret){
            return $this->renderSuccess('留言成功',[]);
        }else{
            return $this->renderError('留言失败',[]);
        }
    }
}
