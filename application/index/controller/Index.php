<?php
namespace app\index\controller;
use think\Controller;
/**
 * 首页
 * Class Index
 * @package app\index\controller
 */
class Index extends Controller
{
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
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }

    /**
     * 留言
     * @param array $data
     * @return \think\response\View
     */
    public function msg($data=[])
    {
        return renderJson('0','留言成功','');
    }
}
