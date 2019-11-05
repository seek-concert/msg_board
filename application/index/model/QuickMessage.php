<?php
namespace app\index\model;
use app\common\model\Base;
/**
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
    
}