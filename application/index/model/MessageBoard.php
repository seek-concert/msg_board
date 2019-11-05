<?php
namespace app\index\model;
use app\common\model\Base;
/**
 * 留言板模型
 * Class MessageBoard
 * @package app\index\model
 */
class MessageBoard extends Base
{
    //开启自动写入时间
    protected $autoWriteTimestamp = true;

    //添加时间
    protected $createTime = 'inputtime';
    /**
     * 关联用户表信息 【被留言人】
     * @return \think\model\relation\HasOne
     */
    public function member(){
        return $this->hasOne('Member','id','member_id')->bind(['nickname']);
    }

    /**
     * 关联用户表信息 【留言人】
     * @return \think\model\relation\HasOne
     */
    public function sendMember(){
        return $this->hasOne('Member','id','send_member_id')->bind(['send_nickname'=>'nickname']);
    }
}