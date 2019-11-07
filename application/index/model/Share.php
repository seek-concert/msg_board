<?php
namespace app\index\model;
use app\common\model\Base;
/**
 *  分享模型
 * Class Setting
 * @package app\index\model
 */
class Share extends Base
{
    //开启自动写入时间
    protected $autoWriteTimestamp = true;

    //添加时间
    protected $createTime = 'inputtime';
}