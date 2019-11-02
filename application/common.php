<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
 * 对象转换成数组
 * @param object $obj
 * @return array
 */
function objToArray($obj)
{
    return json_decode(json_encode($obj), true);
}

/**
 * 管理操作日志记录
 *
 * @param integer $admin_id 管理员id
 * @param string $message 操作记录
 * @return void
 */
function set_admin_log($admin_id=0,$message=''){
    $log_sql = [];
    $log_sql['admin_id'] = $admin_id;
    $log_sql['aciton'] = $message;
    $log_sql['ip'] = request()->ip();
    model('message_admin/AdminAction')->add_data($log_sql);
}