<?php
// +----------------------------------------------------------------------
// | p7ing.com [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2029 http://p7ing.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Leimon <leimon1314@gmail.com>
// +----------------------------------------------------------------------

// [ 留言板模型 ]
namespace app\message_admin\model;
use app\common\model\Base;

class MessageBoard extends Base
{
 //开启自动写入时间
 protected $autoWriteTimestamp = true;
    
 //添加时间
 protected $createTime = 'inputtime';
    /**
     * 关联用户表信息
     *
     * @return void
     */
    public function member(){
        return $this->hasOne('Member','id','member_id')->bind(['nickname']);
    }

    /**
     * 关联用户表信息
     *
     * @return void
     */
    public function sendMember(){
        return $this->hasOne('Member','id','send_member_id')->bind(['send_nickname'=>'nickname']);
    }
}