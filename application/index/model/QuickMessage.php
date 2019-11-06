<?php
namespace app\index\model;
use app\common\model\Base;
/**
 *
 * 快捷留言标签模型
 * Class QuickMessage
 * @package app\index\model
 */
class QuickMessage extends Base
{
     //开启自动写入时间
     protected $autoWriteTimestamp = true;
    
     //添加时间
     protected $createTime = 'inputtime';

    /**
     * 获取标签
     * @param $page
     * @return array|bool
     */
    public function get_label($page=1){
        $data = $this->get_all_data_page([],$page,5,'',['message']);
        $pages = $page;
        if(empty($data)){
            $data = $this->get_all_data_page([],1,5,'',['message']);
            $pages = 1;
        }
        $data['page'] = $pages;
        return $data;
    }
}