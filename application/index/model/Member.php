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

}