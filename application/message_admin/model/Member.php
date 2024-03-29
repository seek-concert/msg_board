<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 后台用户模型 ]
namespace app\message_admin\model;
use app\common\model\Base;

class Member extends Base
{
     //开启自动写入时间
     protected $autoWriteTimestamp = true;
    
     //添加时间
     protected $createTime = 'inputtime';
 
     public function getIsPayAttr($value){
          $is_pay = [
               '-1'=>'<font color="red">未缴费</font>',
               '1'=>'<font color="green">已缴费</font>'
          ];
          return $is_pay[$value];
     }
}