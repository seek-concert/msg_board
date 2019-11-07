<?php
namespace app\index\model;
use app\common\model\Base;
/**
 *  支付
 * Class Setting
 * @package app\index\model
 */
class Pay extends Base
{
    //开启自动写入时间
    protected $autoWriteTimestamp = true;

    //添加时间
    protected $createTime = 'input_time';

    /**
     * 添加支付订单数据
     * @param $data
     * @return bool|int
     */
    public function insert_pay($data){
        if(!$data){
            return false;
        }
        return $this->add_data($data);
    }
}