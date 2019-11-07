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
        return $this->hasOne('Member','id','send_member_id')->bind(['send_avatar'=>'avatar','send_nickname'=>'nickname']);
    }

    /**
     * 获取留言条数
     * @param $member_id
     * @return bool|int
     */
    public function get_msg_count($member_id){
        if(!$member_id){
            return false;
        }
        return $this->get_all_count(['member_id'=>$member_id]);
    }

    /**
     * 获取留言数据
     * @param $member_id
     * @return bool|int
     */
    public function get_msg_data($member_id){
        if(!$member_id){
            return false;
        }
        return $this->get_all_data(['member_id'=>$member_id],'','',['sendMember']);
    }

    /**
     * 添加留言数据
     * @param $data
     * @return bool|int
     */
    public function insert_msg($data){
        if(!$data){
            return false;
        }
        return $this->add_data($data);
    }
}