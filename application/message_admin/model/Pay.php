<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 支付记录模型 ]
namespace app\message_admin\model;
use app\common\model\Base;

class Pay extends Base
{
    /**
     * 完成状态转换
     *
     * @param [type] $value
     * @return void
     */
    public function getStatusAttr($value){
        $is_pay = [
             '-1'=>'<font color="red">支付失败</font>',
             '1'=>'<font color="green">支付成功</font>'
        ];
        return $is_pay[$value];
   }
   /**
    * 完成人民币符号
    *
    * @param [type] $value
    * @return void
    */
   public function getnumAttr($value){
        return '￥'.$value;
    }

    /**
     * 关联用户表信息
     *
     * @return void
     */
    public function member(){
        return $this->hasOne('Member','id','member_id')->bind(['nickname']);
    }
}