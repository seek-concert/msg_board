<?php
namespace app\index\model;
use app\common\model\Base;
/**
 * 用户模型
 * Class Member
 * @package app\index\model
 */
class Member extends Base
{
     //开启自动写入时间
     protected $autoWriteTimestamp = true;
    
     //添加时间
     protected $createTime = 'inputtime';

    //修改时间
    protected $updateTime = 'update_time';

    //登陆ip及登陆时间
    public function login_data(){
        $data['login_at']=time();
        $data['login_ip']=request()->ip();
        return $data;
    }

    /**
     * 获取被留言人信息
     * @param $member_id
     * @return array|bool
     */
     public function get_member_info($member_id){
         if(!$member_id){
             return false;
         }
         return $this->get_one_data(['id'=>$member_id]);
     }

}