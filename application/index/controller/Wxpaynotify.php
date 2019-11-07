<?php
/* |------------------------------------------------------
 * | 微信支付回调
 * |------------------------------------------------------
 * |
 * */
namespace app\index\controller;

use think\Loader;
use think\cache\driver\Redis;
ini_set('date.timezone','Asia/Shanghai');
Loader::import('WxPay.lib.WxPay#Config');
Loader::import('WxPay.lib.WxPay#Api');
Loader::import('WxPay.lib.WxPay#JsApiPay');
Loader::import('WxPay.lib.WxPay#Notify');

class Wxpaynotify extends \WxPayNotify
{

    public function index()
    {
        $this->Handle(true);
    }

    /**
     *
     * 回调方法入口，子类可重写该方法
     * 注意：
     * 1、微信回调超时时间为2s，建议用户使用异步处理流程，确认成功之后立刻回复微信服务器
     * 2、微信服务器在调用失败或者接到回包为非确认包的时候，会发起重试，需确保你的回调是可以重入
     * @param array $data 回调解释出的参数
     * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
     * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     */
    public function NotifyProcess($data=[], &$msg='')
    {
        
        $notfiyOutput = array();
        //file_put_contents(CACHE_PATH . '/shop/' . 'NotifyProcess.php', serialize($data));

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        /*//获取服务器返回的数据
           $order_sn = $data['out_trade_no'];  //订单单号
           $order_id = $data['attach'];        //附加参数,选择传递订单ID
           $openid = $data['openid'];          //付款人openID
           $total_fee = $data['total_fee'];    //付款金额*/

        //查询支付信息
        $pay_info=model('Pay')->get_one_data(['number'=>$data['out_trade_no']]);
        //更新支付信息
        if($pay_info){
            model('Pay')->update_data([
                'trade_num'=>$data["transaction_id"],
                'result_code'=>$data["result_code"],
                'status' => 1,
                'pay_time' => time()
            ],['number'=>$data['out_trade_no']]);
            model('Member')->update_data([
                'is_pay' => 1
            ],['id'=>$pay_info['member_id']]);
        }

       
    }

}
