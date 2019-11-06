<?php
namespace app\index\model;
use app\common\model\Base;
/**
 *  系统配置
 * Class Setting
 * @package app\index\model
 */
class Setting extends Base
{
    /**
     * 获取配置项
     * @return array|bool
     */
    public function get_setting(){
        return $this->get_all_data([],'');
    }
}