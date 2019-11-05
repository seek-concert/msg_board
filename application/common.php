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

/**
 * 返回数据
 * @param int $code 0  1
 * @param string $msg
 * @param array $data
 * @return array
 */
function renderJson($code, $msg = '', $data = [])
{
    return compact('code', 'msg', 'data');
}
/** cURL函数简单封装
 * @param $url
 * @param null $data
 * @return mixed
 */
function https_request($url, $data = null, $method = 'POST')
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}