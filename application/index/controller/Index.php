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
     * @var $setting_model @设置模型
     * @var $quick_message_model @快捷留言标签模型
     * @var $message_board_model @留言模型
     */
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
//        $id = input('id');
//        if(!$id){
//            return $this->renderError('非法访问');
//        }
        $data = [];
        $this->setting_model = model('Setting');
        $this->quick_message_model = model('QuickMessage');
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
     * @param array $data
     * @return array
     */
    public function msg($data=[])
    {
        $this->message_board_model = model('MessageBoard');
        return $this->renderSuccess('留言成功',[]);
    }
}
